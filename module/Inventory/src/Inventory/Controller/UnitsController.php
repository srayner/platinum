<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\UnitForm,
    Inventory\Form\ConfirmationForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\Unit;

class UnitsController extends AbstractInventoryController
{
	public function indexAction()
	{
		$aColumns = array('u.name');
		
		// Get parameters from the ajax request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 1);
		$iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
		$sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
		$sSearch        = $this->params()->fromQuery('sSearch', '');
		
		// Build the from clause.
		$from = 'FROM Inventory\Entity\Unit u ';
		
		// Get record count.
		$dql = 'SELECT COUNT(u.id) ' . $from;
		$where = '';
		if($sSearch != '')
		{
			$where = "WHERE u.name LIKE '%" . $sSearch . "%' ";
			$dql = $dql . $where;
		}
		$query = $this->getEntityManager()->createQuery($dql);
		$count = $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
		
		// Build the order by clause.
		$orderBy = '';
		if ($iSortingCols > 0)
		{
			$orderBy = 'ORDER BY ' . $aColumns[$iSortIndex] . ' ' . strtoupper($sSortDir) . ' ';
		}
		
		// build query
		$dql = 'SELECT u ' . $from . $where  . $orderBy;
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setFirstResult($iDisplayStart);
		$query->setMaxResults($iDisplayLength);
		
		// Build the view models, passing in all relevant data.
		$variables = array(
				"sEcho" => $sEcho,
				"iTotalRecords" => $count,
				"iTotalDisplayRecords" => $count,
				'units' => $query->getResult()
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
			$view->setTemplate('inventory/units/data');
		}
		
		// Finally return the view
		return $view;
	  
	}

	public function addAction()
	{
		// Create a new form instance.
		$form = new UnitForm();

		// Check if the request is a POST
		$request = $this->getRequest();
		if ($request->isPost())
		{		
			// Create a new Unit intance.
			$unit = new Unit();

			// Pull the validator from entity and check form is valid.
			$form->setInputFilter($unit->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Populate the item object and persist it to the database.
				$unit->populate($form->getData());
				$this->getEntityManager()->persist($unit);
				$this->getEntityManager()->flush();

				// Create information message.
				$this->flashMessenger()->addMessage('Unit ' . $unit->name . ' sucesfully added');
				
				// Redirect to list of units
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'units'));
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
			return $this->redirect()->toRoute('inventory/default', array('controller' => 'units', 'action'=>'add'));
		}
		$unit = $this->getEntityManager()->find('Inventory\Entity\Unit', $id);

		// Create a new form instance and bind the entity to it.
		$form = new UnitForm();
		$form->bind($unit);
		$form->get('submit')->setAttribute('label', 'Edit');
		
		// Check if this request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{	
		    $form->setData($request->getPost());
		    if ($form->isValid())
		    {
		        // Persist changes to the database.
		        $this->getEntityManager()->persist($unit);
		        $this->getEntityManager()->flush();

				// Create information message.
				$this->flashMessenger()->addMessage('Unit ' . $unit->name . ' sucesfully updated');
				
				// Redirect to list of units
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'units'));
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
			return $this->redirect()->toRoute('inventory/default', array('controller' => 'units'));
		}
		
		// Create a new form instance.
		$form = new ConfirmationForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost()->get('yes', 'No');
			if ($del == 'Yes') {
				$id = (int)$request->getPost()->get('id');
				$unit = $this->getEntityManager()->find('Inventory\Entity\Unit', $id);
				if ($unit) {
					
				    // Delete from the database.
				    $this->getEntityManager()->remove($unit);
					$this->getEntityManager()->flush();
					
					// Create information message.
					$this->flashMessenger()->addMessage('Unit ' . $unit->name . ' sucesfully deleted');
				}
			}

			// Redirect to list of units
			return $this->redirect()->toRoute('inventory/default', array(
					'controller' => 'units',
					'action' => 'index',
			));
		}

		$form->populateValues(array('id' => $id));
		return array(
				'id' => $id,
				'unit' => $this->getEntityManager()->find('Inventory\Entity\Unit', $id)->getArrayCopy(),
		        'form' => $form
		);
	}

}