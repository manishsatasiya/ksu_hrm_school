<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Observation_scores_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function get_observations($limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data,$campus_id=0,$count = false) {
        $fields = $this->db->list_fields('users');

        if (!empty($search_data)) {
            !empty($search_data['username']) ? $data['users.username'] = $search_data['username'] : "";
            !empty($search_data['first_name']) ? $data['users.first_name'] = $search_data['first_name'] : "";
            !empty($search_data['ca_lead_teacher']) ? $data['ca_lead.first_name'] = $search_data['ca_lead_teacher'] : "";
            !empty($search_data['section_title']) ? $data['course_section.section_title'] = $search_data['section_title'] : "";
            !empty($search_data['elsd_id']) ? $data['users.elsd_id'] = $search_data['elsd_id'] : "";
            !empty($search_data['email']) ? $data['users.email'] = $search_data['email'] : "";
            !empty($search_data['user_roll_name']) ? $data['user_roll_name'] = $search_data['user_roll_name'] : "";
            !empty($search_data['name_suffix']) ? $data['users.name_suffix'] = $search_data['name_suffix'] : "";
            !empty($search_data['address1']) ? $data['users.address1'] = $search_data['address1'] : "";
            !empty($search_data['city']) ? $data['users.city'] = $search_data['city'] : "";
            !empty($search_data['state']) ? $data['users.state'] = $search_data['state'] : "";
            !empty($search_data['zip']) ? $data['users.zip'] = $search_data['zip'] : "";
            !empty($search_data['cell_phone']) ? $data['users.cell_phone'] = $search_data['cell_phone'] : "";
            !empty($search_data['campus']) ? $data['users.campus'] = $search_data['campus'] : "";
        }
        $this->db->select('users.*,course_section.section_title,obs_detail.comment,obs_detail.comment,obs_detail.score1,obs_detail.et_score1,obs_detail.updated_at AS obs_date,observer.first_name as observer_name',FALSE);
        $this->db->from('users');  
        $this->db->join('course_class','users.user_id = course_class.primary_teacher_id','left');  
        $this->db->join('course_section','course_class.section_id = course_section.section_id','left');  
		$this->db->join('obs_detail','users.user_id = obs_detail.user_id','left');
		$this->db->join('users as observer','observer.user_id = obs_detail.created_by','left');  
        
		$this->db->where('users.active','1');       
		if($campus_id > 0) {
			$this->db->where('users.campus_id = '.$campus_id);
		}
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if(count(get_user_campus_privilages()) > 0)
		{	
			$this->db->where_in('users.campus_id',get_user_campus_privilages());
		}
		        
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
		
		$this->db->group_by(array("users.user_id"));
        $query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
			
        if($query->num_rows() > 0) {
            return $query;
        }
    }
    
}

/* End of file list_teacher_student_model.php */
