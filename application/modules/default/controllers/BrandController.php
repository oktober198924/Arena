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
		$this->TypeSpecService = new TypeSpecService();
	}

    public function indexAction()
    {	
    	$id = $this->_getParam('id');
		$this->view->rowsBrand = $this->BrandService->getAllData();
		$this->view->rows = $this->TypeSpecService->getDataCopmlite($id);
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