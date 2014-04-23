<?php

class Admin_BrandController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user');
		$this->_helper->_acl->allow('viewer');
	}
	
	public function preDispatch() 
	{
		//$this->FotoService = new FotoService();
		$this->BrandService = new BrandService();		
	}
	
	public function indexAction()
	{
		//$this->view->rows = $this->FotoService->getAllData();
		$this->view->rows = $this->BrandService->getAllData();
	}

	public function addAction()	
	{		
		if ( $this->getRequest()->isPost() ) 
		{			
			$brand = $this->getRequest()->getParam('brand');
			$this->BrandService->addData($brand) ;
			$this->_redirect('/admin/brand');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');		
		if ( $this->getRequest()->isPost() ) 
		{			
			$brand = $this->getRequest()->getParam('brand');			
			$this->BrandService->editData($id, $brand);		
			$this->_redirect('/admin/brand');
		} else {
			$this->view->row = $this->BrandService->getData($id);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->BrandService->softDeleteData($id);
		$this->_redirect('/admin/brand');
	}
}