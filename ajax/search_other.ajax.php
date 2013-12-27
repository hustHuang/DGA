<?php
require_once  '../common.php';
require_once ABSPATH . 'add/searchgene.php';
$query_names = $_POST['query'];
//$view = $_POST['view'];
$query_type=$_POST['qtype'];
$count=$_POST['count'];
//$query_names="multiple myeloma ";
//$count=200;
//$query_type='d2g';
$result = array();
$search = new searchgene();
$search->set_search_type($view);
$search->set_search_params($query_names, $query_type);
if($query_type=='d2g'){
    $search->execute_search($count);
    $result['cw_node_data'] = $search->get_cw_node_data();
    $result['cw_edge_data'] = $search->get_cw_edge_data();     
}else{
    $search->execute_searchg2d($count);
    $result['cw_node_data'] = $search->get_cw_node_data();
    $result['cw_edge_data'] = $search->get_cw_edge_data();
}
echo json_encode($result);
?>