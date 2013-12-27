<?php

require_once '../common.php';
require_once ABSPATH . 'add/searchgene.php';

//$query_names = $_REQUEST['query'];
//$query_type=$_REQUEST['qtype'];
$query_names = $_POST['query'];
$query_type=$_POST['qtype'];
if(!$query_names||!$query_type){
    echo"fail";
    exit;
}
//$query_names='pterygium';
//$view = $_POST['view'];
$view='tv_g2g';
//$type = $_POST['type'];
//$type='coexp';
//$query_type = $_POST['query_type'];
//$query_type='d2g';


$result = array();
$search = new searchgene();
$search->set_search_type($view);
$search->set_search_params($query_names, $query_type);
$info = $search->execute_search();
//switch ($view) {
//    case 'tv_g2g': {    
//            $result['table_list_data'] = $search->get_view_table_data();
//            $result['table_list_count'] = $search->get_result_count();
//        }break;
//    case 'nv': {   
//              $result['cw_node_data'] = $search->get_cw_node_data();
//              $result['cw_edge_data'] = $search->get_cw_edge_data();
//       }break;
//    default:
//       break;
//}

echo json_encode($search->get_view_table_data());

?>
