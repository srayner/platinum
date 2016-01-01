<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,
    Application\Entity\Event,
    Application\Form\EventForm;

class CalendarController extends AbstractActionController
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
    
    // The main home page.
    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {
        // Create a new form instance.
        $form = new EventForm();
        
        // Check if the request is a POST
        $request = $this->getRequest();
        if ($request->isPost())
        {
            // Create a new Event intance.
            $event = new Event();
        
            // Pull the validator from entity and check form is valid.
            $form->setInputFilter($event->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                // Populate the item object and persist it to the database.
                //die(var_dump($form->getData()));
                $event->populate($form->getData());
                $this->getEntityManager()->persist($event);
                $this->getEntityManager()->flush();
        
                // Create information message.
                $this->flashMessenger()->addMessage('Event ' . $event->name . ' sucesfully added');
        
                // Redirect to list of units
                return $this->redirect()->toRoute('calendar/default', array('controller' => 'calendar'));
            }
        }
        
        // If not a POST request, then just render the form.
        return array('form' => $form);
        
    }
    
    public function eventsAction()
    {
        $start = (int)$this->params()->fromQuery('start');
        $end = (int)$this->params()->fromQuery('end');
        
        $dql = 'SELECT e FROM Application\Entity\Event e WHERE e.start >= ?1 AND e.start <= ?2';
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(1, date('Y-m-d H:i:s', $start));
        $query->setParameter(2, date('Y-m-d H:i:s', $end));
        $results = $query->getResult();
        
        $view = new ViewModel(array("events" => $results));
        $view->setTerminal(true);
        return $view;
    }
    
    public function updateAction()
    {
        $id = (int)$this->params()->fromQuery('id');
        $start = substr($this->params()->fromQuery('start'), 0, 33);
        $end = substr($this->params()->fromQuery('end'), 0, 33);
        $allday = ($this->params()->fromQuery('allDay') == 'true');
        
        $event = $this->getEntityManager()->find('Application\Entity\Event', $id);
        $event->setStart($start);
        $event->setEnd($end);
        $event->setAllday($allday);
        $this->getEntityManager()->flush();
        
        // Short circuit the view layer completly.
        return $this->response;
    }
}
