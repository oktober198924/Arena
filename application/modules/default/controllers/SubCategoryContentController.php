<?php

class SubCategoryContentController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow();
		
	}
	
	public function preDispatch() 
	{
		//$this->max_height = 800;
		//$this->max_width = 750;
		$this->BrandService = new BrandService();
	}

    public function indexAction()
    {
        // action body
		$this->view->rowsBrand = $this->BrandService->getAllData();
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