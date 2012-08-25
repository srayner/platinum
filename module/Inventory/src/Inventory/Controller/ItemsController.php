<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\ItemForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\Item;

class ItemsController extends AbstractActionController
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
		$aColumns = array('i.item_code', 'i.short_description');
		
		// Get parameters from the request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 0);
		$iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
		$sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
		$sSearch        = $this->params()->fromQuery('sSearch', '');
		
		// Build the from clause.
		$from = 'FROM Inventory\Entity\Item i '; // We could add joins here.
		
		// Get record count.
		$dql = 'SELECT COUNT(i.id) ' . $from;
		$where = '';
		if($sSearch != '')
		{
			$where = "WHERE i.item_code LIKE '%" . $sSearch . "%' OR " .
					"i.short_description LIKE '%" . $sSearch . "%' ";
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
		$dql = 'SELECT i ' . $from . $where  . $orderBy;
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setFirstResult($iDisplayStart);
		$query->setMaxResults($iDisplayLength);
		
		// Build the view model, passing in all relevant data.
		$variables = array(
				"sEcho" => $sEcho,
				"iTotalRecords" => $count,
				"iTotalDisplayRecords" => $count,
				'items' => $query->getResult()
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
			$view->setTemplate('inventory/items/data');
		}
		
		// Finally return the view
		return $view;
		
	}
	
	public function addAction()
	{
		// Create a new form instance.
		$form = new ItemForm($this->getEntityManager());
	
		// Check if the request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{	
			// Create a new Item intance.
			$item = new Item();
	
			// Pull the validator from entity and check form is valid.
			$form->setInputFilter($item->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid())
			{	
				// Populate the item object and persist it to the database.
				$category_id = (int)$this->params()->fromPost('category_id');
				$category = $this->getEntityManager()->find('Inventory\Entity\Category', $category_id);
				$item->populate($form->getData());
				$item->category = $category;
				$this->getEntityManager()->persist($item);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('Item ' . $item->item_code . ' sucesfully added');
				
				// Redirect to list of items
				return $this->redirect()->toRoute('items');
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
			return $this->redirect()->toRoute('items', array('action'=>'add'));
		}
		$item = $this->getEntityManager()->find('Inventory\Entity\Item', $id);
		
		// Create a new form instance.
		$form = new ItemForm($this->getEntityManager());
		
		// Check if this request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{
			// Pull the validator from the entity, populate the form and check it's valid.
			$form->setInputFilter($item->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Populate the entity with values from the form.
				$category_id = (int)$this->params()->fromPost('category_id');
				$category = $this->getEntityManager()->find('Inventory\Entity\Category', $category_id);
				$item->populate($form->getData());
				$item->category = $category;
				$this->getEntityManager()->persist($item);
				$this->getEntityManager()->flush();
	
				// Redirect to list of items
				return $this->redirect()->toRoute('items');
			}
		}
	
		// If request is GET then render the form with current values from entity.
		$form->populateValues($item->toArray());
		return array(
				'id' => $id,
				'form' => $form,
		);
	}
	
	public function deleteAction()
	{
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('items');
		}
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost()->get('del', 'No');
			if ($del == 'Yes') {
				$id = (int)$request->getPost()->get('id');
				$item = $this->getEntityManager()->find('Inventory\Entity\Item', $id);
				if ($item) {
					$this->getEntityManager()->remove($item);
					$this->getEntityManager()->flush();
				}
			}
	
			// Redirect to list of items
			return $this->redirect()->toRoute('default', array(
					'controller' => 'items',
					'action' => 'index',
			));
		}
	
		return array(
				'id' => $id,
				'item' => $this->getEntityManager()->find('Inventory\Entity\Item', $id)->getArrayCopy()
		);
	}
	
}