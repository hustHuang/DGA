<?php

error_reporting(E_ALL ^ E_NOTICE);
include './DB.php';
include './GeneRelation.php';
include './GeneData.php';
include './Relation.php';
include './Search.class.php';
class searcha{
private $view;
private $type;
private $query_type;
private $query_name;
private $table_list_data = '';
private $cw_node_data = '[';
private $cw_edge_data = '[';
private $result_count = 0;
function __construct() {
        
    }
function set_search_type($view, $type) {
        $this->view = $view;
        $this->type = $type;
}
function remove_same_name($name_array){
        $result_array = array();
        foreach ($name_array as $name){
            $name = trim($name);
            if ($name == '' || $name == ' ')
                continue;
            if (!in_array($name, $result_array)){
                array_push($result_array, $name);
            } else {
                continue;
            }
        }
        return $result_array;
    }
function execute_search() {
        $relat=new Search();
        $relation = new GeneRelation();
        
        if ($this->query_type == 'd2g') {
            $this->query_name = $relat->get_autocomplete_disease($q);
        }
        
             //$keys = explode(STRING_SEPARATOR, $this->query_name);
             $keys = array_filter(explode(STRING_SEPARATOR, $this->query_name));
            $keys= $this->remove_same_name($keys);
        $query_feature_names = array();
        foreach ($mix_name_array as $name) {
            
            $feature_name = $this->get_feature_name($name);
            if (!in_array($feature_name, $query_feature_names)) {
                array_push($query_feature_names, $feature_name);
            }
        }
        
       
        $query_result_array = array();
        $relation = new GeneRelation();
        
        switch ($this->view) {
            case 'tv':
                

                if ($this->query_type == 'd2g') {
                    foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                         $tmp_result_array = $relation->getIntercorrDate($gene, $this->type);
                        
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);
                    }
                } 
                 else {
                    // Do nothing
                }
                $this->get_table_result_from_query($query_result_array, $this->type, $query_feature_names);
                break;
            case 'nv':
                if ($this->query_type == 'd2g') {
                    foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = array();

                       
                            $tmp_result_array = $relation->getIntercorrDate($gene, $this->type);
                            $query_result_array = array_merge($query_result_array, $tmp_result_array);
                        }
                    
                    $this->get_network_result_from_query($query_result_array, $this->type, $query_feature_names);
                
                } else {
                    // Do nothing
                }
                break;
            default:
                break;
        }
    }
    
/*function execute_search() {
        $relat=new Search();
        $relation = new GeneRelation();
        
        if ($this->query_type == 'd2g') {
            $this->query_name = $relat->get_autocomplete_disease($q);
        }

        // Replace all separate symbols to space and remove the spaces in beganing and end.
        // $this->query_names = $this->init_gene_names($this->query_names);
        // $this->query_names = trim(str_replace('.', ' ', $this->query_names));
        //$mix_name_array = array_filter(explode('|', $this->query_name));
        //$mix_name_array = $this->remove_same_name($mix_name_array);
        $keys = explode(STRING_SEPARATOR, $this->query_name);

        // Remove some search word and blank key word.
        $keys = $relat->format_keys($keys);
        
           $id_array = array();
        if ($type == 'd2g') {
            $detail_array = array();
            foreach ($keys as $term_name) {
                $term_id = $relat->get_term_id_by_name($term_name);
                if (is_null($term_id) || $term_id == '')
                    continue;
                array_push($id_array, $term_id);
            }

            $total_count = $relat->get_mapped_gene_count($id_array);
            $detail_array = $relation->getGeneInfoByTermId($term_id); 
            $mix_name_array= $detail_array->Symbol;
            $mix_name_array = $this->remove_same_name($mix_name_array);    
            $query_result_array = array();
        //$relation1 = new GeneRelation();
        switch ($this->view) {
            case 'tv':
                switch($this->type){
            case 'coexp':
                foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'coloc':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'genet':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'pi':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'path':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'pre':    
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'spd':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            default:break;
            }
        $this->get_table_result_from_query($query_result_array, $this->type, $query_Gene_names);
                break;
            case 'nv':
                
                foreach ($mix_name_array as $gene) {
                   if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                    $tmp_result_array = array();
                     $tmp_result_array = $relation->getIntercorrDate($gene, $this->type);
                     $query_result_array = array_merge($query_result_array, $tmp_result_array);
                        
                    
                    $this->get_network_result_from_query($query_result_array, $this->type, $query_Gene_names);
                } 
                    

                   
                break;
            default:
                break;
        }
    }
    */
