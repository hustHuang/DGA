<?php

/**
 * @author GGCoke
 * 2012-3-4 16:39:47
 */
require_once '../common.php';
require_once (ABSPATH . 'class/Search.class.php');
$q = $_REQUEST['q'];
$type = $_REQUEST['type'];
if (is_null($q) || $q == '')
    return;
$search_service = new Search();
$result = array();
if ($type == 'g') {
    $result = $search_service->get_autocomplete_gene($q);
} else if ($type == 'd'){
    $result = $search_service->get_autocomplete_disease($q);
}
foreach ($result as $item)
        echo $item . "\n";
// End of script
