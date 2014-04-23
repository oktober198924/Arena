<?php
class SubCategoryService
{  
	function __construct()
	{
		$this->sub_category = new SubCategory();
	}

	function getData($id)
	{
		$select = $this->sub_category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'sub_category'), array('*'))
			->where('a.id_sub_category = ?', $id);

		$result = $this->sub_category->fetchRow($select);
		return $result;
	}

	function getDataWhereIdCategory($id)
	{
		$select = $this->sub_category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'sub_category'), array('*'))
			->where('a.id_category = ?', $id);

		$result = $this->sub_category->fetchAll($select);
		return $result;
	}

	function getAllData()
	{ 
		$select = $this->sub_category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'sub_category'), array('*'))
			->where('a.status = 1');

		$result = $this->sub_category->fetchAll($select);
		return $result;
	}

	function addData($sub_category) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sub_category' => $sub_category, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->sub_category->insert($params);	
	}

	function editData($id, $sub_category)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sub_category' => $sub_category, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->sub_category->getAdapter()->quoteInto('id_sub_category = ?', $id);
		$this->sub_category->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->sub_category->getAdapter()->quoteInto('id_sub_category = ?', $id);
		$this->sub_category->delete($where);
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
 		
		$where = $this->sub_category->getAdapter()->quoteInto('id_sub_category = ?', $id);
		$this->sub_category->update($params, $where);
	}

	public function enumData($sql)
	{
		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		return $stmt->fetch();
	}

}