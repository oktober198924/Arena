<?php
class TypeSpecService
{  
	function __construct()
	{
		$this->type_spec = new TypeSpec();
	}

	function getDataCopmlite($id)
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
				WHERE b.id_type = $id
				GROUP BY b.type, b.brand, a.category, a.sub_category";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
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

	function getAllDataContent($id_type, $id_sub_category_content)
	{
		$select = $this->type_spec->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'type_spec'), array('id','id_type','id_content'))
			->joinLeft(array('b' => 'content'), 'a.id_content = b.id_content', array('sub_category_content', 'content'))
			->where('a.status = 1')
			->where('a.id_type = ?', $id_type)
			->where('b.sub_category_content = ?', $id_sub_category_content);

		$result = $this->type_spec->fetchAll($select);
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
	
	/*
	$select = $this->spd->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'spd'), array('*'))
			->joinLeft(array('b' => 'mata_anggaran'), 'a.id_mata_anggaran = b.id', array('nomor', 'mata_anggaran', 'uraian_mata_anggaran'))
			->joinLeft(array('c' => 'db_siap.satker'), 'a.id_satker = c.id', array('nama_satker'))
			->joinLeft(array('d' => 'ttd'), 'a.id_bendahara_pengeluaran = d.id_pegawai', array('nama AS nama_bendahara_pengeluaran'))
			->joinLeft(array('e' => 'ttd'), 'a.id_ppk = e.id_pegawai', array('nama AS nama_ppk'))
			->joinLeft(array('f' => 'db_siap.pegawai'), 'a.id_penerima_kasbon = f.id', array('nama AS nama_penerima_kasbon'))
			->joinLeft(array('g' => 'db_siap.pegawai'), 'a.id_penandatangan_kasbon = g.id', array('nama AS nama_penandatangan_kasbon'))
			->where('a.id = ?', $id);
	*/
	/*
	$select = $this->type_spec->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'type_spec'), array('id','id_type','id_content'))
			->joinLeft(array('b' => 'content'), 'a.id_content = b.id_content', array('id_content', 'sub_category_content', 'content'))
			->where('a.status = 1');
	*/
/*	SELECT  type_spec.id,
		type_spec.id_type,
        type_spec.id_content,
        content.id_content,
        content.sub_category_content,
        content.content
FROM `type_spec` 
inner join content
on `type_spec` .`id_content` = content.id_content*/

}