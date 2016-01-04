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
        
        // Get parameters from the ajax request
        $sEcho          = $this->params()->fromQuery('sEcho');
        $iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
        $iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
        $iSortingCols   = $this->params()->fromQuery('iSortingCols', 1);
        $iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
        $sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
        $sSearch        = $this->params()->fromQuery('sSearch', '');
        
        // Build the from clause.
        $from = 'FROM Sales\Entity\Branch b ';
		
        // Get record count.
        $dql = 'SELECT COUNT(b.id) ' . $from;
        $where = '';
        if($sSearch != '')
        {
            $where = "WHERE b.company_name LIKE '%" . $sSearch . "%' ";
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
		
        // Build query.
        $dql = 'SELECT b ' . $from . $where  . $orderBy;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($iDisplayStart);
        $query->setMaxResults($iDisplayLength);
		
        // Build the view models, passing in all relevant data.
        $variables = array(
            "sEcho" => $sEcho,
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
}