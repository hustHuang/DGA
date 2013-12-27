<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchAction2
 *
 * @author
 */
class GeneRelation {

    function getGeneIDByGeneName($gene_name) {
        $query = "SELECT g.GeneID FROM geneinfo g WHERE g.Symbol = '" . $gene_name . "' ";
        $dbc = DBC::get_conn();
        if (is_null($dbc))
            return -1;
        $result = mysqli_query($dbc, $query);
        if (!$result)
            return -1;
        if ($row = mysqli_fetch_row($result)) {
            return $row[0];
        }
        return -1;
    }

    function gettermnameByGeneId($geneid) {

        $query = "SELECT t.name FROM geneinfo i, term t, generif g, term_mapping m WHERE m.generif_md5 = g.md5 AND t.DOID = m.term_id AND (m.status = 0 OR m.status = 1) AND i.GeneID = g.geneid AND g.geneid='" . $geneid . "'";
        $dbc = DBC::get_conn();
        if (is_null($dbc))
            return null;

        $result = mysqli_query($dbc, $query);

        if (!$result)
            return null;

        $term_name = "";
        $term = array();
        while ($row = mysqli_fetch_row($result)) {
            $term_name = $row[0];
            //echo "term_name:".$term_name."<br \>";
            array_push($term, $term_name);
        }

        return $term;
    }

    function getGenenameByTermId($termid) {
        $query = "SELECT  g.Symbol FROM geneinfo g, generif r, term_mapping m, term t WHERE g.GeneID = r.geneid AND r.md5 = m.generif_md5 AND (m.status = 0 OR m.status = 1) AND t.DOID = m.term_id AND t.DOID ='" . $termid . "'";
        $dbc = DBC::get_conn();
        if (is_null($dbc))
            return -1;
        $result = mysqli_query($dbc, $query);

        if (!$result)
            return -2;

        $gene = array();
        while ($row = mysqli_fetch_row($result)) {

            array_push($gene, $row[0]);
        }
        return $gene;
    }

    function getGeneDataByGeneNamel($gene) {
        // $query = "SELECT g.idGENE, g.Primary_SGDID, g.Feature_Name, g.Standard_Gene_Name, g.Alias, g.Description FROM gene g WHERE g.Feature_Name = '" . $gene_name . "' OR g.Standard_Gene_Name = '" . $gene_name . "'";
        $query = "SELECT g.GeneID,g.Symbol, g.Chromosome, g.MapLocation, g.Description FROM geneinfo g WHERE g.Symbol = '" . $gene . "' ";
        $dbc = DBC::get_conn();
        if (is_null($dbc))
            return -1;
        $result = mysqli_query($dbc, $query);
        if (!$result)
            return -2;
        //echo "jieguo:".$gene;
        if ($row = mysqli_fetch_row($result)) {
            //while($row = mysqli_fetch_row($result)) {
            $gene_data = new GeneData();
            $gene_data->gene_id = addslashes($row[0]);
            //echo $gene_data->gene_id."<br \>";
            $gene_data->Symbol = addslashes($row[1]);
            //echo $gene_data->Symbol."<br \>";
            $gene_data->chrompsome = addslashes($row[2]);
            //echo $gene_data->chrompsome."<br \>";
            $gene_data->MapLocation = addslashes($row[3]);
            //echo $gene_data->MapLocation."<br \>";
            $gene_data->description = addslashes($row[4]);
            //echo $gene_data->description."<br \>";
            //$gene_data->dbXrefs = addslashes($row[5]);
            //$gene_data->dbXrefs = addslashes($row[5]);
            return $gene_data;
            //echo $gene_data->description."<br \>";
        }
        return null;
    }

    function getGeneIdListBYGeneName($gene_name) {
        $query = "select g.GeneID from geneinfo g where g.Symbol='" . $gene_name . "'";
        $strain_array = array();
        $dbc = DBC::get_conn();
        if (is_null($dbc))
            return -1;
        $result = mysqli_query($dbc, $query);
        if (!$result)
            return -1;
        /* if ($row = mysqli_fetch_row($result)) {
          return $row[0];
          } */
        while ($row = mysqli_fetch_row($result)) {
            array_push($strain_array, $row[0]);
        }
        return $strain_array;
    }

    function getGeneNameBYGeneID($GeneId) {
        $query = "select g.Symbol from geneinfo g where g.GeneID=$GeneId";

        $dbc = DBC::get_conn();
        if (is_null($dbc))
            return -1;
        $result = mysqli_query($dbc, $query);
        if (!$result)
            return -2;
        if ($row = mysqli_fetch_row($result)) {
            return $row[0];
        }
        return -3;
    }

