<?php
    
    /**
    * class для показа всех таблиц администратору
    */
    class adminTablesController  {
    
    	private $query;
    	private $response;
    	
    	public function __construct() 
    	{
        	$this->query = new Query();
        	if (isset($_GET['table'])) {
            	$this->actionViewTable($_GET['table']);
            	
        	} else {
            	$this->actionViewAll();
            	
        	}
    	}
    	
    	public function actionViewAll()
    	{
        	$result = $this->query->runSql("SELECT TABLE_NAME, TABLE_TYPE, ENGINE, IFNULL(TABLE_COMMENT, '') as 'Комментарий' FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='allservi' order by 1");
        	$this->response = '<ul>';
        	
        	foreach ($result as $key => $row) {
            	
             	$this->response .= "<li>{$row['ENGINE']} {$row['TABLE_TYPE']} <a href='/admin/tables/?table={$row['TABLE_NAME']}'>{$row['TABLE_NAME']}</a> {$row['Комментарий']}</li>";
/*
               	foreach ($row as $nameField => $value)
            	    $this->response .= "<li>$nameField $value</li>";

            	$this->response .= '</ul></li>';
*/
        	}
        	$this->response .= '</ul>';
    	}
    	
    	public function actionViewTable($table)
    	{
        	$tableView = new tableDrawing($table);
            $this->response = $tableView->getTable();
    	}
        
        public function getResponse()
        {
           return  $this->response;
        }
    
    }
    