<?php

class Admin_RekapitulasiController extends Zend_Controller_Action
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
		$this->rekapitulasiService = new RekapitulasiService();
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

		$this->view->rows = $this->rekapitulasiService->getRekapitulasi($jenis, $tahun, $bulan);
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
		$this->view->bulan = $this->_helper->GetMonthName($bulan);
		$rows = $this->rekapitulasiService->getRekapitulasi($jenis, $tahun, $bulan);

		$bulan = ($bulan == "*") ? "" : "Bulan " . $this->_helper->GetMonthName($bulan);
		$tahun = ($tahun == "*") ? "" : "Tahun " . $tahun;
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
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Rekapitulasi Perkara per Tahun');
				$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DPR RI');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:O2');
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A3', $filter);
				$objPHPExcel->getActiveSheet()->mergeCells('A3:O3');
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No.');
				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('B5', '');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Jan');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Feb');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Mar');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Apr');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Mei');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Jun');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('I5', 'Jul');
				$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('J5', 'Ags');
				$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('K5', 'Sep');
				$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('L5', 'Okt');
				$objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('M5', 'Nov');
				$objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('N5', 'Des');
				$objPHPExcel->getActiveSheet()->getStyle('N5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('O5', 'Total');
				$objPHPExcel->getActiveSheet()->getStyle('O5')->applyFromArray($styleArray);

				foreach($rows as $row)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$maxRow, $indeks);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$maxRow, '');
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$maxRow, $row->jan);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$maxRow, $row->feb);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$maxRow, $row->mar);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$maxRow, $row->apr);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$maxRow, $row->mei);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$maxRow, $row->jun);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$maxRow, $row->jul);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$maxRow, $row->ags);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$maxRow, $row->sep);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$maxRow, $row->okt);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$maxRow, $row->nov);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$maxRow, $row->des);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$maxRow, $row->total);

					$indeks++;
					$maxRow++;
				}
				break;
			
			case 2:	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Rekapitulasi Perkara per Jenis Perkara per Tahun');
				$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DPR RI');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:O2');
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A3', $filter);
				$objPHPExcel->getActiveSheet()->mergeCells('A3:O3');
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No.');
				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('B5', '');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Jan');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Feb');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Mar');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Apr');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Mei');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Jun');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('I5', 'Jul');
				$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('J5', 'Ags');
				$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('K5', 'Sep');
				$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('L5', 'Okt');
				$objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('M5', 'Nov');
				$objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('N5', 'Des');
				$objPHPExcel->getActiveSheet()->getStyle('N5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('O5', 'Total');
				$objPHPExcel->getActiveSheet()->getStyle('O5')->applyFromArray($styleArray);

				foreach($rows as $row)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$maxRow, $indeks);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$maxRow, $row->kategori_perkara);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$maxRow, $row->jan);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$maxRow, $row->feb);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$maxRow, $row->mar);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$maxRow, $row->apr);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$maxRow, $row->mei);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$maxRow, $row->jun);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$maxRow, $row->jul);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$maxRow, $row->ags);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$maxRow, $row->sep);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$maxRow, $row->okt);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$maxRow, $row->nov);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$maxRow, $row->des);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$maxRow, $row->total);

					$indeks++;
					$maxRow++;
				}
				break;
			
			case 3:	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Rekapitulasi Perkara per Subjenis Perkara per Tahun');
				$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DPR RI');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:O2');
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A3', $filter);
				$objPHPExcel->getActiveSheet()->mergeCells('A3:O3');
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No.');
				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('B5', '');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Jan');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Feb');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Mar');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Apr');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Mei');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Jun');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('I5', 'Jul');
				$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('J5', 'Ags');
				$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('K5', 'Sep');
				$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('L5', 'Okt');
				$objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('M5', 'Nov');
				$objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('N5', 'Des');
				$objPHPExcel->getActiveSheet()->getStyle('N5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('O5', 'Total');
				$objPHPExcel->getActiveSheet()->getStyle('O5')->applyFromArray($styleArray);

				foreach($rows as $row)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$maxRow, $indeks);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$maxRow, $row->subkategori_perkara);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$maxRow, $row->jan);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$maxRow, $row->feb);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$maxRow, $row->mar);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$maxRow, $row->apr);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$maxRow, $row->mei);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$maxRow, $row->jun);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$maxRow, $row->jul);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$maxRow, $row->ags);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$maxRow, $row->sep);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$maxRow, $row->okt);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$maxRow, $row->nov);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$maxRow, $row->des);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$maxRow, $row->total);

					$indeks++;
					$maxRow++;
				}
				break;
			
			case 4:	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Rekapitulasi Perkara per Status Perkara per Tahun');
				$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DPR RI');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:O2');
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->setCellValue('A3', $filter);
				$objPHPExcel->getActiveSheet()->mergeCells('A3:O3');
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No.');
				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Status Perkara');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Jan');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Feb');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Mar');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Apr');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Mei');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Jun');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('I5', 'Jul');
				$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('J5', 'Ags');
				$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('K5', 'Sep');
				$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('L5', 'Okt');
				$objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('M5', 'Nov');
				$objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('N5', 'Des');
				$objPHPExcel->getActiveSheet()->getStyle('N5')->applyFromArray($styleArray);

				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue('O5', 'Total');
				$objPHPExcel->getActiveSheet()->getStyle('O5')->applyFromArray($styleArray);

				foreach($rows as $row)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$maxRow, $indeks);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$maxRow, $row->status_perkara);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$maxRow, $row->jan);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$maxRow, $row->feb);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$maxRow, $row->mar);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$maxRow, $row->apr);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$maxRow, $row->mei);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$maxRow, $row->jun);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$maxRow, $row->jul);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$maxRow, $row->ags);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$maxRow, $row->sep);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$maxRow, $row->okt);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$maxRow, $row->nov);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$maxRow, $row->des);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$maxRow, $row->total);

					$indeks++;
					$maxRow++;
				}
				break;
		}

		header("Content-Type: application/ms-excel");
		header("Content-Disposition: attachment; filename=rekapitulasi.xls");

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$objWriter->save("php://output");
	}
	
}