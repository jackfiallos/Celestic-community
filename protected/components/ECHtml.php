<?php
/**
 * ECHtml class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * ECHtml is the customized base chtml class
 *
 * @property integer $countComments
 * @property integer $item_id
 *
 **/
class ECHtml extends CHtml
{
	/**
	 * Split string with determinate lenght
	 * @param string $str
	 * @param int $words
	 * @return string new string splitted
	 */
	public function word_split($str,$words=15)
	{
		$arr = preg_split("/[\s]+/", $str,$words+1);
		$arr = array_slice($arr,0,$words);
		return join(' ',$arr).((count($arr)>$words) ? "..." : null);
	}
	
	/**
	 * Find url into string and convert into clicable url
	 * @param string $string
	 * @return string new string with url converted to links
	 */
	public function createLinkFromString($string)
	{
		$reg_exUrl = "/(http??:\/\/)(\S*)?/";
		$url = array();
		if (preg_match_all($reg_exUrl, $string, $url, PREG_SET_ORDER))
		{
			for($i=0; $i<count($url); $i++)
			{
				//$string = preg_replace($reg_exUrl, CHtml::link($url[$i][0], $url[$i][0]), $string);
				$link = CHtml::link($url[$i][0], $url[$i][0],array('target'=>'_blank'));
				$string = str_replace($url[$i][0], $link, $string);
			}
		}
		return $string;
	}
	
	/**
	 * 
	 * Return size of path directory using recursive trace 
	 * @param string $directory
	 * @param boolean $format
	 */
	function directory_size($directory, $format=false)
	{
		$size = 0;
	
		// if the path has a slash at the end we remove it here
		if(substr($directory,-1) == '/')
			$directory = substr($directory,0,-1);
	
		// if the path is not valid or is not a directory ...
		if(!file_exists($directory) || !is_dir($directory) || !is_readable($directory))
			return -1;
		
		// we open the directory
		if($handle = opendir($directory))
		{
			// and scan through the items inside
			while(($file = readdir($handle)) !== false)
			{
				// we build the new path
				$path = $directory.'/'.$file;
	
				// if the filepointer is not the current directory
				// or the parent directory
				if($file != '.' && $file != '..')
				{
					// if the new path is a file, add the filesize to the total size
					if(is_file($path))
						$size += filesize($path);
					elseif(is_dir($path)) //if the new path is a directory
					{
						// we call this function with the new path
						$handlesize = directory_size($path);
	
						// if the function returns more than zero, we add the result to the total size
						if($handlesize >= 0)
							$size += $handlesize;
						else //else we return -1 and exit the function
							return -1;
					}
				}
			}
			// close the directory
			closedir($handle);
		}
		// if the format is set to human readable
		if($format == true)
		{
			if($size / 1048576 > 1)
				return round($size / 1048576, 1).' MB';
			elseif($size / 1024 > 1)
				return round($size / 1024, 1).' KB';
			else
				return round($size, 1).' bytes';
		}
		else
			return $size;
	}
	
}