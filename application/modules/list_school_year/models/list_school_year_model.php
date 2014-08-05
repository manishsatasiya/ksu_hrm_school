<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_school_year_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_members: get the school year data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_school_year($limit = 0, $offset = 0, $order_by = "school_year_id", $sort_order = "asc", $search_data) {
        $fields = $this->db->list_fields('school_year');
		
        //if (!in_array($order_by, $fields)) return array();

        if (!empty($search_data)) {
            !empty($search_data['school_year']) ? $data['school_year'] = $search_data['school_year'] : "";
            !empty($search_data['school_year_title']) ? $data['school_year_title'] = $search_data['school_year_title'] : "";
            !empty($search_data['school_type']) ? $data['school_type'] = $search_data['school_type'] : "";
            
        }
		
        $this->db->select('*');
        $this->db->from('school_year');        
        $this->db->join('school', 'school.school_id = school_year.school_id');        
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
     * count_all_school_year: count all school year in the table
     *
     *
     */
    
    public function count_all_school_year()
    {
        return $this->db->count_all_results('school_year');
    }

    /**
     *
     * update_school_year: update school year data
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

    public function update_school_year($school_year_id,$school_id, $school_year, $school_year_title, $school_type, $school_week) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(
                'school_id'       => $school_id,
                'school_year'    => $school_year,
				'school_year_title'	=> $school_year_title,
                'school_type'     => $school_type,
				'school_week'   => $school_week);

       
        $this->db->where('school_year_id', $school_year_id);
        $this->db->update('school_year', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_school_year: count all school year in the table
     *
     * @param int $school_year_id the school year id
     * @return boolean
     *
     */


    public function delete_school_year($school_year_id) {
        $this->db->where('school_year_id', $school_year_id);
        $this->db->delete('school_year');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * get_school_year_by_id: return school year by school_year_id
     *
     * @param int $school_year_id the school_year_id
     * @return mixed
     *
     */

    public function get_school_year_by_id($school_year_id) {
        $this->db->select('school_year')->from('school_year')->where('school_year_id', $school_year_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->school_year;
        }
        return "";
    }

    
    /**
     *
     * count_all_search_school_year: count all members when performing search
     *
     * @param string $school_year the school year
     * @param string $school_year_title the school year title
     * @param string $school_type the member school type
     * @return mixed
     *
     */

    public function count_all_search_school_year($search_data) {
        $this->db->select('*');
        $this->db->from('school_year');
        $this->db->join('school', 'school.school_id = school_year.school_id');
        !empty($search_data) ? $this->db->or_like($search_data) : "";
        return $this->db->count_all_results();
    }
    
    /**
     *
     * add_enable_week: add enable week for school 
     *
     * @param string $weeks
     * @param string $last_date
     * @param string $time
     * @param string $school_year_id
     * @return mixed
     *
     */
	 public function add_enable_week($weeks,$last_date,$time,$school_year_id,$no_of_day){
    	 
    	$this->db->where('school_year_id', $school_year_id);
    	$this->db->delete('enable_school_week');
    	if($weeks){
    		foreach($weeks as $week){
			$no_of_dayweek = 5;
			
				if(isset($no_of_day["no_of_day_".$week]))
				{	
					$no_of_dayweek = $no_of_day["no_of_day_".$week];
				}
    			$data = array(
    					'school_year_id' => $school_year_id,
    					'week_id' => $week,
    					'last_date' => date('Y-m-d',strtotime($last_date[$week])),
						'no_of_day' => $no_of_dayweek
    			);
    			$this->db->insert('enable_school_week', $data);
    		}
    		set_activity_log($school_year_id,'add','school','list enable week');
    	}
    	 
    	return true;
    }
    
    
    /**
     *
     * add_enable_time: add enable time for school
     *
     * @param string $am_time
     * @param string $pm_time
     * @return mixed
     *
     */
    public function add_enable_time($am_start_time,$am_time,$pm_start_time,$pm_time) {
    	// if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.
    	$this->db->truncate('enable_school_time');
    	$data = array(
    			'am_start_time'    => $am_start_time,
    			'am_time'    => $am_time,
    			'pm_start_time'    => $pm_start_time,
    			'pm_time'    => $pm_time);
    
    	$this->db->insert('enable_school_time', $data);
    
    	if($this->db->affected_rows() == 1) {
    		return true;
    	}
    	return false;
    }
    
	/**
     *
     * attendance_week_activation_time: add enable time for attendance
     *
     * @param string $activation_time
     * @return mixed
     *
     */
    public function attendance_week_activation_time($activation_time) {
    	// if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.
    	$this->db->truncate('attendance_week_activation_time');
    	$data = array('activation_time'    => $activation_time);
    
    	$this->db->insert('attendance_week_activation_time', $data);
    
    	if($this->db->affected_rows() == 1) {
    		return true;
    	}
    	return false;
    }
	
    public function get_school_year_data($school_year_id){
    	$this->db->select('*')->from('school_year')->where('school_year_id', $school_year_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
	
	public function get_attendance_pdf_settings(){
    	$this->db->select('*')->from('weekly_attendance_pdf_settings');
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
	
	public function add_attendance_pdf_settings($day1,$day2,$day3,$day4,$day5,$date_title,$export_type) {
		$user_id = $this->session->userdata('user_id');
    	// if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.
    	$this->db->truncate('weekly_attendance_pdf_settings');
    	$data = array(
				'day1' => $day1,
				'day2' => $day2,
				'day3' => $day3,
				'day4' => $day4,
				'day5' => $day5,
				'date_title' => $date_title,
				'export_type' => $export_type,
				'created_date' => date('Y-m-d h:i:s'),
				'updated_by' => $user_id,
				'active' => "Y");
    
    	$this->db->insert('weekly_attendance_pdf_settings', $data);
    
    	if($this->db->affected_rows() == 1) {
    		return true;
    	}
    	return false;
    }
	
	public function get_enable_school_week($school_year_id){
		$this->db->select('*');
		$this->db->from('enable_school_week');
		$this->db->where('school_year_id',$school_year_id);
		return $this->db->get();
	}
	
	public function get_enable_school_time(){
		$this->db->select('*');
        $this->db->from('enable_school_time');
		return $this->db->get();
	}
	
	public function get_activation_time(){
		$this->db->select('*');
        $this->db->from('attendance_week_activation_time');
        return $this->db->get();
	}
	
	public function save_elsd_id_setting($elsd_year,$elsd_number) {

    	$data = array(
				'elsd_year' => $elsd_year,
				'elsd_number' => $elsd_number,
				'elsd_flag' => 1);
    	
		$this->db->where('school_id', 1);
		$this->db->update('school', $data);
		   
    	if($this->db->affected_rows() == 1) {
    		return true;
    	}
    	return false;
    }
}

/* End of file list_school_year_model.php */