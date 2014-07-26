<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_role_model extends CI_Model {

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

    public function get_role($limit = 0, $offset = 0, $order_by = "user_roll_name", $sort_order = "asc", $search_data) {
        $fields = $this->db->list_fields('user_roll');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['user_roll_name']) ? $data['user_roll_name'] = $search_data['user_roll_name'] : "";
            
            
        }
        $this->db->select('*');
        $this->db->from('user_roll');        
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query;
        }
    }

    
    
    public function count_all_role_grid($search_data)
    {
    	!empty($search_data) ? $this->db->or_like($search_data) : "";
    	return $this->db->count_all_results('user_roll');
    }

   
    public function get_role_data($user_roll_id){
    	$this->db->select('*')->from('user_roll')->where('user_roll_id', $user_roll_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file list_members_model.php */