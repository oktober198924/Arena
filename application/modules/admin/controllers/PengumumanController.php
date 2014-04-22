<?php

class Admin_PengumumanController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'load'));
	}
	
	public function preDispatch() 
	{
		$this->PengumumanService = new PengumumanService();
	}
	
	public function indexAction()	
	{
		$this->view->rows = $this->PengumumanService->getAllData();
	}

	public function addAction()	
	{		
		if ( $this->getRequest()->isPost() ) 
		{
			$judul = $this->getRequest()->getParam('judul');
			$isi = $this->getRequest()->getParam('isi');
			$id = $this->PengumumanService->addData($judul, $isi);

			// Attachment
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) 
			{
				$path = BERKAS_PATH . '/pengumuman/';
				$path_info = pathinfo($file_name);
				$file_name = 'PU-' . date('Ymdhis') . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . $file_name);
				$this->PengumumanService->editFile($id, $file_name, $file_size, $file_type);
			}

			$this->_redirect('/admin/pengumuman');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$judul = $this->getRequest()->getParam('judul');
			$isi = $this->getRequest()->getParam('isi');
			$this->PengumumanService->editData($id, $judul, $isi);

			// Attachment
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) 
			{
				$path = BERKAS_PATH . '/pengumuman/';
				$path_info = pathinfo($file_name);
				$file_name = 'PU-' . date('Ymdhis') . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . $file_name);
				$this->PengumumanService->editFile($id, $file_name, $file_size, $file_type);
			}

			$this->_redirect('/admin/pengumuman');
		} else {
			$this->view->row = $this->PengumumanService->getData($id);
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->PengumumanService->softDeleteData($id);
		$this->_redirect('/admin/pengumuman');
	}

	public function loadAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->PengumumanService->getAllData()->toArray();
		echo Zend_Json_Encoder::encode($rows);
	}

	public function popupAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();

		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->PengumumanService->getData($id);
	}

	public function deleteFileAction()	
	{
		$id = $this->getRequest()->getParam('id');
		$row = $this->PengumumanService->getData($id);

		$path = BERKAS_PATH . '/pengumuman/';
		unlink($path . "/" . $row->file_name);

		$this->PengumumanService->deleteFile($id);
		$this->_redirect('/admin/pengumuman');
	}

}