<?php

class Admin_RestController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user');
	}
	
	public function preDispatch() 
	{
		$this->RestService = new RestService();
	}
	
	public function loadSatkerAction()	
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->RestService->getAllData("satker");
		echo Zend_Json_Encoder::encode($rows);
	}

	public function loadPegawaiAction()	
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->RestService->getAllData("pegawai");
		echo Zend_Json_Encoder::encode($rows);
	}

	public function loadDokterAction()	
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rows = $this->RestService->getAllData("dokter");
		echo Zend_Json_Encoder::encode($rows);
	}

}