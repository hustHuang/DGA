<?php

set_time_limit(0);
require_once '../common.php';
//echo ABSPATH;
require_once ABSPATH . 'add/searchgene.php';
$id = $_POST['id'];
//$count=$_POST['count'];
$count = 5;
$group = $_POST['group'];
$query_type = $_POST['qtype'];
$ngc = $_POST['ngc'];
$search_action = new searchgene();
if ($query_type == 'g2d') {
    if ($group == 'edges') {
        echo $search_action->get_click_generelatedges_info($id);
    } else {
        if ($ngc == 'r') {
            echo $search_action->get_click_target_disease_info($id);
        } else {
            echo $search_action->get_click_target_genenode_info($id, $count);
        }
    }
} else {
    if ($group == 'edges') {
        echo $search_action->get_click_generelatedges_info($id);
    } else {
        if ($ngc == 'q') {
            echo $search_action->get_click_target_disease_info($id);
        } else {
            echo $search_action->get_click_target_genenode_info($id, $count);
        }
    }
}
?>

