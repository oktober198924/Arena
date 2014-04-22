<?php

class Admin_KendaraanController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'load'));
	}
	
	public function preDispatch() 
	{
		$this->KendaraanService = new KendaraanService();
		$this->TipePenghuniService = new TipePenghuniService();
	}
	
	public function indexAction()	
	{
		$this->view->rows = $this->KendaraanService->getAllData();
	}

	public function addAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$nomor_polisi = $this->getRequest()->getParam('nomor_polisi');
			$id_tipe_pemilik = $this->getRequest()->getParam('id_tipe_pemilik');
			$nama_pemilik = $this->getRequest()->getParam('nama_pemilik');
			$id_pemilik = $this->getRequest()->getParam('id_pemilik');
			$nomor_kendaraan = $this->getRequest()->getParam('nomor_kendaraan');
			$this->KendaraanService->addData($nomor_polisi, $id_tipe_pemilik, $nama_pemilik, $id_pemilik, $nomor_kendaraan);

			$this->_redirect('/admin/kendaraan');
		} else {
			$this->view->rows = $this->TipePenghuniService->getAllData();
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$nomor_polisi = $this->getRequest()->getParam('nomor_polisi');
			$id_tipe_pemilik = $this->getRequest()->getParam('id_tipe_pemilik');
			$nama_pemilik = $this->getRequest()->getParam('nama_pemilik');
			$id_pemilik = $this->getRequest()->getParam('id_pemilik');
			$nomor_kendaraan = $this->getRequest()->getParam('nomor_kendaraan');
			$this->KendaraanService->editData($id, $nomor_polisi, $id_tipe_pemilik, $nama_pemilik, $id_pemilik, $nomor_kendaraan);

			$this->_redirect('/admin/kendaraan');
		} else {
			$this->view->row = $this->KendaraanService->getData($id);
			$this->view->rows = $this->TipePenghuniService->getAllData();
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->KendaraanService->softDeleteData($id);
		$this->_redirect('/admin/kendaraan');
	}

	public function loadAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->KendaraanService->getAllData()->toArray();
		echo Zend_Json_Encoder::encode($rows);
	}

}