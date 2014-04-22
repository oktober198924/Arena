<?php
class CategoryService
{  
	function __construct()
	{
		$this->category = new Category();
	}

	function getData($id)
	{
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'category'), array('*'))
			->where('a.id = ?', $id);

		$result = $this->category->fetchRow($select);
		return $result;
	}

	function getAllData()
	{ 
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'category'), array('*'))
			->where('a.status = 1');

		$result = $this->category->fetchAll($select);
		return $result;
	}

	function addData($category) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'category' => $category, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->category->insert($params);	
	}

	function editData($id, $category)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'category' => $category, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->category->getAdapter()->quoteInto('id = ?', $id);
		$this->category->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->category->getAdapter()->quoteInto('id = ?', $id);
		$this->category->delete($where);
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
 		
		$where = $this->category->getAdapter()->quoteInto('id = ?', $id);
		$this->category->update($params, $where);
	}

}