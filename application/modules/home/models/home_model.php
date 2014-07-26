<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    public function get_staff_count() {
		
		$this->db->from('users');
		$this->db->where_not_in('users.user_roll_id',array('1','4'));
		$this->db->where_in('active',array(2,1));
		return $this->db->count_all_results();
    }
	
	public function get_pending_count() {
		
		$this->db->from('users');
		$this->db->where_not_in('users.user_roll_id',array('1','4'));
		$this->db->where('active',3);
		return $this->db->count_all_results();
    }
	
	public function get_block_count() {
		
		$this->db->from('users');
		$this->db->where_not_in('users.user_roll_id',array('1','4'));
		$this->db->where('active',2);
		return $this->db->count_all_results();
    }
	
	public function get_teacher_count($campus,$contractor,$role = '',$banned_teacher = 1,$gender = 'M') {
		
		$this->db->from('users');
		$this->db->join('user_profile','users.user_id=user_profile.user_id','left');
		$this->db->where_in('active',array(1,2));
		
		if($campus!=false)
			$this->db->where('campus_id',$campus);
		if($contractor!=false)	
			$this->db->where('user_profile.contractor',$contractor);
			
		if(is_array($role) && count($role) > 0){
			$this->db->where_in('users.user_roll_id',$role);
		} else if($role <> '') {
			$role = explode(',',$role);
			$this->db->where_not_in('users.user_roll_id',$role);
		}	 	
		if($banned_teacher!=false)
			$this->db->where('user_profile.banned_teacher',$banned_teacher);
		if($gender!=false)
			$this->db->where('gender',$gender);
		
		$count = $this->db->count_all_results();
		//echo $this->db->last_query();	
		//exit;
		return $count;
    }
	
	public function departure_stats() 
    {
		$this->db->select('COUNT(DISTINCT(users.user_id)) as count, contractors.contractor as contractor_name,user_profile.resignation_resons',FALSE);
		$this->db->from('users');
		$this->db->join('user_profile','users.user_id=user_profile.user_id','left');
		$this->db->join('contractors','user_profile.contractor=contractors.id','left');
		$this->db->where_not_in('users.user_roll_id',array('1','4'));
		$this->db->where('users.active','0');
		$this->db->group_by('user_profile.contractor, user_profile.resignation_resons');
		
        $query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
    }
	
	public function new_users() 
    {
		$this->db->select('users.user_id,users.elsd_id,users.first_name,users.last_name,users.gender,school_campus.campus_name',FALSE);
		$this->db->join('school_campus','users.campus_id=school_campus.campus_id','left');
		$this->db->where_in('active',array(1,2));
		$this->db->order_by('users.created_date', 'desc');
		$this->db->limit(7, 0);
		$this->db->from('users');
		
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
	}
	
	public function get_nationality() 
    {
		$query = "SELECT 
						'UK' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` = 'British'
				 UNION
				 SELECT 
						'Ireland' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` = 'Irish'
				 UNION
				 SELECT 
						'America' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` = 'American'
				 UNION
				 SELECT 
						'Canada' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` = 'Canadian'
				 UNION
				 SELECT 
						'Australia' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` = 'Australian'
				 UNION
				 SELECT 
						'New Zealand' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` = 'New Zealander'
				 UNION
				 SELECT 
						'Saudi Arabia' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` = 'Saudi Arabian'
				 UNION
				 SELECT 
						'Native Total' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` IN('British','Irish','American','Canadian','Australian','New Zealander')
				 UNION
				 SELECT 
						'Non native Total' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 LEFT JOIN `countries` ON `user_profile`.`nationality`=`countries`.`id` 
				 WHERE `active` IN (1, 2) 
				 AND `countries`.`nationality` NOT IN('British','Irish','American','Canadian','Australian','New Zealander')
				 UNION
				 SELECT 
						'Unknown' as nationality, 
						SUM(IF(gender='M', 1, 0)) as male_count, 
						SUM(IF(gender='F', 1, 0)) as female_count, 
						COUNT(*) as total 
				 FROM `users` 
				 LEFT JOIN `user_profile` ON `user_profile`.`user_id`=`users`.`user_id` 
				 WHERE `active` IN (1, 2) 
				 AND `user_profile`.`nationality` = ''
				 ";
		
		$query = $this->db->query($query);
		
    	//echo $this->db->last_query();
		//exit;
		if($query->num_rows() > 0) {
    		return $query;
    	}
		
	}

}

/* End of file home_model.php */
