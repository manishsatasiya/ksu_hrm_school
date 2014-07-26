<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('generic/flash_error');
?>
<link rel="stylesheet" href="<?php print base_url(); ?>js/datepicker/development-bundle/themes/base/jquery.ui.all.css">
<script>
	
	function checked_action(user_id){
		$.ajax({
			type:'post',
  			url: '<?php echo site_url("add_privilege/add_single_user_privilege/get_user_existing_privilege"); ?>',
			data: "user_id="+user_id,
  			success: function(data) {
  	  			var obj = $.parseJSON(data);
  	  			//alert(obj.length);return false;
  	  			if(obj.length > 1){
  	  				var frmobj = document.add_single_user_privilege_form;
					for(var i=0; i < frmobj['action[]'].length; i++)
				    {
  	  	  				for(var j=0;j<obj.length;j++){
  	  	  	  				if(frmobj['action[]'][i].value == obj[j]){
  	  	  						//alert(obj[j]);
  	  	  						frmobj['action[]'][i].checked = true;
  	  	  						$("#unchk_"+obj[j]).hide();
  	  	  	    				$("#chk_"+obj[j]).show();
  	  	  	  				}
  	  	  	  			}
				    }
  	  	  		}else{
					var frmobj = document.add_single_user_privilege_form;
					for(var i=0; i < frmobj['action[]'].length; i++)
				    {
				        frmobj['action[]'][i].checked = false;
				        $("#unchk_"+frmobj['action[]'][i].value).show();
	  	    			$("#chk_"+frmobj['action[]'][i].value).hide();
				    } 
  	  	  	  	}
    			
  			}
		});
	}

	$(document).ready(function(){
		checked_action(<?=$user_id?>);
	});
</script>
<style>
.tbl_border{
}
</style>
<h2><?php echo $this->lang->line('privi_p_heading'); ?></h2>
<?php 
print form_open('add_privilege/add_single_user_privilege/add', array('id' => 'add_single_user_privilege_form','name'=>'add_single_user_privilege_form')) ."\r\n";

print form_hidden('user_id', $user_id);
//echo "<pre>";print_r($previlage_action); exit;
?>
<h3 style="text-align:center;"><?=$first_name?></h3>
<div id="admin">
		<table border="1" cellspacing="0" cellpadding="5" width="70%" class="tbl_border" style="margin:0 auto; font-size:smaller;">
			<tr style="background: #ddd;">
				<td style="font-weight:bold;" class="tbl_border"><?php echo $this->lang->line('privi_p_list_hd_menu'); ?></td>
				<td class="tbl_border">
					<table border="0" cellspacing="0" cellpadding="5" width="100%">
						<tr>
							<td width="30%" style="border-right: none;">&nbsp;</td>
							<td style="border-left: none;">
								<div style="float: left;margin-left: 64px;">&nbsp;</div>
								<div style="float: left;margin-left: 64px;font-weight:bold;"><?php echo $this->lang->line('privi_p_list_hd_action'); ?></div>
								<div style="float: left;margin-left: 64px;">&nbsp;</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		
        
		<?php 
        if($previlage_action){
			for($i=0;$i<count($previlage_action);$i++){
        ?>
        <tr>
			<td valign="top" width="15%" style="font-weight:bold;  border-color:#000; background: #ddd;" class="tbl_border"><?php echo $this->lang->line($previlage_action[$i]['lang_menu_name']); ?></td>
			<td valign="top" width="" class="tbl_border" style=" border-color:#000;">
			<?php
			if($previlage_action[$i]['menu_action']){
			?>
				<table border="0" cellspacing="0" cellpadding="5" width="100%" style="" >
					<tr>
						<td width="33%" style="border-right: none; ">&nbsp;</td>
						<td style="border-left: none;">
							<?php
							for($j=0;$j<count($previlage_action[$i]['menu_action']);$j++){
							?>
								<div style="float: left;margin-left: 0px;width:115px; line-height: 18px;">
										<?php 
											$menu_act_id = $previlage_action[$i]['menu_action'][$j]['menu_id'].'_'.$previlage_action[$i]['menu_action'][$j]['value'];
											echo $previlage_action[$i]['menu_action'][$j]['name']; 
											echo form_checkbox('action[]', $menu_act_id, FALSE,'id="chkbox_'.$menu_act_id.'" style="display:; float:left;"');
										?>
								</div>
							<?php 	
							} 
							?>
						</td>
					</tr>
				</table>
    				
    		<?php 
    			
    		}else{
				if($previlage_action[$i]['sub_menu']){ 
					$total_row = count($previlage_action[$i]['sub_menu']) - 1;
					for($j=0;$j<count($previlage_action[$i]['sub_menu']);$j++){
					$border_style = '';
					if($j < $total_row) {
						$border_style = 'style="border-bottom:solid 1px;"';
					}	
    		?>
				<table border="0" cellspacing="0" cellpadding="5" width="100%" <?= $border_style ?> >
					<tr>
						<td width="33%" style="border-right:solid 1px;"><?php echo $this->lang->line($previlage_action[$i]['sub_menu'][$j]['lang_menu_name']); ?></td>
						<td>
							<?php
							$menu_action = $previlage_action[$i]['sub_menu'][$j]['menu_action'];
							if($menu_action){
								for($k=0;$k<count($menu_action);$k++){
								?>
										<div style="float: left;margin-left: 0px;width:115px; line-height: 18px;">
											<?php 
												$menu_act_id =$menu_action[$k]['menu_id'].'_'.$menu_action[$k]['value'];
												echo $menu_action[$k]['name'];
												echo form_checkbox('action[]', $menu_act_id, FALSE,'id="chkbox_'.$menu_act_id.'" style="display:; float:left;"');
											?>
										</div>
								<?php 	
								}
							} 
							?>
						</td>
					</tr>
				</table>
			<?php
					}
				}
			} 
			?>
			</td>
		</tr>
        <?php
    		}
    	 }
    		?>
        </table>
		<?php print form_submit(array('name' => 'add_single_user_privilege_submit', 'id' => 'add_single_user_privilege_submit', 'value' => $this->lang->line('privi_p_btn'), 'class' => 'input_submit', 'style' => 'float:none; margin:16px auto; display: inherit;')) ."<br />\r\n"; ?>
</div>

<?php print form_close() ."\r\n";
?>