<?php
    
    /**
    * log work program
    */
    class loggerClass {
    
    	private $log = [];
    	private $debug = false;
    	
    	public function __construct($debug) 
    	{
        	$this->debug = $debug;
    	}
    	
    	public function __destruct() 
    	{
        	$text = '';
        	foreach($this->log as $key => $value) {
            	
            	if (is_array($value)) {
                	foreach($value as $key1 => $value1) {
                    	$text .= $value1;
                	}
            	}
            	
            	$text .= $value;
            	
        	}
        	
        	if ($this->debug) {
            	echo $text;
        	} else {
            	file_put_contents(__DIR__ . "/log_" . date('Y-m-d h:i:s') . ".txt", $text);
        	}
    	}
    	
    	public function addTextToLog($text) 
    	{
        	$this->log[] = $text;
    	}
    	
    	public function addArrayToLog(array $arrText) 
    	{
        	$this->log[] = $arrText;
    	}
    	
    	public function getLog()
    	{
        	return $this->log;
    	}
    
    }
    