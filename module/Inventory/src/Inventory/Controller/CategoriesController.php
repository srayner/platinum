<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Inventory\Form\CategoryForm,
    Inventory\Form\ConfirmationForm,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Inventory\Entity\Category;

class CategoriesController extends AbstractInventoryController
{
	public function indexAction()
	{
		$aColumns = array('c.name');
		
		// Get parameters from the ajax request
		$sEcho          = $this->params()->fromQuery('sEcho');
		$iDisplayStart  = $this->params()->fromQuery('iDisplayStart', 0);
		$iDisplayLength = $this->params()->fromQuery('iDisplayLength', 10);
		$iSortingCols   = $this->params()->fromQuery('iSortingCols', 1);
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
		
		// Build query.
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

				// Create information message.
				$this->flashMessenger()->addMessage('Category ' . $category->name . ' sucesfully added');
				
				// Redirect to list of categories
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'categories'));
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
			return $this->redirect()->toRoute('inventory/default', array('controller' => 'categories', 'action'=>'add'));
		}
		$category = $this->getEntityManager()->find('Inventory\Entity\Category', $id);

		// Create a new form instance and bind the entity to it.
		$form = new CategoryForm();
		$form->bind($category);
		$form->get('submit')->setAttribute('label', 'Edit');
		
		// Check if this request is a POST.
		$request = $this->getRequest();
		if ($request->isPost())
		{	
		    // Validate the data.
			$form->setData($request->getPost());
			if ($form->isValid())
			{
				// Persist changes.
				$this->getEntityManager()->persist($category);
				$this->getEntityManager()->flush();

				// Create information message.
				$this->flashMessenger()->addMessage('Category ' . $category->name . ' sucesfully updated');
				
				// Redirect to list of categories
				return $this->redirect()->toRoute('inventory/default', array('controller' => 'categories'));
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
			return $this->redirect()->toRoute('inventory/default', array('controller' => 'categories'));
		}
		
		// Create a new form instance.
		$form = new ConfirmationForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost()->get('yes', 'No');
			if ($del == 'Yes') {
				$id = (int)$request->getPost()->get('id');
				$category = $this->getEntityManager()->find('Inventory\Entity\Category', $id);
				if ($category) {
					
				    // Delete from the database.
				    $this->getEntityManager()->remove($category);
					$this->getEntityManager()->flush();
					
					// Create information message.
					$this->flashMessenger()->addMessage('Category ' . $category->name . ' sucesfully deleted');
				}
			}

			// Redirect to list of categories
			return $this->redirect()->toRoute('inventory/default', array(
					'controller' => 'categories',
					'action' => 'index',
			));
		}

		$form->populateValues(array('id' => $id));
		return array(
				'id' => $id,
				'category' => $this->getEntityManager()->find('Inventory\Entity\Category', $id)->getArrayCopy(),
		        'form' => $form
		);
	}

}