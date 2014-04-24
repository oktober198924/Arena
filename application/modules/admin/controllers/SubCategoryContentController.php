<?php

class Admin_SubCategoryContentController extends Zend_Controller_Action
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
		$this->SubCategoryContent = new SubCategoryContentService();		
	}
	
	private function enumDropdown($table_name, $column_name, $select_value, $column_name1)
	{
		$selectDropdown = "<select name=\"$column_name\">";		
		$result = "SELECT $column_name1,$column_name FROM $table_name where status=1";
		$row = $this->SubCategoryContent->enumData($result);
		
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
		$this->view->rows = $this->SubCategoryContent->getAllData();
	}

	public function addAction()	
	{		
		$this->view->enum = $this->enumDropdown('sub_category', 'sub_category', "","id_sub_category");		
		if ( $this->getRequest()->isPost() ) 
		{			
			$id_sub_category = $this->getRequest()->getParam('id_sub_category');
			$sub_category_content = $this->getRequest()->getParam('sub_category_content');
			$this->SubCategoryContent->addData($id_sub_category, $sub_category_content) ;
			$this->_redirect('/admin/subcategorycontent');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');	
		$id_sub_category = $this->getRequest()->getParam('id_sub_category');	
		$this->view->enum = $this->enumDropdown('sub_category', 'sub_category', $id_sub_category, 'id_sub_category');	
		if ( $this->getRequest()->isPost() ) 
		{			
			$id_sub_category = $this->getRequest()->getParam('sub_category');
			$sub_category_content = $this->getRequest()->getParam('sub_category_content');
			$this->SubCategoryContent->editData($id, $id_sub_category, $sub_category_content);	
			$this->_redirect('/admin/subcategorycontent');
		} else {
			$this->view->row = $this->SubCategoryContent->getData($id);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->SubCategoryContent->softDeleteData($id);
		$this->_redirect('/admin/subcategorycontent');
	}
}