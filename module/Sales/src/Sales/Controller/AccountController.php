<?php

namespace Sales\Controller;

use Zend\View\Model\ViewModel;
use Doctrine\ORM\Query;
        
class AccountController extends AbstractController
{
    public function indexAction()
    {
        $aColumns = array('a.accountNumber', 'a.companyName');
        
        // Get parameters from the ajax request
        $sEcho          = $this->params()->fromQuery('sEcho');
        $iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
        $iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
        $iSortingCols   = $this->params()->fromQuery('iSortingCols', 1);
        $iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
        $sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
        $sSearch        = $this->params()->fromQuery('sSearch', '');
        
        // Build the from clause.
        $from = 'FROM Sales\Entity\Account a ';
		
        // Get record count.
        $dql = 'SELECT COUNT(a.id) ' . $from;
        $where = '';
        if($sSearch != '')
        {
            $where = "WHERE a.company_name LIKE '%" . $sSearch . "%' ";
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
        $dql = 'SELECT a ' . $from . $where  . $orderBy;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($iDisplayStart);
        $query->setMaxResults($iDisplayLength);
		
        // Build the view models, passing in all relevant data.
        $variables = array(
            "sEcho" => $sEcho,
            "iTotalRecords" => $count,
            "iTotalDisplayRecords" => $count,
            'accounts' => $query->getResult()
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
            $view->setTemplate('sales/account/data');
	}
		
        // Finally return the view
        return $view;
    }
    
    public function addAction()
    {
        // Create a new form instance.
	$form = $this->getServiceLocator()->get('sales_account_form');

	// Check if the request is a POST
	$request = $this->getRequest();
	if ($request->isPost())
	{		
	    // Create a new Account instance.
	    $account = $this->getServiceLocator()->get('sales_account');

	    // Check form is valid.
            $form->bind($account);
	    $form->setData($request->getPost());
	    if ($form->isValid())
	    {
		$this->getEntityManager()->persist($account);
		$this->getEntityManager()->flush();

		// Create information message.
		$this->flashMessenger()->addMessage('Account ' . $account->account_name . ' sucesfully added');
				
		// Redirect to list of accounts
		return $this->redirect()->toRoute('sales/default', array('controller' => 'account'));
	    }
	}

	// If not a POST request, or invalid data, then just render the form.
	return array('form' => $form);
    }
    
    public function editAction()
    {
        return array(
            
        );
    }
    
    public function deleteAction()
    {
        return array(
            
        );
    }
}

