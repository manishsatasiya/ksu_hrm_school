<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('generic/flash_error');
?>
<link rel="stylesheet" href="<?php print base_url(); ?>js/datepicker/development-bundle/themes/base/jquery.ui.all.css">
<style>
.tbl_border{
	box-shadow:3px 3px 5px #282828;
	-moz-box-shadow:3px 3px 5px #282828;
	-webkit-box-shadow:3px 3px 5px #282828;
}
.main_menu_heading {float: left; font-size: 10px; font-weight: bold; border-bottom: 1px solid; width: 100%; padding-bottom: 2px;}
.main_privilege {float:left; margin:0px; width:54px; min-height:px; padding: 2px 0; border-right: solid 1px;}
.main_privilege .menu_heading {float:left; margin:0 0 2px 0; width:50px; padding:2px; word-wrap: break-word; font-size: 9px;font-weight: bold; border-bottom: 1px solid;/* min-height:32px;*/}
.main_privilege .menu_all_action {float:left; margin:0px; width:54px; }
.main_privilege .menu_all_action .action{float:left; margin:0px; width:50px; text-align:center; font-size: 10px; }
.main_privilege .menu_all_action .action input[type="checkbox"] { float:left; margin-top:0px;}
#user_privilege_paging { float:left; margin:0 0 0 20px; line-height:30px;}
</style>
<h2><?php echo $this->lang->line('privi_p_heading'); ?></h2>

<div class="pagination" style="float:left;"><?php print $this->pagination->create_links(); ?> </div>
<?php
print form_open('add_privilege/add_user_privilege', array('id' => 'user_privilege_paging','name'=>'user_privilege_paging')) ."\r\n";
$custom_per_page = $this->session->userdata('user_privilege_per_page');
?>
<select name="per_page" id="per_page" onchange="this.form.submit();">
	<option value="10" <?php if($custom_per_page == 10){ echo 'selected="selected"'; } ?>>10</option>
	<option value="25" <?php if($custom_per_page == 25){ echo 'selected="selected"'; } ?>>25</option>
	<option value="50" <?php if($custom_per_page == 50){ echo 'selected="selected"'; } ?>>50</option>
	<option value="100" <?php if($custom_per_page == 100){ echo 'selected="selected"'; } ?>>100</option>
	<option value="200" <?php if($custom_per_page == 200){ echo 'selected="selected"'; } ?>>200</option>
</select>
<?php
print form_close() ."\r\n";
?>
<?php
print form_open('add_privilege/add_user_privilege', array('id' => 'user_privilege_paging','name'=>'user_privilege_paging')) ."\r\n";
$custom_per_page = $this->session->userdata('user_privilege_per_page');
?>
<input type="text" name="search_user" />
<input type="submit" name="submit" value="Search" />
<?php
print form_close() ."\r\n";
?>
<div class="clear"></div>
<?php
if($users){
print form_open('add_privilege/add_user_privilege/add_custom_privilege', array('id' => 'add_privilege_form','name'=>'add_privilege_form')) ."\r\n";
$user_ids = '';
foreach($users AS $user)
{
	$first_name = $user['first_name']; 
	$user_id = $user['user_id']; 
	$user_ids .= $user_id.',';
	$current_privilege = $user['current_privilege']; 
	?>
<div id="admin">
	
	<table border="1" cellspacing="0" cellpadding="5" width="100%" class="tbl_border"> 
		<tr><td colspan="<?=count($previlage_action);?>" style="font-weight:bold;"><?=$first_name?></td></tr>     
		<tr>  
		<?php 
		if($previlage_action){
			for($i=0;$i<count($previlage_action);$i++)
			{		
		?>
			<td valign="top" class="tbl_border" width="">
				<div style="float:left;">
					<h3 class="main_menu_heading" style=""><?php echo $this->lang->line($previlage_action[$i]['lang_menu_name']); ?></h3>
					<?php
					$width = 0; 
					if($previlage_action[$i]['sub_menu']){ 
						$width = 108.4;
						$total_div = count($previlage_action[$i]['sub_menu']);
						if($total_div<=2)
							$width = 27.1;
						//$width = $total_div*$width;
						$total_div = $total_div -1;
					}
					?>
					<div style="float:left; min-width:<?=$width?>px;">
						
						<?php
						if($previlage_action[$i]['menu_action'])
						{
						?>
							<div class="main_privilege" style=" border:none;">
								<div class="menu_all_action" style="">
									<?php
									for($j=0;$j<count($previlage_action[$i]['menu_action']);$j++){
									?>
									<div class="action" style=""><?php 
										$menu_act_id = $previlage_action[$i]['menu_action'][$j]['menu_id'].'_'.$previlage_action[$i]['menu_action'][$j]['value'];
										$check = false;
										if(isset($current_privilege[$previlage_action[$i]['menu_action'][$j]['menu_id']]) 
											&& in_array($previlage_action[$i]['menu_action'][$j]['value'],$current_privilege[$previlage_action[$i]['menu_action'][$j]['menu_id']])
										) { $check = true; }
										echo form_checkbox('action['.$user_id.'][]', $menu_act_id, $check,'id="chkbox_'.$menu_act_id.'" style="display:;"');
										echo $previlage_action[$i]['menu_action'][$j]['name']; ?>
									</div>
									<?php 	
									} 
									?>
								</div>
							</div>
						<?php 	
						}else{
							if($previlage_action[$i]['sub_menu']){ 
								for($j=0;$j<count($previlage_action[$i]['sub_menu']);$j++)
								{
								
						?>
							<div class="main_privilege" <?php if($j%2!=0) { echo 'style="border:none;"'; } ?>>
								<div class="menu_heading" style=""><?php echo $this->lang->line($previlage_action[$i]['sub_menu'][$j]['lang_menu_name']); ?></div>
								<div class="menu_all_action" style="">
									<?php
									$menu_action = $previlage_action[$i]['sub_menu'][$j]['menu_action'];
									if($menu_action){
										for($k=0;$k<count($menu_action);$k++){
										?>
										<div class="action" style=""><?php 
											$menu_act_id =$menu_action[$k]['menu_id'].'_'.$menu_action[$k]['value'];
											$check = false;
											if(isset($current_privilege[$menu_action[$k]['menu_id']]) 
												&& in_array($menu_action[$k]['value'],$current_privilege[$menu_action[$k]['menu_id']])
											) { $check = true; }
											echo form_checkbox('action['.$user_id.'][]', $menu_act_id, $check,'id="chkbox_'.$menu_act_id.'" style="display:;"');
											echo $menu_action[$k]['name'];?>
										</div>
										<?php 	
										}
									} 
									?>
								</div>
							</div>
						<?php
								if($j%2!=0){ echo '<div class="clear"></div>'; }
								}
							}
						} 
						?>
						
					</div>
				</div>
			</td>
		<?php
			}
		 }
		?>
		</tr>
	</table>
</div>
<?php
}
$user_ids = trim(trim($user_ids),',');
print form_hidden('user_ids', $user_ids);
print form_submit(array('name' => 'add_privilege_submit', 'id' => 'add_privilege_submit', 'value' => $this->lang->line('privi_p_btn'), 'class' => 'input_submit')) ."<br />\r\n";
print form_close() ."\r\n";
} ?>
<?php 
?>
<div class="pagination"><?php print $this->pagination->create_links(); ?> </div>