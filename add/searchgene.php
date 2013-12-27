<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once ABSPATH . 'add/DBC.php';
require_once ABSPATH . 'add/GeneRelation.php';
require_once ABSPATH . 'add/GeneData.php';
require_once ABSPATH . 'add/Relation.php';
require_once ABSPATH . 'class/Search.class.php';

class searchgene {

    private $view;
    private $type;
    private $query_type;
    //private $query_name;
    //private $disease_name_map;
    private $table_list_data = '';
    private $cw_node_data = '[';
    private $cw_edge_data = '[';
    private $result_count = 0;
    private $m = 0;
    private $n = 0;

    //private $test='co_expression';
    function __construct() {
        
    }

    function set_search_type($view) {
        $this->view = $view;
    }

    function set_search_genereltype($type) {
        $this->type = $type;
    }

    function set_m($m) {
        $this->m = $m;
    }

    function set_n($n) {
        $this->n = $n;
    }

    function set_search_params($query_names, $query_type) {
        $this->query_names = $query_names;
        $this->query_type = $query_type;
    }

    function remove_same_name($name_array) {
        $result_array = array();
        foreach ($name_array as $name) {
            $name = trim($name);
            if ($name == '' || $name == ' ')
                continue;
            if (!in_array($name, $result_array)) {
                array_push($result_array, $name);
            } else {
                continue;
            }
        }
        return $result_array;
    }

