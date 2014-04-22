<?php
class RekapitulasiService {
	  
 	function __construct() 
	{
	}

	function getDashboardData()
	{
		$sql = "SELECT 'Hari ini' keterangan, COUNT(*) AS jumlah
				FROM pendaftaran
				WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
				UNION ALL
				SELECT 'Kemarin' keterangan, COUNT(*) AS jumlah
				FROM pendaftaran
				WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y-%m-%d') = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY), '%Y-%m-%d')
				UNION ALL
				SELECT 'Minggu ini' keterangan, COUNT(*) AS jumlah
				FROM pendaftaran
				WHERE WEEK(tanggal_pendaftaran) = WEEK(NOW())
				UNION ALL
				SELECT 'Bulan ini' keterangan, COUNT(*) AS jumlah
				FROM pendaftaran
				WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
				UNION ALL
				SELECT 'Tahun ini' keterangan, COUNT(*) AS jumlah
				FROM pendaftaran
				WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y') = DATE_FORMAT(NOW(), '%Y')
				UNION ALL
				SELECT 'Rata-rata kunjungan harian (bulan kemarin)' keterangan, AVG(jumlah) AS jumlah
				FROM (
					SELECT DATE_FORMAT(tanggal_pendaftaran, '%Y-%m-%d') AS keterangan, COUNT(*) AS jumlah
					FROM pendaftaran
					WHERE DATE_FORMAT(tanggal_pendaftaran, '%Y-%m') = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -1 MONTH), '%Y-%m')
					GROUP BY DATE_FORMAT(tanggal_pendaftaran, '%Y-%m-%d')
				) a";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getRekapitulasi($jenis, $tahun, $bulan)
	{
		switch($jenis)
		{			
			case "1":
				$sql = "SELECT bidang, 
						SUM(IF(bulan = 1, 1, 0)) AS 'jan',
						SUM(IF(bulan = 2, 1, 0)) AS 'feb',
						SUM(IF(bulan = 3, 1, 0)) AS 'mar',
						SUM(IF(bulan = 4, 1, 0)) AS 'apr',
						SUM(IF(bulan = 5, 1, 0)) AS 'mei',
						SUM(IF(bulan = 6, 1, 0)) AS 'jun',
						SUM(IF(bulan = 7, 1, 0)) AS 'jul',
						SUM(IF(bulan = 8, 1, 0)) AS 'ags',
						SUM(IF(bulan = 9, 1, 0)) AS 'sep',
						SUM(IF(bulan = 10, 1, 0)) AS 'okt',
						SUM(IF(bulan = 11, 1, 0)) AS 'nov',
						SUM(IF(bulan = 12, 1, 0)) AS 'des',
						COUNT(*) AS total
						FROM (
							SELECT d.bidang AS bidang, MONTH(tanggal_registrasi) AS bulan
							FROM formulir_laboratorium_data a
							LEFT JOIN formulir_laboratorium b ON a.id_formulir = b.id
							LEFT JOIN pemeriksaan_laboratorium c ON a.id_pemeriksaan = c.id
							LEFT JOIN bidang_laboratorium d ON c.id_bidang = d.id
							WHERE YEAR(b.tanggal_registrasi) = " . $tahun . "
							AND a.status = 1 AND b.status = 1
						) a
						GROUP BY bidang";
				break;
		
			case "2":
				$bulan = ($bulan == "*") ? "" : " AND MONTH(a.tanggal_perkara) = " . $bulan;
				$sql = "SELECT jenis_pemeriksaan,
						SUM(IF(bulan = 1, 1, 0)) AS 'jan',
						SUM(IF(bulan = 2, 1, 0)) AS 'feb',
						SUM(IF(bulan = 3, 1, 0)) AS 'mar',
						SUM(IF(bulan = 4, 1, 0)) AS 'apr',
						SUM(IF(bulan = 5, 1, 0)) AS 'mei',
						SUM(IF(bulan = 6, 1, 0)) AS 'jun',
						SUM(IF(bulan = 7, 1, 0)) AS 'jul',
						SUM(IF(bulan = 8, 1, 0)) AS 'ags',
						SUM(IF(bulan = 9, 1, 0)) AS 'sep',
						SUM(IF(bulan = 10, 1, 0)) AS 'okt',
						SUM(IF(bulan = 11, 1, 0)) AS 'nov',
						SUM(IF(bulan = 12, 1, 0)) AS 'des',
						COUNT(*) AS total
						FROM (
							SELECT c.jenis_pemeriksaan AS jenis_pemeriksaan, MONTH(tanggal_registrasi) AS bulan
							FROM formulir_laboratorium_data a
							LEFT JOIN formulir_laboratorium b ON a.id_formulir = b.id
							LEFT JOIN pemeriksaan_laboratorium c ON a.id_pemeriksaan = c.id
							WHERE YEAR(b.tanggal_registrasi) = " . $tahun . "
							AND a.status = 1 AND b.status = 1
						) a
						GROUP BY jenis_pemeriksaan";
				break;
			
			case "3":
				$bulan = ($bulan == "*") ? "" : " AND MONTH(a.tanggal_perkara) = " . $bulan;
				$sql = "SELECT tahun,
						SUM(IF(bulan = 1, 1, 0)) AS 'jan',
						SUM(IF(bulan = 2, 1, 0)) AS 'feb',
						SUM(IF(bulan = 3, 1, 0)) AS 'mar',
						SUM(IF(bulan = 4, 1, 0)) AS 'apr',
						SUM(IF(bulan = 5, 1, 0)) AS 'mei',
						SUM(IF(bulan = 6, 1, 0)) AS 'jun',
						SUM(IF(bulan = 7, 1, 0)) AS 'jul',
						SUM(IF(bulan = 8, 1, 0)) AS 'ags',
						SUM(IF(bulan = 9, 1, 0)) AS 'sep',
						SUM(IF(bulan = 10, 1, 0)) AS 'okt',
						SUM(IF(bulan = 11, 1, 0)) AS 'nov',
						SUM(IF(bulan = 12, 1, 0)) AS 'des',
						COUNT(*) AS total
						FROM (
							SELECT YEAR(tanggal_registrasi) AS tahun, MONTH(tanggal_registrasi) AS bulan
							FROM formulir_laboratorium
							WHERE YEAR(tanggal_registrasi) = " . $tahun . "
							AND status = 1
						) a
						GROUP BY tahun";
				break;
		}

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getAllData($id)
	{
		switch($id)
		{						
			case "1":
				$sql = "SELECT kategori_perkara AS kategori_perkara,
						SUM(IF(bulan = 1, 1, 0)) AS 'jan',
						SUM(IF(bulan = 2, 1, 0)) AS 'feb',
						SUM(IF(bulan = 3, 1, 0)) AS 'mar',
						SUM(IF(bulan = 4, 1, 0)) AS 'apr',
						SUM(IF(bulan = 5, 1, 0)) AS 'mei',
						SUM(IF(bulan = 6, 1, 0)) AS 'jun',
						SUM(IF(bulan = 7, 1, 0)) AS 'jul',
						SUM(IF(bulan = 8, 1, 0)) AS 'ags',
						SUM(IF(bulan = 9, 1, 0)) AS 'sep',
						SUM(IF(bulan = 10, 1, 0)) AS 'okt',
						SUM(IF(bulan = 11, 1, 0)) AS 'nov',
						SUM(IF(bulan = 12, 1, 0)) AS 'des',
						COUNT(*) AS total
						FROM (
							SELECT b.kategori_perkara, MONTH(a.tanggal_perkara) AS bulan
							FROM perkara a
							LEFT JOIN kategori_perkara b ON a.id_kategori_perkara = b.id
							WHERE YEAR(a.tanggal_perkara) = '2012'
							AND a.status = 1
						) a
						GROUP BY kategori_perkara";
				break;
		}

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

}