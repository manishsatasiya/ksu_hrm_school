<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_class_section_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_members: get the members data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_class_section($limit = 0, $offset = 0, $order_by = "section_id", $sort_order = "asc", $search_data) {
        $fields = $this->db->list_fields('course_section');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
        }
        $this->db->select('*');
        $this->db->from('course_section');        
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');        
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query;
        }
    }

    /**
     *
     * count_all_members: count all members in the table
     *
     *
     */
    
    public function count_all_class_section()
    {
        return $this->db->count_all_results('course_section');
    }
    
    public function count_all_class_section_grid($search_data)
    {
    	if (!empty($search_data)) {
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['ca_lead_teacher']) ? $data['ca_lead.first_name'] = $search_data['ca_lead_teacher'] : "";
			!empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
        }
        
    	$this->db->from('course_section');     
		$this->db->join('school_campus','school_campus.campus_id = camps_id','left');  
    	$this->db->join('users AS ca_lead','course_section.ca_lead_teacher = ca_lead.user_id','left');  
    	 !empty($data) ? $this->db->or_like($data) : "";
		if($this->session->userdata('role_id') > 4 && $this->session->userdata('ca_lead_teacher') == 0 && ($this->session->userdata('campus_id') > 0 || $this->session->userdata('campus') != ""))
		{
			$this->db->join('course_class','course_class.section_id = course_section.section_id','left');  
			$this->db->join('users','course_class.primary_teacher_id = users.user_id','left');  
			
			if($this->session->userdata('campus_id') > 0)
				$this->db->where('course_section.camps_id',$this->session->userdata('campus_id'));
			else if($this->session->userdata('campus') != "")
				$this->db->where('users.campus',$this->session->userdata('campus'));	
			
			$this->db->group_by(array("course_section.section_id"));		
		}
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		$query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
		return 0;
    }

    /**
     *
     * update_member: update member data
     *
     * @param int $id the member id
     * @param string $username the member username
     * @param string $email the member e-mail address
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param bool $change_username do we want to change the username?
     * @param bool $change_email do we want to change the user e-mail?
     * @return mixed
     *
     */

    public function update_class_section($section_id, $section_title) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'section_title'    => $section_title);

       
        $this->db->where('section_id', $section_id);
        $this->db->update('course_section', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_member: count all members in the table
     *
     * @param int $id the member id
     * @return boolean
     *
     */


    public function delete_class_section($section_id) {
        $this->db->where('section_id', $section_id);
        $this->db->delete('course_section');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * get_username_by_id: return username by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_section_title_by_id($section_id) {
        $this->db->select('section_title')->from('course_section')->where('section_id', $section_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->section_title;
        }
        return "";
    }

    
    /**
     *
     * count_all_search_members: count all members when performing search
     *
     * @param string $username the member username
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param string $email the member e-mail address 
     * @return mixed
     *
     */

    public function count_all_search_class_section($search_data) {
        $data = array();
        !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
             

        $this->db->select('*');
        $this->db->from('course_section');
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("course_section.section_id", "asc");
        return $this->db->count_all_results();
    }

    
    public function get_section_data($section_id){
    	$this->db->select('*')->from('course_section')->where('section_id', $section_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file list_members_model.php */