<?php

/**
 * Description of Search
 *
 * @author GGCoke
 * 2012-3-4 19:41:18
 */
class Search {
    private $GET_AUTOCOMPLETE_GENE_NAME = "SELECT g.Symbol FROM geneinfo g WHERE g.Symbol LIKE ?";
    private $GET_AUTOCOMPLETE_GENE_ALIAS = "SELECT g.Synonyms FROM geneinfo g WHERE g.Synonyms LIKE ?";
    private $GET_AUTOCOMPLETE_DISEASE = "SELECT t.name FROM term t WHERE t.name LIKE ?";
    private $GET_TERM_ID_BY_NAME = "SELECT t.DOID FROM term t WHERE t.name = ?";
    private $GET_MAPPED_GENE_COUNT_BY_TERMID = 'SELECT COUNT(m.id) FROM geneinfo g, generif r, term_mapping m, term t WHERE g.GeneId = r.geneid AND r.md5 = m.generif_md5 AND (m.status = 0 OR m.status = 1) AND t.DOID = m.term_id AND m.term_id IN (';
    private $GET_MAPPED_GENE_INFO_BY_TERM_ID = 'SELECT t.name, g.GeneID, g.Symbol, g.Chromosome, g.MapLocation, r.generifid, r.text FROM geneinfo g, generif r, term_mapping m, term t WHERE g.GeneId = r.geneid AND r.md5 = m.generif_md5 AND (m.status = 0 OR m.status = 1) AND t.DOID = m.term_id AND m.term_id IN (';
    private $GET_GENE_ID_BY_NAME = 'SELECT g.GeneID FROM geneinfo g WHERE g.Symbol = ? OR g.Synonyms LIKE ? OR g.Synonyms LIKE ? OR g.Synonyms LIKE ?';
    private $GET_MAPPED_TERM_COUNT_BY_GENEID = 'SELECT COUNT(m.id) FROM term t, generif g, term_mapping m WHERE m.generif_md5 = g.md5 AND t.DOID = m.term_id AND (m.status = 0 OR m.status = 1) AND g.geneid IN (';
    private $GET_MAPPED_TERM_INFO_BY_GENE_ID = 'SELECT i.Symbol, t.name, t.DOID, g.generifid, g.text FROM geneinfo i, term t, generif g, term_mapping m WHERE m.generif_md5 = g.md5 AND t.DOID = m.term_id AND (m.status = 0 OR m.status = 1) AND i.GeneID = g.geneid AND g.geneid IN (';
    private $GET_CHILD_NODE_BY_TERM_ID = 'SELECT t.name, t.DOID FROM term2term tt, term t WHERE t.DOID = tt.term1_id AND tt.term2_id LIKE ?';
    private $CHECK_HAS_CHILD_NODE = 'SELECT COUNT(tt.id) FROM term2term tt WHERE tt.term2_id LIKE ?';
    private $CHECK_TERM_EXIST_BY_ID = 'SELECT COUNT(t.id) FROM term t WHERE t.DOID = ?';
    private $GET_PARENT_NODE_ID = 'SELECT t.term2_id FROM term2term t WHERE t.term1_id = ?';
    private $GET_TERM_BASE_INFO = 'SELECT t.DOID, t.name, t.definition, t.comment FROM term t WHERE t.DOID = ?';
    private $GET_TERM_ALT_ID = 'SELECT t.alt_id FROM term_altid t WHERE t.term_id = ?';
    private $GET_TERM_SUBSET = 'SELECT t.subset FROM term_subset t WHERE t.term_id = ?';
    private $GET_TERM_SYSNONYM = 'SELECT t.synonym FROM term_synonym t WHERE t.term_id = ?';
    private $GET_TERM_XREF = 'SELECT t.xref_name, t.xref_value FROM term_xref t WHERE t.term_id = ?';
    private $GET_TERM_RELATION = 'SELECT t.relation, t.term2_id FROM term2term t WHERE t.term1_id = ?';
    
