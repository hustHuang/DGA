<?php

require_once './common.php';
require_once (ABSPATH . 'class/Search.class.php');
$search_service = new Search();
$type_array = array('g2d', 'd2g');
$query = key_exists('searchWord', $_REQUEST) ? trim($_REQUEST['searchWord']) : null;
$type = key_exists('searchType', $_REQUEST) ? trim($_REQUEST['searchType']) : null;
$current_time = mktime();
header('content-type:text/XML;charset=utf-8');
header("Cache-Control: max-age=200000");
$result = '';
if (is_null($query) || is_null($type) || strlen($query) == 0 || strlen($type) == 0) {
    $result = <<<EOS
<?xml version="1.0" encoding="UTF-8" ?>
<failure>Paramaters cannot be null.</failure>
EOS;
    echo $result;
} else if ($type != 'g2d' && $type != 'd2g') {
    $result = <<<EOS
<?xml version="1.0" encoding="UTF-8" ?>
<failure>searchType must be $type_array[0] or $type_array[1]</failure>
EOS;
    echo $result;
} else {
    $search_result = $search_service->batch_query($query, $type);
    $count = count($search_result);
    $result = <<<EOS
<?xml version="1.0" encoding="UTF-8" ?>
<success>
 <searchDate>$current_time</searchDate>
  <data>
EOS;
    for ($i = 0; $i < $count; $i++){
        $data = $search_result[$i];
        $result .= <<<EOS
    <searchResultBean>
      <parameters>
        <searchWord>$data[query]</searchWord>
        <searchType>$data[type]</searchType>
      </parameters>
      <annotations>
EOS;
        $detail = $data[data];
        foreach ($detail as $item) {
        $result .= <<<EOS
        <annotationBean>
          <DOID>$item[DOID]</DOID>
          <DOName>$item[name]</DOName>
          <GeneID>$item[GeneID]</GeneID>
          <GeneName>$item[Symbol]</GeneName>
          <Chromosome>$item[Chromosome]</Chromosome>
          <MapLocation>$item[MapLocation]</MapLocation>
          <GeneRIFID>$item[generifid]</GeneRIFID>
          <DOURL>http://bioportal.bioontology.org/ontologies/45125?p=terms&amp;conceptid=DOID%3A$item[DOID]</DOURL>
          <GeneURL>http://www.ncbi.nlm.nih.gov/gene/$item[GeneID]</GeneURL>
          <GeneRIFURL>http://www.ncbi.nlm.nih.gov/pubmed?term=$item[generifid]</GeneRIFURL>
        </annotationBean>
EOS;
        }
    $result .= "\r\n";
    $result .= <<<EOS
      </annotations>
    </searchResultBean>
EOS;
}
    $result .= <<<EOS
  </data>
</success>
EOS;
    echo $result;
}
