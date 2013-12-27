<?php
set_time_limit(0);
require_once '../common.php';
require_once ABSPATH . 'add/DBC.php';
require_once ABSPATH . 'class/Search.class.php';
$id = $_POST['id'];
//$id = 'primary breast cancer';
$name = $_POST['name'];
//$name = 'PLAU';
$result = array();
$search_action = new Search();
$gene_id = $search_action->get_gene_id_by_name($name);
$term_id = $search_action->get_term_id_by_name($id);
$result = $search_action->get_text_gene_info($gene_id, $term_id);
echo json_encode($result);
?>


