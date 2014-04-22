<?php

class Admin_KehilanganController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'load'));
	}
	
	public function preDispatch() 
	{
		$this->KehilanganService = new KehilanganService();
		$this->TipePenghuniService = new TipePenghuniService();
	}
	
	public function indexAction()	
	{
		$this->view->rows = $this->KehilanganService->getAllData();
	}

	public function addAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$id_tipe_penghuni = $this->getRequest()->getParam('id_tipe_penghuni');
			$nama_penghuni = $this->getRequest()->getParam('nama_penghuni');
			$id_penghuni = $this->getRequest()->getParam('id_penghuni');
			$keterangan = $this->getRequest()->getParam('keterangan');
			$id = $this->KehilanganService->addData($id_tipe_penghuni, $nama_penghuni, $id_penghuni, $keterangan);

			// Attachment
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) 
			{
				$path = BERKAS_PATH . '/kehilangan/';
				$path_info = pathinfo($file_name);
				$file_name = 'HL-' . date('Ymdhis') . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . $file_name);
				$this->KehilanganService->editFile($id, $file_name, $file_size, $file_type);
			}

			$this->_redirect('/admin/kehilangan');
		} else {
			$this->view->rows = $this->TipePenghuniService->getAllData();
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$id_tipe_penghuni = $this->getRequest()->getParam('id_tipe_penghuni');
			$nama_penghuni = $this->getRequest()->getParam('nama_penghuni');
			$id_penghuni = $this->getRequest()->getParam('id_penghuni');
			$keterangan = $this->getRequest()->getParam('keterangan');
			$this->KehilanganService->editData($id, $id_tipe_penghuni, $nama_penghuni, $id_penghuni, $keterangan);

			// Attachment
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) 
			{
				$path = BERKAS_PATH . '/kehilangan/';
				$path_info = pathinfo($file_name);
				$file_name = 'HL-' . date('Ymdhis') . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . $file_name);
				$this->KehilanganService->editFile($id, $file_name, $file_size, $file_type);
			}

			$this->_redirect('/admin/kehilangan');
		} else {
			$this->view->row = $this->KehilanganService->getData($id);
			$this->view->rows = $this->TipePenghuniService->getAllData();
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->KehilanganService->softDeleteData($id);
		$this->_redirect('/admin/kehilangan');
	}

	public function loadAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->KehilanganService->getAllData()->toArray();
		echo Zend_Json_Encoder::encode($rows);
	}

	public function popupAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();

		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->KehilanganService->getData($id);
	}

	public function deleteFileAction()	
	{
		$id = $this->getRequest()->getParam('id');
		$row = $this->KehilanganService->getData($id);

		$path = BERKAS_PATH . '/kehilangan/';
		unlink($path . "/" . $row->file_name);

		$this->KehilanganService->deleteFile($id);
		$this->_redirect('/admin/kehilangan');
	}

}