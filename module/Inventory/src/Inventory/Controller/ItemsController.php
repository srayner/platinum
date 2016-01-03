<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\ItemForm,
    Inventory\Form\ConfirmationForm,
    Inventory\Form\EnquireForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\Item;
use Zend\Http\Response;

class ItemsController extends AbstractInventoryController
{	
	public function searchAction()
	{
	    // Build the query.
	    $search = $this->params()->fromQuery('query');
	    $dql = "SELECT i FROM Inventory\Entity\Item i WHERE i.item_code LIKE '%" . $search . "%'";
	    
	    $query = $this->getEntityManager()->createQuery($dql);
	    
	    $response = $this->getResponse();
	    $response->getheaders()->addHeaderLine('Content-Type: text/json');
	    
	    // Setup and return the view model.
	    $view = new ViewModel(array('items' => $query->getResult()));
	    $view->setTerminal(true);
	    $view->setTemplate('inventory/items/search');
	    return $view;
	}
	
	public function indexAction()
	{
		$aColumns = array('i.item_code', 'i.short_description');
		
		// Get parameters from the request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 1);
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
				$category = $this->getEntityManager()->getReference('Inventory\Entity\Category', $category_id);
				$item->populate($form->getData());
				$item->category = $category;
                                $item->stock_qty = 0;
				$this->getEntityManager()->persist($item);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('Item ' . $item->item_code . ' sucesfully added');
				
				// Redirect to list of items
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'items'));
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
			return $this->redirect()->toRoute('inventory/default', array('controller' => 'items', 'action'=>'add'));
		}
		$item = $this->getEntityManager()->find('Inventory\Entity\Item', $id);
		
		// Create a new form instance and bind the entity to it.
		$form = new ItemForm($this->getEntityManager());
		$form->bind($item);
		$form->get('submit')->setAttribute('label', 'Edit');
		
		// Check if this request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{
			// Validate the data.
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Populate the entity with values from the form.
				$category_id = (int)$this->params()->fromPost('category_id');
				$category = $this->getEntityManager()->getReference('Inventory\Entity\Category', $category_id);
				$item->category = $category;
				$this->getEntityManager()->persist($item);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('Item ' . $item->item_code . ' successfully updated');
				
				// Redirect to list of items
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'items'));
			}
		}
		
	    // Render (or re-render) the form.
		return array(
				'id' => $id,
				'form' => $form,
		);
	}
	
	public function deleteAction()
	{
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('inventory/default', array('controller' => 'items'));
		}
	
		// Create a new form instance.
		$form = new ConfirmationForm();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost()->get('yes', 'No');
			if ($del == 'Yes') {
				$id = (int)$request->getPost()->get('id');
				$item = $this->getEntityManager()->find('Inventory\Entity\Item', $id);
				if ($item) {
					
				    // Delete the item.
				    $this->getEntityManager()->remove($item);
					$this->getEntityManager()->flush();
					
					// Create information message.
					$this->flashMessenger()->addMessage('Item ' . $item->item_code . ' sucesfully deleted');
					
				}
			}
	
			// Redirect to list of items
			return $this->redirect()->toRoute('inventory/default', array(
					'controller' => 'items',
					'action' => 'index',
			));
		}
		
		$form->populateValues(array('id' => $id));
		return array(
				'id' => $id,
				'item' => $this->getEntityManager()->find('Inventory\Entity\Item', $id)->getArrayCopy(),
		        'form' => $form
		);
	}
	
	public function enquireAction()
	{   
	    // Check if this request is a POST.
	    $request = $this->getRequest();
	    if ($request->isPost())
	    {
	        $item_code = $request->getPost()->get('item_code');
	        $item = $this->getEntityManager()->getRepository('Inventory\Entity\Item')->findOneBy(array('item_code' => $item_code));
	        if ($item)
	        {
	            // Redirect to item status
	            return $this->redirect()->toRoute('inventory/default', array(
	                'controller' => 'items',
	                'action' => 'status',
	                'id' => $item->id
	            ));
	        }
	        else
	        {
	            // Create information message.
	            $message = array('Item ' . $item_code . ' could not be found');
	        }
	    }
	    
	    // Render an enquiry form, and any existing messages.
	    $form = new EnquireForm();
	    $return = array('form' => $form);
	    if (isset($message))
	    {
	        $return['messages'] = $message;
	    }
	    return $return;   
	}
	
	public function statusAction()
	{
	    // Get the entity manager.
	    $em = $this->getEntityManager();
	    
	    // get the item.
	    $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
	    $item = $em->find('Inventory\Entity\Item', $id);
	    
	    // sum the receipts
	    $dql = 'SELECT sum(t.qty) FROM Inventory\Entity\Transaction t WHERE t.item = ?1';
	    $qty = $em->createQuery($dql)
	              ->setParameter(1, $item)
	              ->getSingleScalarResult();
	    
	    // Deal with null.
	    if (!$qty)
	    {
	        $qty = 0;
	    }
	    
	    // Pass to the view.
	    return(array(
	        'item' => $item,
	        'qty'  => $qty
	    ));
	}
	
	public function locationsAction()
	{
	    // Get the entity manager.
	    $em = $this->getEntityManager();
	     
	    // get the item.
	    $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
	    $item = $em->find('Inventory\Entity\Item', $id);
	    
	    // sum the transactions
	    $dql = 'SELECT l.location_code, l.location_name, sum(t.qty) ' . 
	           'FROM Inventory\Entity\Location l, Inventory\Entity\Transaction t ' . 
	           'WHERE t.item = ?1 AND t.location = l ' .
	           'GROUP BY l.location_code, l.location_name';
	    $locations = $em->createQuery($dql)
	                    ->setParameter(1, $item)
	                    ->getResult(Query::HYDRATE_ARRAY);
	    
	    // Pass to the view.
	    return array(
	        'item' => $item,
	        'locations' => $locations
	    );
	}
	
	public function movementsAction()
	{
	    $aColumns = array('t.trans_date', 'i.item_code', 'm.name', 't.qty', 'l.location_name');
	    
	    // Get parameters from the request
	    $sEcho          = $this->params()->fromQuery('sEcho');
	    $iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
	    $iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
	    $iSortingCols   = $this->params()->fromQuery('iSortingCols', 1);
	    $iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
	    $sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
	    $sSearch        = $this->params()->fromQuery('sSearch', '');
	    
	    // Build the from clause.
	    $from = 'FROM Inventory\Entity\Transaction t ' .
	       	    'JOIN t.item i ' .
	       	    'JOIN t.movement_type m ' .
	       	    'JOIN t.location l ';
	    
	    // Get record count.
	    $dql = 'SELECT COUNT(t.id) ' . $from;
	    $where = '';
	    if($sSearch != '')
	    {
	        $where = "WHERE i.item_code LIKE '%" . $sSearch . "%' OR " .
	                 "m.name LIKE '%" . $sSearch . "%' OR " . 
	                 "l.location_name LIKE '%" . $sSearch . "%' ";
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
	    
	    // Build the query.
	    $dql = 'SELECT t, i, m, l ' . $from . $where  . $orderBy;
	    $query = $this->getEntityManager()->createQuery($dql);
	    $query->setFirstResult($iDisplayStart);
	    $query->setMaxResults($iDisplayLength);
	    
	    // Build the view model, passing in all relevant data.
	    $variables = array(
	            "sEcho" => $sEcho,
	            "iTotalRecords" => $count,
	            "iTotalDisplayRecords" => $count,
	            "transactions" => $query->getResult()
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
	        $view->setTemplate('inventory/items/movements_data');
	    }
	    
	    // Finally return the view
	    return $view;
	}
	
	public function valuationAction()
	{
	    // Get the entity manager.
	    $em = $this->getEntityManager();
	    
	    // Build and excute the query.
	    // This sums the transactions and groups by item and location.
	    $dql = 'SELECT i.item_code, i.short_description, l.location_code, l.location_name, sum(t.qty) ' .
	       	    'FROM Inventory\Entity\Transaction t JOIN t.item i JOIN t.location l ' .
	       	    'GROUP BY i.item_code, i.short_description, l.location_code, l.location_name';
	    $items = $em->createQuery($dql)
	                ->getResult(Query::HYDRATE_ARRAY);
	    
	    // Pass to the view.
	    return array('items' => $items);
	}
	
}