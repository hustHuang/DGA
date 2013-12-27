<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeneData
 *
 * @author GGCoke
 */
class GeneData {
    //put your code here
    public $gene_id;
    public $sdg_id;
    public $Symbol;
    public $Chromosome;
    public $MapLocation;
    public $description;
    //private $gene_dbXrefs;
    //private $description;
    
    function __construct() {}
    
    function __get($name) {
        return $this->$name;
    }
    
    function __set($name, $value) {
        $this->$name = $value;
    }
    
    function setSGDID($value){
        $this->sdg_id = $value;
    }
    
    function getSGDID(){
        return $this->sdg_id;
    }
}

?>
