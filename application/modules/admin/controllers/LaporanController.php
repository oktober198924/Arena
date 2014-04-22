<?php

class Admin_LaporanController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user');
		$this->_helper->_acl->allow('viewer');

		$this->_helper->_acl->allow('user.dokter.umum');
		$this->_helper->_acl->allow('user.dokter.gigi');
		$this->_helper->_acl->allow('user.perawat');
		$this->_helper->_acl->allow('user.fisioterapi');
		$this->_helper->_acl->allow('user.perekam.medis');
		$this->_helper->_acl->allow('user.asisten.apoteker');
		$this->_helper->_acl->allow('user.pranata.labkes');
		$this->_helper->_acl->allow('user.bidan');
	}

	public function preDispatch() 
	{
		$this->laporanService = new LaporanService();
	}
	
	public function indexAction()
	{		
		//$this->view->penggunaRows = $this->penggunaService->getAllData();
	}

	public function dataAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();

		$jenis = $this->_getParam('jenis');
		$tahun = $this->_getParam('tahun');
		$bulan = $this->_getParam('bulan');
		$user = $this->_getParam('user');
		
		$this->view->jenis = $jenis;
		$this->view->tahun = $tahun;
		//$this->view->bulan = $this->_helper->GetMonthName($bulan);
		$this->view->user = $user;

		$this->view->rows = $this->laporanService->getLaporan($jenis, $tahun, $bulan, $user);
	}

	public function exportAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$styleArray = array(
			'font' => array(
				'bold' => true
			)
		);

		$jenis = $this->_getParam('jenis');
		$tahun = $this->_getParam('tahun');
		$bulan = $this->_getParam('bulan');
		
		$this->view->jenis = $jenis;
		$this->view->tahun = $tahun;
		//$this->view->bulan = $this->_helper->GetMonthName($bulan);
		$rows = $this->laporanService->getLaporan($jenis, $tahun, $bulan);

		$bulan = ($bulan == "*") ? "" : "BULAN " . $this->_helper->GetMonthName($bulan);
		$tahun = ($tahun == "*") ? "" : "TAHUN " . $tahun;
		$filter = $bulan . ' ' . $tahun;

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("BDSI");
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPSheet = $objPHPExcel->getActiveSheet();
		
		$indeks = 1;
		$maxRow = 6;
		switch($jenis)
		{
			case 1:	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'KASUS-KASUS YANG MASUK KE BADAN KEHORMATAN DPR RI');
				$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A2', ' MELALUI PENGADUAN');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
				$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A3', $filter);
				$objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
				$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No.');
				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Tanggal');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Pengadu');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Materi aduan');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Ditindaklanjuti');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Ditunda / dipending');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Tidak ditindaklanjuti / drop');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Alasan');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('I5', 'Keterangan');
				$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($styleArray);

				foreach($rows as $row)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$maxRow, $indeks);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$maxRow, $row->tanggal_perkara);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$maxRow, $row->nama);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$maxRow, $row->materi_aduan);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$maxRow, $row->hasil_rapat);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$maxRow, '');
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$maxRow, '');
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$maxRow, $row->alasan_status);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$maxRow, $row->keterangan_perkara);

					$indeks++;
					$maxRow++;
				}
				break;
			
			case 2:	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'MATRIKS KEPUTUSAN PERKARA ETIK BADAN KEHORMATAN DPR RI');
				$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A3', $filter);
				$objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
				$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No.');
				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Keputusan Perkara Etik BK');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Perkara');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Pasal Yang Dilanggar');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Disampaikan Ke Pimpinan Tanggal');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Dijadwalkan Di Bamus');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Dibacakan Di Paripurna');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Keterangan');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

				foreach($rows as $row)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$maxRow, $indeks);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$maxRow, $row->tanggal_keputusan . "<br>" . $row->keputusan  . "<br>" . $row->no_keputusan);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$maxRow, $row->ringkasan_aduan);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$maxRow, $row->pasal_dilanggar);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$maxRow, '');
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$maxRow, $row->jadwal_bamus . "<br>" . $row->nama_anggota_pembaca_bamus);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$maxRow, $row->jadwal_paripurna . "<br>" . $row->nama_anggota_pimpinan_paripurna . "<br>" . $row->keterangan_paripurna);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$maxRow, $row->keterangan_keputusan);

					$indeks++;
					$maxRow++;
				}
				break;
		}

		header("Content-Type: application/ms-excel");
		header("Content-Disposition: attachment; filename=laporan.xls");

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$objWriter->save("php://output");
	}
	
}