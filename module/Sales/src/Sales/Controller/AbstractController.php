<?php
namespace Sales\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractController extends AbstractActionController
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
    
    protected function getDataTablesParams()
    {
        return array(
            'sEcho'          => $this->params()->fromQuery('sEcho'),
            'iDisplayStart'  => $this->params()->fromQuery('iDisplayStart', 0),
            'iDisplayLength' => $this->params()->fromQuery('iDisplayLength', 10),
            'iSortingCols'   => $this->params()->fromQuery('iSortingCols', 1),
            'iSortIndex'     => $this->params()->fromQuery('iSortCol_0', 0),
            'sSortDir'       => $this->params()->fromQuery('sSortDir_0', 'asc'),
            'sSearch'        => $this->params()->fromQuery('sSearch', '')
        );
    }
}