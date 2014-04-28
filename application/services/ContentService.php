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
			->where('a.id_content = ?', $id);

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

	function addData($sub_category_content, $content) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sub_category_content' => $sub_category_content, 
			'content' => $content, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->content->insert($params);	
	}

	function editData($id, $sub_category_content, $content)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sub_category_content' => $sub_category_content, 
			'content' => $content, 
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->content->getAdapter()->quoteInto('id_content = ?', $id);
		$this->content->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->content->getAdapter()->quoteInto('id_content = ?', $id);
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
 		
		$where = $this->content->getAdapter()->quoteInto('id_content = ?', $id);
		$this->content->update($params, $where);
	}
	
	public function enumData($sql)
	{
		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		return $stmt->fetchall();
	}

	function getLaporan()
	{
		$sql = "SELECT b.type, b.brand, a.category, a.sub_category, 
				CASE WHEN a.show_view = 1 THEN
					GROUP_CONCAT(CONCAT(a.sub_category_content, ': ', a.content) SEPARATOR ' | ') 
				ELSE
					GROUP_CONCAT(a.content SEPARATOR ' | ') 
				END AS content
				FROM (
					SELECT a.id_category, b.id_sub_category, c.id_sub_category_content, d.id_content,
					a.category, b.sub_category, c.sub_category_content, d.content, c.show_view
					FROM category a
					LEFT JOIN sub_category b ON a.id_category = b.id_category
					LEFT JOIN sub_category_content c ON b.id_sub_category = c.id_sub_category
					LEFT JOIN content d ON c.id_sub_category_content = d.id_sub_category_content
				) a
				LEFT JOIN (
					SELECT a.*, b.type, c.brand
					FROM type_spec a
					LEFT JOIN type b ON a.id_type = b.id_type
					LEFT JOIN brand c ON b.id_brand = c.id_brand
				) b ON a.id_content = b.id_content
				WHERE b.id_type = 4
				GROUP BY b.type, b.brand, a.category, a.sub_category";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

}