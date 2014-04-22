<?php
class TypeSpecService
{  
	function __construct()
	{
		$this->type_spec = new TypeSpec();
	}

	function getData($id)
	{
		$select = $this->type_spec->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'type_spec'), array('*'))
			->where('a.id = ?', $id);

		$result = $this->type_spec->fetchRow($select);
		return $result;
	}

	function getAllData()
	{ 
		$select = $this->type_spec->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'type_spec'), array('*'))
			->where('a.status = 1');

		$result = $this->type_spec->fetchAll($select);
		return $result;
	}

	function addData($type_spec) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'type_spec' => $type_spec, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->type_spec->insert($params);	
	}

	function editData($id, $type_spec)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'type_spec' => $type_spec, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->type_spec->getAdapter()->quoteInto('id = ?', $id);
		$this->type_spec->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->type_spec->getAdapter()->quoteInto('id = ?', $id);
		$this->type_spec->delete($where);
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
 		
		$where = $this->type_spec->getAdapter()->quoteInto('id = ?', $id);
		$this->type_spec->update($params, $where);
	}

}