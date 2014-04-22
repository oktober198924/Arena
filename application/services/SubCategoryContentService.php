<?php
class SubCategoryContentService
{  
	function __construct()
	{
		$this->sub_category_content = new SubCategoryContent();
	}

	function getData($id)
	{
		$select = $this->sub_category_content->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'sub_category_content'), array('*'))
			->where('a.id = ?', $id);

		$result = $this->sub_category_content->fetchRow($select);
		return $result;
	}

	function getAllData()
	{ 
		$select = $this->sub_category_content->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'sub_category_content'), array('*'))
			->where('a.status = 1');

		$result = $this->sub_category_content->fetchAll($select);
		return $result;
	}

	function addData($sub_category_content) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sub_category_content' => $sub_category_content, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->sub_category_content->insert($params);	
	}

	function editData($id, $sub_category_content)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sub_category_content' => $sub_category_content, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->sub_category_content->getAdapter()->quoteInto('id = ?', $id);
		$this->sub_category_content->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->sub_category_content->getAdapter()->quoteInto('id = ?', $id);
		$this->sub_category_content->delete($where);
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
 		
		$where = $this->sub_category_content->getAdapter()->quoteInto('id = ?', $id);
		$this->sub_category_content->update($params, $where);
	}

}