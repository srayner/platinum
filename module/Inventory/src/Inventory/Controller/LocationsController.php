<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\LocationForm,
    Inventory\Form\ConfirmationForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\Location;

class LocationsController extends AbstractInventoryController
{	
	public function indexAction()
	{
		$aColumns = array('l.location_code', 'l.location_name');
		
		// Get parameters from the ajax request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 0);
		$iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
		$sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
		$sSearch        = $this->params()->fromQuery('sSearch', '');
		
		// Build the from clause.
		$from = 'FROM Inventory\Entity\Location l ';
		
		// Get record count.
		$dql = 'SELECT COUNT(l.id) ' . $from;
		$where = '';
		if($sSearch != '')
		{
			$where = "WHERE l.location_code LIKE '%" . $sSearch . "%' OR " . 
					 "l.location_name LIKE '%" . $sSearch . "%' ";
			$dql = $dql . $where;
		}
		$query = $this->getEntityManager()->createQuery($dql);
		$count = $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
		
		// Build the order by clause.
		$orderBy = '';
		if ($iSortingCols > 0)
		{
			$orderBy = 'ORDER BY ' . $aColumns[$iSortIndex] . ' ' . $sSortDir . ' ';
		}
		
		// build query
		$dql = 'SELECT l ' . $from . $where  . $orderBy;
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setFirstResult($iDisplayStart);
		$query->setMaxResults($iDisplayLength);
		
		// Build the view models, passing in all relevant data.
		$variables = array(
				"sEcho" => $sEcho,
				"iTotalRecords" => $count,
				"iTotalDisplayRecords" => $count,
				'locations' => $query->getResult()
		);
		if ($this->flashMessenger()->hasMessages())
		{
			$variables['messages'] = $this->flashMessenger()->getMessages();
		}
		$view = new ViewModel($variables);
		
		// Make adjustments if request is expecting JSON response.
		if ($this->request->isXmlHttpRequest())
		{
			$view->setTerminal(true);
			$view->setTemplate('inventory/locations/data');
		}
		
		// Finally return the view
		return $view;
	    
	}
	
	public function addAction()
	{
		// Create a new form instance.
		$form = new LocationForm();
		$form->get('submit')->setAttribute('label', 'Add');
	
		// Check if the request is a POST
		$request = $this->getRequest();
		if ($request->isPost())
		{	
			// Create a new Location intance.
			$location = new Location();
	
			// Pull the validator from entity and check form is valid.
			$form->setInputFilter($location->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid())
			{	
				// Populate the item object and persist it to the database.
				$location->populate($form->getData());
				$this->getEntityManager()->persist($location);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('Location ' . $location->location_code . ' sucesfully added');
				
				// Redirect to list of locations
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'locations'));
			}
		}
	
		// If not a POST request, then just render the form.
		return array('form' => $form);
	}
	
	public function editAction()
	{
	    // Get a current copy of the entity.
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('invntory/default', array('controller' => 'locations', 'action'=>'add'));
		}
		$location = $this->getEntityManager()->find('Inventory\Entity\Location', $id);
	
		// Create a new form instance and bind the entity to it.
		$form = new LocationForm();
		$form->bind($location);
		$form->get('submit')->setAttribute('label', 'Edit');
	
		// Check if this request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{
			// Validate the data.
		    $form->setData($request->getPost());
			if ($form->isValid())
			{
				// Persist changes to the database.
			    $this->getEntityManager()->persist($location);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('Location ' . $location->location_code . ' successfully updated');
				
				// Redirect to list of locations
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'locations'));
			}
		}
	
		// Render (or re-render) then form.
		return array(
				'id' => $id,
				'form' => $form,
		);
	}
	
	public function deleteAction()
	{
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('inventory/default', array('controller' => 'locations'));
		}
	
		// Create a new form instance.
		$form = new ConfirmationForm();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost()->get('yes', 'No');
			if ($del == 'Yes') {
				$id = (int)$request->getPost()->get('id');
				$location = $this->getEntityManager()->find('Inventory\Entity\Location', $id);
				if ($location)
				{    
				    // Delete from the database.
					$this->getEntityManager()->remove($location);
					$this->getEntityManager()->flush();
					
					// Create information message.
					$this->flashMessenger()->addMessage('Location ' . $location->location_code . ' sucesfully deleted');
				}
			}
	
			// Redirect to list of locations
			return $this->redirect()->toRoute('inventory/default', array(
					'controller' => 'locations',
					'action' => 'index',
			));
		}
	
		$form->populateValues(array('id' => $id));
		return array(
				'id' => $id,
				'location' => $this->getEntityManager()->find('Inventory\Entity\Location', $id)->toArray(),
		        'form' => $form
		);
	}
	
}