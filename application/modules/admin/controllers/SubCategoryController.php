<?php

class Admin_SubCategoryController extends Zend_Controller_Action
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
		$this->SubCategory = new SubCategoryService();		
	}
	
	private function enumDropdown($table_name, $column_name, $select_value)
	{
		$selectDropdown = "<select name=\"$column_name\">";		
		$result = "SELECT $column_name FROM $table_name ";
		$row = $this->SubCategory->enumData($result);
		/* $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
		// ascending value
		arsort($enumList);
		// desc value
		// asort($enumList); */
		foreach($row as $value)
			if($select_value = $value)
			{
				$selectDropdown .= "<option value=\"$value\" selected>$value</option>";
			} else 
			{
				$selectDropdown .= "<option value=\"$value\">$value</option>";
			}
				$selectDropdown .= "</select>";
		return $selectDropdown;
	}
	
	public function indexAction()
	{
		//$this->view->rows = $this->FotoService->getAllData();
		$this->view->rows = $this->SubCategory->getAllData();
	}

	public function addAction()	
	{		
		$this->view->enum = $this->enumDropdown('category', 'category', "");		
		if ( $this->getRequest()->isPost() ) 
		{			
			$category = $this->getRequest()->getParam('category');
			$this->SubCategory->addData($category) ;
			$this->_redirect('/admin/subcategory');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');		
		if ( $this->getRequest()->isPost() ) 
		{			
			$category = $this->getRequest()->getParam('category');			
			$this->SubCategory->editData($id, $category);	
			$this->_redirect('/admin/subcategory');
		} else {
			$this->view->row = $this->SubCategory->getData($id);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->SubCategory->softDeleteData($id);
		$this->_redirect('/admin/subcategory');
	}
}