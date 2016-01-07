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
}