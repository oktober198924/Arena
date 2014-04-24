<?php

class Admin_ContentController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user');
		$this->_helper->_acl->allow('viewer');
	}
	
	public function preDispatch() 
	{
		//$this->FotoService = new FotoService();
		$this->Content = new ContentService();		
	}
	
	private function enumDropdown($table_name, $column_name, $select_value, $column_name1)
	{
		$selectDropdown = "<select name=\"$column_name\">";		
		$result = "SELECT $column_name1,$column_name FROM $table_name where status=1";
		$row = $this->Content->enumData($result);
		/* var_dump($result);
		die(); */
		foreach($row as $value){
			if($select_value == $value[$column_name1])
			{
				$selectDropdown .= "<option value=\"$value[$column_name1]\" selected>$value[$column_name]</option>";
			} else 
			{
				$selectDropdown .= "<option value=\"$value[$column_name1]\">$value[$column_name]</option>";
			}
		}
				$selectDropdown .= "</select>";
		return $selectDropdown;
	}
	
	public function indexAction()
	{
		//$this->view->rows = $this->FotoService->getAllData();
		$this->view->rows = $this->Content->getAllData();
	}

	public function addAction()	
	{		
		$this->view->enum = $this->enumDropdown('sub_category_content', 'sub_category_content', "","id");		
		if ( $this->getRequest()->isPost() ) 
		{			
			$subcategorycontent = $this->getRequest()->getParam('sub_category_content');
			$content = $this->getRequest()->getParam('content');
			$this->Content->addData($subcategorycontent, $content)  ;
			$this->_redirect('/admin/content');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');	
		$id_sub_category = $this->getRequest()->getParam('id_sub_category');	
		$this->view->enum = $this->enumDropdown('sub_category_content', 'sub_category_content', $id_sub_category,"id");	
		if ( $this->getRequest()->isPost() ) 
		{			
			$subcategorycontent = $this->getRequest()->getParam('sub_category_content');
			$content = $this->getRequest()->getParam('content');	
			$this->Content->editData($id, $subcategorycontent, $content);	
			$this->_redirect('/admin/content');
		} else {
			$this->view->row = $this->Content->getData($id);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->Content->softDeleteData($id);
		$this->_redirect('/admin/content');
	}
}