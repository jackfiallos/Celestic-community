<?php
if (!isset($model->document_path) || empty($model->document_path)) {
    exit();
}

$fileTypesAllowed = array('image/png','image/jpeg','image/gif','image/bmp');

$file = basename($model->document_path);
$path = "resources/".$file;

$path = dirname($_SERVER['SCRIPT_FILENAME'])."/".$path;

if (file_exists($path)) {
	if (in_array($model->document_type,$fileTypesAllowed))
	{
		$imagedata1 = file_get_contents($path);
		$base64   = base64_encode($imagedata1); 
		echo CHtml::image('data:' . $model->document_type . ';base64,' . $base64);
		//echo CHtml::image('http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/'.$model->document_path);
	}
	else
	{
		$type = (isset($model->document_type)) ? $model->document_type : "application/octet-stream";
		//Headers
		header("Content-Description: File Transfer");
		header("Content-Type:".$type);
		header("Content-Disposition: attachment; filename=".$file);
		header("Content-Transfer-Encoding: binary");
		header("Expires: 0");
		header("Cache-Control: private, must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache, private");
		header("Content-Length:".filesize($path));
		// Download File
		ob_clean();
		flush();
		readfile($path);
	}
	exit;
} else {
    die("File not exist!! ".$path);
}
?>