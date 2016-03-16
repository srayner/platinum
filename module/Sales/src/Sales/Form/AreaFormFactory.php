<?php

namespace Sales\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AreaFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new AreaForm();
        $form->setInputFilter(new AreaFilter());
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $hydrator = new DoctrineHydrator($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }   
}
