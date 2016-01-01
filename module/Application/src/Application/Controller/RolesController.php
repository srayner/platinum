<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use	Zend\View\Model\ViewModel;
use	Application\Form\RoleForm;
use	Doctrine\ORM\EntityManager;
use	Doctrine\ORM\Query;
use	Application\Entity\Role;
use Zend\Stdlib\Hydrator\ClassMethods;

class RolesController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}

	public function indexAction()
	{
		$aColumns = array('r.name', 'r.default', 'r.parent');
		
		// Get parameters from the request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 0);
		$iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
		$sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
		$sSearch        = $this->params()->fromQuery('sSearch', '');
		
		// Build the from clause.
		$from = 'FROM Application\Entity\Role r '; // We could add joins here.
		
		// Get record count.
		$dql = 'SELECT COUNT(r.id) ' . $from;
		$where = '';
		if($sSearch != '')
		{
			$where = "WHERE r.name LIKE '%" . $sSearch . "%'";
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
		$dql = 'SELECT r ' . $from . $where . $orderBy;
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setFirstResult($iDisplayStart);
		$query->setMaxResults($iDisplayLength);
		
		// Build the view model, passing in all relevant data.
		$variables = array(
				"sEcho" => $sEcho,
				"iTotalRecords" => $count,
				"iTotalDisplayRecords" => $count,
				'roles' => $query->getResult()
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
			$view->setTemplate('application/roles/data');
		}
		
		// Finally return the view
		return $view;
	}
	
	public function addAction()
	{
		// Create a new form instance.
		$form = new RoleForm();
	
		// Check if the request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{
			// Create a new Role intance.
			$role = new Role();
	
			// Pull the validator from entity and check form is valid.
			$form->setInputFilter($role->getInputFilter());
			$form->setHydrator(new ClassMethods(false));
			$form->bind($role);
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Role is populated, persist to the database.
				$this->getEntityManager()->persist($role);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('Role ' . $user->getName() . ' sucesfully added');
	
				// Redirect to list of roles
				return $this->redirect()->toRoute('roles');
			}
		}
	
		// If not a POST request, then just render the form.
		return array('form' => $form);
	}
	
	
}