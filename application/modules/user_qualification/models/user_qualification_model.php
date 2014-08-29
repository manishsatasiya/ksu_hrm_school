<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_qualification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function get_user_qualification($type="",$limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data) {
		$arrCampusPrivilages = get_user_campus_privilages();
    	if (!empty($search_data)) {
    		!empty($search_data['user_id']) ? $data['user_id'] = $search_data['user_id'] : "";
			!empty($search_data['elsd_id']) ? $data['elsd_id'] = $search_data['elsd_id'] : "";
    		!empty($search_data['staff_name']) ? $data['CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name)'] = $search_data['staff_name'] : "";
    		!empty($search_data['email']) ? $data['email'] = $search_data['email'] : "";
    		!empty($search_data['personal_email']) ? $data['personal_email'] = $search_data['personal_email'] : "";
    		!empty($search_data['user_roll_name']) ? $data['user_roll_name'] = $search_data['user_roll_name'] : "";
    		!empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
    		!empty($search_data['contractor']) ? $data['contractors.contractor'] = $search_data['contractor'] : "";
			!empty($search_data['nationality']) ? $data['countries.nationality'] = $search_data['nationality'] : "";
    		!empty($search_data['department_name']) ? $data['department.department_name'] = $search_data['department_name'] : "";
    		!empty($search_data['scanner_id']) ? $data['scanner_id'] = $search_data['scanner_id'] : "";
			!empty($search_data['returning']) ? $data['returning'] = $search_data['returning'] : "";
    	}
		
		$strQueryAllStatus = "";
		$strQueryIntType = "";
		$strQueryIntOutCome = "";
		$arrAllStatus = user_profile_status($type);
		
		foreach($arrAllStatus AS $key=>$val)
		{
			if($key != "")
				$strQueryAllStatus .= " WHEN $key THEN '$val' ";
		}
		
		if($strQueryAllStatus != "")
		{
			$strQueryAllStatus = " CASE users.status $strQueryAllStatus  ELSE 'N/A' END ";
		}
		
		$dt_columns = $this->get_dt_columns();
		//echo '<pre>';
		//print_r($dt_columns);exit;
		$qualification_sql = '';
		if(!empty($dt_columns)){
			foreach($dt_columns as $column_id=>$column_name){
				$qualification_sql .= '(SELECT user_qualification.subject FROM user_qualification WHERE user_qualification.user_id = users.user_id AND qualification_id = '.$column_id.') as '.$column_id.'_subject_name,
						  (SELECT user_qualification.subject_related FROM user_qualification WHERE user_qualification.user_id = users.user_id AND qualification_id = '.$column_id.') as '.$column_id.'_subject_related,';
			}	
    	}
		$this->db->select('users.user_id,
						  users.elsd_id,
						  CONCAT(users.first_name," ",users.middle_name," ",users.last_name) AS staff_name,
						  '.$strQueryAllStatus.' AS status,
						  school_campus.campus_name,
						  contractors.contractor,
						  countries.nationality,
						  '.$qualification_sql.'',FALSE);
    	$this->db->from('users');
		$this->db->join('user_profile', 'user_profile.user_id = users.user_id','left');
		$this->db->join('school_campus', 'school_campus.campus_id = users.campus_id','left');
		//$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id','left');
		//$this->db->join('department', 'department.id = user_profile.department_id','left');
		$this->db->join('contractors', 'contractors.id = user_profile.contractor','left');
		$this->db->join('countries', 'countries.id = user_profile.nationality','left');
		$this->db->where_not_in('users.user_roll_id',array('1','3'));
		if($type != "")
		{
			
			$arrStatus = array_keys($arrAllStatus);			
			if(count($arrStatus) > 0)
				$this->db->where_in('users.status',$arrStatus);
    	}
		
    	!empty($data) ? $this->db->like($data) : "";
		
    	if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->where_in('users.campus_id',$arrCampusPrivilages);
		}
		
		if($this->session->userdata('contractor') > 0){
			$this->db->where('user_profile.contractor',$this->session->userdata('contractor'));
		}
		
		if($order_by != "")
			$this->db->order_by($order_by, $sort_order);
		
		if($limit > 0)
			$this->db->limit($limit, $offset);
    
    	$query = $this->db->get();
		//echo $this->db->last_query();	
		//echo 'fddsf';exit;
		if($limit == 0)
			return $query->num_rows();
			
    	if($query->num_rows() > 0) {
    		return $query;
    	}
    }
	
	public function get_dt_columns() {
		$dt_columns = array();   
        $this->db->select('id,qualification',FALSE);
        $this->db->from('qualifications');
		$this->db->where('show_in_datatable', 'Yes');
		$this->db->order_by('datatable_display_order', 'ASC');

        $query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() AS $result_row){
				//$row = array();
				$dt_columns[$result_row['id']] = $result_row['qualification'];
				//$dt_columns[] = $row;
			}
        }
		
		return $dt_columns;
    }

}

/* End of file contractors_model.php */
