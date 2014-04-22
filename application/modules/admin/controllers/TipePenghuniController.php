<?php

class Admin_TipePenghuniController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'load'));
	}
	
	public function preDispatch() 
	{
		$this->TipePenghuniService = new TipePenghuniService();
	}
	
	public function indexAction()	
	{
		$this->view->rows = $this->TipePenghuniService->getAllData();
	}

	public function addAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$tipe_penghuni = $this->getRequest()->getParam('tipe_penghuni');
			$this->TipePenghuniService->addData($tipe_penghuni);

			$this->_redirect('/admin/tipe-penghuni');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$tipe_penghuni = $this->getRequest()->getParam('tipe_penghuni');
			$this->TipePenghuniService->editData($id, $tipe_penghuni);

			$this->_redirect('/admin/tipe-penghuni');
		} else {
			$this->view->row = $this->TipePenghuniService->getData($id);
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->TipePenghuniService->softDeleteData($id);
		$this->_redirect('/admin/tipe-penghuni');
	}

	public function loadAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->TipePenghuniService->getAllData()->toArray();
		echo Zend_Json_Encoder::encode($rows);
	}

}