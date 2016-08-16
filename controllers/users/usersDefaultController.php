<?php
    
    /**
    * usersDefaultController
    */
    class usersDefaultController  {
    
    	private $query;
    	private $response;
    	
    	public function __construct()
    	{
        	$this->query = new Query();
        	$this->response = file_get_contents('http://allservice.in.ua/text_main.html');
    	}
    
        public function getLeftPanel()
        {
            $result = $this->query->runSql("select * from category order by name");
            $catalog = '<ul>';
        	
        	foreach ($result as $key => $row) {
            	$catalog.= "<li>{$row['name']}</li>";
        	}
        	
        	$catalog .= '</ul>';
        	
            return $catalog;
        }
        public function getResponse()
        {
           return  $this->response;
        }
    
    }
    