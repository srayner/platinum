<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\MovementTypeForm,
    Inventory\Form\ConfirmationForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\MovementType;

class MovementTypesController extends AbstractInventoryController
{
    public function indexAction()
    {
        $aColumns = array('t.code', 't.name');
        
        // Get parameters from the ajax request
        $sEcho          = $this->params()->fromQuery('sEcho');
        $iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
        $iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
        $iSortingCols   = $this->params()->fromQuery('iSortingCols', 0);
        $iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
        $sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
        $sSearch        = $this->params()->fromQuery('sSearch', '');
        
        // Build the from clause.
        $from = 'FROM Inventory\Entity\MovementType t ';
        
        // Get record count.
        $dql = 'SELECT COUNT(t.id) ' . $from;
        $where = '';
        if($sSearch != '')
        {
            $where = "WHERE t.code LIKE '%" . $sSearch . "%' OR " .
                     "t.name LIKE '%" . $sSearch . "%' ";
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
        $dql = 'SELECT t ' . $from . $where  . $orderBy;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($iDisplayStart);
        $query->setMaxResults($iDisplayLength);
        
        // Build the view models, passing in all relevant data.
        $variables = array(
                "sEcho" => $sEcho,
                "iTotalRecords" => $count,
                "iTotalDisplayRecords" => $count,
                'types' => $query->getResult()
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
            $view->setTemplate('inventory/movement-types/data');
        }
        
        // Finally return the view
        return $view;
    }
    
    public function addAction()
    {
        // Create a new form instance.
        $form = new MovementTypeForm();
    
        // Check if the request is a POST
        $request = $this->getRequest();
        if ($request->isPost())
        {
            // Create a new MovementType intance.
            $type = new MovementType();
    
            // Pull the validator from entity and check form is valid.
            $form->setInputFilter($type->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                // Populate the type object and persist it to the database.
                $type->populate($form->getData());
                $this->getEntityManager()->persist($type);
                $this->getEntityManager()->flush();
    
                // Create information message.
                $this->flashMessenger()->addMessage('Movement type ' . $type->code . ' sucesfully added');
                
                // Redirect to list of movement types
                return $this->redirect()->toRoute('inventory/default', array('controller' => 'movement-types'));
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
            return $this->redirect()->toRoute('inventory/default', array('controller' => 'movementtypes', 'action'=>'add'));
        }
        $type = $this->getEntityManager()->find('Inventory\Entity\MovementType', $id);
    
        // Create a new instance of the form and bind the entity to it.
        $form = new MovementTypeForm();
        $form->bind($type);
    
        // Check if this is a POST request.
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            // Validate the data.
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                // Persist changes to database.
                $this->getEntityManager()->persist($type);
                $this->getEntityManager()->flush();
                
                // Create information message.
                $this->flashMessenger()->addMessage('Movement type ' . $type->code . ' successfully updated');
                
                // Redirect to list of types
                return $this->redirect()->toRoute('inventory/default', array('controller' => 'movementtypes'));
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
            return $this->redirect()->toRoute('inventory/default', array('controller' => 'movementtypes'));
        }
    
        // Create a new form instance.
        $form = new ConfirmationForm();
    
        // Check if request is a POST.
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $del = $request->getPost()->get('yes', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $type = $this->getEntityManager()->find('Inventory\Entity\MovementType', $id);
                if ($type) {
                    	
                    // Delete the type.
                    $this->getEntityManager()->remove($type);
                    $this->getEntityManager()->flush();
                    	
                    // Create information message.
                    $this->flashMessenger()->addMessage('Movement type ' . $type->code . ' sucesfully deleted');
                    	
                }
            }
    
            // Redirect to list of items
            return $this->redirect()->toRoute('inventory/default', array(
                    'controller' => 'movementtypes',
                    'action' => 'index',
            ));
        }
    
        $form->populateValues(array('id' => $id));
        return array(
                'id' => $id,
                'type' => $this->getEntityManager()->find('Inventory\Entity\MovementType', $id)->getArrayCopy(),
                'form' => $form
        );
    }
}