<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshops_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_workshop($limit = 0, $offset = 0, $order_by = "workshop_id,title", $sort_order = "asc", $search_data,$count = false) {
		$user_id = $this->session->userdata('user_id');

        if (!empty($search_data)) {
        	!empty($search_data['workshop_id']) ? $data['workshop_id'] = $search_data['workshop_id'] : "";
            !empty($search_data['title']) ? $data['title'] = $search_data['title'] : "";
            !empty($search_data['start_date']) ? $data['start_date'] = $search_data['start_date'] : "";
            !empty($search_data['time']) ? $data['time'] = $search_data['time'] : ""; 
            !empty($search_data['presenter']) ? $data['presenter'] = $search_data['presenter'] : "";
            !empty($search_data['workshop_type']) ? $data['workshop_types.type'] = $search_data['workshop_type'] : "";
            !empty($search_data['venue']) ? $data['venue'] = $search_data['venue'] : "";
			!empty($search_data['attendee_limit']) ? $data['attendee_limit'] = $search_data['attendee_limit'] : "";
        }
   
        $this->db->select('workshops.*,users.first_name as presenter_name,workshop_types.type as workshop_type',FALSE);
        $this->db->from('workshops');  
        $this->db->join('users','users.user_id = workshops.presenter','left');
		$this->db->join('workshop_types','workshop_types.id = workshops.workshop_type_id','left');
        
		//$this->db->where('start_date >= "'.date('Y-m-d').'"');
		$this->db->where('workshops.status',1);	
		$this->db->where('presented',2);
					
        if(!empty($data))
        {
        	$str_data_or_like = "";
        	foreach($data AS $data_key=>$data_val)
        	{
        		$str_data_or_like .= " $data_key LIKE '%$data_val%' OR ";	
        	}
        	$str_data_or_like = trim(trim($str_data_or_like),"OR");
        	
        	if($str_data_or_like != "")
        		$this->db->where("(".$str_data_or_like.")", null, false);
        }
		
        $this->db->order_by($order_by, $sort_order);
		if($count == false)
        	$this->db->limit($limit, $offset);

        $query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
			
		if($query->num_rows() > 0) {
            return $query;
        }
    }
	
	public function get_workshop_inactive($limit = 0, $offset = 0, $order_by = "workshop_id,title", $sort_order = "asc", $search_data,$count = false) {
		$user_id = $this->session->userdata('user_id');

        if (!empty($search_data)) {
        	!empty($search_data['workshop_id']) ? $data['workshop_id'] = $search_data['workshop_id'] : "";
            !empty($search_data['title']) ? $data['title'] = $search_data['title'] : "";
            !empty($search_data['start_date']) ? $data['start_date'] = $search_data['start_date'] : "";
            !empty($search_data['time']) ? $data['time'] = $search_data['time'] : ""; 
            !empty($search_data['presenter']) ? $data['users.first_name'] = $search_data['presenter'] : "";
            !empty($search_data['workshop_type']) ? $data['workshop_types.type'] = $search_data['workshop_type'] : "";
            !empty($search_data['venue']) ? $data['venue'] = $search_data['venue'] : "";
			!empty($search_data['attendee_limit']) ? $data['attendee_limit'] = $search_data['attendee_limit'] : "";
        }
   
        $this->db->select('workshops.*,users.first_name as presenter_name,workshop_types.type as workshop_type',FALSE);
        $this->db->from('workshops');  
        $this->db->join('users','users.user_id = workshops.presenter','left');
		$this->db->join('workshop_types','workshop_types.id = workshops.workshop_type_id','left');
        
		//$this->db->where('start_date < "'.date('Y-m-d').'"');
		$this->db->where('workshops.status',2);	
		//$this->db->where('presented',1);
					
        if(!empty($data))
        {
        	$str_data_or_like = "";
        	foreach($data AS $data_key=>$data_val)
        	{
        		$str_data_or_like .= " $data_key LIKE '%$data_val%' OR ";	
        	}
        	$str_data_or_like = trim(trim($str_data_or_like),"OR");
        	
        	if($str_data_or_like != "")
        		$this->db->where("(".$str_data_or_like.")", null, false);
        }
		
        $this->db->order_by($order_by, $sort_order);
		if($count == false)
        	$this->db->limit($limit, $offset);

        $query = $this->db->get();
		//echo $this->db->last_query();
		if($count == true)
			return $query->num_rows();
			
		if($query->num_rows() > 0) {
            return $query;
        }
    }
    
	public function get_workshop_types()
	{
		$workshop_types = array();
		$this->db->select('workshop_types.*',FALSE);
        $this->db->from('workshop_types');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$workshop_types_data = $query->result_array();
			$workshop_types[0] = '--Select--';
			foreach ($workshop_types_data as $_workshop_types_data){
				$workshop_types[$_workshop_types_data['id']] = $_workshop_types_data['type'];
			}
			
        } 
		return $workshop_types;
	}
	
	public function get_workshop_data($workshop_id){
    	
		$this->db->select('workshops.*,users.first_name as presenter_name,workshop_types.type as workshop_type',FALSE);
        $this->db->from('workshops');
		$this->db->join('users','users.user_id = workshops.presenter','left');
		$this->db->join('workshop_types','workshop_types.id = workshops.workshop_type_id','left');
		$this->db->where('workshops.workshop_id', $workshop_id);
		
    	$query = $this->db->get();		
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
	
	public function get_attendee($workshop_id,$get_count = false){
    	
		$this->db->select('user_workshop.*',FALSE);
        $this->db->from('user_workshop');
		$this->db->where('user_workshop.workshop_id', $workshop_id);
    	$query = $this->db->get();
		if($get_count == true)
    		return $query->num_rows();
			
		if($query->num_rows() > 0) {
    		return $query;
    	}
    	return false;
    }
	
	public function get_attendee_data($user_workshop_id){
    	
		$this->db->select('user_workshop.*',FALSE);
        $this->db->from('user_workshop');
		$this->db->where('user_workshop.user_workshop_id', $user_workshop_id);
		
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
	
	public function get_workshops()
	{
		$workshops = array();
		$this->db->select('workshop_id,title,start_date',FALSE);
        $this->db->from('workshops');
		//$this->db->where('workshops.status', 1);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$workshops_data = $query->result_array();
			$workshops[0] = '--Select--';
			foreach ($workshops_data as $_workshops_data){
				$workshops[$_workshops_data['workshop_id']] = $_workshops_data['title'];
			}
			
        } 
		return $workshops;
	}
	
	public function checkAttendeeUser($attendee,$workshop_id)
	{
		$this->db->select('user_workshop_id',FALSE);
        $this->db->from('user_workshop');
		$this->db->where('user_workshop.attendee', $attendee);
		$this->db->where('user_workshop.workshop_id', $workshop_id);
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return false;
		else
			return true;	
	}  
	
	public function get_workshop_attendees($limit = 0, $offset = 0, $order_by = "user_workshop_id,workshop_id", $sort_order = "asc", $search_data, $workshop_id,$count = false) {
		$user_id = $this->session->userdata('user_id');
		
        if (!empty($search_data)) {
        	!empty($search_data['user_workshop_id']) ? $data['user_workshop_id'] = $search_data['user_workshop_id'] : "";
            !empty($search_data['users.first_name']) ? $data['users.first_name'] = $search_data['users.first_name'] : "";
            !empty($search_data['users.elsd_id']) ? $data['users.elsd_id'] = $search_data['users.elsd_id'] : "";
            !empty($search_data['users.email']) ? $data['users.email'] = $search_data['users.email'] : ""; 
            !empty($search_data['created_at']) ? $data['created_at'] = $search_data['created_at'] : "";
        }
   
        $this->db->select('user_workshop.*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) as attendee_name,
							CONCAT_WS(" ",l.first_name,l.middle_name,l.middle_name2,l.last_name) as line_manager,users.elsd_id,users.email',FALSE);
        $this->db->from('user_workshop');  
        $this->db->join('users','users.user_id = user_workshop.attendee','left');
		$this->db->join('users AS l','l.user_id = users.coordinator','left');
		$this->db->where('workshop_id',$workshop_id);
					
        if(!empty($data))
        {
        	$str_data_or_like = "";
        	foreach($data AS $data_key=>$data_val)
        	{
        		$str_data_or_like .= " $data_key LIKE '%$data_val%' OR ";	
        	}
        	$str_data_or_like = trim(trim($str_data_or_like),"OR");
        	
        	if($str_data_or_like != "")
        		$this->db->where("(".$str_data_or_like.")", null, false);
        }
		
        $this->db->order_by($order_by, $sort_order);
		if($count == false)
        	$this->db->limit($limit, $offset);

        $query = $this->db->get();
		//echo $this->db->last_query();
		if($count == true)
			return $query->num_rows();
			
		if($query->num_rows() > 0) {
            return $query;
        }
    }
	
	public function get_workshop_typesdata($limit = 0, $offset = 0, $order_by = "type", $sort_order = "asc", $search_data,$count = false) {
		if (!empty($search_data)) {
        	!empty($search_data['type']) ? $data['workshop_types.type'] = $search_data['type'] : "";
        }
   
        $this->db->select('workshop_types.*',FALSE);
        $this->db->from('workshop_types');
					
        if(!empty($data))
        {
        	$str_data_or_like = "";
        	foreach($data AS $data_key=>$data_val)
        	{
        		$str_data_or_like .= " $data_key LIKE '%$data_val%' OR ";	
        	}
        	$str_data_or_like = trim(trim($str_data_or_like),"OR");
        	
        	if($str_data_or_like != "")
        		$this->db->where("(".$str_data_or_like.")", null, false);
        }
		
        $this->db->order_by($order_by, $sort_order);
		if($count == false)
        	$this->db->limit($limit, $offset);

        $query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
			
		if($query->num_rows() > 0) {
            return $query;
        }
    }
	
	public function get_workshop_typesdata_by_id($id){
    	
		$this->db->select('workshop_types.*',FALSE);
        $this->db->from('workshop_types');
		$this->db->where('workshop_types.id', $id);
		
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
	
	function get_attendee_user_list() {
		$arrCampusPrivilages = get_user_campus_privilages();
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where_not_in('users.user_roll_id',array('1','3'));
		
		if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->where_in('users.campus_id',$arrCampusPrivilages);
		}
				
		$this->db->order_by('first_name', 'ASC');	
		$query = $this->db->get();
		$student_data = $query->result_array();
		$student_arr = array();
		$student_arr[0] = '--Select--';
		foreach ($student_data as $_student_data){
			$student_arr[$_student_data['user_id']] = $_student_data['first_name'];
		}
		return $student_arr;
	}
}

/* End of file workshops_model.php */
