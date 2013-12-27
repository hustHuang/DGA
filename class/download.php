<?php

//require_once '../common.php';
//require_once (ABSPATH . 'class/Search.class.php');
class download {

    public $file;
    public $keywords;
    public $stype;
    public $rtype;

    function getfilename($file, $search_type, $search_word, $type) {
        $this->file = $file;
        $this->keywords = $search_word;
        $this->stype = $search_type;
        $this->rtype = $type;
    }

    function writedatatotxt() {
        $search_service = new Search();
        $current_page = 1;
        $count = 10000;
//$search_type = 'd2g';
//$search_word = 'primary breast cancer';
        if ($this->keywords == '' || is_null($this->keywords)) {
            echo "No key word are input.";
            exit();
        }
//$keywords = $search_word;
//$type = $search_type;
        $sortname = 'symbol';
        $sortorder = 'asc';
//echo $sortorder;
        $search_result = $search_service->execut_search_getdatatxt($this->keywords, $this->stype, $current_page, $count, $sortname, $sortorder);
        $data = json_encode($search_result);

        $str1 = "NO." . "\t" . "Disease Name" . "\t" . "Gene Symbol" . "\t" . "Chromsome" . "\t" . "MapLocation" . "\t" . "PubMed ID" . "\t" . "GeneRIF Text" . "\r\n";
        $str2 = "NO." . "\t" . "Gene Symbol" . "\t" . "Disease Name" . "\t" . "\t" . "DoID" . "\t" . "PubMed ID" . "\t" . "GeneRIF Text" . "\r\n";
//echo $this->file1;
        @$fp = fopen($this->file, 'ab');
        flock($fp, LOCK_EX);
        if (!$fp) {
            echo "error";
            exit;
        }
        if ($type == 'd2g') {
            fwrite($fp, $str1, strlen($str1));
            fwrite($fp, $data, strlen($data));
        } else {
            fwrite($fp, $str2, strlen($str2));
            fwrite($fp, $data, strlen($data));
        }
        flock($fp, LOCK_UN);
        $str = file_get_contents($this->file);
        $str = str_replace("\"", "\t", $str);
        $str = str_replace(",", "", $str);
        $str = str_replace("[", "", $str);
        $str = str_replace("]", "\r\n", $str);
        $str = stripcslashes($str);


        file_put_contents($this->file, $str);

        fclose($fp);
    }

    function download_gene_relations() {

        $relation = new searchgene();
        if ($this->keywords == '' || is_null($this->keywords)) {
            echo "No key word are input.";
            exit();
        }
        $relation->set_search_params($this->keywords, $this->stype);

        if ($this->rtype == 'show all') {
//    	echo "I  am here";
            $search_rel = $relation->execute_search_t_download();
            $data = json_encode($search_rel);
        } else {
            $relation->set_search_genereltype($this->rtype);
            $search_rel = $relation->execute_search_t_other_download();
            $data = json_encode($search_rel);
        }
        $str1 = "Gene1_name" . "\t" . "Gene2_name" . "\t" . "Network" . "\t" . "Type" . "\t" . "Weight" . "\r\n";
//$str2="NO."."\t"."Gene Symbol"."\t"."Disease Name"."\t"."\t"."DoID"."\t"."PubMed ID"."\t"."GeneRIF Text"."\r\n";
//echo $this->file1;
        @$fp = fopen($this->file, 'ab');
        flock($fp, LOCK_EX);
        if (!$fp) {
            echo "error";
            exit;
        }

        fwrite($fp, $str1, strlen($str1));
        fwrite($fp, $data, strlen($data));

        flock($fp, LOCK_UN);

        $str = file_get_contents($this->file);
        $str = str_replace("!", "\r\n", $str);
        $str = str_replace("\",\"", "", $str);
        $str = str_replace("|", "\t", $str);
//
        $str = str_replace("[\"", "", $str);
        $str = str_replace("\"]", "", $str);
        $str = stripcslashes($str);
//$str=str_replace("{","",$str);
//$str=str_replace("}","\r\n",$str);



        file_put_contents($this->file, $str);

        fclose($fp);
    }

}
