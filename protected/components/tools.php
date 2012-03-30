<?php
Class tools 
{
	/**
    * Write $constants in config file
    *
    * @access public
    * @param array $constants
    * @return boolean
    */
    public static function writeConfigFile($content)
    {
    	return @file_put_contents(Yii::app()->basePath.'/config/db.php', $content);
    }
    
	/**
	 * Verify is file exist
	 * @param string $path
	 * @return boolean
	 */
	public static function file_writable($path)
	{
		if(!is_file($path))
			return true;
		
		$open = @fopen($path, 'a');
		if($open === false)
			return false;
		
		@fclose($open);
		return true;	
	}
	
	/**
	 * Verify is folder exist
	 * @param string $path
	 * @return boolean
	 */
	public static function folder_writable($path)
	{
		if(!is_dir($path))
			return false;
		
		do {
			$test = self::with_slash($path) . sha1(uniqid(rand(), true));
		} while (is_file($test));
		
		$put = @file_put_contents($test, 'test');
		if($put === false)
			return false;
		
		@unlink($test);
		return true;
	}
	
	/**
	 * Return true if string ends with niddle
	 * @param string $string
	 * @param string $niddle
	 * @return boolean
	 */
	public static function str_ends_with($string, $niddle)
	{
		return substr($string, strlen($string) - strlen($niddle), strlen($niddle)) == $niddle;
	}
	
	/**
	 * Return path with trailing slash
	 * @param string $path
	 * @return string path with trailing slash
	 */
	public static function with_slash($path)
	{
		return self::str_ends_with($path, '/') ? $path : $path.'/';
	}
	
	public static function tag($content = '', $type = false)
	{
		if($type)
			return CHtml::tag('span',array('style'=>'color:#008A00;font-size:larger'),"&#10004;")."&nbsp;".CHtml::tag('span', array('style'=>'color:blue;font-weight:bold;'), " Passed - ".$content);
		else
			return CHtml::tag('span',array('style'=>'color:red;font-size:larger'),"&#10006;")."&nbsp;".CHtml::tag('span', array('style'=>'color:red;font-weight:bold;'), " Error - ".$content);
			
	}
}