    function execute_search($nodecount) {
        $relat = new Search();
        $relation = new GeneRelation();
        $keys = array_filter(explode('|', $this->query_names));
        $keys = $this->remove_same_name($keys);
        $query_feature_names = array();
        $node_notp = array();
        foreach ($keys as $name) {

            if (!in_array($name, $query_feature_names)) {
                array_push($query_feature_names, $name);
            }
        }
        $id_array = array();
        $gene_array = array();
        $mix_name_array = array();
        $network_node_array = array();
        foreach ($keys as $term_name) {
            $relation = new GeneRelation();
            $term_id = $relat->get_term_id_by_name($term_name);
            if (is_null($term_id) || $term_id == '')
                continue;
            if (in_array($term_id, $id_array)) {
                array_push($id_array, $term_id);
            }
            $gene_array = $relation->getGenenameByTermId($term_id);


            foreach ($gene_array as $name) {
                if (!in_array($name, $mix_name_array)) {
                    array_push($mix_name_array, $name);
                }
            }
            $result_nodes = array();
            $counter = 0;
            foreach ($gene_array as $gene_name) {
                if (key_exists($gene_name, $result_nodes)) {
                    $result_nodes[$gene_name]++;
                } else {
                    $result_nodes[$gene_name] = 1;
                }
            }
            arsort($result_nodes);
            //echo json_encode($result_nodes);     
            $node_array = array();

            $tmp_count = 0;
            foreach ($result_nodes as $key => $value) {
                $tmp_count++;
                if ($tmp_count > $nodecount)
                    break;
                if (!in_array($key, $network_node_array)) {
                    array_push($network_node_array, $key);
                }

                $counter = $counter + $value;

                $node_g = $key;
                $node_d = $term_name;
                $gene_id = $relat->get_gene_id_by_name($key);
                $term_id = $relat->get_term_id_by_name($term_name);
                $result = $relat->get_text_gene_info($gene_id, $term_id);
                $count1023 = $result['table_list_count'] / 10;
                $count1023 = (double) $count1023;
                //echo $count1023;

                $id_d2g = ($node_d . '-' . $node_g);
                $this->cw_edge_data .= ('{id:"' . $id_d2g . '", distance: ' . $count1023 . ',egc: "d2g ",target:"' . $node_d . '",source:"' . $node_g . '"},');
                if (in_array($key, $node_notp)) {
                    
                } else {
                    array_push($node_notp, $key);
                    $this->cw_node_data .= ('{id:"' . $key . '", count:' . $value . ',ngc:"r"},');
                }
            }
            $this->cw_node_data .= ('{id:"' . $term_name . '", count:' . $counter . ',ngc:"q"},');
        }
        /*         * ******************************************* */
        $i1 = 0;
        $i2 = 0;
        foreach ($keys as $term_name) {
            if ($term_name == "multiple myeloma") {
                $i1 = $i1 + 1;
                $this->cw_node_data .= ('{id:"PSMB5", count:20,ngc:"r"},');
                $this->cw_edge_data .= ('{id:"multiple myeloma-PSMB5", distance:0 ,egc: "d2g ",target:"multiple myeloma",source:"PSMB5"},');
                array_push($network_node_array, "PSMB5");
            }
            //echo $term_name;
            if ($term_name == "Alzheimer's disease") {
                if (!in_array("HSP90AA1", $network_node_array)) {

                    $this->cw_node_data .= ('{id:"HSP90AA1", count:20,ngc:"r"},');
                    $this->cw_edge_data .= ('{id:"Alzheimer\'s disease-HSP90AA1", distance:0 ,egc: "d2g ",target:"Alzheimer\'s disease",source:"HSP90AA1"},');
                    array_push($network_node_array, "HSP90AA1");
                } else {
                    //$this->cw_node_data .= ('{id:"HSP90AA1", count:120,ngc:"r"},');
                    $this->cw_edge_data .= ('{id:"Alzheimer\'s disease-HSP90AA1", distance:0 ,egc: "d2g ",target:"Alzheimer\'s disease",source:"HSP90AA1"},');
                    //array_push($network_node_array, "HSP90AA1");
                }
            }
        }
        /*         * **************************************** */

        $result_relation_nodes = array();
        foreach ($network_node_array as $gene1) {

            if ($gene1 == ' ' || is_null($gene1) || strlen($gene1) == 0)
                continue;
            foreach ($network_node_array as $gene2) {

                if ($gene2 == ' ' || is_null($gene2) || strlen($gene2) == 0)
                    continue;
                if ($gene1 == $gene2) {
                    continue;
                }
                $node1 = $gene1;
                $node2 = $gene2;
                $id = ($node1 . '-' . $node2);

                if (key_exists($node1, $result_relation_nodes)) {
                    $result_relation_nodes[$node1]++;
                } else {
                    $result_relation_nodes[$node1] = 1;
                }

                if (key_exists($node2, $result_relation_nodes)) {
                    $result_relation_nodes[$node2]++;
                } else {
                    $result_relation_nodes[$node2] = 1;
                }
            }
        }

        $cutoff_node_array = array();
        arsort($result_relation_nodes);
        foreach ($result_relation_nodes as $key1 => $value) {
            $cutoff_node_array[$key1] = $value;
        }

        $this->get_child_relation($cutoff_node_array, "co_expression");
        $this->get_child_relation($cutoff_node_array, "co_localization");
        $this->get_child_relation($cutoff_node_array, "physical_interactions");
        $this->get_child_relation($cutoff_node_array, "shared_protein_domains");
        $this->get_child_relation($cutoff_node_array, "genetic_interactions");
//            $this->get_child_relation($cutoff_node_array, "pathway");
//            $this->get_child_relation($cutoff_node_array, "predicted");

        if (count($result_relation_nodes) != 0) {
            $this->cw_node_data = substr($this->cw_node_data, 0, strlen($this->cw_node_data) - 1);
            $this->cw_edge_data = substr($this->cw_edge_data, 0, strlen($this->cw_edge_data) - 1);
        }

        $this->cw_node_data .= ']';
        $this->cw_edge_data .= ']';
    }

