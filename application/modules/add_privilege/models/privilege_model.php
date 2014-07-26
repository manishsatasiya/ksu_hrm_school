<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privilege_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        }

    /**
     *
     * create_school
     *
     * @param string $school_name
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $area_code
     * @param string $phone
     * @param string $principal
     * @param string $www_address
     * @param string $email
     * @return mixed
     *
     */

    public function create_privilege($user_roll_id, $action) {
    	$this->db->where('user_roll_id',$user_roll_id);
    	$delete = $this->db->delete('user_privilege');
    	
		for($i=0;$i<count($action);$i++){
			if($action[$i]){
				$actArr = explode('_', $action[$i]);
				$menu_id = $actArr[0];
				$rights = $actArr[1];
				
				$this->db->select('*');
				$this->db->from('menu_action');
				$this->db->where('menu_id',$menu_id);
				$this->db->where('rights',$rights);
				$query = $this->db->get();
				$menu_action = $query->result_array();
				if($menu_action){
					foreach ($menu_action as $menu_actions){
						
						$data = array(
								'user_roll_id' => $user_roll_id,
								'menu_action_id' => $menu_actions['menu_action_id']
						);
						$this->db->insert('user_privilege', $data);
					}
				}
				
			}
	   	
		}
		return true;
    }
	
    /**
     *
     * get_menu_actions
     *
     * @return mixed
     *
     */
	public function get_menu_actions() {
		$this->db->select('*');
		$this->db->from('menu_master');
		$this->db->where('parent_id !=','0');
		$query = $this->db->get();
		$menu_data = $query->result_array();
		$i = 0;
		foreach ($menu_data as $menu_datas){
			$this->db->select('*');
			$this->db->from('menu_action');
			$this->db->where('menu_id',$menu_datas['menu_id']);
			$querys = $this->db->get();
			$menu_data[$i]['actions'] = $querys->result_array();
			$i++;
		}
        
        return $menu_data;
        
    }

    
    public function get_existing_privilege($user_roll_id) {
    	$this->db->select('*');
    	$this->db->from('user_privilege');
    	$this->db->where('user_roll_id',$user_roll_id);
    	$query = $this->db->get();
    	$menu_data = $query->result_array();
    	
    	$user_roll_id = array();
    	$i = 0;
    	$action_arr = array();
    	foreach ($menu_data as $menu_datas){
    		$this->db->select('*');
    		$this->db->from('menu_action');
    		$this->db->where('menu_action_id',$menu_datas['menu_action_id']);
    		$querys = $this->db->get();
    		$menuaction = $querys->result_array();
    		foreach ($menuaction as $menuactions){
    			$action_arr[] = $menuactions['menu_id'].'_'.$menuactions['rights'];
    		}
    		$i++;
    	}
    	return json_encode($action_arr);
    
    }
	
	public function create_user_privilege($user_id, $action) {
    	for($i=0;$i<count($action);$i++){
			if($action[$i]){
				$actArr = explode('_', $action[$i]);
				$menu_id = $actArr[0];
				$rights = $actArr[1];
				
				$this->db->select('*');
				$this->db->from('menu_action');
				$this->db->where('menu_id',$menu_id);
				$this->db->where('rights',$rights);
				$query = $this->db->get();
				$menu_action = $query->result_array();
				if($menu_action){
					foreach ($menu_action as $menu_actions){
						
						$data = array(
								'user_id' => $user_id,
								'menu_action_id' => $menu_actions['menu_action_id']
						);
						
						$this->db->insert('user_custom_privilege', $data);
					}
				}
			}
		}
		return true;
    }
	
	public function get_user_menu_action_privilege($user_id) {
    	$this->db->select('menu_action.menu_id,menu_action.rights');
    	$this->db->from('user_custom_privilege');
    	$this->db->join('menu_action','menu_action.menu_action_id = user_custom_privilege.menu_action_id','left');
		$this->db->where('user_id',$user_id);
    	$query = $this->db->get();
		$menu_data = array();
		if($query->num_rows() > 0)
        {
			foreach($query->result_array() as $menu) {
				$menu_data[$menu['menu_id']][] = $menu['rights'];
			}
		}
    	return $menu_data;
    }
	
	public function get_user_existing_privilege($user_id) {
    	$this->db->select('*');
    	$this->db->from('user_custom_privilege');
    	$this->db->where('user_id',$user_id);
    	$query = $this->db->get();
    	$menu_data = $query->result_array();
    	
    	$user_roll_id = array();
    	$i = 0;
    	$action_arr = array();
    	foreach ($menu_data as $menu_datas){
    		$this->db->select('*');
    		$this->db->from('menu_action');
    		$this->db->where('menu_action_id',$menu_datas['menu_action_id']);
    		$querys = $this->db->get();
    		$menuaction = $querys->result_array();
    		foreach ($menuaction as $menuactions){
    			$action_arr[] = $menuactions['menu_id'].'_'.$menuactions['rights'];
    		}
    		$i++;
    	}
    	return json_encode($action_arr);
    
    }
	
	public function create_single_user_privilege($user_id, $action) {
    	$this->db->where('user_id',$user_id);
    	$delete = $this->db->delete('user_custom_privilege');
		for($i=0;$i<count($action);$i++){
			if($action[$i]){
				$actArr = explode('_', $action[$i]);
				$menu_id = $actArr[0];
				$rights = $actArr[1];
				
				$this->db->select('*');
				$this->db->from('menu_action');
				$this->db->where('menu_id',$menu_id);
				$this->db->where('rights',$rights);
				$query = $this->db->get();
				$menu_action = $query->result_array();
				if($menu_action){
					foreach ($menu_action as $menu_actions){
						
						$data = array(
								'user_id' => $user_id,
								'menu_action_id' => $menu_actions['menu_action_id']
						);
						$this->db->insert('user_custom_privilege', $data);
					}
				}
				
			}
	   	
		}
		return true;
    }
}

/* End of file privilege_model.php */