<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\LocationForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\Location;

class LocationsController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}
	
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
		if ($request->isPost()) {
			
			// Create a new Location intance.
			$location = new Location();
	
			// Pull the validator from entity and check form is valid.
			$form->setInputFilter($location->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid()) {
				
				// Populate the item object and persist it to the database.
				$location->populate($form->getData());
				$this->getEntityManager()->persist($location);
				$this->getEntityManager()->flush();
	
				// Redirect to list of locations
				return $this->redirect()->toRoute('locations');
			}
		}
	
		// If not a POST request, then just render the form.
		return array('form' => $form);
	}
	
	public function editAction()
	{
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('locations', array('action'=>'add'));
		}
		$location = $this->getEntityManager()->find('Inventory\Entity\Location', $id);
	
		$form = new LocationForm();
		$form->setBindOnValidate(false);
		$form->bind($location);
		$form->get('submit')->setAttribute('label', 'Edit');
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$form->bindValues();
				$this->getEntityManager()->flush();
	
				// Redirect to list of locations
				return $this->redirect()->toRoute('locations');
			}
		}
	
		return array(
				'id' => $id,
				'form' => $form,
		);
	}
	
	public function deleteAction()
	{
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('locations');
		}
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost()->get('del', 'No');
			if ($del == 'Yes') {
				$id = (int)$request->getPost()->get('id');
				$location = $this->getEntityManager()->find('Inventory\Entity\Location', $id);
				if ($location) {
					$this->getEntityManager()->remove($item);
					$this->getEntityManager()->flush();
				}
			}
	
			// Redirect to list of locations
			return $this->redirect()->toRoute('default', array(
					'controller' => 'locations',
					'action' => 'index',
			));
		}
	
		return array(
				'id' => $id,
				'item' => $this->getEntityManager()->find('Inventory\Entity\Location', $id)->getArrayCopy()
		);
	}
	
}