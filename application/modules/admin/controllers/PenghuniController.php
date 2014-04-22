<?php

class Admin_PenghuniController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user');
	}
	
	public function preDispatch() 
	{
		$this->TipePenghuniService = new TipePenghuniService();
		$this->PenghuniService = new PenghuniService();
	}

	public function indexAction()
	{
		$tipe = $this->getRequest()->getParam('tipe');
		$this->view->tipe = $tipe;
		$this->view->rows = $this->PenghuniService->getAllData($tipe);
	}

	public function addAction()
	{
		$tipe = $this->getRequest()->getParam('tipe');

		if ( $this->getRequest()->isPost() ) 
		{
			$id_tipe_penghuni = $this->getRequest()->getParam('id_tipe_penghuni');
			$nomor = $this->getRequest()->getParam('nomor');
			$nama = $this->getRequest()->getParam('nama');
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$tempat_lahir = $this->getRequest()->getParam('tempat_lahir');
			$tanggal_lahir = $this->_helper->CDate($this->getRequest()->getParam('tanggal_lahir'));
			$telepon = $this->getRequest()->getParam('telepon');
			$status_kawin = $this->getRequest()->getParam('status_kawin');
			$kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');
			$telepon = $this->getRequest()->getParam('telepon');
			$agama = $this->getRequest()->getParam('agama');
			$pendidikan_terakhir = $this->getRequest()->getParam('pendidikan_terakhir');
			$pekerjaan = $this->getRequest()->getParam('pekerjaan');
			$alamat = $this->getRequest()->getParam('alamat');
			$masa_berlaku = $this->_helper->CDate($this->getRequest()->getParam('masa_berlaku'));

			$tipe_referensi = $this->getRequest()->getParam('tipe_referensi');			
			$id_referensi = $this->getRequest()->getParam('id_referensi');

			$this->PenghuniService->addData($tipe, $nama, $nomor, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $status_kawin, $kewarganegaraan, $telepon, $agama, $pendidikan_terakhir, $pekerjaan, $alamat, $masa_berlaku, $tipe_referensi, $id_referensi);

			$this->_redirect('/admin/penghuni/index/tipe/PK');
		} else {
			$this->view->TipePenghuniRows = $this->TipePenghuniService->getAllData();
			$this->view->tipe = $tipe;
		}
	}

	public function editAction()
	{
		$tipe = $this->getRequest()->getParam('tipe');
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$id_tipe_penghuni = $this->getRequest()->getParam('id_tipe_penghuni');
			$nomor = $this->getRequest()->getParam('nomor');
			$nama = $this->getRequest()->getParam('nama');
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$tempat_lahir = $this->getRequest()->getParam('tempat_lahir');
			$tanggal_lahir = $this->_helper->CDate($this->getRequest()->getParam('tanggal_lahir'));
			$telepon = $this->getRequest()->getParam('telepon');
			$status_kawin = $this->getRequest()->getParam('status_kawin');
			$kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');
			$telepon = $this->getRequest()->getParam('telepon');
			$agama = $this->getRequest()->getParam('agama');
			$pendidikan_terakhir = $this->getRequest()->getParam('pendidikan_terakhir');
			$pekerjaan = $this->getRequest()->getParam('pekerjaan');
			$alamat = $this->getRequest()->getParam('alamat');
			$masa_berlaku = $this->_helper->CDate($this->getRequest()->getParam('masa_berlaku'));

			if ($tipe_ex == 'KA' || $tipe_ex == 'KK' || $tipe_ex == 'PK')
				$this->PenghuniService->editData($id, $tipe, $nomor, $nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $status_kawin, $kewarganegaraan, $telepon, $agama, $pendidikan_terakhir, $pekerjaan, $alamat, $masa_berlaku, $tipe_referensi, $id_referensi);
			else
				$this->PenghuniService->editExternalData($tipe_ex, $id, $no_rekam_medis, $masa_berlaku, $alergi, $golongan_darah);

			$this->_redirect('/admin/penghuni/index/tipe/'.$tipe_ex);
		} else {
			$this->view->tipe = $tipe;
			$this->view->row = $this->PenghuniService->getData($tipe, $id);
			$this->view->TipePenghuniRows = $this->TipePenghuniService->getAllData();
		}
	}
	
	public function loadAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$tipe = $this->getRequest()->getParam('tipe');
		$rows = $this->PenghuniService->getLoadData($tipe);
		echo Zend_Json_Encoder::encode($rows);
	}

	public function photoAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$tipe = $this->getRequest()->getParam('tipe');
		$id = $this->getRequest()->getParam('id');
		$row = $this->PenghuniService->getData($tipe, $id);
		
		switch($tipe)
		{
			case "A":
				$url_photo = 'https://berkas.dpr.go.id/minangwan/photo/' . $row->foto_penghuni;
				break;
			case "K":
				$url_photo = 'https://berkas.dpr.go.id/siap/photos/' . $row->nomor . '.jpg';
				break;
			case "TA":
				$url_photo = 'https://sitanang.dpr.go.id/photo/' . $row->foto;
				break;
			case "AA":
				$url_photo = 'https://sitanang.dpr.go.id/photo/' . $row->foto;
				break;
		}
		//if (file_exists($url_photo)) {
		echo '<center><img width="120" src="' . $url_photo . '"><br><br><hr><br>' . $row->nama_penghuni . '</center>';
		//} else {
		//	echo '<center><img src="/photo/blankface.jpg"><br><br><hr><br>' . $row->nama . '</center>';
		//}
	}
	
}