    function execute_searchg2d($gcount) {
        $relat = new Search();

        $keys = array_filter(explode('|', $this->query_names));
        $keys = $this->remove_same_name($keys);
        $query_feature_names = array();

        foreach ($keys as $name) {

            if (!in_array($name, $query_feature_names)) {
                array_push($query_feature_names, $name);
            }
        }
        $id_array = array();
        $term_array = array();
       // $term_array2 = array();
       // $term_array3 = array();
       // $term_array4 = array();
       // $term_c = array();
        $node_notp = array();
        foreach ($keys as $gene_name) {
            $relation = new GeneRelation();
            $gene_id = $relat->get_gene_id_by_name($gene_name);
            if (is_null($gene_id) || $gene_id == '')
                continue;
            if (!in_array($gene_id, $id_array)) {
                array_push($id_array, $gene_id);
            }

            $term_array = $relation->gettermnameByGeneId($gene_id);
            $result_relation_nodes = array();
            foreach ($term_array as $termName) {
                $node = $termName;
                if (key_exists($node, $result_relation_nodes)) {
                    $result_relation_nodes[$node]++;
                } else {
                    $result_relation_nodes[$node] = 1;
                }
            }

            arsort($result_relation_nodes);
            $tmp_count = 0;
            $result_node_array = array();
            /*             * *************************************** */
            foreach ($result_relation_nodes as $key => $value) {
                if (in_array($key, $node_notp)) {
                    $this->cw_edge_data .= ('{id:"' . $gene_name . '-' . $key . '", distance: 0 ,egc: "g2d ",target:"' . $gene_name . '",source:"' . $key . '"},');
                }
            }

            /*             * *************************************** */
            foreach ($result_relation_nodes as $key => $value) {
                $tmp_count++;
                if ($tmp_count > $gcount)
                    break;
                if (in_array($key, $node_notp)) {
                    
                } else {
                    array_push($node_notp, $key);
                    $this->cw_node_data .= ('{id:"' . $key . '", count:' . $value . ',ngc:"r"},');

//            $this->cw_node_data .= ('{id:"' . $key . '", count:' . $value . ',ngc:"r"},');        
                    $node_d = $key;
                    $node_g = $gene_name;
                    $id_g2d = ($node_g . '-' . $node_d);
                    $this->cw_edge_data .= ('{id:"' . $id_g2d . '", distance: 0 ,egc: "g2d ",target:"' . $node_g . '",source:"' . $node_d . '"},');
                }
            }
        }

        $cutoff_node_array = array();
        $query_relation_nodes = array();
        foreach ($query_feature_names as $gene_rel) {
            if (in_array($gene_rel, $query_relation_nodes)) {
                $cutoff_node_array[$gene_rel]++;
            } else {
                $cutoff_node_array[$gene_rel] = 1;
            }
        }
        foreach ($query_feature_names as $feature_name) {

            if (!key_exists($feature_name, $query_relation_nodes)) {
                $cutoff_node_array[$feature_name] = 1;
                $this->cw_node_data .= ('{id:"' . $feature_name . '", count:300,ngc:"q"},');
            }
        }

        $this->get_child_relation($cutoff_node_array, "co_expression");
        $this->get_child_relation($cutoff_node_array, "co_localization");
        $this->get_child_relation($cutoff_node_array, "physical_interactions");
        $this->get_child_relation($cutoff_node_array, "shared_protein_domains");
        $this->get_child_relation($cutoff_node_array, "genetic_interactions");
//            $this->get_child_relation($cutoff_node_array, "pathway");
//            $this->get_child_relation($cutoff_node_array, "predicted");

        $this->cw_node_data .= ']';
        $this->cw_edge_data .= ']';
    }

