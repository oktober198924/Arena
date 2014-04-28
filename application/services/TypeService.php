<?php
class TypeService
{  
	function __construct()
	{
		$this->type = new Type();
	}

	function getData($id)
	{
		$select = $this->type->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'type'), array('*'))
			->where('a.id_type = ?', $id);

		$result = $this->type->fetchRow($select);
		return $result;
	}

	function getAllData()
	{ 
		$select = $this->type->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'type'), array('*'))
			->where('a.status = 1');

		$result = $this->type->fetchAll($select);
		return $result;
	}

	function addData($id_brand, $type) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_brand' => $id_brand, 
			'type' => $type, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->type->insert($params);	
	}

	function editData($id, $id_brand, $type)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_brand' => $id_brand, 
			'type' => $type, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->type->getAdapter()->quoteInto('id_type = ?', $id);
		$this->type->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->type->getAdapter()->quoteInto('id_type = ?', $id);
		$this->type->delete($where);
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
 		
		$where = $this->type->getAdapter()->quoteInto('id_type = ?', $id);
		$this->type->update($params, $where);
	}
	
	public function enumData($sql)
	{
		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		return $stmt->fetchall();
	}

}