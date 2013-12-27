<?php
/**
 * @author GGCoke
 * 2012-3-11 20:20:44
 */
require_once '../common.php';
require_once (ABSPATH . 'class/Search.class.php');
$search_service = new Search();
$keywords = $_REQUEST['search_word'];
$keys = explode(STRING_SEPARATOR, $keywords);
$keys = $search_service->format_keys($keys);
$all_ids = array();
$parent_ids = array();
$result = array();
$queried_ids = array();
foreach ($keys as $term_name) {
    $term_id = $search_service->get_term_id_by_name($term_name);
    if (is_null($term_id) || $term_id == '' || $term_id == null)
        continue;
    if (!in_array('li_' . $term_id, $queried_ids)){
        array_push($queried_ids, 'li_' . $term_id);
    }
    $parent_id = 'li_' . $search_service->get_parent_node_id($term_id);
    if (!in_array($parent_id, $parent_ids)){
        array_push($parent_ids, $parent_id);
    }
    $tmp_array = array();
    $tmp = $search_service->get_all_parent_ids($term_id, $tmp_array);
    $all_ids = array_merge($all_ids, $tmp);
}
$result['queried_ids'] = $queried_ids;
$result['initially_load'] = $all_ids;
$result['initially_open'] = $parent_ids;
echo json_encode($result);

//End of script