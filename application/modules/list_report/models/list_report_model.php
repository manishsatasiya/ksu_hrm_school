<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_activity_log: get the log data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_activity_log($limit = 0, $offset = 0, $order_by = "user_activity_id", $sort_order = "asc", $search_data) {
        $fields = $this->db->list_fields('user_activity_log');
        if (!empty($search_data)) {
            !empty($search_data['parent_menu']) ? $data['parent_menu'] = $search_data['parent_menu'] : "";            
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";            
            !empty($search_data['user_activity_id']) ? $data['user_activity_id'] = $search_data['user_activity_id'] : "";            
        }
        $this->db->select('*,users.first_name,user_activity_log.created_date');
        $this->db->from('user_activity_log');        
        $this->db->join('users', 'users.user_id = user_activity_log.user_id');        
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        if($query->num_rows() > 0) {
            return $query;
        }
    }
	
	public function get_viewactivity_log($user_activity_id) {
        $this->db->select('data_array,tablename');
        $this->db->from('user_activity_log');        
        $this->db->where('user_activity_id',$user_activity_id);        
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    /**
     *
     * count_all_report: count all log in the table
     *
     *
     */
    
    public function count_all_report($search_data)
    {
       if (!empty($search_data)) {
            !empty($search_data['parent_menu']) ? $data['parent_menu'] = $search_data['parent_menu'] : "";            
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";            
            !empty($search_data['user_activity_id']) ? $data['user_activity_id'] = $search_data['user_activity_id'] : "";            
        }
        $this->db->select('*,users.first_name,user_activity_log.created_date');
        $this->db->from('user_activity_log');        
        $this->db->join('users', 'users.user_id = user_activity_log.user_id');        
        !empty($data) ? $this->db->or_like($data) : "";
        
        $query = $this->db->get();
		return $query->num_rows();
    }

   

    /**
     *
     * get_parent_menu_by_id: return parent_menu by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_parent_menu_by_id($user_activity_id) {
        $this->db->select('parent_menu')->from('user_activity_log')->where('user_activity_id', $user_activity_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->parent_menu;
        }
        return "";
    }

   

    /**
     *
     * count_all_search_report: count all report when performing search
     *
     * @param string $username the member username
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param string $email the member e-mail address 
     * @return mixed
     *
     */

    public function count_all_search_report($search_data) {
        $data = array();
        !empty($search_data['parent_menu']) ? $data['parent_menu'] = $search_data['parent_menu'] : "";
        
        $this->db->select('*');
        $this->db->from('user_activity_log');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("school.user_activity_id", "asc");
        return $this->db->count_all_results();
    }
}

/* End of file list_report_model.php */