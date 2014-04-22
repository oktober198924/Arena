<?php

class Admin_StatistikController extends Zend_Controller_Action
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
		$this->statistikService = new StatistikService();
	}
	
	public function indexAction()
	{		
	}

	public function dataAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();

		$jenis = $this->_getParam('jenis');
		$tahun = $this->_getParam('tahun');
		$bulan = $this->_getParam('bulan');
		
		$this->view->jenis = $jenis;
		$this->view->tahun = $tahun;
		$this->view->bulan = $this->_helper->GetMonthName($bulan);

		$this->view->rows = $this->statistikService->getStatistik($jenis, $tahun, $bulan);
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
		$rows = $this->statistikService->getStatistik($jenis, $tahun, $bulan);
		$bulan = $this->_helper->GetMonthName($bulan);
			
		$this->view->jenis = $jenis;
		$this->view->tahun = $tahun;
		$this->view->bulan = $bulan;

		switch($jenis)
		{
			case "1":	$caption = "Statistik Perkara";			$caption2 = "Bulan " . $bulan . " Tahun " . $tahun;	break;		
			case "2":	$caption = "Statistik Perkara per Jenis Perkara";			$caption2 = "Tahun " . $tahun;						break;		
			case "3":	$caption = "Statistik Perkara per Subjenis Perkara";			$caption2 = "Tahun " . $tahun;						break;	
			case "4":	$caption = "Statistik Perkara per Status Perkara";			$caption2 = "Tahun " . $tahun;						break;	
		}

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("BDSI");
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPSheet = $objPHPExcel->getActiveSheet();
		
		$indeks = 5;
		$no = 1;
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $caption);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);

		$objPHPExcel->getActiveSheet()->setCellValue('A2', $caption2);
		$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($styleArray);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'NO.');
		$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleArray);

		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('B4', 'KETERANGAN');
		$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleArray);

		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('C4', 'JUMLAH SURAT');
		$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleArray);

		foreach($rows as $row)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$indeks, $no);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$indeks, $row->keterangan);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$indeks, $row->jumlah);

			$indeks++;
			$no++;
		}

		header("Content-Type: application/ms-excel");
		header("Content-Disposition: attachment; filename=Statistik.xls");

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$objWriter->save("php://output");
	}
	
}