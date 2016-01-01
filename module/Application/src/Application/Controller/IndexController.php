<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    // The main home page.
	public function indexAction()
    {
        return new ViewModel();
    }
    
    // The system menu page.
    public function systemIndexAction()
    {
        
    	return new ViewModel();
    }
}