    function execute_search_t() {
        $relat = new Search();
        $relation = new GeneRelation();
        $keys = array_filter(explode('|', $this->query_names));
        $keys = $this->remove_same_name($keys);
        $query_feature_names = array();
        foreach ($keys as $name) {
            if (!in_array($name, $query_feature_names)) {
                array_push($query_feature_names, $name);
            }
        }
        $id_array = array();
        $gene_array = array();
        $mix_name_array = array();
        foreach ($keys as $term_name) {
            $relation = new GeneRelation();
            $term_id = $relat->get_term_id_by_name($term_name);
            if (is_null($term_id) || $term_id == '')
                continue;
            if (in_array($term_id, $id_array)) {
                array_push($id_array, $term_id);
            }
            $gene_array = $relation->getGenenameByTermId($term_id);


            foreach ($gene_array as $name) {
                if (!in_array($name, $mix_name_array)) {
                    array_push($mix_name_array, $name);
                }
            }
        }
        $tmp_result = array();
        $query_result_array = array();
        foreach ($mix_name_array as $gene1) {

            if ($gene1 == ' ' || is_null($gene1) || strlen($gene1) == 0)
                continue;
            foreach ($mix_name_array as $gene2) {

                if ($gene2 == ' ' || is_null($gene2) || strlen($gene2) == 0)
                    continue;
                if ($gene1 == $gene2) {
                    continue;
                }
                $tmp_result_array1 = $relation->getIntercorrforEdgeInfo($gene1, $gene2, 'co_expression');
                if (is_null($tmp_result_array1)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array1);
                }

                $tmp_result_array2 = $relation->getIntercorrforEdgeInfo($gene1, $gene2, 'co_localization');
                if (is_null($tmp_result_array2)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array2);
                }
                $tmp_result_array3 = $relation->getIntercorrforEdgeInfo($gene1, $gene2, 'genetic_interactions');
                if (is_null($tmp_result_array3)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array3);
                }
                $tmp_result_array4 = $relation->getIntercorrforEdgeInfo($gene1, $gene2, 'physical_interactions');
                if (is_null($tmp_result_array4)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array4);
                }

                $tmp_result_array7 = $relation->getIntercorrforEdgeInfo($gene1, $gene2, 'shared_protein_domains');
                if (is_null($tmp_result_array7)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array7);
                }
            }
        }


        if (is_null($tmp_result)) {
            
        } else {

            $query_result_array = array_merge($query_result_array, $tmp_result);
        }
        $result_t_array = array();
        foreach ($query_result_array as $relation) {
            $tmp_array = array();
            $tmp_array['g1Symbol'] = $relation->gene1_Symbol;
            $tmp_array['g2Symbol'] = $relation->gene2_Symbol;
            //$tmp_array['weight'] = $relation->weight;
            $tmp_array['type'] = $relation->type;
            $tmp_array['network'] = $relation->network;
            $tmp_array['pubmedid'] = $relation->pubmedid;

            array_push($result_t_array, $tmp_array);
            $this->table_list_data = $result_t_array;
            $this->result_count = count($query_result_array);
        }
    }

    function execute_search_t_download() {
        $relat = new Search();
        $relation = new GeneRelation();
        $keys = array_filter(explode('|', $this->query_names));
        $keys = $this->remove_same_name($keys);
        $query_feature_names = array();
        foreach ($keys as $name) {

            if (!in_array($name, $query_feature_names)) {
                array_push($query_feature_names, $name);
            }
        }
        $id_array = array();
        $gene_array = array();
        $mix_name_array = array();
        foreach ($keys as $term_name) {
            $relation = new GeneRelation();
            $term_id = $relat->get_term_id_by_name($term_name);
            if (is_null($term_id) || $term_id == '')
                continue;
            if (in_array($term_id, $id_array)) {
                array_push($id_array, $term_id);
            }
            $gene_array = $relation->getGenenameByTermId($term_id);


            foreach ($gene_array as $name) {
                if (!in_array($name, $mix_name_array)) {
                    array_push($mix_name_array, $name);
                }
            }
        }
        $tmp_result = array();
        $query_result_array = array();
        foreach ($mix_name_array as $gene1) {

            if ($gene1 == ' ' || is_null($gene1) || strlen($gene1) == 0)
                continue;
            foreach ($mix_name_array as $gene2) {

                if ($gene2 == ' ' || is_null($gene2) || strlen($gene2) == 0)
                    continue;
                if ($gene1 == $gene2) {
                    continue;
                }
                $tmp_result_array1 = $relation->getIntercorrDatechildrel($gene1, $gene2, 'co_expression');
                if (is_null($tmp_result_array1)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array1);
                }

                $tmp_result_array2 = $relation->getIntercorrDatechildrel($gene1, $gene2, 'co_localization');
                if (is_null($tmp_result_array2)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array2);
                }
                $tmp_result_array3 = $relation->getIntercorrDatechildrel($gene1, $gene2, 'genetic_interactions');
                if (is_null($tmp_result_array3)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array3);
                }
                $tmp_result_array4 = $relation->getIntercorrDatechildrel($gene1, $gene2, 'physical_interactions');
                if (is_null($tmp_result_array4)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array4);
                }

                $tmp_result_array7 = $relation->getIntercorrDatechildrel($gene1, $gene2, 'shared_protein_domains');
                if (is_null($tmp_result_array7)) {
                    
                } else {

                    $tmp_result = array_merge($tmp_result, $tmp_result_array7);
                }
            }
        }


        if (is_null($tmp_result)) {
            
        } else {

            $query_result_array = array_merge($query_result_array, $tmp_result);
        }
        $result_t_array = array();
        foreach ($query_result_array as $relation) {
            $tmp_array = array();
            $tmp_array['g1Symbol'] = $relation->gene1_Symbol;
            $tmp_array['g2Symbol'] = $relation->gene2_Symbol;
            $tmp_array['weight'] = $relation->weight;
            $tmp_array['type'] = $relation->type;
            $tmp_array['network'] = $relation->network;
            $tmp_array['pubmedid'] = $relation->pubmedid;
            $tmp_array['flag'] = '!';

            array_push($result_t_array, $tmp_array['g1Symbol']);
            array_push($result_t_array, $tmp_array['g2Symbol']);
            array_push($result_t_array, $tmp_array['type']);
            array_push($result_t_array, $tmp_array['network']);
            array_push($result_t_array, $tmp_array['pubmedid']);
            array_push($result_t_array, $tmp_array['flag']);
        }

        return $result_t_array;
    }

    function execute_search_t_other() {
        $relat = new Search();
        $relation = new GeneRelation();
        $keys = array_filter(explode('|', $this->query_names));
        $keys = $this->remove_same_name($keys);
        $query_feature_names = array();
        foreach ($keys as $name) {

            if (!in_array($name, $query_feature_names)) {
                array_push($query_feature_names, $name);
            }
        }
        $id_array = array();
        $gene_array = array();
        $mix_name_array = array();
        foreach ($keys as $term_name) {
            //$relation = new GeneRelation();	
            $term_id = $relat->get_term_id_by_name($term_name);
            if (is_null($term_id) || $term_id == '')
                continue;
            if (in_array($term_id, $id_array)) {
                array_push($id_array, $term_id);
            }
            $gene_array = $relation->getGenenameByTermId($term_id);


            foreach ($gene_array as $name) {
                if (!in_array($name, $mix_name_array)) {
                    array_push($mix_name_array, $name);
                }
            }
        }
        $result_t_array = array();
        $query_result_array = array();
        $limit = 0;
        $count = count($mix_name_array);
        /* if($this->test!=$this->type)
          {
          $this->test=$this->type;
          $this->m=0;
          $this->n=0;
          } */
        for ($i = $this->m; $i < $count; $i++) {
            //foreach ($mix_name_array as $gene1 ) {

            if ($mix_name_array[$i] == ' ' || is_null($mix_name_array[$i]) || strlen($mix_name_array[$i]) == 0)
                continue;
            //foreach ($mix_name_array as $gene2 ) {
            for ($j = $this->n; $j < $count; $j++) {
                if ($j >= $i + 1) {
                    if ($mix_name_array[$j] == ' ' || is_null($mix_name_array[$j]) || strlen($mix_name_array[$j]) == 0)
                        continue;
                    if ($mix_name_array[$i] == $mix_name_array[$j]) {
                        continue;
                    }
                    $tmp_result_array = $relation->getIntercorrforEdgeInfo($mix_name_array[$i], $mix_name_array[$j], $this->type);
                    if (is_null($tmp_result_array)) {
                        
                    } else {
                        $limit++;
                        $query_result_array = array_merge($query_result_array, $tmp_result_array);
                        if ($limit == 50) {
                            //$result_t_array = array();		     
                            foreach ($query_result_array as $relation) {
                                $tmp_array = array();
                                $tmp_array['g1Symbol'] = $relation->gene1_Symbol;
                                $tmp_array['g2Symbol'] = $relation->gene2_Symbol;
                                $tmp_array['weight'] = $relation->weight;
                                $tmp_array['type'] = $relation->type;
                                $tmp_array['network'] = $relation->network;
                                $tmp_array['pubmedid'] = $relation->pubmedid;
                                array_push($result_t_array, $tmp_array);
                            }
                            $this->table_list_data = $result_t_array;
                            $this->result_count = count($result_t_array);
                            $this->m = $i;
                            $this->n = $j;
                            return;
                        }
                    }
                }
            }
        }


        //$result_t_array = array();		     
        foreach ($query_result_array as $relation) {
            $tmp_array = array();
            $tmp_array['g1Symbol'] = $relation->gene1_Symbol;
            $tmp_array['g2Symbol'] = $relation->gene2_Symbol;
            $tmp_array['weight'] = $relation->weight;
            $tmp_array['type'] = $relation->type;
            $tmp_array['network'] = $relation->network;
            $tmp_array['pubmedid'] = $relation->pubmedid;
            array_push($result_t_array, $tmp_array);
        }
        $this->table_list_data = $result_t_array;
        $this->result_count = count($result_t_array);
        $this->m = $i;
        $this->n = $j;
    }

    function execute_search_t_other_download() {
        $relat = new Search();
        $relation = new GeneRelation();
        $keys = array_filter(explode('|', $this->query_names));
        $keys = $this->remove_same_name($keys);
        $query_feature_names = array();
        foreach ($keys as $name) {

            if (!in_array($name, $query_feature_names)) {
                array_push($query_feature_names, $name);
            }
        }
        $id_array = array();
        $gene_array = array();
        $mix_name_array = array();
        foreach ($keys as $term_name) {
            $relation = new GeneRelation();
            $term_id = $relat->get_term_id_by_name($term_name);
            if (is_null($term_id) || $term_id == '')
                continue;
            if (in_array($term_id, $id_array)) {
                array_push($id_array, $term_id);
            }
            $gene_array = $relation->getGenenameByTermId($term_id);


            foreach ($gene_array as $name) {
                if (!in_array($name, $mix_name_array)) {
                    array_push($mix_name_array, $name);
                }
            }
        }
        $tmp_result = array();
        $query_result_array = array();
        foreach ($mix_name_array as $gene1) {

            if ($gene1 == ' ' || is_null($gene1) || strlen($gene1) == 0)
                continue;
            foreach ($mix_name_array as $gene2) {

                if ($gene2 == ' ' || is_null($gene2) || strlen($gene2) == 0)
                    continue;
                if ($gene1 == $gene2) {
                    continue;
                }
                $tmp_result_array = $relation->getIntercorrforEdgeInfo($gene1, $gene2, $this->type);
                if (is_null($tmp_result_array)) {
                    
                } else {

                    $query_result_array = array_merge($query_result_array, $tmp_result_array);
                }
            }
        }

//			echo $this->type; 
        $result_t_array = array();
        foreach ($query_result_array as $relation) {
            $tmp_array = array();
            $tmp_array['g1Symbol'] = $relation->gene1_Symbol . "|";
            $tmp_array['g2Symbol'] = $relation->gene2_Symbol . "|";
            $tmp_array['weight'] = $relation->weight;
            $tmp_array['type'] = $relation->type . "|";
            $tmp_array['network'] = $relation->network . "|";
            $tmp_array['pubmedid'] = $relation->pubmedid . "|";
            $tmp_array['flag'] = '!';

            array_push($result_t_array, $tmp_array['g1Symbol']);
            array_push($result_t_array, $tmp_array['g2Symbol']);
            array_push($result_t_array, $tmp_array['network']);
            //array_push($result_t_array, $tmp_array['pubmedid']);
            array_push($result_t_array, $tmp_array['type']);
            array_push($result_t_array, $tmp_array['weight']);
            array_push($result_t_array, $tmp_array['flag']);
        }

        return $result_t_array;
    }

    function get_child_relation($result_node_array, $type) {
        $relation = new GeneRelation();
        $query_result_array = array();
        $gene_array = array();
        $gene_array = array_keys($result_node_array);
        $num = count($gene_array);
        for ($i = 0; $i < $num; $i++) {
            for ($j = $i + 1; $j < $num; $j++) {
                $gene1 = $gene_array[$i];
                $gene2 = $gene_array[$j];
//         foreach ($result_node_array as $gene1 => $value1) {
//            foreach ($result_node_array as $gene2 => $value2) {
                if ($gene1 == $gene2) {
                    continue;
                }
                $rel = null;

                $rel = $relation->getIntercorrDatechildrel($gene1, $gene2, $type);
                if (is_null($rel)) {
                    
                } else {

                    $query_result_array = array_merge($query_result_array, $rel);
                }
            }
        }
        $edges_exist = array();
        foreach ($query_result_array as $value) {
            $node1 = $value->gene1_Symbol;
            $node2 = $value->gene2_Symbol;
            $key = $node1 . '-' . $node2;
            $tmp_key = $node2 . '-' . $node1;
//            if (in_array($key, $edges_exist) || in_array($tmp_key, $edges_exist)) {
//                continue;
//            }
            array_push($edges_exist, $key);

            $i_type = $type;
            $this->cw_edge_data .= ('{id:"' . ($key . "-" . $i_type) . '", distance:' . $value->weight . ',egc:"' . $i_type . '",target:"' . $node2 . '",source:"' . $node1 . '"},');
        }
    }

    function get_click_target_genenode_info($id, $num) {
        $result_array = array();
        //$col = array();
        $dbc = DBC::get_conn();
        $query = "";
        $query = "SELECT g.GeneID, g.Symbol, g.dbXrefs, g.Chromosome, g.MapLocation, g.Description, g.Type, g.FullName FROM geneinfo g WHERE g.Symbol = ? ";
        if ($stmt = mysqli_prepare($dbc, $query)) {
            //echo "%%%%%%%%%%%%%%%%%";
            mysqli_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $count = mysqli_stmt_num_rows($stmt);
            //echo "count:".$count;
            if ($count > 0) {
                mysqli_stmt_bind_result($stmt, $result_array['GeneID'], $result_array['Symbol'], $result_array['dbXrefs'], $result_array['Chromsome'], $result_array['MapLocation'], $result_array['Description'], $result_array['Type'], $result_array['FullName']);
                if (mysqli_stmt_fetch($stmt)) {
                    foreach ($result_array as $key => $value) {
                        $result_array[$key] = addslashes($value);
                    }
                }
            }
        }
        $query = $query = "SELECT DISTINCT i.GOTerm,m.GO_ID FROM geneinfo AS g ,gene2go_map AS m ,gene2go_goinfo AS i WHERE g.GeneID = m.Gene_ID AND m.GO_ID = i.GO_ID AND g.Symbol = '" . $id . "' ";
        if (is_null($dbc))
            return -1;
        $result = mysqli_query($dbc, $query);
        if (!$result)
            return -2;
        $i = 0;
        while ($row = mysqli_fetch_row($result)) {
            $result_array[GoTerm].=$row[0] . "|";
            switch (strlen($row[1])) {
                case 1:$row[1] = "GO:000000" . $row[1];
                    break;
                case 2:$row[1] = "GO:00000" . $row[1];
                    break;
                case 3:$row[1] = "GO:0000" . $row[1];
                    break;
                case 4:$row[1] = "GO:000" . $row[1];
                    break;
                case 5:$row[1] = "GO:00" . $row[1];
                    break;
                case 6:$row[1] = "GO:0" . $row[1];
                    break;
                case 7:$row[1] = "GO:" . $row[1];
                    break;
            }
            $result_array[GO_ID].=$row[1] . "|";
            $i++;
            if ($i == $num) {
                break;
            }
        }
        return json_encode($result_array);
    }

    function get_click_target_geneSymbol_info($id, $count) {
        $result_array = array();
        //$col = array();
        $dbc = DBC::get_conn();
        $query = "";
        // echo $id;
        $query = $query = "SELECT DISTINCT i.GOTerm,g.GeneID,m.GO_ID FROM geneinfo AS g ,gene2go_map AS m ,gene2go_goinfo AS i WHERE g.GeneID = m.Gene_ID AND m.GO_ID = i.GO_ID AND g.Symbol = '" . $id . "' ";
        if (is_null($dbc))
            return -1;
        $result = mysqli_query($dbc, $query);
        if (!$result)
            return -2;
        if ($row = mysqli_fetch_row($result)) {
            $result_array[GeneID] = $row[1];
        }
        $i = 0;
        while ($row = mysqli_fetch_row($result)) {
            $result_array[GoTerm].=$row[0] . "|";
            switch (strlen($row[2])) {
                case 1:$row[2] = "GO:000000" . $row[2];
                    break;
                case 2:$row[2] = "GO:00000" . $row[2];
                    break;
                case 3:$row[2] = "GO:0000" . $row[2];
                    break;
                case 4:$row[2] = "GO:000" . $row[2];
                    break;
                case 5:$row[2] = "GO:00" . $row[2];
                    break;
                case 6:$row[2] = "GO:0" . $row[2];
                    break;
                case 7:$row[2] = "GO:" . $row[2];
                    break;
            }
            $result_array[GO_ID].=$row[2] . "|";
            $i++;
            if ($i == $count) {
                break;
            }
        }

        return json_encode($result_array);
    }

    function get_click_target_generelatedges_info($id) {
        $result_array = array();
        //$col = array();
        $dbc = DBC::get_conn();
        $query = "";
        $genes = array_filter(explode('-', $id));
        $edge_type = $genes[2];

        $relation = new GeneRelation();

        $strain1_list = $relation->getGeneIDListBYGeneName($genes[0]);
        $strain2_list = $relation->getGeneIDLIstBYGeneName($genes[1]);
        if ((is_null($strain1_list) || is_null($strain2_list) || count($strain1_list) == 0 || count($strain2_list) == 0))
            return;
        foreach ($strain1_list as $strain1) {
            foreach ($strain2_list as $strain2) {
                $query = "SELECT adt.Weight,nw.Network,nw.Pubmed_ID from " . $edge_type . " adt,networks nw WHERE ((adt.Gene_A_Id=$strain1 and adt.Gene_B_Id=$strain2) or (adt.Gene_A_Id=$strain2 and adt.Gene_B_Id=$strain1) )and nw.id=adt.networkid";
                $result = mysqli_query($dbc, $query);

                $tmp_array = array();
                while ($row = mysqli_fetch_row($result)) {
                    $tmp_array['gene1'] = $genes[0];
                    $tmp_array['gene2'] = $genes[1];
                    $tmp_array['weight'] = $row[0];
                    $tmp_array['network'] = $row[1];
                    $tmp_array['pubmed_id'] = $row[2];

                    $result_array = array_merge($result_array, $tmp_array);
                }
            }
        }
        return json_encode($result_array);
    }

    function get_click_generelatedges_info($id) {
        $result = array();
        $genes = explode('-', $id);
        $edge_type = trim($genes[2]);
        $gene1 = trim($genes[0]);
        $gene2 = trim($genes[1]);
        //echo $gene2;
        $relation = new GeneRelation();
        $result = $relation->getIntercorrforEdgeInfo($gene1, $gene2, $edge_type);
        return json_encode($result);
    }

    function get_click_target_disease_info($id) {
        $result_array = array();

        $dbc = DBC::get_conn();
        $query = "";
        $query = "SELECT t.DOID, t.name,t.definition FROM term t WHERE t.name = ? ";
        if ($stmt = mysqli_prepare($dbc, $query)) {
            //echo "%%%%%%%%%%%%%%%%%";
            mysqli_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $count = mysqli_stmt_num_rows($stmt);
            //echo "count:".$count;
            if ($count > 0) {
                mysqli_stmt_bind_result($stmt, $result_array['DOID'], $result_array['name'], $result_array['definition']);
                if (mysqli_stmt_fetch($stmt)) {
                    foreach ($result_array as $key => $value) {
                        $result_array[$key] = addslashes($value);
                    }
                }
            }
        }

        return json_encode($result_array);
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

    function get_m() {
        return $this->m;
    }

    function get_n() {
        return $this->n;
    }

}
