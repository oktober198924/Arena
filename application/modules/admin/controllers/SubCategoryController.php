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
	
	private function enumDropdown($table_name, $column_name, $select_value, $column_name1)
	{
		$selectDropdown = "<select name=\"$column_name\">";		
		$result = "SELECT $column_name1,$column_name FROM $table_name where status=1";
		$row = $this->SubCategory->enumData($result);
		
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
		$this->view->rows = $this->SubCategory->getAllData();
	}

	public function addAction()	
	{		
		$this->view->enum = $this->enumDropdown('category', 'category', "","id_category");		
		if ( $this->getRequest()->isPost() ) 
		{			
			$category = $this->getRequest()->getParam('category');
			$subcategory = $this->getRequest()->getParam('subcategory');
			$this->SubCategory->addData($category,$subcategory) ;
			$this->_redirect('/admin/subcategory');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');	
		$id_category = $this->getRequest()->getParam('id_category');	
		$this->view->enum = $this->enumDropdown('category', 'category', $id_category, 'id_category');	
		if ( $this->getRequest()->isPost() ) 
		{			
			$category = $this->getRequest()->getParam('category');
			$subcategory = $this->getRequest()->getParam('subcategory');	
			$this->SubCategory->editData($id, $category, $subcategory);	
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