    /**
     * Get autocomplete result of gene names, including alias.
     * @global ADOConnection $global_do_conn
     * @param String $q Key word
     * @return array Query result
     */
    function get_autocomplete_gene($q) {
        $key = '%' . $q . '%';
        global $global_do_conn;
        $genes = array();
        $result = get_array_from_resultset($global_do_conn->Execute($this->GET_AUTOCOMPLETE_GENE_NAME, array($key)));
        if (!is_null($result)) {
            foreach ($result as $item) {
                array_push($genes, $item['Symbol']);
            }
        }
        $result = get_array_from_resultset($global_do_conn->Execute($this->GET_AUTOCOMPLETE_GENE_ALIAS, array($key)));
        if (!is_null($result)) {
            foreach ($result as $item) {
                if ($item['Synonyms'] != null && $item['Synonyms'] != '') {
                    $synonymses = explode('|', $item['Synonyms']);
                    foreach ($synonymses as $synonymse) {
                        if ($synonymse == null || $synonymse == '')
                            continue;

                        // Here should use '===' because stripos return 0 when $synonymse started with $q.
                        if (stripos($synonymse, $q) === false)
                            continue;
                        array_push($genes, $synonymse);
                    }
                }
            }
        }

        return $genes;
    }

    /**
     *
     * @global ADOConnection $global_do_conn
     * @param type $q Key word
     * @return array Query result
     */

function execut_search_getdatatxt($keywords, $type, $current_page, $count, $sortname, $sortorder) {
        $search_result = array();
        //$search_result['page'] = $current_page;
        $keys = explode(STRING_SEPARATOR, $keywords);

        // Remove some search word and blank key word.
        $keys = $this->format_keys($keys);
        if (is_null($keys) || count($keys) == 0) {
            //$search_result['total'] = $current_page;
            return json_encode($search_result);
        }
        $start = ($current_page - 1) * $count;
        $total_count = 0;
        //$row_array = array();
        $id_array = array();
        if ($type == 'd2g') {
            $detail_array = array();
            foreach ($keys as $term_name) {
                $term_id = $this->get_term_id_by_name($term_name);
                if (is_null($term_id) || $term_id == '')
                    continue;
                array_push($id_array, $term_id);
            }

            $total_count = $this->get_mapped_gene_count($id_array);
            $detail_array = $this->get_mapped_gene_info($id_array, $start, $count, $sortname, $sortorder);
            if (!is_null($detail_array) && count($detail_array) != 0) {
                $i = $start;
                foreach ($detail_array as $item) {
                    $i++;
                    //$ceil_array = array();
                    $tmp_array = array();
                    array_push($tmp_array, $i);
                    array_push($tmp_array, $item['name']);
                    array_push($tmp_array, $item['Symbol']);
                    //array_push($tmp_array, $item['DOID']);
                    //array_push($tmp_array, '<a target="_blank" href="http://www.ncbi.nlm.nih.gov/gene/' . $item['GeneID'] . '">' . $item['Symbol'] . '</a>');
                    array_push($tmp_array, $item['Chromosome']);
                    array_push($tmp_array, $item['MapLocation']);
                    array_push($tmp_array, $item['generifid'] );
                    array_push($tmp_array, $item['text']);
                    //$ceil_array['cell'] = $tmp_array;
                    array_push($search_result, $tmp_array);
                }
            }
        } else if ($type == 'g2d') {
            $detail_array = array();
            foreach ($keys as $gene_name) {
                $gene_id = $this->get_gene_id_by_name($gene_name);
                if (is_null($gene_id) || $gene_id == '')
                    continue;
                array_push($id_array, $gene_id);
            }
            $total_count = $this->get_mapped_term_count($id_array);
            $detail_array = $this->get_mapped_term_info($id_array, $start, $count, $sortname, $sortorder);
            if (!is_null($detail_array) && count($detail_array)!= 0) {
                $i = $start;
                foreach ($detail_array as $item) {
                    $i++;
                    $ceil_array = array();
                    $tmp_array = array();
                    array_push($tmp_array, $i);
                    array_push($tmp_array, $item['Symbol']);
                    array_push($tmp_array, $item['name']);
                    //array_push($tmp_array, $item['DOID']);
                    array_push($tmp_array, $item['DOID']);
                    array_push($tmp_array, $item['generifid'] );
                    array_push($tmp_array, $item['text']);
                    //$ceil_array['cell'] = $tmp_array;
                    array_push($search_result, $tmp_array);
                }
            }
        }

        //$search_result['total'] = $total_count;
        //$search_result['rows'] =$row_array;
        return $search_result;
    }
    
    function get_autocomplete_disease($q) {
        $key = '%' . $q . '%';
        global $global_do_conn;
        $disease = array();
        $result = get_array_from_resultset($global_do_conn->Execute($this->GET_AUTOCOMPLETE_DISEASE, array($key)));
        if (!is_null($result)) {
            foreach ($result as $item) {
                array_push($disease, $item['name']);
            }
        }
        return $disease;
    }