    function getIntercorrDatechildrel($gene1, $gene2, $type) {
        $intercorr_array = array();
        $intercorr1 = $this->getGeneIDBYGeneName($gene1);
        $intercorr2 = $this->getGeneIDBYGeneName($gene2);
        if ($intercorr1 == -1 || $intercorr2 == -1)
            return null;
        $query = "SELECT adt.Weight from " . $type . " adt WHERE (adt.Gene_A_Id=$intercorr1 and adt.Gene_B_Id=$intercorr2) or(adt.Gene_A_Id=$intercorr2 and adt.Gene_B_Id=$intercorr1) ";

        $dbc = DBC::get_conn();
        if ($dbc == null) {
            return null;
        }
        $result = mysqli_query($dbc, $query);
        if (!$result) {
            return null;
        }
        // $inte2=null;
        $j = 0;
        while ($row = mysqli_fetch_row($result)) {
            //echo $row[0];

            $weight+=$row[0];
            $j++;
            //echo json_encode($intercorr_array);   	 
        }
        if ($j != 0) {
            $weight = $weight / $j;
            $inte2 = $this->getIntercorr($gene1, $gene2, $weight, $type);
//    if(!in_array($inte2, $intercorr_array))        
            array_push($intercorr_array, $inte2);
            return $intercorr_array;
        }
    }

    function getIntercorrforEdgeInfo($gene1, $gene2, $type) {
        $intercorr_array = array();
        $unique_array = array();
        $intercorr1 = $this->getGeneIDBYGeneName($gene1);
        //echo $intercorr1;
        $intercorr2 = $this->getGeneIDBYGeneName($gene2);
        //echo $intercorr2;
        //if($intercorr1==-1 || $intercorr2==-1) return null; 
        $query = "SELECT adt.Gene_A_Id,adt.Gene_B_Id,adt.Weight,nw.Network,nw.Pubmed_ID,nw.DatabaseSource from " . $type . " adt,networks nw WHERE ((adt.Gene_A_Id=$intercorr1 and adt.Gene_B_Id=$intercorr2) or (adt.Gene_A_Id=$intercorr2 and adt.Gene_B_Id=$intercorr1))and nw.id=adt.networkid ";

        $dbc = DBC::get_conn();
        if ($dbc == null) {
            return null;
        }
        $result = mysqli_query($dbc, $query);
        if (!$result) {
            return null;
        }
        while ($row = mysqli_fetch_row($result)) {
            //echo "here".$row[4];
            $interaction = $this->getIntercorrforJudge($gene1, $gene2, $type, $row[5]);
            $inte2 = $this->getIntercorrEdgeInfo($gene1, $gene2, $row[2], $type, $row[3], $row[4]);
            if (!in_array($interaction, $unique_array)) {
                array_push($intercorr_array, $inte2);
                array_push($unique_array, $interaction);
                //echo $inter2;
            }
        }
        if ($intercorr_array != null)
            return $intercorr_array;
    }

    function getIntercorrEdgeInfo($gene1, $gene2, $weight, $type, $network, $pubmedid) {

        $intercorr_data = new Relation();
        $intercorr_data->gene1_Symbol = $gene1;
        $intercorr_data->gene2_Symbol = $gene2;
        // $intercorr_data->weight = number_format($weight, 5);
        $intercorr_data->weight = $weight;
        $intercorr_data->type = $type;
        $intercorr_data->network = $network;
        $intercorr_data->pubmedid = $pubmedid;
        //echo $intercorr_data->pubmedid;
        return $intercorr_data;
    }

    function getIntercorr($gene1, $gene2, $weight, $type) {

        $intercorr_data = new Relation();
        $intercorr_data->gene1_Symbol = $gene1;
        $intercorr_data->gene2_Symbol = $gene2;
      //$intercorr_data->weight = number_format($weight, 5);
        $intercorr_data->weight = $weight;
        $intercorr_data->type = $type;
//      $intercorr_data->network=$network;
//      $intercorr_data->pubmedid=$pubmedid;
        return $intercorr_data;
    }

    function getIntercorrforJudge($gene1, $gene2, $type, $db) {

        $intercorr_data = new Relation();
        $intercorr_data->gene1_Symbol = $gene1;
        $intercorr_data->gene2_Symbol = $gene2;
        //$intercorr_data->weight = number_format($weight, 3);
        $intercorr_data->type = $type;
        $intercorr_data->network = $db;
        //$intercorr_data->pubmedid=$pubmedid;
        return $intercorr_data;
    }

}

