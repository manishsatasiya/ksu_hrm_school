<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_school_campus_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_grade_range: get the course cateory data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_campus($limit = 0, $offset = 0, $order_by = "campus_id", $sort_order = "asc", $search_data,$count = false) {
        $fields = $this->db->list_fields('school_campus');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
            !empty($search_data['campus_location']) ? $data['campus_location'] = $search_data['campus_location'] : "";
            
        }
        $this->db->select('*');
        $this->db->from('school_campus');        
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');        
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
		if($count == false)
       		$this->db->limit($limit, $offset);

        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query;
        }
    }

    /**
     *
     * count_all_grade_range: count all course category in the table
     *
     *
     */
    
    public function count_all_campus()
    {
        return $this->db->count_all_results('school_campus');
    }
    
    public function count_all_campus_grid($search_data)
    {
    	!empty($search_data) ? $this->db->or_like($search_data) : "";
    	return $this->db->count_all_results('school_campus');
    }

    /**
     *
     * update_course_category: update course category data
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

    public function update_campus($campus_id, $grade_name, $category_code) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'grade_name'    => $grade_name);

       
        $this->db->where('campus_id', $campus_id);
        $this->db->update('school_campus', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_course_category: delete course category in the table
     *
     * @param int $id the member id
     * @return boolean
     *
     */


    public function delete_campus($campus_id) {
        $this->db->where('campus_id', $campus_id);
        $this->db->delete('school_campus');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * get_course_title_by_id: return grade_name by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_course_title_by_id($campus_id) {
        $this->db->select('grade_name')->from('school_campus')->where('campus_id', $campus_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->grade_name;
        }
        return "";
    }

   
    /**
     *
     * count_all_search_grade_range: count all course category when performing search
     *
     * @param string $username the member username
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param string $email the member e-mail address 
     * @return mixed
     *
     */

    public function count_all_search_campus($search_data) {
        $data = array();
        !empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
        !empty($search_data['campus_location']) ? $data['campus_location'] = $search_data['campus_location'] : "";
        

        $this->db->select('*');
        $this->db->from('school_campus');
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("school_campus.campus_id", "asc");
        return $this->db->count_all_results();
    }

    
    /**
     *
     * toggle_active: (de)activate member
     *
     * @param int $id the member id
     * @param string $active activate or deactivate?
     * @return boolean
     *
     */

    public function toggle_active($id, $active) {
        $data = array('active' => ($active ? '0' : '1'));
        $this->db->where('user_id', $id);
        $this->db->update('users', $data);                  
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;

    }

    public function add_course_category($grade_name){
    	$data = array(
    			'grade_name' => $grade_name
    	);
    	
    	$this->db->insert('school_campus', $data);
    	$lastinsertid = $this->db->insert_id();
    	if ($this->db->affected_rows() == 1) {
    		return $lastinsertid;
    	}
    	return false;
    }
    
    public function update_course_cat($id,$columnName,$value){
    	$data = array(
    			$columnName    => $value);
    	
    	
    	$this->db->where('campus_id', $id);
    	$this->db->update('school_campus', $data);
    	
    	
    }
    public function get_campus_data($campus_id){
    	$this->db->select('*')->from('school_campus')->where('campus_id', $campus_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
	
	 public function update_user_campus($id,$data_user){
	 	$this->db->where('campus_id', $id);
		$this->db->update('users', $data_user);
	 }
    
}

/* End of file list_course_category_model.php */