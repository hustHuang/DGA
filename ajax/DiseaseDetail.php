<?php
require_once '../common.php';
require_once (ABSPATH . 'class/Search.class.php');
$search_service = new Search();
$disease_name = $_REQUEST['disease_name'];
$index = $_REQUEST['index'];
$result = array();
$term_info = $search_service->get_term_detail($disease_name);
$result['info'] = $term_info;
$result['index'] = $index;
echo json_encode($result);
