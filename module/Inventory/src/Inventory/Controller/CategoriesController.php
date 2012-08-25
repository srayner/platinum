<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\CategoryForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\Category;

class CategoriesController extends AbstractActionController
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
		$aColumns = array('c.name');
		
		// Get parameters from the ajax request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 0);
		$iSortIndex     = $this->params()->fromQuery('iSortCol_0', 0);
		$sSortDir       = $this->params()->fromQuery('sSortDir_0', 'asc');
		$sSearch        = $this->params()->fromQuery('sSearch', '');
		
		// Build the from clause.
		$from = 'FROM Inventory\Entity\Category c ';
		
		// Get record count.
		$dql = 'SELECT COUNT(c.id) ' . $from;
		$where = '';
		if($sSearch != '')
		{
			$where = "WHERE c.name LIKE '%" . $sSearch . "%' ";
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
		$dql = 'SELECT c ' . $from . $where  . $orderBy;
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setFirstResult($iDisplayStart);
		$query->setMaxResults($iDisplayLength);
		
		// Build the view models, passing in all relevant data.
		$variables = array(
				"sEcho" => $sEcho,
				"iTotalRecords" => $count,
				"iTotalDisplayRecords" => $count,
				'categories' => $query->getResult()
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
			$view->setTemplate('inventory/categories/data');
		}
		
		// Finally return the view
		return $view;
	  
	}

	public function addAction()
	{
		// Create a new form instance.
		$form = new CategoryForm();

		// Check if the request is a POST
		$request = $this->getRequest();
		if ($request->isPost())
		{		
			// Create a new Item intance.
			$category = new Category();

			// Pull the validator from entity and check form is valid.
			$form->setInputFilter($category->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Populate the item object and persist it to the database.
				$category->populate($form->getData());
				$this->getEntityManager()->persist($category);
				$this->getEntityManager()->flush();

				// Redirect to list of albums
				return $this->redirect()->toRoute('categories');
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
			return $this->redirect()->toRoute('categories', array('action'=>'add'));
		}
		$category = $this->getEntityManager()->find('Inventory\Entity\Category', $id);

		// Create a new form instance.
		$form = new CategoryForm();
		
		// Check if this request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{	
			// Pull the validator from the entity, populate the form and check it's valid.
			$form->setInputFilter($category->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Populate the entity with values from the form, and persist cahanges.
				$category->populate($form->getData());
				$this->getEntityManager()->persist($category);
				$this->getEntityManager()->flush();

				// Redirect to list of categories
				return $this->redirect()->toRoute('categories');
			}
		}

		// If request is GET then render the form with current values from entity.
		$form->populateValues($category->toArray());
		return array(
				'id' => $id,
				'form' => $form,
		);
	}

	public function deleteAction()
	{
		$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			return $this->redirect()->toRoute('categories');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost()->get('del', 'No');
			if ($del == 'Yes') {
				$id = (int)$request->getPost()->get('id');
				$category = $this->getEntityManager()->find('Inventory\Entity\Category', $id);
				if ($category) {
					$this->getEntityManager()->remove($category);
					$this->getEntityManager()->flush();
				}
			}

			// Redirect to list of categories
			return $this->redirect()->toRoute('default', array(
					'controller' => 'categories',
					'action' => 'index',
			));
		}

		return array(
				'id' => $id,
				'category' => $this->getEntityManager()->find('Inventory\Entity\Category', $id)->getArrayCopy()
		);
	}

}