<?php
//require_once './common.php';
require_once  './searchgene.php';
$search = new searchgene();
//$term_name = 'pre-eclampsia';
$q='primary breast cancer';
$info = $search->execute_search();

