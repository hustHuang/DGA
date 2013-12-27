<?php
$GET_GENE_B_ID = 'SELECT co_localization.Gene_B_Id FROM co_localization';
$GET_GENE_NAME = 'SELECT map.genename FROM map WHERE map.ENS_ID=';
$GET_GENE_ID = 'SELECT g.GeneID FROM geneinfo g WHERE g.Symbol=';
$UPDATEB = 'UPDATE co_localization SET co_localization.Gene_B_Id=';
$LIM = " WHERE co_localization.Gene_B_Id LIKE 'E%'";
$LIMB = ' WHERE co_localization.Gene_B_Id=';
$con = mysql_connect("localhost","root","NUBIC2011");
mysql_select_db("do_nv", $con);
$resultb = mysql_query($GET_GENE_B_ID.$LIM);
echo "²éÑ¯½á¹û¹²ÓÐ".mysql_num_rows($resultb)."Ìõ¼ÇÂ¼<br/>";
if (!is_null($resultb)) {
            while($row = mysql_fetch_row($resultb)) {
                $result1 = mysql_query($GET_GENE_NAME.'"'.$row[0].'"');
                if($row1 = mysql_fetch_array($result1)) { 
                $result2 = mysql_query($GET_GENE_ID.'"'.$row1[0].'"');
                }
                if (!is_null($result2)) {
                if($row2 = mysql_fetch_array($result2)) {
                mysql_query($UPDATEB.'"'.$row2[0].'"'.$LIMB.'"'.$row[0].'"');
                } 
                }
            }
            echo "ok<br/>";
}
mysql_close($con);
?>
