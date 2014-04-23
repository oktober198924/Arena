<?php
class ContentService
{  
	function __construct()
	{
		$this->content = new Content();
	}

	function getData($id)
	{
		$select = $this->content->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'content'), array('*'))
			->where('a.id = ?', $id);

		$result = $this->content->fetchRow($select);
		return $result;
	}
	
	function getDataWhereIdCategory($id)
	{
		$select = $this->content->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'content'), array('*'))
			->where('a.sub_category_content = ?', $id);

		$result = $this->content->fetchAll($select);
		return $result;
	}
	
	function getAllData()
	{ 
		$select = $this->content->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'content'), array('*'))
			->where('a.status = 1');

		$result = $this->content->fetchAll($select);
		return $result;
	}

	function addData($content) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'content' => $content, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->content->insert($params);	
	}

	function editData($id, $content)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'content' => $content, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->content->getAdapter()->quoteInto('id = ?', $id);
		$this->content->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->content->getAdapter()->quoteInto('id = ?', $id);
		$this->content->delete($where);
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
 		
		$where = $this->content->getAdapter()->quoteInto('id = ?', $id);
		$this->content->update($params, $where);
	}

}