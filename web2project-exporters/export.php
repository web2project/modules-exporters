<?php /* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')){
	die('You should not access this file directly.');
}

if (!$canRead){
	$AppUI->redirect( "m=public&a=access_denied" );
}
$separator = ',';
$project_id = (int) w2PgetParam($_POST, 'project_id', '0');
$msproject = "MsProject".w2PgetParam($_POST, 'msproject', '2003');
$file = w2PgetParam($_POST, 'sql_file', $msproject."-".$project_id);
if (!$file) {
	$file = $msproject."-".$project_id;
}
$file .= '.xml'; 
$zipped = w2PgetParam($_POST, 'zipped', false);

$clazz = $msproject."Exporter";
require_once("exports/".strtolower($msproject)."exporter.class.php");
$exporter = new $clazz($file, $_POST);
$output = $exporter->export($project_id);
$mime_type = 'application/vnd.ms-project';
if ($zipped){
	include('lib/zip.lib.php');
	$zip = new zipfile;
	$zip->addFile($output,$file);
	$output = $zip->file();
	$file .= '.zip';
	$mime_type = 'application/x-zip';
}
$testing = false;
if (!$testing){
	header('Content-Disposition: inline; filename="' . $file . '"');
	header('Content-Type: ' . $mime_type);
} else {
	echo '<code>';
	print_r($_POST);
	$output .= '</code>';
}
echo $output;
