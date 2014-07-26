<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bug_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        	$this->load->helper('password');
        }

	function Bug_model() {
        parent::Model();
        
        $this->bug_img_path = realpath(APPPATH . '../images');
        $this->bug_img_path_url = base_url().'images/';
    }
   

    public function add_bugs($bug_title, $description, $attachment) {
    	$user_id = $this->session->userdata('user_id');
    	
        $data = array(
        	'user_id' => $user_id,	
            'bug_title' => $bug_title, 
			'description' => $description,
        	'created_date' => date('Y-m-d H:i:s')
        );
        $this->db->insert('system_bugs', $data);
        $lastinsertid = $this->db->insert_id();
        
        $curr_dir = str_replace("\\","/",getcwd()).'/';
        $destinationpath = $curr_dir.'images/bug_images/';
        if ($lastinsertid) {
        	if(count($attachment['attchment']['tmp_name']) > 0){
        		for ($i =0;$i<count($attachment['attchment']['tmp_name']);$i++){
        			if($attachment['attchment']['tmp_name'][$i] != ''){
        				$filename = time().'_'.$attachment['attchment']['name'][$i];
        				
        				if(move_uploaded_file($attachment['attchment']['tmp_name'][$i], $destinationpath.$filename)){
        					$dataimage = array(
        							'system_bug_id' => $lastinsertid,
        							'image_path' => $filename
        					);
        					$this->db->insert('system_bugs_image', $dataimage);
        					
        				}
        				
        			}
        		}
        	}
           
        } 
        
        return $lastinsertid;
    }
    
    public function get_bug($limit = 0, $offset = 0, $order_by = "system_bugs.system_bug_id", $sort_order = "asc", $search_data) {
    	$fields = $this->db->list_fields('system_bugs');
    	if (!empty($search_data)) {
    		!empty($search_data['bug_title']) ? $data['bug_title'] = $search_data['bug_title'] : "";
    		
    	}
    	$this->db->select('system_bugs.*,users.*,system_bugs_image.image_path,system_bugs_image.bug_image_id');
    	$this->db->from('system_bugs');
    	$this->db->join('users', 'users.user_id = system_bugs.user_id','left');
    	$this->db->join('system_bugs_image', 'system_bugs_image.system_bug_id = system_bugs.system_bug_id','left');
    	!empty($data) ? $this->db->or_like($data) : "";
    	$this->db->order_by($order_by, $sort_order);
    	$this->db->limit($limit, $offset);
    
    	$query = $this->db->get();
    	
    	if($query->num_rows() > 0) {
    		return $query;
    	}
    }
	
    public function count_all_bug()
    {
    	return $this->db->count_all_results('system_bugs');
    }

    public function get_bug_title_by_id($system_bug_id) {
    	$this->db->select('bug_title')->from('system_bugs')->where('system_bug_id', $system_bug_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row->bug_title;
    	}
    	return "";
    }
    
    public function count_all_search_bug($search_data) {
    	$data = array();
    	!empty($search_data['bug_title']) ? $data['bug_title'] = $search_data['bug_title'] : "";
    	
    	$this->db->select('*');
    	$this->db->from('system_bugs');

    	!empty($data) ? $this->db->or_like($data) : "";
    	$this->db->order_by("system_bugs.system_bug_id", "asc");
    	return $this->db->count_all_results();
    }
    
    public function toggle_active($id, $active) {
    	
    	if($active == 'open'){
    		$active1 = 'close';
    	}
    	if($active == 'close'){
    		$active1 = 'open';
    	}
    	
    	$data = array('status' => $active1);
    	$this->db->where('system_bug_id', $id);
    	$this->db->update('system_bugs', $data);
    	
    	if($this->db->affected_rows() == 1) {
    		return true;
    	}
    	return false;
    
    }
    
    function save_comment($system_bug_id,$comment){
    	$user_id = $this->session->userdata('user_id');
    	 
    	$data = array(
    			'user_id' => $user_id,
    			'comment' => $comment,
    			'system_bug_id' => $system_bug_id, 
    			'created_date' => date('Y-m-d H:i:s')
    	);
    	$this->db->insert('bugs_comment', $data);
    	$lastinsertid = $this->db->insert_id();
    	if($lastinsertid){
    		return $lastinsertid;
    	}else{
    		return false;
    	}
    }
	
	function get_bugs_comment($system_bug_id){
		$this->db->select('*');
		$this->db->from('bugs_comment');
		$this->db->where('system_bug_id',$system_bug_id);
		return $query = $this->db->get();
	}
	
	function count_bugs_comment($system_bug_id){
		$this->db->select('*');
		$this->db->from('bugs_comment');
		$this->db->where('system_bug_id',$this->input->post('id'));
		return $this->db->count_all_results();
	}
	
	function get_bug_data($system_bug_id){
		$this->db->select('*');
		$this->db->from('system_bugs');
		$this->db->join('users', 'users.user_id = system_bugs.user_id','left');
		$this->db->where('system_bug_id',$system_bug_id);
		return $this->db->get();
	}
	
	function get_bug_image($system_bug_id){
		$this->db->select('*');
		$this->db->from('system_bugs_image');
		$this->db->where('system_bug_id',$system_bug_id);
		return $this->db->get();
	}
	
	function get_bugcomment_data($id){
		$this->db->select('bugs_comment.*,users.first_name');
		$this->db->from('bugs_comment');
		$this->db->join('users', 'users.user_id = bugs_comment.user_id','left');
		$this->db->where('system_bug_id',$id);
		return $this->db->get();
	}
	
	function get_postbugcomment_data($id){
		$this->db->select('bugs_comment.*,users.first_name');
		$this->db->from('bugs_comment');
		$this->db->join('users', 'users.user_id = bugs_comment.user_id','left');
		$this->db->where('comment_id',$id);
		return $this->db->get();
	}
}

/* End of file bug_model.php */