    function batch_query($keywords, $type){
        $search_result = array();
        $temp_array = array();
        $keys = explode(STRING_SEPARATOR, $keywords);
        $keys = $this->format_keys($keys);
    if (is_null($keys) || count($keys) == 0) {
            return $search_result;
        }
        global $global_do_conn;
        if ($type == 'd2g'){
        	foreach ($keys as $term_name) {
            $term_id = $this->get_term_id_by_name($term_name);
            if (is_null($term_id) || $term_id == ''){
                continue;
            }
            $query_sql = 'SELECT t.DOID, t.name, g.GeneID, g.Symbol, g.Chromosome, g.MapLocation, r.generifid FROM term_mapping tm, generif r, geneinfo g, term t WHERE t.DOID = tm.term_id AND r.md5 = tm.generif_md5 AND g.GeneID = r.geneid AND (r.status = 0 OR r.status = 1) AND tm.term_id = ?';
            $temp_array['query'] = $term_name;
            $temp_array['type'] = $type;
            $temp_array['data'] = get_array_from_resultset($global_do_conn->Execute($query_sql, array($term_id)));
            array_push($search_result, $temp_array);
            }        	
        } else if ($type == 'g2d'){
        	foreach ($keys as $gene_name) {
            $gene_id = $this->get_gene_id_by_name($gene_name);
            if (is_null($gene_id) || $gene_id == ''){
                continue;
            }
            $query_sql = 'SELECT t.DOID, t.name, i.GeneID, i.Symbol, i.Chromosome, i.MapLocation, g.generifid FROM geneinfo i, term t, generif g, term_mapping m WHERE m.generif_md5 = g.md5 AND t.DOID = m.term_id AND (m.status = 0 OR m.status = 1) AND i.GeneID = g.geneid AND g.geneid = ?';
            $temp_array['query'] = $gene_name;
            $temp_array['type'] = $type;
            $temp_array['data'] = get_array_from_resultset($global_do_conn->Execute($query_sql, array($gene_id)));
            array_push($search_result, $temp_array);
        }
        }
        return $search_result;
    }
    
    function execut_search($keywords, $type, $current_page, $count, $sortname, $sortorder) {
        $search_result = array();
        $search_result['page'] = $current_page;
        $keys = explode(STRING_SEPARATOR, $keywords);

        // Remove some search word and blank key word.
        $keys = $this->format_keys($keys);
        if (is_null($keys) || count($keys) == 0) {
            $search_result['total'] = $current_page;
            return json_encode($search_result);
        }
        $start = ($current_page - 1) * $count;
        $total_count = 0;
        $row_array = array();
        $id_array = array();
        if ($type == 'd2g') {
            $detail_array = array();
            foreach ($keys as $term_name) {
                $term_id = $this->get_term_id_by_name($term_name);
                if (is_null($term_id) || $term_id == '')
                    continue;
                array_push($id_array, $term_id);
            }

            $total_count = $this->get_mapped_gene_count($id_array);
            $detail_array = $this->get_mapped_gene_info($id_array, $start, $count, $sortname, $sortorder);
            if (!is_null($detail_array) && count($detail_array) != 0) {
                $i = $start;
                foreach ($detail_array as $item) {
                    $i++;
                    $ceil_array = array();
                    $tmp_array = array();
                    array_push($tmp_array, $i);
                    array_push($tmp_array, $item['name']);
                    //array_push($tmp_array, '<a target="_blank" href="http://www.ncbi.nlm.nih.gov/gene/' . $item['GeneID'] . '">' . $item['Symbol'] . '</a>');
                    array_push($tmp_array, '<a href="#" class="doid">' . $item['Symbol'] . '</a>');
                    array_push($tmp_array, $item['Chromosome']);
                    array_push($tmp_array, $item['MapLocation']);
                    array_push($tmp_array, '<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed?term=' . $item['generifid'] . '">' . $item['generifid'] . '</a>');
                    array_push($tmp_array, $item['text']);
                    $ceil_array['cell'] = $tmp_array;
                    array_push($row_array, $ceil_array);
                }
            }
        } else if ($type == 'g2d') {
            $detail_array = array();
            foreach ($keys as $gene_name) {
                $gene_id = $this->get_gene_id_by_name($gene_name);
                if (is_null($gene_id) || $gene_id == '')
                    continue;
                array_push($id_array, $gene_id);
            }
            $total_count = $this->get_mapped_term_count($id_array);
            $detail_array = $this->get_mapped_term_info($id_array, $start, $count, $sortname, $sortorder);
            if (!is_null($detail_array) && count($detail_array) != 0) {
                $i = $start;
                foreach ($detail_array as $item) {
                    $i++;
                    $ceil_array = array();
                    $tmp_array = array();
                    array_push($tmp_array, $i);
                    array_push($tmp_array, $item['Symbol']);
                    array_push($tmp_array, $item['name']);
                    array_push($tmp_array, '<a target="_blank" href="http://bioportal.bioontology.org/ontologies/45125?p=terms&conceptid=DOID%3A' . $item['DOID'] . '">' . $item['DOID'] . '</a>');
                    array_push($tmp_array, '<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed?term=' . $item['generifid'] . '">' . $item['generifid'] . '</a>');
                    array_push($tmp_array, $item['text']);
                    $ceil_array['cell'] = $tmp_array;
                    array_push($row_array, $ceil_array);
                }
            }
        }

        $search_result['total'] = $total_count;
        $search_result['rows'] = $row_array;
        return $search_result;
    }

