<?php

namespace Sales\Controller;

use Zend\View\Model\ViewModel;
use Sales\Form\ConfirmationForm;
use Doctrine\ORM\Query;
        
class AreaController extends AbstractController
{
    public function indexAction()
    {
        $aColumns = array('a.code', 'a.description');
        $params = $this->getDataTablesParams();
        
        // Build the from clause.
        $from = 'FROM Sales\Entity\Area a ';
        
        // Get record count.
        $dql = 'SELECT COUNT(a.id) ' . $from;
        $where = '';
        if($params['sSearch'] != '')
        {
            $where = "WHERE a.description LIKE '%" . $params['sSearch'] . "%' ";
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
        $dql = 'SELECT a ' . $from . $where . $orderBy;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($params['iDisplayStart']);
        $query->setMaxResults($params['iDisplayLength']);
        
        // Build the view models, passing in all relevant data.
        $variables = array(
            "sEcho" => $params['sEcho'],
            "iTotalRecords" => $count,
            "iTotalDisplayRecords" => $count,
            'areas' => $query->getResult()
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
            $view->setTemplate('sales/area/data');
	}
        
        // Finally return the view
        return $view;
    }
    
    public function addAction()
    {
        // Create a new form instance.
	$form = $this->getServiceLocator()->get('sales_area_form');

	// Check if the request is a POST
	$request = $this->getRequest();
	if ($request->isPost())
	{		
	    // Create a new Account instance.
	    $area = $this->getServiceLocator()->get('sales_area');

	    // Check form is valid.
            $form->bind($area);
	    $form->setData($request->getPost());
	    if ($form->isValid())
	    {
		$this->getEntityManager()->persist($area);
		$this->getEntityManager()->flush();

		// Create information message.
		$this->flashMessenger()->addMessage('Area ' . $area->getCode() . ' sucesfully added');
				
		// Redirect to list of areas
		return $this->redirect()->toRoute('sales/default', array('controller' => 'area'));
	    }
	}

	// If not a POST request, or invalid data, then just render the form.
	return array('form' => $form);
        
    }
    
    public function editAction()
    {
        // Ensure we have an id, otherwise redirect to add action.
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('sales/default', array('controller' => 'area', 'action'=>'add'));
        }
	
        // Grab a copy of the area entity.
        $area= $this->getEntityManager()->find('Sales\Entity\Area', $id);
        
        // Create a new form instance and bind the entity to it.
	$form = $this->getServiceLocator()->get('sales_area_form');
        $form->bind($area);
		
	// Check if this request is a POST.
	$request = $this->getRequest();
	if ($request->isPost())
        {	
            // Validate the data.
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                // Persist changes.
                $this->getEntityManager()->persist($area);
                $this->getEntityManager()->flush();

                // Create information message.
                $this->flashMessenger()->addMessage('Area ' . $area->getCode() . ' sucesfully updated');
				
                // Redirect to list of areas
		return $this->redirect()->toRoute('sales/default', array('controller' => 'area'));
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
            return $this->redirect()->toRoute('sales/default', array('controller' => 'area'));
        }
		
        // Create a new form instance.
        $form = new ConfirmationForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('yes', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
		$area = $this->getEntityManager()->find('Sales\Entity\Area', $id);
		if ($area) {
					
                    // Delete from the database.
		    $this->getEntityManager()->remove($area);
		    $this->getEntityManager()->flush();
					
		    // Create information message.
		    $this->flashMessenger()->addMessage('Area ' . $area->getCode() . ' sucesfully deleted');
		}
	    }

	    // Redirect to list of categories
	    return $this->redirect()->toRoute('sales/default', array('controller' => 'area'));
	}

	$form->populateValues(array('id' => $id));
	return array(
	    'area' => $this->getEntityManager()->find('Sales\Entity\Area', $id),
            'form' => $form
	);
        
    }
    
    public function dataAction()
    {
        
    }
}