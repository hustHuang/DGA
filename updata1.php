<?php
$GET_GENE_A_ID = 'SELECT co_localization.Gene_A_Id FROM co_localization';
$GET_GENE_NAME = 'SELECT map.genename FROM map WHERE map.ENS_ID=';
$GET_GENE_ID = 'SELECT g.GeneID FROM geneinfo g WHERE g.Symbol=';
$UPDATEA = 'UPDATE co_localization SET co_localization.Gene_A_Id=';
$LIM = " WHERE co_localization.Gene_A_Id LIKE 'E%'";
$LIMA = ' WHERE co_localization.Gene_A_Id=';
$con = mysql_connect("localhost","root","NUBIC2011");
mysql_select_db("do_nv", $con);
$resulta = mysql_query($GET_GENE_A_ID.$LIM);
echo "²éÑ¯½á¹û¹²ÓÐ".mysql_num_rows($resulta)."Ìõ¼ÇÂ¼<br/>";
if (!is_null($resulta)) {
            while($row = mysql_fetch_array($resulta)) {
                $result1 = mysql_query($GET_GENE_NAME.'"'.$row[0].'"');
                if($row1 = mysql_fetch_array($result1)) { 
                $result2 = mysql_query($GET_GENE_ID.'"'.$row1[0].'"');
                }
                if (!is_null($result2)) {
                if($row2 = mysql_fetch_array($result2)) {
                mysql_query($UPDATEA.'"'.$row2[0].'"'.$LIMA.'"'.$row[0].'"');
                }
                } 
            }
            echo "ok<br/>";
}
mysql_close($con);
?>