    function format_keys($keys) {
        $result = array();
        foreach ($keys as $key) {
            $key = trim($key, ' ');
            if (is_null($key) || $key == '' || $key == ' ') {
                continue;
            }
            if (!in_array($key, $result))
                array_push($result, $key);
        }

        return $result;
    }

    function get_term_id_by_name($name) {
        global $global_do_conn;
        return $global_do_conn->GetOne($this->GET_TERM_ID_BY_NAME, array($name));
    }

    function get_gene_id_by_name($name) {
        global $global_do_conn;
        $left = $name . '|%';
        $middle = '%|' . $name . '|%';
        $right = '%|' . $name;
        return $global_do_conn->GetOne($this->GET_GENE_ID_BY_NAME, array($name, $left, $middle, $right));
    }

    function get_mapped_gene_count($id_array) {
        if (is_null($id_array) || count($id_array) == 0)
            return 0;
        $query = $this->GET_MAPPED_GENE_COUNT_BY_TERMID;
        foreach ($id_array as $id) {
            $query .= trim($id) . ',';
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ')';
        global $global_do_conn;
        return $global_do_conn->GetOne($query);
    }

    function get_mapped_gene_info($id_array, $start, $count, $sortname, $sortorder) {
        if (is_null($id_array) || count($id_array) == 0)
            return null;
        $query = $this->GET_MAPPED_GENE_INFO_BY_TERM_ID;
        foreach ($id_array as $id) {
            $query .= trim($id) . ',';
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ') ORDER BY';
        switch ($sortname) {
            case 'dname':
                $query .= ' t.name ' . $sortorder;
                break;
            case 'symbol':
                $query .= ' g.Symbol ' . $sortorder;
                break;
          //ADD BY Song jingwei  
          case 'chromosome':
                $query .= ' g.Chromosome ' . $sortorder;
                break;
        }
        $query .= ' LIMIT ?, ?';
        global $global_do_conn;
        return get_array_from_resultset($global_do_conn->Execute($query, array($start, $count)));
    }
function get_text_gene_info($gene_id, $term_id) {
        $query = "SELECT r.generifid, r.text FROM geneinfo g, generif r, term_mapping m, term t WHERE g.GeneId = r.geneid AND r.md5 = m.generif_md5 AND (m.status = 0 OR m.status = 1) AND t.DOID = m.term_id AND m.term_id =$term_id AND r.geneid=$gene_id";
        global $global_do_conn; 
        $data= get_array_from_resultset($global_do_conn->Execute($query));
        $result_count = count($data);   
        $result['table_list_data'] =  $data; 
        $result['table_list_count'] = $result_count;
        return $result;
    }

    function get_mapped_term_count($id_array) {
        if (is_null($id_array) || count($id_array) == 0)
            return 0;
        $query = $this->GET_MAPPED_TERM_COUNT_BY_GENEID;
        foreach ($id_array as $id) {
            $query .= trim($id) . ',';
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ')';
        global $global_do_conn;
        return $global_do_conn->GetOne($query);
    }

    function get_mapped_term_info($id_array, $start, $count, $sortname, $sortorder) {
        if (is_null($id_array) || count($id_array) == 0)
            return 0;
        $query = $this->GET_MAPPED_TERM_INFO_BY_GENE_ID;
        foreach ($id_array as $id) {
            $query .= trim($id) . ',';
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ') ORDER BY';
        switch ($sortname) {
            case 'dname':
                $query .= ' t.name ' . $sortorder;
                break;
            case 'symbol':
                $query .= ' i.Symbol ' . $sortorder;
                break;
        }
        $query .= ' LIMIT ?, ?';
        global $global_do_conn;
        return get_array_from_resultset($global_do_conn->Execute($query, array($start, $count)));
    }

    function get_child_node_by_id($term_id) {
        global $global_do_conn;
        $term_id .= ' !%';
        $children = get_array_from_resultset($global_do_conn->Execute($this->GET_CHILD_NODE_BY_TERM_ID, array($term_id)));
        $result = '[';
        $empty = true;
        if (!is_null($children) && count($children) != 0) {
            $empty = false;
            foreach ($children as $child) {
                $result .= '{"attr" : {"id" : "li_' . $child['DOID'] . '"}, "data": {"title":"' . $child['name'] . '","attr":{"id":"' . $child['DOID'] . '", "href": "javascript:void(0);","class":"treenode"}},"state": "closed"},';
            }
        }
        if (!$empty) {
            $result = substr($result, 0, (strlen($result) - 1));
        }
        $result .= ']';
        return $result;
    }

    function get_all_parent_ids($term_id, $parent_node_ids){
        $parent_id = $this->get_parent_node_id($term_id);
        if (!is_null($parent_id)){
            array_push($parent_node_ids, 'li_' . $parent_id);
            $parent_node_ids = $this->get_all_parent_ids($parent_id, $parent_node_ids);
        }
        
        return $parent_node_ids;
    }
    
    function get_parent_node_id($term_id){
        global $global_do_conn;
        $mix_name = $global_do_conn->GetOne($this->GET_PARENT_NODE_ID, array($term_id));
        if (!is_null($mix_name) && strlen($mix_name) != 0){
            $names = explode('!', $mix_name);
            return strval(trim($names[0], ' '));
        }
        return null;
    }
    
    function has_child_node($term_id){
        global $global_do_conn;
        $term_id .= ' !%';
        return $global_do_conn->GetOne($this->CHECK_HAS_CHILD_NODE, array($term_id));
    }
    
    function get_child_node($term_id){
        global $global_do_conn;
        $term_id .= ' !%';
        return get_array_from_resultset($global_do_conn->Execute($this->GET_CHILD_NODE_BY_TERM_ID, array($term_id)));
    }
    
    function check_term_exist($term_id){
        global $global_do_conn;
        return $global_do_conn->GetOne($this->CHECK_TERM_EXIST_BY_ID, array($term_id)) == 0 ? false : true;
    }
    
    function get_term_detail($term_name){
        $term_info = array();
        $term_id = $this->get_term_id_by_name($term_name);
        if ($term_id == null || $term_id == 0){
            return null;
        }
        
        $basic_info = $this->get_term_base_info($term_id);
        if (is_null($basic_info) || count($basic_info) == 0){
            return null;
        }
        
        $alt_ids = $this->get_term_alt_id($term_id);
        $sub_sets = $this->get_term_subset($term_id);
        $synonyms = $this->get_term_synonym($term_id);
        $xrefs = $this->get_term_xref($term_id);
        $relations = $this->get_term_relation($term_id);
        
        $term_info['id'] = $basic_info['DOID'];
        $term_info['name'] = $basic_info['name'];
        $term_info['definition'] = $basic_info['definition'];
        $term_info['comment'] = $basic_info['comment'];
        $term_info['alt_ids'] = $alt_ids;
        $term_info['subsets'] = $sub_sets;
        $term_info['synonyms'] = $synonyms;
        $term_info['xrefs'] = $xrefs;
        $term_info['relations'] = $relations;
        return $term_info;
    }
    
    function get_term_base_info($term_id){
        global $global_do_conn;
        $result = get_array_from_resultset($global_do_conn->Execute($this->GET_TERM_BASE_INFO, array($term_id)));
        return is_null($result) || count($result) == 0 ? null : $result[0];
    }
    
    function get_term_alt_id($term_id){
        global $global_do_conn;
        return get_array_from_resultset($global_do_conn->Execute($this->GET_TERM_ALT_ID, array($term_id)));
    }
    
    function get_term_subset($term_id){
        global $global_do_conn;
        return get_array_from_resultset($global_do_conn->Execute($this->GET_TERM_SUBSET, array($term_id)));
    }
    
    function get_term_synonym($term_id){
        global $global_do_conn;
        return get_array_from_resultset($global_do_conn->Execute($this->GET_TERM_SYSNONYM, array($term_id)));
    }
    
    function get_term_xref($term_id){
        global $global_do_conn;
        return get_array_from_resultset($global_do_conn->Execute($this->GET_TERM_XREF, array($term_id)));
    }
    
    function get_term_relation($term_id){
        global $global_do_conn;
        return get_array_from_resultset($global_do_conn->Execute($this->GET_TERM_RELATION, array($term_id)));
    }
}