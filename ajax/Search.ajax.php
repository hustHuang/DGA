<?php
require_once '../common.php';
require_once (ABSPATH . 'class/Search.class.php');
$search_service = new Search();
$current_page = intval($_REQUEST['page']);
//$current_page=1;
$count = intval($_REQUEST['rp']);
//$count=1000;
$keywords = $_REQUEST['query'];
//$keywords='chikungunya';
$type = $_REQUEST['qtype'];
//$type='d2g';
$sortname = $_REQUEST['sortname'];
$sortorder = $_REQUEST['sortorder'];
//$sortname = 'symbol';
//$sortorder = 'asc';
$search_result = $search_service->execut_search($keywords, $type, $current_page, $count, $sortname, $sortorder);
echo json_encode($search_result);
