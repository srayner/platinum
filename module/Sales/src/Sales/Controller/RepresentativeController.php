<?php

namespace Sales\Controller;

use Zend\View\Model\ViewModel;
use Sales\Form\ConfirmationForm;
use Doctrine\ORM\Query;
        
class RepresentativeController extends AbstractController
{
    public function indexAction()
    {
        $aColumns = array('r.firstname', 'r.lastname', 'r.description');
        $params = $this->getDataTablesParams();
        
        // Build the from clause.
        $from = 'FROM Sales\Entity\Representative r ';
        
        // Get record count.
        $dql = 'SELECT COUNT(r.id) ' . $from;
        $where = '';
        if($params['sSearch'] != '')
        {
            $where = "WHERE r.description LIKE '%" . $params['sSearch'] . "%' ";
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
        $dql = 'SELECT r ' . $from . $where  . $orderBy;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($params['iDisplayStart']);
        $query->setMaxResults($params['iDisplayLength']);
        
        // Build the view models, passing in all relevant data.
        $variables = array(
            "sEcho" => $params['sEcho'],
            "iTotalRecords" => $count,
            "iTotalDisplayRecords" => $count,
            'representative' => $query->getResult()
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
            $view->setTemplate('sales/representative/data');
	}
		
        // Finally return the view
        return $view;
    }
    
    public function addAction()
    {
        return new ViewModel(array(
            
        ));
    }
    
    public function editAction()
    {
        return new ViewModel(array(
            
        ));
    }
    
    public function deleteAction()
    {
        return new ViewModel(array(
            
        ));
    }
}