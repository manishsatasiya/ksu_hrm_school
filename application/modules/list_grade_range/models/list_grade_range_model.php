<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_grade_range_model extends CI_Model {

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

    public function get_grade_range($limit = 0, $offset = 0, $order_by = "grade_range_id", $sort_order = "asc", $search_data) {
        $fields = $this->db->list_fields('grade_range');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['grade_min_range']) ? $data['grade_min_range'] = $search_data['grade_min_range'] : "";
            !empty($search_data['grade_max_range']) ? $data['grade_max_range'] = $search_data['grade_max_range'] : "";
            !empty($search_data['grade_name']) ? $data['grade_name'] = $search_data['grade_name'] : "";
        }
        $this->db->select('*');
        $this->db->from('grade_range');    
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
     * count_all_grade_range: count all course category in the table
     *
     *
     */
    
    public function count_all_grade_range()
    {
        return $this->db->count_all_results('grade_range');
    }
    
    public function count_all_grade_range_grid($search_data)
    {
    	!empty($search_data) ? $this->db->or_like($search_data) : "";
    	return $this->db->count_all_results('grade_range');
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

    public function update_course_category($grade_range_id, $grade_name, $category_code) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'grade_name'    => $grade_name);

       
        $this->db->where('grade_range_id', $grade_range_id);
        $this->db->update('grade_range', $data);

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


    public function delete_course_category($grade_range_id) {
        $this->db->where('grade_range_id', $grade_range_id);
        $this->db->delete('grade_range');
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

    public function get_course_title_by_id($grade_range_id) {
        $this->db->select('grade_name')->from('grade_range')->where('grade_range_id', $grade_range_id);
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

    public function count_all_search_grade_range($search_data) {
        $data = array();
        !empty($search_data['grade_min_range']) ? $data['grade_min_range'] = $search_data['grade_min_range'] : "";
        !empty($search_data['grade_max_range']) ? $data['grade_max_range'] = $search_data['grade_max_range'] : "";
        !empty($search_data['grade_name']) ? $data['grade_name'] = $search_data['grade_name'] : "";
        

        $this->db->select('*');
        $this->db->from('grade_range');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("grade_range.grade_range_id", "asc");
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
    	
    	$this->db->insert('grade_range', $data);
    	$lastinsertid = $this->db->insert_id();
    	if ($this->db->affected_rows() == 1) {
    		return $lastinsertid;
    	}
    	return false;
    }
    
    public function update_course_cat($id,$columnName,$value){
    	$data = array(
    			$columnName    => $value);
    	
    	
    	$this->db->where('grade_range_id', $id);
    	$this->db->update('grade_range', $data);
    	
    	
    }
    public function get_course_category_data($grade_range_id){
    	$this->db->select('*')->from('grade_range')->where('grade_range_id', $grade_range_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
    
}

/* End of file list_course_category_model.php */