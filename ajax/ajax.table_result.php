<?php
require_once'../common.php';
require_once ABSPATH . 'add/searchgene.php';
$query_names = $_POST['query'];
$query_type=$_POST['qtype'];
$type=$_POST['type'];
//$sel=$_POST['sel'];
$sel=0;
$m=$_POST['m'];
$n=$_POST['n'];
//$query_names='primary breast cancer';
//$query_type='d2g';
//$type='co_expression';
$result = array();
$search = new searchgene();
$search->set_search_type($view);
$search->set_m($m);
$search->set_n($n);
$search->set_search_params($query_names, $query_type);
$search->set_search_genereltype($type);
if($sel)
{
if($query_type=='d2g')
    {
       $search->execute_search_t();
       $result['table_list_data'] = $search->get_view_table_data();
       $result['table_list_count'] =$search->get_result_count();
    }	
}
else{
    if($query_type=='d2g')
    {
       $search->execute_search_t_other();
       $result['table_list_data'] = $search->get_view_table_data();
       $result['table_list_count'] =$search->get_result_count();
       $result['m'] =$search->get_m();
       $result['n'] =$search->get_n();
    }
  }
echo json_encode($result);