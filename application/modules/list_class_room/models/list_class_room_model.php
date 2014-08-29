<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_class_room_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_class_room: get the class room data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_class_room($limit = 0, $offset = 0, $order_by = "class_room_id", $sort_order = "asc", $search_data) {
		$arrCampusPrivilages = get_user_campus_privilages();
        $fields = $this->db->list_fields('course_class_room');
        //if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
            !empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
            
        }
        $this->db->select('course_class_room.*,campus_name',FALSE);
        $this->db->from('course_class_room');        
		$this->db->join('school_campus','school_campus.campus_id = camps_id','left');  
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');        
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

        if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->join('course_class','course_class.class_room_id = course_class_room.class_room_id','left');  
			$this->db->join('users','course_class.primary_teacher_id = users.user_id','left');  
			
			$this->db->where_in('course_class_room.camps_id',$arrCampusPrivilages);
			
			$this->db->group_by(array("course_class_room.class_room_id"));
		}
		
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query;
        }
    }

    /**
     *
     * count_all_class_room: count all class room in the table
     *
     *
     */
    
    public function count_all_class_room()
    {
        return $this->db->count_all_results('course_class_room');
    }
    
    public function count_all_class_room_grid($search_data)
    {
		$arrCampusPrivilages = get_user_campus_privilages();
    	if (!empty($search_data)) {
            !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
			!empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
        }
        $this->db->select('*');
        $this->db->from('course_class_room');      
		$this->db->join('school_campus','school_campus.campus_id = camps_id','left');  		
        !empty($data) ? $this->db->or_like($data) : "";
        
        if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->join('course_class','course_class.class_room_id = course_class_room.class_room_id','left');  
			$this->db->join('users','course_class.primary_teacher_id = users.user_id','left');  
			
			$this->db->where_in('course_class_room.camps_id',$arrCampusPrivilages);
			
			$this->db->group_by(array("course_class_room.class_room_id"));	
		}
		
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
		return 0;
    }

    /**
     *
     * update_class_room: update class room data
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

    public function update_class_room($class_room_id, $class_room_title) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'class_room_title'    => $class_room_title);

       
        $this->db->where('class_room_id', $class_room_id);
        $this->db->update('course_class_room', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_member: class room in the table
     *
     * @param int $id the member id
     * @return boolean
     *
     */


    public function delete_class_room($class_room_id) {
        $this->db->where('class_room_id', $class_room_id);
        $this->db->delete('course_class_room');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * get_room_title_by_id: return class_room_title by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_room_title_by_id($class_room_id) {
        $this->db->select('class_room_title')->from('course_class_room')->where('class_room_id', $class_room_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->class_room_title;
        }
        return "";
    }

    
    /**
     *
     * count_all_search_class_room: count all class room when performing search
     *
     * @param string $username the member username
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param string $email the member e-mail address 
     * @return mixed
     *
     */

    public function count_all_search_class_room($search_data) {
        $data = array();
        !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
             

        $this->db->select('*');
        $this->db->from('course_class_room');
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("course_class_room.class_room_id", "asc");
        return $this->db->count_all_results();
    }

    public function get_class_room_data($class_room_id){
    	$this->db->select('*')->from('course_class_room')->where('class_room_id', $class_room_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }

}

/* End of file list_class_room_model.php */