<?php
Class databaseConfiguration 
{
	/**
    * Execute database installation
    * @access public
    * @param string $file path
    * @param string $delimiter
    * @return boolean
    */	
	public static function SplitSQL($file, $delimiter = ';')
	{
		set_time_limit(0);
		$result = array();
	    if (is_file($file) === true)
	    {
	        $file = fopen($file, 'r');
	
	        if (is_resource($file) === true)
	        {
	            $query = array();
	
	            while (feof($file) === false)
	            {
	                $query[] = fgets($file);
	
	                if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1)
	                {
	                    $query = trim(implode('', $query));
	
	                    if (Yii::app()->db->createCommand($query)->query() === false)
	                        $result[] = '<h3>ERROR: ' . $query . '</h3>' . "\n";
	                    else
	                        $result[] ='<h3>SUCCESS: ' . $query . '</h3>' . "\n";
	
	                    while (ob_get_level() > 0)
	                    {
	                        ob_end_flush();
	                    }
	
	                    flush();
	                }
	
	                if (is_string($query) === true)
	                {
	                    $query = array();
	                }
	            }
	
	            return fclose($file);
	        }
	    }
	
	    return false;
	}		
}