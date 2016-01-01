<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\PostingForm,
    Inventory\Form\TransferForm,
    Inventory\Entity\Transaction,
    Inventory\Entity\Item,
    Inventory\Entity\Location,
    Inventory\Entity\MovementType;

class PostingController extends AbstractInventoryController
{
    public function bookoutAction()
    {
        // Create a new form instance.
        $form = new PostingForm($this->getEntityManager());
        
        // Check if the request is a POST.
        $request = $this->getRequest();
        if ($request->isPost())
        {
            // Get data from post.
            $movement_type_id = MovementType::GOODS_BOOKOUT;
            $location_id = (int)$this->params()->fromPost('location_id');
            $item_code = $this->params()->fromPost('item_code');
            $qty = -(int)$this->params()->fromPost('qty');
            
            // Find the required entities.
            $type = $this->getEntityManager()->find('Inventory\Entity\MovementType', $movement_type_id);
            $location = $this->getEntityManager()->find('Inventory\Entity\Location', $location_id);
            $item = $this->getEntityManager()->getRepository('Inventory\Entity\Item')->findOneBy(array('item_code' => $item_code));

            // Create the transaction and persist to database.
            $transaction = new Transaction($type, $item, $location, $qty);
            $this->getEntityManager()->persist($transaction);
            $this->getEntityManager()->flush();
            
            // Create information message.
            $this->flashMessenger()->addMessage('Goods booked out sucesfully.');
            
            // Redirect to inventory index page
            return $this->redirect()->toRoute('inventory/default', array('controller' => 'index'));
        }
        
        // Render the form.
        return array('form' => $form);
        
    }   
    
    public function receiptAction()
    {
        // Create a new form instance.
        $form = new PostingForm($this->getEntityManager());
        
        // Check if the request is a POST.
        $request = $this->getRequest();
        if ($request->isPost())
        {
            // Pull the validator from entity and check form is valid.
            //$form->setInputFilter($item->getInputFilter());
            //$form->setData($request->getPost());
            //if ($form->isValid())
            //{
                // Get data from post.
                $movement_type_id = MovementType::GOODS_RECEIPT;
                $location_id = (int)$this->params()->fromPost('location_id');
                $item_code = $this->params()->fromPost('item_code');
                $qty = (int)$this->params()->fromPost('qty');
                
                // Find the required entities.
                $type = $this->getEntityManager()->find('Inventory\Entity\MovementType', $movement_type_id);
                $location = $this->getEntityManager()->find('Inventory\Entity\Location', $location_id);
                $item = $this->getEntityManager()->getRepository('Inventory\Entity\Item')->findOneBy(array('item_code' => $item_code));
                 
                // Create the transaction and persist to database.
                $transaction = new Transaction($type, $item, $location, $qty);
                $this->getEntityManager()->persist($transaction);
                $this->getEntityManager()->flush();
        
                // Create information message.
                $this->flashMessenger()->addMessage('Goods receipt posted sucesfully.');
        
                // Redirect to inventory index page
                return $this->redirect()->toRoute('inventory/default', array('controller' => 'index'));
            //}
        }
        
        // If not a POST request, then just render the form.
        return array('form' => $form);
    }
    
    public function adjustmentAction()
    {
        // Create a new form instance.
        $form = new PostingForm($this->getEntityManager());
    
        // Check if the request is a POST.
        $request = $this->getRequest();
        if ($request->isPost())
        {
            // Get data from post.
            $movement_type_id = MovementType::STOCK_ADJUSTMENT;
            $location_id = (int)$this->params()->fromPost('location_id');
            $item_code = $this->params()->fromPost('item_code');
            $qty = (int)$this->params()->fromPost('qty');
    
            // Find the required entities.
            $type = $this->getEntityManager()->find('Inventory\Entity\MovementType', $movement_type_id);
            $location = $this->getEntityManager()->find('Inventory\Entity\Location', $location_id);
            $item = $this->getEntityManager()->getRepository('Inventory\Entity\Item')->findOneBy(array('item_code' => $item_code));
             
            // Create the transaction and persist to database.
            $transaction = new Transaction($type, $item, $location, $qty);
            $this->getEntityManager()->persist($transaction);
            $this->getEntityManager()->flush();
    
            // Create information message.
            $this->flashMessenger()->addMessage('Stock adjusted successfully.');
    
            // Redirect to inventory index page
            return $this->redirect()->toRoute('inventory/default', array('controller' => 'index'));
        }
    
        // If not a POST request, then just render the form.
        return array('form' => $form);
    }
    
    public function transferAction()
    {
        // Create a new form instance.
        $form = new TransferForm($this->getEntityManager());
        
        // Check if the request is a POST.
        $request = $this->getRequest();
        if ($request->isPost())
        {
            // TODO: Validate the form.
             
                // Get data from post.
                $movement_type_id = MovementType::STOCK_TRANSFER;
                $from_location_id = (int)$this->params()->fromPost('from_location_id');
                $to_location_id = (int)$this->params()->fromPost('to_location_id');
                $item_code = $this->params()->fromPost('item_code');
                $qty = (int)$this->params()->fromPost('qty');
                
                // Find the required entities.
                $item = $this->getEntityManager()->getRepository('Inventory\Entity\Item')->findOneBy(array('item_code' => $item_code));
                $type = $this->getEntityManager()->find('Inventory\Entity\MovementType', $movement_type_id);
                $from = $this->getEntityManager()->find('Inventory\Entity\Location', $from_location_id);
                $to = $this->getEntityManager()->find('Inventory\Entity\Location', $to_location_id);
                
                // Create and populate the transactions.
                $fromTran = new Transaction($type, $item, $from, 0 - $qty);
                $toTran = new Transaction($type, $item, $to, $qty);
                
                // Persist to database.
                $this->getEntityManager()->persist($fromTran);
                $this->getEntityManager()->persist($toTran);
                $this->getEntityManager()->flush();
                
                // Create information message.
                $this->flashMessenger()->addMessage('Stock sucesfully transfered.');
                
                // Redirect to inventory index page
                return $this->redirect()->toRoute('inventory/default', array('controller' => 'index'));
                
        }
        
        // If not a POST request, then just render the form.
        return array('form' => $form);
    }
	
}