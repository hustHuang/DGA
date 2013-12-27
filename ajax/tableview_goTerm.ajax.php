<?php
set_time_limit(0);
require_once '../common.php';
//echo ABSPATH;
require_once ABSPATH . 'add/searchgene.php';
$id = $_POST['id'];
$count= $_POST['count'];
//$id='TP53';
$search_action = new searchgene();
echo $search_action->get_click_target_geneSymbol_info($id,$count);
      
   

