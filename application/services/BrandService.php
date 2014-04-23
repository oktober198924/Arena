<?php
class BrandService
{  
	function __construct()
	{
		$this->brand = new Brand();
	}

	function getData($id)
	{
		$select = $this->brand->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'brand'), array('*'))
			->where('a.id_brand = ?', $id);

		$result = $this->brand->fetchRow($select);
		return $result;
	}

	function getAllData()
	{ 
		$select = $this->brand->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'brand'), array('*'))
			->where('a.status = 1');

		$result = $this->brand->fetchAll($select);
		return $result;
	}

	function addData($brand) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'brand' => $brand, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->brand->insert($params);	
	}

	function editData($id, $brand)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'brand' => $brand, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->brand->getAdapter()->quoteInto('id_brand = ?', $id);
		$this->brand->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->brand->getAdapter()->quoteInto('id_brand = ?', $id);
		$this->brand->delete($where);
	}

	public function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->brand->getAdapter()->quoteInto('id_brand = ?', $id);
		$this->brand->update($params, $where);
	}

}