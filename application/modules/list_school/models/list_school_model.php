<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_school_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_school: get the members data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_school($limit = 0, $offset = 0, $order_by = "school_id", $sort_order = "asc", $search_data) {
        $fields = $this->db->list_fields('school');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['school_name']) ? $data['school_name'] = $search_data['school_name'] : "";
            !empty($search_data['principal']) ? $data['principal'] = $search_data['principal'] : "";
            !empty($search_data['email']) ? $data['email'] = $search_data['email'] : "";
            !empty($search_data['www_address']) ? $data['www_address'] = $search_data['www_address'] : "";
            !empty($search_data['address']) ? $data['address'] = $search_data['address'] : "";
            !empty($search_data['state']) ? $data['state'] = $search_data['state'] : "";
            !empty($search_data['zip']) ? $data['zip'] = $search_data['zip'] : "";
            !empty($search_data['area_code']) ? $data['area_code'] = $search_data['area_code'] : "";
            !empty($search_data['phone']) ? $data['phone'] = $search_data['phone'] : "";
        }
        $this->db->select('*');
        $this->db->from('school');        
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');        
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        $this->db->last_query();
        if($query->num_rows() > 0) {
            return $query;
        }
    }

    /**
     *
     * count_all_school: count all school in the table
     *
     *
     */
    
    public function count_all_school()
    {
        return $this->db->count_all_results('school');
    }

    /**
     *
     * update_school: update school data
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

    public function update_school($school_id, $school_name, $address, $city, $state, $zip, $area_code,$phone,$principal,$www_address,$email,$show_total_grade,$show_grade_range,$attendance_time_limit,$grade_time_limit) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(
                'school_id'       => $school_id,
                'school_name'    => $school_name,
				'address'	=> $address,
                'city'     => $city,
				'state'   => $state,
				'zip'      => $zip,
				'area_code'      => $area_code,
				'phone'          => $phone,
				'principal'         => $principal,
				'www_address'           => $www_address,
				'email'    => $email,
				'show_total_grade'    => $show_total_grade,
				'show_grade_range'    => $show_grade_range,
				'attendance_time_limit'    => $attendance_time_limit,
				'grade_time_limit'    => $grade_time_limit,
				'updated_date'   => date('Y-m-d H:i:s'));

       
        $this->db->where('school_id', $school_id);
        $this->db->update('school', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_school: count all school in the table
     *
     * @param int $id the member id
     * @return boolean
     *
     */


    public function delete_member($school_id) {
        $this->db->where('school_id', $school_id);
        $this->db->delete('school');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * get_school_name_by_id: return school name by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_school_name_by_id($school_id) {
        $this->db->select('school_name')->from('school')->where('school_id', $school_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->school_name;
        }
        return "";
    }

     /**
     *
     * count_all_search_school: count all school when performing search
     *
     * @param string $username the member username
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param string $email the member e-mail address 
     * @return mixed
     *
     */

    public function count_all_search_school($search_data) {
        $this->db->select('*');
        $this->db->from('school');
        //$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');
        !empty($search_data) ? $this->db->or_like($search_data) : "";
        return $this->db->count_all_results();
    }

	public function get_school_data($school_id){
    	$this->db->select('*')->from('school')->where('school_id', $school_id);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file list_school_model.php */