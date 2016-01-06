<?php

namespace Sales\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class OrderFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new OrderForm();
        $form->setInputFilter(new OrderFilter());
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $hydrator = new DoctrineHydrator($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }   
}