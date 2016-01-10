<?php

namespace Sales\Controller;

use Zend\View\Model\ViewModel;
use Sales\Form\ConfirmationForm;
use Doctrine\ORM\Query;
use DateTime;
        
class OrderController extends AbstractController
{
    public function createAction()
    {
        // Create a new form instance.
	$form = $this->getServiceLocator()->get('sales_order_form');

	// Check if the request is a POST
	$request = $this->getRequest();
	if ($request->isPost())
	{		
	    // Create a new Order instance.
	    $order = $this->getServiceLocator()->get('sales_order');

	    // Check form is valid.
            $form->bind($order);
	    $form->setData($request->getPost());
	    if ($form->isValid())
	    {
                $accountRef = $this->getEntityManager()->getReference('Sales\Entity\Account', 1);
                $branchRef  = $this->getEntityManager()->getReference('Sales\Entity\Branch', 1);
                $order->setOrderStatus('Being entered.')
                      ->setOrderDate(new DateTime())
                      ->setAccount($accountRef)
                      ->setBranch($branchRef);
		$this->getEntityManager()->persist($order);
		$this->getEntityManager()->flush();

		// Create information message.
		$this->flashMessenger()->addMessage('Order ' . $order->getId() . ' sucesfully added');
				
		// Redirect to order detail.
		return $this->redirect()->toRoute('sales/default', array(
                    'controller' => 'order',
                    'action' => 'detail',
                    'id' => $order->getId()
                ));
	    }
	}

	// If not a POST request, or invalid data, then just render the form.
	return array('form' => $form);
    }
 
    public function detailAction()
    {
        // Ensure we have an id, otherwise redirect to add action.
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('sales/default', array('controller' => 'order', 'action'=>'create'));
        }
        
        // Grab a copy of the order and pass to the view.
        return array(
            'order' => $this->getEntityManager()->find('Sales\Entity\Order', $id)
        );
    }
    
    public function indexAction()
    {
        $aColumns = array('o.id', 'o.customerRef');
        $params = $this->getDataTablesParams();
        
        // Build the from clause.
        $from = 'FROM Sales\Entity\Order o ';
        
        // Get record count.
        $dql = 'SELECT COUNT(o.id) ' . $from;
        $where = '';
        if($params['sSearch'] != '')
        {
            $where = "WHERE o.customerRef LIKE '%" . $params['sSearch'] . "%' ";
            $dql = $dql . $where;
        }
        $query = $this->getEntityManager()->createQuery($dql);
        $count = $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
        
        // Build the order by clause.
        $orderBy = '';
        if ($params['iSortingCols'] > 0)
        {
            $orderBy = 'ORDER BY ' . $aColumns[$params['iSortIndex']] . ' ' . strtoupper($params['sSortDir']) . ' ';
        }
        
        // Build query.
        $dql = 'SELECT o ' . $from . $where . $orderBy;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($params['iDisplayStart']);
        $query->setMaxResults($params['iDisplayLength']);
        
        // Build the view models, passing in all relevant data.
        $variables = array(
            "sEcho" => $params['sEcho'],
            "iTotalRecords" => $count,
            "iTotalDisplayRecords" => $count,
            'orders' => $query->getResult()
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
            $view->setTemplate('sales/order/data');
	}
		
        // Finally return the view
        return $view;
    }
    
    public function addlineAction()
    {
        // Ensure we have an id, otherwise redirect to add action.
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('sales/default', array('controller' => 'order'));
        }
        
        $order = $this->getEntityManager()->find('Sales\Entity\Order', $id);
        
        // Create a new form instance.
	$form = $this->getServiceLocator()->get('sales_line_form');

	// Check if the request is a POST
	$request = $this->getRequest();
	if ($request->isPost())
	{		
	    // Create a new Account instance.
	    $line = $this->getServiceLocator()->get('sales_order_line');
            
	    // Check form is valid.
            $form->bind($line);
	    $form->setData($request->getPost());
	    if ($form->isValid())
	    {
                $item_code = $this->params()->fromPost('item_code');
                $item = $this->getEntityManager()->getRepository('Inventory\Entity\Item')->findOneBy(array('item_code' => $item_code));
                $line->setItem($item)
                     ->setOrder($order)
                     ->setOrderStatus('Awaiting acknowledgement');
		$this->getEntityManager()->persist($line);
		$this->getEntityManager()->flush();

		// Create information message.
		$this->flashMessenger()->addMessage('Item added ok.');
				
		// Redirect to order detail
		return $this->redirect()->toRoute('sales/default', array('controller' => 'order', 'action' => 'detail', 'id' => $id));
	    }
	}
        
        
        return array(
            'order' => $order,
            'form' => $form
        );
    }
}