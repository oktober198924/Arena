<?php
class StatistikService {
	  
 	function __construct() 
	{
	}	
	
	function getDashboardData($id)
	{
		switch($id)
		{
			case 1:
				$sql = "SELECT DATE_FORMAT(tanggal_pendaftaran, '%d %b') AS keterangan, COUNT(*) AS jumlah
						FROM pendaftaran
						WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
						GROUP BY DATE_FORMAT(tanggal_pendaftaran, '%d %b')";	
				break;
			case 2:
				$sql = "SELECT DATE_FORMAT(tanggal_pendaftaran, '%M %Y') AS keterangan, COUNT(*) AS jumlah
						FROM pendaftaran
						WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y') = DATE_FORMAT(NOW(), '%Y')
						GROUP BY DATE_FORMAT(tanggal_pendaftaran, '%M %Y')";
				break;
			case 3:
				$sql = "SELECT CASE tipe_pasien
							WHEN 'A' THEN 'Anggota' 
							WHEN 'KA' THEN 'Keluarga Anggota' 
							WHEN 'K' THEN 'Karyawan' 
							WHEN 'KK' THEN 'Keluarga Karyawan' 
							WHEN 'TA' THEN 'Tenaga Ahli' 
							WHEN 'AA' THEN 'Asisten Anggota' 
							WHEN 'LL' THEN 'Lain-Lain' 
						END AS keterangan, COUNT(*) AS jumlah
						FROM pendaftaran
						WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y') = DATE_FORMAT(NOW(), '%Y')
						GROUP BY tipe_pasien";
				break;
		}

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getStatistik($jenis, $tahun, $bulan)
	{
		$db = Zend_Registry::get('db');

		$sql = "SET lc_time_names = 'id_ID';";
		$stmt = $db->query($sql);

		switch($jenis)
		{
			case "1":
				$sql = "SELECT d.bidang AS keterangan, COUNT(*) AS jumlah
							FROM formulir_laboratorium_data a
							LEFT JOIN formulir_laboratorium b ON a.id_formulir = b.id
							LEFT JOIN pemeriksaan_laboratorium c ON a.id_pemeriksaan = c.id
							LEFT JOIN bidang_laboratorium d ON c.id_bidang = d.id
							WHERE YEAR(b.tanggal_registrasi) = " . $tahun . "
							AND a.status = 1 AND b.status = 1
							GROUP BY d.bidang";
				break;
			case "2":
				$sql = "SELECT c.jenis_pemeriksaan AS keterangan, COUNT(*) AS jumlah
							FROM formulir_laboratorium_data a
							LEFT JOIN formulir_laboratorium b ON a.id_formulir = b.id
							LEFT JOIN pemeriksaan_laboratorium c ON a.id_pemeriksaan = c.id
							WHERE YEAR(b.tanggal_registrasi) = " . $tahun . "
							AND a.status = 1 AND b.status = 1
							GROUP BY c.jenis_pemeriksaan";
				break;
			case "3":
				$sql = "SELECT MONTHNAME(tanggal_registrasi) AS keterangan, COUNT(*) AS jumlah
							FROM formulir_laboratorium
							WHERE YEAR(tanggal_registrasi) = " . $tahun . "
							AND status = 1
							GROUP BY tanggal_registrasi";
				break;
		}

		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getAllData($id)
	{ 
		switch($id)
		{
			case 1:
				$sql = "SELECT MONTHNAME(tanggal_perkara) AS keterangan, COUNT(*) AS jumlah
						FROM perkara
						WHERE YEAR(tanggal_perkara) = '2012'
						AND status = 1
						GROUP BY MONTHNAME(tanggal_perkara)
						ORDER BY MONTH(tanggal_perkara)";
				break;

			case 2:
				$sql = "SELECT c.kode_etik AS keterangan, COUNT(*) AS jumlah
						FROM perkara a
						LEFT JOIN kategori_perkara b ON a.id_kategori_perkara = b.id
						LEFT JOIN kode_etik c ON b.id_kode_etik = c.id
						WHERE YEAR(a.tanggal_perkara) = '2012'
						AND a.status = 1
						GROUP BY c.kode_etik";
				break;
		}
		
		$db = Zend_Registry::get('db');
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

}