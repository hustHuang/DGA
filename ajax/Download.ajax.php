<?php
require_once '../common.php';
require_once (ABSPATH . 'class/Search.class.php');
require_once (ABSPATH . 'class/download.php');
require_once (ABSPATH . 'add/searchgene.php');
$prefix = ABSPATH . 'data/result/';
$download=new download();
$relation=new searchgene();
$tab_type = 'search';
//$search_type ='d2g' ;
//$search_word = 'primary breast cancer';
//$sel='1';
//$rtype='co_expression';
$search_type =$_POST['qtype'];
$search_word =$_POST['query'];
//$sel=$_POST['sel'];
$rtype=$_POST['type'];
//$search_word = $_POST['query'];
//$search_type=$_POST['qtype'];
$type = key_exists('exportType', $_REQUEST) ? $_REQUEST['exportType'] : 'all';
$filename = '';
if ($type == 'all') {
    $filename = 'MappingResult.obo';
} else if ($type == 'ids') {
    $filename = 'IDMappings.rdf';
}
else  if ($type=='data'){
    $timetamp=mktime();
    $filename = $timetamp.'data.txt';
    $file = $prefix . $filename;
    $download->getfilename($file,$search_type,$search_word,$rtype);
    $download->writedatatotxt();
}
else if ($type=='rel_data'){
    $timetamp=mktime();
    $filename = $timetamp.'data.txt';
    $file = $prefix . $filename;
    $download->getfilename($file,$search_type,$search_word,$rtype);   
    $download->download_gene_relations();
}
$file = $prefix . $filename;

if (!file_exists($file)) {
    echo 'Sorry. File not exist.';
    exit();
} else {
    header('Content-Description: File Transfer');
    header('Content-type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header("Content-Length: " . filesize($file));
    readfile($file);
}
