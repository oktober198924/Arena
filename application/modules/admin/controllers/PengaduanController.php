<?php

class Admin_PengaduanController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'load'));
	}
	
	public function preDispatch() 
	{
		$this->PengaduanService = new PengaduanService();
	}
	
	public function indexAction()	
	{
		$this->view->rows = $this->PengaduanService->getAllData();
	}

	public function addAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$nama_pengirim = $this->getRequest()->getParam('nama_pengirim');
			$email_pengirim = $this->getRequest()->getParam('email_pengirim');
			$jabatan_pengirim = $this->getRequest()->getParam('jabatan_pengirim');
			$status_pengaduan = ($this->getRequest()->getParam('status_pengaduan') == 'on') ? 1 : 0;
			$pertanyaan = $this->getRequest()->getParam('pertanyaan');
			$this->PengaduanService->addData($nama_pengirim, $email_pengirim, $jabatan_pengirim, $status_pengaduan, $pertanyaan);

			$this->_redirect('/admin/pengaduan');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$nama_pengirim = $this->getRequest()->getParam('nama_pengirim');
			$email_pengirim = $this->getRequest()->getParam('email_pengirim');
			$jabatan_pengirim = $this->getRequest()->getParam('jabatan_pengirim');
			$status_pengaduan = ($this->getRequest()->getParam('status_pengaduan') == 'on') ? 1 : 0;
			$pertanyaan = $this->getRequest()->getParam('pertanyaan');
			$jawaban = $this->getRequest()->getParam('jawaban');
			$this->PengaduanService->editData($id, $nama_pengirim, $email_pengirim, $jabatan_pengirim, $status_pengaduan, $pertanyaan, $jawaban);

			$this->_redirect('/admin/pengaduan');
		} else {
			$this->view->row = $this->PengaduanService->getData($id);
		}
	}

	public function loadAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->PengaduanService->getAllData()->toArray();
		echo Zend_Json_Encoder::encode($rows);
	}

}