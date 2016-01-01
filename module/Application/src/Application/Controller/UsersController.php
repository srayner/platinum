<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use	Zend\View\Model\ViewModel;
use	Application\Form\UserForm;
use	Doctrine\ORM\EntityManager;
use	Doctrine\ORM\Query;
use	Application\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Hydrator\ClassMethods;

class UsersController extends AbstractActionController
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
		$aColumns = array('u.username', 'u.email', 'u.displayname');
		
		// Get parameters from the request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 0);
		$iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
		$sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
		$sSearch        = $this->params()->fromQuery('sSearch', '');
		
		// Build the from clause.
		$from = 'FROM Application\Entity\User u '; // We could add joins here.
		
		// Get record count.
		$dql = 'SELECT COUNT(u.id) ' . $from;
		$where = '';
		if($sSearch != '')
		{
			$where = "WHERE u.username LIKE '%" . $sSearch . "%' OR " .
					"u.email LIKE '%" . $sSearch . "%' OR " . 
			        "u.displayName LIKE '%" . $sSearch . "%'";
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
		$dql = 'SELECT u ' . $from . $where . $orderBy;
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setFirstResult($iDisplayStart);
		$query->setMaxResults($iDisplayLength);
		
		// Build the view model, passing in all relevant data.
		$variables = array(
				"sEcho" => $sEcho,
				"iTotalRecords" => $count,
				"iTotalDisplayRecords" => $count,
				'users' => $query->getResult()
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
			$view->setTemplate('application/users/data');
		}
		
		// Finally return the view
		return $view;
	}
	
	public function addAction()
	{
		// Create a new form instance.
		$form = new UserForm();
	
		// Check if the request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{
			// Create a new Item intance.
			$user = new User();
	
			// Pull the validator from entity and check form is valid.
			$form->setInputFilter($user->getInputFilter());
			$form->setHydrator(new ClassMethods(false));
			$form->bind($user);
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// User is populated, now hash the password.
				$bcrypt = new Bcrypt;
				$bcrypt->setCost(14);
				$user->setPassword($bcrypt->create($user->getPassword()));
				
				// Persist user to the database.
				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('User ' . $user->getUsername() . ' sucesfully added');
	
				// Redirect to list of users
				return $this->redirect()->toRoute('users');
			}
		}
	
		// If not a POST request, then just render the form.
		return array('form' => $form);
	}
	
	/**
	 * Edit action
	 */
	public function editAction()
	{
		// Get a current copy of the entity.
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('users', array('action'=>'add'));
		}
		$user = $this->getEntityManager()->find('Application\Entity\User', $id);
	
		// Create a new form instance and bind to entity.
		$form = new UserForm();
		$form->remove('password');
		$form->setHydrator(new ClassMethods(false));
		$form->bind($user);
	
		// Check if this request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{
			// Pull the validator from the entity, populate the form and check it's valid.
			$form->setInputFilter($user->getInputFilter());
			$form->setValidationGroup('id', 'username', 'email', 'displayName');
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Entity is populated, so persist to database.
				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();
	
				// Create information message.
				$this->flashMessenger()->addMessage('User ' . $user->getUsername() . ' sucesfully updated');
				
				// Redirect to list of users
				return $this->redirect()->toRoute('users');
			}
			else
			{
				die('validation failing!');
			}
		}
	
		// If request is GET then render the form with current values from entity.
		return array(
				'id' => $id,
				'form' => $form,
		);
	}
}

