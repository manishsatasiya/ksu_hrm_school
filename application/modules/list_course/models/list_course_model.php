<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_course_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	public function delete_data($table,$where,$id) {
        $this->db->where($where, $id);
        $this->db->delete($table);
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }	
    /**
     *
     * get_course: get the course data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_course($limit = 0, $offset = 0, $order_by = "course_title", $sort_order = "asc", $search_data) {
		
		
        $fields = $this->db->list_fields('courses');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
            !empty($search_data['course_code']) ? $data['course_code'] = $search_data['course_code'] : "";
            !empty($search_data['max_hours']) ? $data['max_hours'] = $search_data['max_hours'] : "";
            !empty($search_data['total_hours_all_weeks']) ? $data['total_hours_all_weeks'] = $search_data['total_hours_all_weeks'] : "";
            
        }
        $this->db->select('*');
        $this->db->from('courses');        
        $this->db->join('courses_subject', 'courses_subject.course_subject_id = courses.course_subject_id','left');        
		$this->db->join('school', 'school.school_id = courses.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = courses.school_year_id','left');
		
		if($this->session->userdata('campus_id') > 0)
			$this->db->where('courses.camps_id',$this->session->userdata('campus_id'));
				
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
     * count_all_course: count all course in the table
     *
     *
     */
    
    public function count_all_course()
    {
        return $this->db->count_all_results('courses');
    }
    
    public function count_all_course_grid1($search_data)
    {
    	!empty($search_data) ? $this->db->or_like($search_data) : "";
		if($this->session->userdata('campus_id') > 0)
			$this->db->where('courses.camps_id',$this->session->userdata('campus_id'));
    	return $this->db->count_all_results('courses');
    }

    /**
     *
     * update_course: update course data
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

    public function update_course($course_id, $school_year_id, $school_id, $course_title,$max_hours) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'school_year_id'    => $school_year_id,
				'school_id'    => $school_id,
				'course_title'    => $course_title,
				'max_hours'    => $max_hours);

      
        $this->db->where('course_id', $course_id);
        $this->db->update('courses', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_course: delete course in the table
     *
     * @param int $id the member id
     * @return boolean
     *
     */


    public function delete_course($course_id) {
        $this->db->where('course_id', $course_id);
        $this->db->delete('courses');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * get_course_title_by_id: return course_title by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_course_title_by_id($course_id) {
        $this->db->select('course_title')->from(' courses')->where('course_id', $course_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->course_title;
        }
        return "";
    }

    
    /**
     *
     * count_all_search_course: count all course when performing search
     *
     * @param string $username the member username
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param string $email the member e-mail address 
     * @return mixed
     *
     */

    public function count_all_search_course($search_data) {
        $data = array();
        !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
        !empty($search_data['course_code']) ? $data['course_code'] = $search_data['course_code'] : "";        

        $this->db->select('*');
        $this->db->from('courses');
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("courses.course_id", "asc");
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

    public function get_section_data($course_id){
    	$this->db->select('*')->from('courses')->where('course_id', $course_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file list_course_model.php */