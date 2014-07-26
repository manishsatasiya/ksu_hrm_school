<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>	
$(document).ready(function() {	
	<?php
	$j = 1;
	foreach ($school->result() as $schools){
	?>
		$("#edit_school_form<?php echo $j; ?>").validate({
			submitHandler: function(form) {
				form.submit();
			},
			rules: {
				school_name: "required",
				principal: "required",
				email: {
					required: true,
					email: true
				}
			},
			messages: {
				school_name: "Please enter your school name",
				principal: "Please enter your principal",
				email: "Please enter valid email address"			
			}
		});
	<?php
	$j++;
	}
	?>
});
</script>
<div class="grade-rpt list-school">
    <div id="admin">
    	<?php $this->load->view('generic/flash_error'); ?>
<!-- search box start -->
        <div id="searchbox" class="graderpt_search_box dataTables_wrapper">
    	<div class="row">
        <div class="col-md-12">
				<?php
				print form_open('list_school/index/school_name/asc/post/0') ."\r\n";
				?> 
        <div class="row form-row">
              <div class="col-md-3">
              <?php /*?><label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_sea_section') ?>
              </label><?php */?>
              <?php print form_input(array('name' => 'school_name', 'id' => 'school_name', 'class' => 'form-control', 'placeholder'=> $this->lang->line('school_p_ser_school_name'))); ?>

              </div>
              <div class="col-md-3">
              <?php /*?><label class="form-label">
			  <?php echo $this->lang->line('school_p_ser_principal'); ?>
              </label><?php */?>             
			<?php print form_input(array('name' => 'principal', 'id' => 'principal', 'class' => 'form-control', 'placeholder'=>$this->lang->line('school_p_ser_principal'))); ?>
              </div>
              <div class="col-md-3">
              <?php /*?><label class="form-label">
			  <?php echo $this->lang->line('school_p_ser_email'); ?>
              </label> <?php */?>             
			<?php print form_input(array('name' => 'email', 'id' => 'email', 'class' => 'form-control', 'placeholder'=>$this->lang->line('school_p_ser_email'))); ?>
              </div>
              
              <div class="col-md-3">
              <?php print form_submit(array('name' => 'school_search_submit', 'id' => 'school_search_submit', 'value' => $this->lang->line('school_p_ser_btn'), 'class' => 'btn btn-success')) ."\r\n"; ?>
			  </div>
			<?php print form_close() ."\r\n"; ?>
        </div>
        </div>
        </div>     
 		</div>
        <!-- search box end -->
        
    <div class="grid simple">
        <div class="grid-title">
        <h4><?php echo $this->lang->line('school_p_heading'); ?></h4>
        </div>
        <div class="grid-body">	
		<div class="dataTables_wrapper">
       <!--Pagination start -->
        <div class="row">
            <div class="col-md-12 top-pgination">
            <div class="dataTables_paginate paging_bootstrap pagination">
              <?php
            print $this->pagination->create_links();
            ?>
            </div>
            <div class="dataTables_info">
                <?php print "Total rows: ". $total_rows; ?>
            </div>
            </div>
        </div>    
       <!--Pagination end --> 
       <!--accordion-typ1 start-->
      <div class="accordion-typ1">
      <div class="row">
        <div class="col-xs-12">
        <div class="acc-mainttl">
            <div class="row">
               <span class="meta col-md-4">
			   <a href="<?php print base_url() ."list_school/index/school_name/". ($order_by == "school_name" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>"><?php echo $this->lang->line('school_p_list_hd_school_name'); ?>
               <span class="sort <?php print ($order_by == "school_name" ? ($sort_order == "asc" ? "asc" : "desc" ) : ""); ?>" data-sort="data-name" ></span>
                </a>
                </span>
				
                
               <span class="meta col-md-4">
			   <a href="<?php print base_url() ."list_school/index/email/". ($order_by == "email" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>"><?php echo $this->lang->line('school_p_list_hd_email'); ?>
               <span class="sort <?php print ($order_by == "email" ? ($sort_order == "asc" ? "asc" : "desc" ) : ""); ?>" data-sort="data-name" ></span>
               </a>
				
                </span>
                
               <span class="meta col-md-4">
			   <a href="<?php print base_url() ."list_school/index/principal/". ($order_by == "principal" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>"><?php echo $this->lang->line('school_p_list_hd_principal'); ?>
               <span class="sort <?php print ($order_by == "principal" ? ($sort_order == "asc" ? "asc" : "desc" ) : ""); ?>" data-sort="data-name" ></span>
               </a>
				
               </span>
               	
               
             </div>  
        </div>
		<div class="panel-group" id="accordion" data-toggle="collapse">
        	<?php
			$i = 1;
			if (isset($school)) {
				foreach ($school->result() as $schools)
			{
			?>
        	
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $j; ?>">
                <span class="row">
				   <span class="col-md-4"><?php if($schools->school_name){echo $schools->school_name;}else{echo '&nbsp;';} ?></span>
                   <span class="col-md-4"><i class="fa fa-envelope"></i> &nbsp; <?php if($schools->email){echo $schools->email;}else{echo '&nbsp;';} ?></span>
                   <span class="col-md-4"><i class="fa fa-shield"></i>  <?php if($schools->principal){echo $schools->principal;}else{echo '&nbsp;';} ?></span>
                 </span>  
				</a>
			  </h4>
			</div>
			<div id="collapse-<?php echo $j; ?>" class="panel-collapse collapse">
			  <div class="panel-body">
					<?php 
					print form_open('list_school/action_school/'. $schools->school_id .'/'. $offset .'/'. $order_by .'/'. $sort_order .'/'. $search, array('id' => 'edit_school_form'.$i, 'class' => 'edit_school_form')) ."\r\n";
					?>
					<div class="post">
					  
					  <div class="form-row">
						<div class="col-md-6">						  
							<label class="form_label" for="reg_first_name"><?php echo $this->lang->line('school_p_field_school_name'); ?></label>						  
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'school_name', 'class' => 'form-control', 'value' => $schools->school_name)); ?> </div>
						</div>
						<div class="col-md-6">
							<label class="form_label" for="reg_middle_name"><?php echo $this->lang->line('school_p_field_principal'); ?></label>
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'principal', 'class' => 'form-control', 'value' => $schools->principal)); ?> </div>
						</div>
						<div class="clear"></div>
					  </div>
					  <div class="form-row">
						<div class="col-md-6">
							<label class="form_label" for="reg_first_name"><?php echo $this->lang->line('school_p_field_email'); ?></label>
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'email', 'class' => 'form-control', 'value' => $schools->email)); ?> </div>
						  <div id="email_valid"></div>
						  <div id="email_taken"></div>
						</div>
						<div class="col-md-6">
							<label class="form_label" for="reg_last_name"><?php echo $this->lang->line('school_p_field_web_add'); ?></label>
						  <div class="input_box_thin"><?php print form_input(array('name' => 'www_address', 'class' => 'form-control', 'value' => $schools->www_address)); ?></div>
						</div>
						<div class="clear"></div>
					  </div>
					  <div class="form-row">
					  	<div class="col-md-6">
							<label class="form_label" for="reg_first_name"><?php echo $this->lang->line('school_p_field_phone'); ?></label>
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'phone', 'class' => 'form-control', 'value' => $schools->phone)); ?> </div>
						</div>
					  	<div class="col-md-6">
							<label class="form_label" for="reg_middle_name"><?php echo $this->lang->line('school_p_field_address'); ?></label>
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'address', 'class' => 'form-control', 'value' => $schools->address)); ?> </div>
						</div>
					  </div>	
					  <div class="form-row">
						<div class="col-md-6">
							<label class="form_label" for="reg_last_name"><?php echo $this->lang->line('school_p_field_city'); ?></label>
						  <div class="input_box_thin"><?php print form_input(array('name' => 'city', 'class' => 'form-control', 'value' => $schools->city)); ?></div>
						</div>
						<div class="col-md-6">
							<label class="form_label" for="reg_first_name"><?php echo $this->lang->line('school_p_field_state'); ?></label>
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'state', 'class' => 'form-control', 'value' => $schools->state)); ?> </div>
						</div>
						<div class="clear"></div>
					  </div>
					  <div class="form-row">
						<div class="col-md-6">
							<label class="form_label" for="reg_middle_name"><?php echo $this->lang->line('school_p_field_zip'); ?></label>
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'zip', 'class' => 'form-control', 'value' => $schools->zip)); ?> </div>
						</div>
						<div class="col-md-6">
							<label class="form_label" for="reg_last_name"><?php echo $this->lang->line('school_p_field_aear_code'); ?></label>
						  <div class="input_box_thin"><?php print form_input(array('name' => 'area_code', 'class' => 'form-control', 'value' => $schools->area_code)); ?></div>
						</div>
						<div class="clear"></div>
					  </div>
					  <div class="form-row">
						<div class="col-md-6">
							<label class="form_label" for="reg_middle_name"><?php echo $this->lang->line('show_total_tab_in_grades'); ?></label>
						  <div class="input_box_thin"> <?php print form_dropdown("show_total_grade",$arr_show_total_grade,$schools->show_total_grade,'id="reg_show_total_grade" class="qtip_show_total_grade"'); ?> </div>
						</div>
						<div class="col-md-6">
							<label class="form_label" for="reg_last_name"><?php echo $this->lang->line('show_grade_range_in_total_tab'); ?></label>
						  <div class="input_box_thin"> <?php print form_dropdown("show_grade_range",$arr_show_grade_range,$schools->show_grade_range,'id="reg_show_grade_range" class="qtip_show_grade_range"'); ?> </div>
						</div>
						<div class="clear"></div>
					  </div>
					  <div class="form-row">
						<div class="col-md-6">
							<label class="form_label" for="reg_first_name"><?php echo $this->lang->line('attendance_time_limit'); ?></label>
						  <div class="input_box_thin"> <?php print form_input(array('name' => 'attendance_time_limit', 'class' => 'form-control', 'value' => $schools->attendance_time_limit)); ?> </div>
						  <div id="email_valid"></div>
						  <div id="email_taken"></div>
						</div>
						<div class="col-md-6">
							<label class="form_label" for="reg_last_name"><?php echo $this->lang->line('grade_time_limit'); ?></label>
						  <div class="input_box_thin"><?php print form_input(array('name' => 'grade_time_limit', 'class' => 'form-control', 'value' => $schools->grade_time_limit)); ?></div>
						</div>
						<div class="clear"></div>
					  </div>
					 </div>
					 <div class="form-actions">
					 <div class="post col-md-12"> 
					  <div class="accordion_submit">
						<?php print form_submit(array('name' => 'update', 'class' => 'btn btn-primary', 'title' => 'update', 'value' => $this->lang->line('school_p_update_btn'))); ?>
					</div>
					 </div>
					 </div> 
					  <?php
					print form_hidden('school_id', $schools->school_id);
					print form_close() ."\r\n";
					?>
			  </div>
			</div>
		  </div>
			<?php	
			$i++;
			}
			}
			?>
		</div>
        <!--accordion div end-->

        </div>
      </div>
    </div>
    <!--accordion-typ1 end-->
    <!--Pagination start -->
    <div class="row">
    	<div class="col-md-12 top-pgination">
    	<div class="dataTables_paginate paging_bootstrap pagination">
		  <?php
        print $this->pagination->create_links();
        ?>
        </div>
        <div class="dataTables_info">
        	<?php print "Total rows: ". $total_rows; ?>
        </div>
        </div>
    </div>    
   <!--Pagination end --> 
       </div>
       </div>  
	</div>

</div>
</div>


