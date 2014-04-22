<?php

class Admin_RegulasiController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'load'));
	}
	
	public function preDispatch() 
	{
		$this->RegulasiService = new RegulasiService();
	}
	
	public function indexAction()	
	{
		$this->view->rows = $this->RegulasiService->getAllData();
	}

	public function addAction()	
	{		
		if ( $this->getRequest()->isPost() ) 
		{
			$judul = $this->getRequest()->getParam('judul');
			$isi = $this->getRequest()->getParam('isi');
			$id = $this->RegulasiService->addData($judul, $isi);

			// Attachment
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) 
			{
				$path = BERKAS_PATH . '/regulasi/';
				$path_info = pathinfo($file_name);
				$file_name = 'PU-' . date('Ymdhis') . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . $file_name);
				$this->RegulasiService->editFile($id, $file_name, $file_size, $file_type);
			}

			$this->_redirect('/admin/regulasi');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$judul = $this->getRequest()->getParam('judul');
			$isi = $this->getRequest()->getParam('isi');
			$this->RegulasiService->editData($id, $judul, $isi);

			// Attachment
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) 
			{
				$path = BERKAS_PATH . '/regulasi/';
				$path_info = pathinfo($file_name);
				$file_name = 'PU-' . date('Ymdhis') . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . $file_name);
				$this->RegulasiService->editFile($id, $file_name, $file_size, $file_type);
			}

			$this->_redirect('/admin/regulasi');
		} else {
			$this->view->row = $this->RegulasiService->getData($id);
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->RegulasiService->softDeleteData($id);
		$this->_redirect('/admin/regulasi');
	}

	public function loadAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->RegulasiService->getAllData()->toArray();
		echo Zend_Json_Encoder::encode($rows);
	}

	public function popupAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();

		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->RegulasiService->getData($id);
	}

	public function deleteFileAction()	
	{
		$id = $this->getRequest()->getParam('id');
		$row = $this->RegulasiService->getData($id);

		$path = BERKAS_PATH . '/regulasi/';
		unlink($path . "/" . $row->file_name);

		$this->RegulasiService->deleteFile($id);
		$this->_redirect('/admin/regulasi');
	}

}