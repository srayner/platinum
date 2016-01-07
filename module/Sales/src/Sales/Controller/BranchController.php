<?php

namespace Sales\Controller;

use Zend\View\Model\ViewModel;
use Sales\Form\ConfirmationForm;
use Doctrine\ORM\Query;
        
class BranchController extends AbstractController
{
    public function indexAction()
    {
        $aColumns = array('b.branchNumber', 'b.companyName');
        $params = $this->getDataTablesParams();
        
        // Build the from clause.
        $from = 'FROM Sales\Entity\Branch b ';
		
        // Get record count.
        $dql = 'SELECT COUNT(b.id) ' . $from;
        $where = '';
        if($params['sSearch'] != '')
        {
            $where = "WHERE b.company_name LIKE '%" . $params['sSearch'] . "%' ";
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
        $dql = 'SELECT b ' . $from . $where  . $orderBy;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($params['iDisplayStart']);
        $query->setMaxResults($params['iDisplayLength']);
		
        // Build the view models, passing in all relevant data.
        $variables = array(
            "sEcho" => $params['sEcho'],
            "iTotalRecords" => $count,
            "iTotalDisplayRecords" => $count,
            'branches' => $query->getResult()
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
            $view->setTemplate('sales/branch/data');
	}
		
        // Finally return the view
        return $view;
    }
    
    public function addAction()
    {
        // Create a new form instance.
	$form = $this->getServiceLocator()->get('sales_branch_form');

	// Check if the request is a POST
	$request = $this->getRequest();
	if ($request->isPost())
	{		
	    // Create a new Account instance.
	    $branch = $this->getServiceLocator()->get('sales_branch');

	    // Check form is valid.
            $form->bind($branch);
	    $form->setData($request->getPost());
	    if ($form->isValid())
	    {
		$this->getEntityManager()->persist($branch);
		$this->getEntityManager()->flush();

		// Create information message.
		$this->flashMessenger()->addMessage('Branch ' . $branch->getBranchNumber() . ' sucesfully added');
				
		// Redirect to list of branches
		return $this->redirect()->toRoute('sales/default', array('controller' => 'branch'));
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
            return $this->redirect()->toRoute('sales/default', array('controller' => 'branch', 'action'=>'add'));
        }
	
        // Grab a copy of the branch entity.
        $branch = $this->getEntityManager()->find('Sales\Entity\Branch', $id);
        
        // Create a new form instance and bind the entity to it.
	$form = $this->getServiceLocator()->get('sales_branch_form');
        $form->bind($branch);
		
	// Check if this request is a POST.
	$request = $this->getRequest();
	if ($request->isPost())
        {	
            // Validate the data.
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                // Persist changes.
                $this->getEntityManager()->persist($branch);
                $this->getEntityManager()->flush();

                // Create information message.
                $this->flashMessenger()->addMessage('Branch ' . $branch->getBranchNumber() . ' sucesfully updated');
				
                // Redirect to list of branches
		return $this->redirect()->toRoute('sales/default', array('controller' => 'branch'));
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
            return $this->redirect()->toRoute('sales/default', array('controller' => 'branch'));
        }
		
        // Create a new form instance.
        $form = new ConfirmationForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('yes', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
		$branch = $this->getEntityManager()->find('Sales\Entity\Branch', $id);
		if ($branch) {
					
                    // Delete from the database.
		    $this->getEntityManager()->remove($branch);
		    $this->getEntityManager()->flush();
					
		    // Create information message.
		    $this->flashMessenger()->addMessage('Branch ' . $branch->getBranchNumber() . ' sucesfully deleted');
		}
	    }

	    // Redirect to list of branches
	    return $this->redirect()->toRoute('sales/default', array('controller' => 'branch'));
	}

	$form->populateValues(array('id' => $id));
	return array(
	    'branch' => $this->getEntityManager()->find('Sales\Entity\Branch', $id),
            'form' => $form
	);
    }
}