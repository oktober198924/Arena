<?php

class Admin_CategoryController extends Zend_Controller_Action
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
		$this->CategoryService = new CategoryService();		
	}
	
	public function indexAction()
	{
		//$this->view->rows = $this->FotoService->getAllData();
		$this->view->rows = $this->CategoryService->getAllData();
	}

	public function addAction()	
	{		
		if ( $this->getRequest()->isPost() ) 
		{			
			$category = $this->getRequest()->getParam('category');
			$this->CategoryService->addData($category) ;
			$this->_redirect('/admin/category');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');		
		if ( $this->getRequest()->isPost() ) 
		{			
			$category = $this->getRequest()->getParam('category');			
			$this->CategoryService->editData($id, $category);	
			$this->_redirect('/admin/category');
		} else {
			$this->view->row = $this->CategoryService->getData($id);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->CategoryService->softDeleteData($id);
		$this->_redirect('/admin/category');
	}
}