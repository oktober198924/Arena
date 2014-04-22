<?php
class LaporanService {
	  
 	function __construct() 
	{
	}

	function getLaporan($jenis, $tahun, $bulan)
	{
		$db = Zend_Registry::get('db');

		$sql = "SET lc_time_names = 'id_ID';";
		$stmt = $db->query($sql);

		switch($jenis)
		{
			case "1":
				$bulan = ($bulan == "*") ? "" : " AND MONTH(a.tanggal_perkara) = " . $bulan; 

				$sql = "SELECT DATE_FORMAT(a.tanggal_perkara, '%d-%m-%Y') AS tanggal_perkara, b.nama, a.ringkasan_aduan, c.hasil_rapat, 
				        a.alasan_status, a.keterangan_perkara
						FROM perkara a
						LEFT JOIN perkara_pengadu b ON a.id = b.id_perkara
						LEFT JOIN perkara_rapat c ON a.id = c.id_perkara
						WHERE YEAR(a.tanggal_perkara) = " . $tahun . $bulan . "
						AND a.status = 1
						ORDER BY a.tanggal_perkara DESC";
				break;
			case "2":
				$bulan = ($bulan == "*") ? "" : " AND MONTH(a.tanggal_keputusan) = " . $bulan; 

				$sql = "SELECT DATE_FORMAT(a.tanggal_keputusan, '%d %M %Y') AS tanggal_keputusan, no_keputusan, keputusan, b.ringkasan_aduan,
						a.pasal_dilanggar, DATE_FORMAT(a.jadwal_bamus, '%d %M %Y') AS jadwal_bamus, c.nama AS nama_anggota_pembaca_bamus,
						DATE_FORMAT(a.jadwal_paripurna, '%d %M %Y') AS jadwal_paripurna, d.nama AS nama_anggota_pimpinan_paripurna, a.keterangan_paripurna, a.keterangan_keputusan
						FROM perkara_keputusan a
						LEFT JOIN perkara b ON a.id_perkara = b.id
						LEFT JOIN db_minangwan.view_anggota_bk c ON a.id_anggota_pembaca_bamus = c.id AND a.id_periode_pembaca_bamus = c.id_periode
						LEFT JOIN db_minangwan.view_anggota d ON a.id_anggota_pimpinan_paripurna = d.id AND a.id_periode_pimpinan_paripurna = d.id_periode
						WHERE YEAR(a.tanggal_keputusan) = " . $tahun . $bulan . "
						AND a.status = 1
						ORDER BY a.tanggal_keputusan DESC";
				break;
		}
		//echo $sql;

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

}