function get_table_result_from_query($query_result_array, $type, $query_Gene_names) {
        $result_array = array();
        $query_nodes = array();

        foreach ($query_result_array as $relation) {
            $tmp_array = array();
            $tmp_array['g1GeneName'] = $relation->gene1_Gene_name;
            //$tmp_array['g1Name'] = $relation->gene1_standard_gene_name;
            $tmp_array['g1symnonyms'] = $relation->gene1_symnonyms;
            $tmp_array['g1Desc'] = $relation->gene1_description;
            $tmp_array['g2Feature'] = $relation->gene2_Gene_name;
            //$tmp_array['g2Name'] = $relation->gene2_standard_gene_name;
            $tmp_array['g2symnonyms'] = $relation->gene2_symnonyms;
            $tmp_array['g2Desc'] = $relation->gene2_description;
            //$tmp_array['s1Strain'] = $relation->strain1_name;
            //$tmp_array['s2Strain'] = $relation->strain2_name;
            $tmp_array['weight'] = $relation->weight;
            //if ($type == 'n' || $type == 'p') {
               // $tmp_array['pValue'] = $relation->p_value;
            //}

            array_push($result_array, $tmp_array);
            $node1 =  $relation->gene1_Gene_name ;
            $node2 = $relation->gene2_Gene_name ;

            if (!in_array($relation->gene1_Gene_name, $query_Gene_names)) {
                if (key_exists($node1, $query_nodes)) {
                    $query_nodes[$node1]++;
                } else {
                    $query_nodes[$node1] = 1;
                }
            }
            if (!in_array($relation->gene2_Gene_name, $query_Gene_names)) {
                if (key_exists($node2, $query_nodes)) {
                    $query_nodes[$node2]++;
                } else {
                    $query_nodes[$node2] = 1;
                }
            }
        }

        $this->table_list_data = json_encode($result_array);
        arsort($query_nodes);
        foreach ($query_nodes as $key => $value) {
            $this->tree_result_data .= ('{"data":{"title":"' . $key . '","attr":{"id":"' . $key . '","href":"javascript:void(0)", "class":"treenode"}}},');
        }

        if (count($query_nodes) != 0) {
            $this->tree_result_data = substr($this->tree_result_data, 0, strlen($this->tree_result_data) - 1);
        }
        $this->tree_result_data .= '],"state":"open"}]';
        $this->result_count = count($query_result_array);
    }
