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