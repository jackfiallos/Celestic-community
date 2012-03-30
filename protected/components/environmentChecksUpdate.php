<?php
Class environmentChecksUpdate 
{
	/**
    * Array of files and folders that need to writable
    * @var array
    */
    static $check_is_writable = array(
    	'/assets',
	    '/resources',
		'/runtime'
	);
    
    /**
    * Array of extensions requireds
    * @var array
    */
    static $check_extensions = array(
		'mysql', 'gd'
	);
	
    /**
    * Execute environment checks
    * @access public
    * @param void
    * @return array
    */
	public static function run()
	{
        $checklist = array();
        $all_ok = true;
              
		if(version_compare(PHP_VERSION, '5.0.2', 'ge'))
			$checklist[] = tools::tag("PHP version is " . PHP_VERSION, true);
      	else
      	{
        	$checklist[] = tools::tag("You PHP version is ".PHP_VERSION.". PHP 5.0.2 or newer is required", false);
        	$all_ok = false;
      	}
      
		foreach(self::$check_extensions as $extension_name)
		{
        	if(extension_loaded($extension_name))
          		$checklist[] = tools::tag($extension_name." extension is loaded", true);
        	else
        	{
          		$checklist[] = tools::tag($extension_name." extension isn't loaded", false);
          		$all_ok = false;
        	}
      	}
      	
      	$checklist[] = tools::tag("Comments table not updated yet.", true);
      
		if(is_array(self::$check_is_writable))
		{
        	foreach(self::$check_is_writable as $relative_folder_path)
        	{
          		if (($relative_folder_path == '/assets') || ($relative_folder_path == '/resources'))
        			$check_this = Yii::app()->assetManager->basePath;
          		else
          			$check_this = Yii::app()->basePath.$relative_folder_path;
          			
	          	$is_writable = false;
	          	if(is_file($check_this))
	            	$is_writable = tools::file_writable($check_this);
	          	elseif(is_dir($check_this))
	            	$is_writable = tools::folder_writable($check_this);
				
	          	if($is_writable)
	            	$checklist[] = tools::tag($relative_folder_path." is writable", true);
	          	else
	          	{
		            $checklist[] = tools::tag($relative_folder_path." isn't writable", false);
	            	$all_ok = false;
	          	}
			}
		}
		
		$checklist[] = (bool)$all_ok;
		
		return $checklist;
    }
}