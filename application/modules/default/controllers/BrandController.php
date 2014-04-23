<?php

class BrandController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow();
		
	}
	
	public function preDispatch() 
	{
		$this->BrandService = new BrandService();
		$this->CategoryService = new CategoryService();
	}

    public function indexAction()
    {
		$this->view->rowsBrand = $this->BrandService->getAllData();
		$this->view->rowsCategory = $this->CategoryService->getAllData();
    }

	public function compareAction()
    {
        // action body
    }
	
	public function finderAction()
    {
        // action body
    }

}