<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_search_data')) {
    /**
     *
     * get_grid_data: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_search_data($aColumns = array()) {
    	
    	/*
    	 * Paging
    	 */
    	$per_page =  10;
    	$offset = 0;
    		
    	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    	{
    		$per_page =  $_GET['iDisplayLength'];
    		$offset = $_GET['iDisplayStart'];
    	}
    	
    	/*
    	 * Ordering
    	 */
    	$order_by = ""; 
    	$sort_order = "";
    	if ( isset( $_GET['iSortCol_0'] ) )
    	{
    		$order_by = "";
    		$sort_order = "";
    		
    		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
    		{
    			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
    			{
    				$order_by = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
    				$sort_order = $_GET['sSortDir_'.$i];
    			}
    		}
    	}
    	
    	/*
    	 * Filtering
    	 * NOTE this does not match the built-in DataTables filtering which does it
    	 * word by word on any field. It's possible to do here, but concerned about efficiency
    	 * on very large tables, and MySQL's regex functionality is very limited
    	 */
    	$search_data = array();
    	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    	{
    		$sWhere = "WHERE (";
    		for ( $i=0 ; $i<count($aColumns) ; $i++ )
    		{
    			$search_data[$aColumns[$i]] = $_GET['sSearch'];
    		}
    	}
    	
    	/* Individual column filtering */
    	for ( $i=0 ; $i<count($aColumns) ; $i++ )
    	{
    		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
    		{
    			$search_data[$aColumns[$i]] = $_GET['sSearch_'.$i];
    		}
    	}
    	$data = array();
    	$data['order_by'] = $order_by;
    	$data['sort_order'] = $sort_order;
    	$data['search_data'] = $search_data;
    	$data['per_page'] = $per_page;
    	$data['offset'] = $offset;
    	return $data;
    }
    
    function grid_update_data($whrid_column,$id,$columnName,$value,$table){
    	$ci =& get_instance();
    	$data = array();
    	if ($columnName == 'password'){
    		$nonce = md5(uniqid(mt_rand(), true));
    		$data = array(
    				'password' => hash_password($value, $nonce),
    				'nonce' => $nonce
    		);
    		
    	}
		else if($columnName == 'academic_status'){
		
			$query = "SELECT week_id FROM `enable_school_week` where last_date >= '".DATE('Y-m-d')."' order by last_date limit 1 ";
			$query_res = $ci->db->query($query);
			$arrWeek = $query_res->result_array();
			$week_id = 0;
			foreach($arrWeek AS $row)
				$week_id = $row["week_id"];
				
    		$data = array(
    				$columnName    => $value,'discontinue_date' => DATE('Y-m-d'),'discontinue_week_id'=>$week_id);
    	}
		else{
    		$data = array(
    				$columnName    => $value);
    	}
    	
    	$ci->db->where($whrid_column, $id);
    	$ci->db->update($table, $data);
		
		if ($columnName == 'academic_status' && $value == "Withdrawn")
			return "update_weekly_attendance";
		if($columnName != 'campus_id' && $columnName != 'campus')	
			echo "success";
    }
    
    function grid_add_data($data = array(),$table){
    	$ci =& get_instance();
    	 
    	$ci->db->insert($table, $data);
    	
    	$lastinsertid = $ci->db->insert_id();
    	if ($ci->db->affected_rows() == 1) {
    		return $lastinsertid;
    	}
    	return false;
    	
    }
    
    function grid_data_updates($data = array(),$table,$wher_column_name,$id){
    	$ci =& get_instance();
    	$ci->db->where($wher_column_name, $id);
    	$ci->db->update($table, $data);
    }
	
	function grid_delete($table,$where_col,$where_col_id){
    	$ci =& get_instance();
    	$ci->db->where($where_col, $where_col_id);
		$ci->db->delete($table);
        if($ci->db->affected_rows() > 0) {
            return true;
        }
        return false;
		
    }
	
}

/* End of file grid_function_helper.php */
/* Location: ./application/helpers/grid_function_helper.php */