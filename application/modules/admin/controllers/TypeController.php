<?php

class Admin_TypeController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow();
		
	}
	
	public function preDispatch() 
	{
		//$this->max_height = 800;
		//$this->max_width = 750;
		$this->Type = new TypeService();
	}
	
	private function enumDropdown($table_name, $column_name, $select_value, $column_name1)
	{
		$selectDropdown = "<select name=\"$column_name\">";		
		$result = "SELECT $column_name1,$column_name FROM $table_name where status=1";
		$row = $this->Type->enumData($result);
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
        // action body
		$this->view->rows=$this->Type->getAllData();
			
    }
	
	public function addAction()	
	{		
		$this->view->enum = $this->enumDropdown('brand', 'brand', $id_brand,"id_brand");		
		if ( $this->getRequest()->isPost() ) 
		{			
			$id_brand = $this->getRequest()->getParam('brand');
			$type = $this->getRequest()->getParam('type');
			$this->Type->addData($id_brand, $type);
			$this->_redirect('/admin/type');
		}
	}
	
	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');	
		$id_brand = $this->getRequest()->getParam('id_brand');	
		$this->view->enum = $this->enumDropdown('brand', 'brand', $id_brand,"id_brand");			
		if ( $this->getRequest()->isPost() ) 
		{			
			$id_brand = $this->getRequest()->getParam('brand');
			$type = $this->getRequest()->getParam('type');	
			$this->Type->editData($id, $id_brand, $type);	
			$this->_redirect('/admin/type');
		} else {
			$this->view->row = $this->Type->getData($id);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->Type->softDeleteData($id);
		$this->_redirect('/admin/type');
	}
}