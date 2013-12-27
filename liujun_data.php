<?php
require_once ('add/DBC.php');
$GET_GENE_A_ID = 'SELECT p.Gene_A_Id FROM predicted p';
$GET_GENE_B_ID = 'SELECT p.Gene_B_Id FROM predicted p';
$GET_GENE_NAME = 'SELECT map.genename FROM map WHERE map.ENS_ID=';
$GET_GENEID = 'SELECT g.GeneID FROM geneinfo g WHERE g.Symbol=';
$UPDATEA = 'UPDATE predicted SET predicted.Gene_A_Id=';
$UPDATEB = 'UPDATE predicted SET predicted.Gene_B_Id=';
$LIMA = ' WHERE predicted.Gene_A_Id=';
$LIMB = ' WHERE predicted.Gene_B_Id=';
$dbc = DBC::get_conn();
/*
$resulta = mysqli_query($dbc,$GET_GENE_A_ID);
if (!is_null($resulta)) {
            while($row = mysqli_fetch_array($resulta)) {
            	if(ereg('^E', $row[0])) {
                $result1 = mysqli_query($dbc,$GET_GENE_NAME.'"'.$row[0].'"');
                if($row1 = mysqli_fetch_array($result1)) { 
                $result2 = mysqli_query($dbc,$GET_GENEID.'"'.$row1[0].'"');
                }
                while($row2 = mysqli_fetch_array($result2)) {
                mysqli_query($dbc,$UPDATEA.'"'.$row2[0].'"'.$LIMA.'"'.$row[0].'"');
                } 
            	}
            }
            echo "ok<br/>";
}
*/

$resultb = mysqli_query($dbc,$GET_GENE_B_ID);
if (!is_null($resultb)) {
            while($row = mysqli_fetch_row($resultb)) {
            	if(ereg('^E', $row[0])) {
                $result1 = mysqli_query($dbc,$GET_GENE_NAME.'"'.$row[0].'"');
                if($row1 = mysqli_fetch_array($result1)) { 
                $result2 = mysqli_query($dbc,$GET_GENEID.'"'.$row1[0].'"');
                }
                while($row2 = mysqli_fetch_array($result2)) {
                mysqli_query($dbc,$UPDATEB.'"'.$row2[0].'"'.$LIMB.'"'.$row[0].'"');
                } 
            	}
            }
            echo "ok<br/>";
}