function get_network_result_from_query($query_result_array, $type, $query_Gene_names) {
        $query_relation_nodes = array();
        $result_relation_nodes = array();
        $all_relation_edge_array = array();

        $this->gene_name_id_map = $this->get_gene_id_map();
        $this->Gene_name_map = $this->get_Gene_name_map();
        foreach ($query_result_array as $relation) {
            $Gene_name1 = $relation->gene1_Gene_name;
            $Gene_name2 = $relation->gene2_Gene_name;
            $weight = '';
            if ($type == 'i') {
                $weight = $relation->weight . '_' . $relation->p_value;
            } else {
                $weight = $relation->weight . '_0';
            }
            $node1 = $relation->gene1_Gene_name ;
            $node2 = $relation->gene2_Gene_name ;
            $id = ($node1 . '_' . $node2);
            $all_relation_edge_array[$id] = $weight;
            if (in_array($Gene_name1, $query_Gene_names)) {
                if (key_exists($node1, $query_relation_nodes)) {
                    $query_relation_nodes[$node1]++;
                } else {
                    $query_relation_nodes[$node1] = 1;
                }
            } else {
                if (key_exists($node1, $result_relation_nodes)) {
                    $result_relation_nodes[$node1]++;
                } else {
                    $result_relation_nodes[$node1] = 1;
                }
            }

            if (in_array($Gene_name2, $query_Gene_names)) {
                if (key_exists($node2, $query_relation_nodes)) {
                    $query_relation_nodes[$node2]++;
                } else {
                    $query_relation_nodes[$node2] = 1;
                }
            } else {
                if (key_exists($node2, $result_relation_nodes)) {
                    $result_relation_nodes[$node2]++;
                } else {
                    $result_relation_nodes[$node2] = 1;
                }
            }
        }

        $cutoff_node_array = array();
        arsort($query_relation_nodes);
        foreach ($query_relation_nodes as $key => $value) {
            $cutoff_node_array[$key] = $value;
            $this->cw_node_data .= ('{id:"' . $key . '", count:' . $value . ',ngc:"q"},');
        }

        foreach ($query_Gene_names as $Gene_name) {
            $tmp_name = array_search($Gene_name, $this->Gene_name_map);
            if (!key_exists($tmp_name, $query_relation_nodes)) {
                $cutoff_node_array[$tmp_name] = 1;
                $this->cw_node_data .= ('{id:"' . $tmp_name . '", count:1,ngc:"q"},');
            }
        }

        arsort($result_relation_nodes);
        $tmp_count = 0;
        $result_node_array = array();
        foreach ($result_relation_nodes as $key => $value) {
            $tmp_count++;
            if ($tmp_count > 20)
                break;
            $cutoff_node_array[$key] = $value;
            $result_node_array[$key] = $value;
            $this->cw_node_data .= ('{id:"' . $key . '", count:' . $value . ',ngc:"r"},');
            $this->tree_result_data .= ('{"data":{"title":"' . $key . '","attr":{"id":"' . $key . '","href":"javascript:void(0)", "class":"treenode"}}},');
        }

        $edges_exist = array();
        foreach ($all_relation_edge_array as $key => $value) {
            $node_ids = explode('_', $key);
            $values = explode('_', $value);
            $tmp_key = $node_ids[1] . '_' . $node_ids[0];
            if (in_array($key, $edges_exist) || in_array($tmp_key, $edges_exist)) {
                continue;
            }
            array_push($edges_exist, $key);
            // $edge_type = $this->type == 'i' ? "ai" : "c";
            $edge_type = $this->type == 'i' ? ($values[0] > 0 ? "pai" : "nai") : "c";
            if ((array_key_exists($node_ids[0], $cutoff_node_array)) && (array_key_exists($node_ids[1], $cutoff_node_array))) {
                $this->cw_edge_data .= ('{id:"' . ($key . "_" . $edge_type) . '", distance:' . $values[0] . ',pvalue:' . $values[1] . ',egc:"' . $edge_type . '",target:"' . $node_ids[1] . '",source:"' . $node_ids[0] . '"},');
            }
        }

        if ($this->query_type == 'with') {
            $this->get_child_relation($result_node_array, "ai");
        }

        if ($this->type == 'i') {
            $this->get_child_relation($cutoff_node_array, "coexp");
            $this->get_child_relation($cutoff_node_array, "coloc");
            $this->get_child_relation($cutoff_node_array, "pi");
            $this->get_child_relation($cutoff_node_array, "spd");
        }

        if (count($query_result_array) != 0) {
            $this->cw_node_data = substr($this->cw_node_data, 0, strlen($this->cw_node_data)-1);
            $this->cw_edge_data = substr($this->cw_edge_data, 0, strlen($this->cw_edge_data)-1);
        }
        if (strrpos($this->tree_result_data, '[') != (strlen($this->tree_result_data)-1)){
            $this->tree_result_data = substr($this->tree_result_data, 0, strlen($this->tree_result_data)-1);
        }    
        $this->cw_node_data .= ']';
        $this->cw_edge_data .= ']';
        $this->tree_result_data .= '],"state":"open"}]';
    }
      function get_Gene_name($name) {
        $query = 'SELECT g.Symbol FROM geneinfo g WHERE g.Symbol = "' . $name . '" ';
        $dbc = DB::get_conn();
        if (is_null($dbc)) {
            return "";
        }

        $result = mysqli_query($dbc, $query);
        if (!$result)
            return "";
        if ($row = mysqli_fetch_row($result)) {
            return $row[0];
        }

        return "";
    }


       function get_view_table_data() {
        return $this->table_list_data;
    }
    function get_cw_node_data() {
        return $this->cw_node_data;
    }

       function get_cw_edge_data() {
        return $this->cw_edge_data;
    }

        function get_result_count() {
        return $this->result_count;
    }
}
/*switch($this->$type){
            
                case 'coexp':
                
                foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'coloc':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'genet':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'pi':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'path':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'pre':    
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            case 'spd':
            foreach ($mix_name_array as $gene) {
                        if ($gene == ' ' || is_null($gene) || strlen($gene) == 0) continue;
                        $tmp_result_array = $relation->getIntercorrDate($gene, $this->$type);
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);}
                break;
            default:break;
            }*/