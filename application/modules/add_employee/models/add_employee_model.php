<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_Employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_employee_data($user_id) {
		$this->db->select('users.*,users.user_id as user_unique_id,user_profile.*');
    	$this->db->from('users');
		$this->db->join('user_profile', 'user_profile.user_id = users.user_id','left');
    	$this->db->where('users.user_id',$user_id);
    	$query = $this->db->get();
		//echo $this->db->last_query();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_cv_reference($user_id) {
		$this->db->select('user_cv_reference.*');
    	$this->db->from('user_cv_reference');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		//echo $this->db->last_query();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_quali_certi($id,$type) {
		$this->db->select('user_qualification.*');
    	$this->db->from('user_qualification');
		$this->db->where('type',$type);
		$this->db->where('user_qualification_id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_emergency_contact_data($id) {
		$this->db->select('emergency_contacts.*');
    	$this->db->from('emergency_contacts');
		$this->db->where('emergency_contact_id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_emergency_contacts($user_id) {
		$this->db->select('emergency_contacts.*,countries.country as country_name');
    	$this->db->from('emergency_contacts');
		$this->db->join('countries', 'countries.id = emergency_contacts.country','left');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_user_quali_certi($user_id,$type) {
		$this->db->select('user_qualification.*,qualifications.qualification');
    	$this->db->from('user_qualification');
		$this->db->join('qualifications', 'qualifications.id = user_qualification.qualification_id','left');
		$this->db->where('user_id',$user_id);
		$this->db->where('user_qualification.type',$type);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_user_experience($user_id) {
		$this->db->select('user_workhistory.*');
    	$this->db->from('user_workhistory');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_user_workshop($user_id) {
		$this->db->select('workshops.title,workshops.topic,workshops.start_date,CONCAT(users.first_name,users.last_name) as presenter_name,workshop_types.type',false);
    	$this->db->from('workshops');
		$this->db->join('user_workshop', 'user_workshop.workshop_id = workshops.workshop_id','left');
		$this->db->join('users', 'workshops.presenter = users.user_id','left');
		$this->db->join('workshop_types', 'workshop_types.id = workshops.workshop_type_id','left');
		$this->db->where('user_workshop.attendance',1);
		$this->db->where('user_workshop.attendee',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_experience($id) {
		$this->db->select('user_workhistory.*');
    	$this->db->from('user_workhistory');
		$this->db->where('user_workhistory_id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_user_permossion($user_id) {
		$this->db->select('user_permissions.*');
    	$this->db->from('user_permissions');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_user_documents($user_id) {
		$this->db->select('profile_certificate.*');
    	$this->db->from('profile_certificate');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		$arrData = array();
		
    	if($query->num_rows() > 0) {
    		foreach($query->result_array() AS $data)
			{
				$arrData[$data["certificate_type"]] = $data["certificate_file"];
			}
    	}
		
		return $arrData;
	}
	
	public function delete_user_document($user_id,$certificate_type) {
        $this->db->where('user_id', $user_id);
        $this->db->where('certificate_type', $certificate_type);
        $this->db->delete('profile_certificate');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
	
	public function update_user_profileid($userid, $table, $table_profile_field='profile_id', $table_where_field='user_id') {
        $data = array($table_profile_field => 'user_profile_id');

        $this->db->where($table_where_field, $userid);
        $this->db->update($table, $data);
		$this->db->join('user_profile', 'user_profile.user_id = '.$table.$table_where_field,'left');
    }
}

/* End of file list_user_model.php */
