<?php
require_once '../common.php';
require_once (ABSPATH . 'class/Search.class.php');
$search_service = new Search();
$term_id = $_REQUEST['term_id'];
$result = $search_service->get_child_node_by_id($term_id);
echo $result;
// End of script