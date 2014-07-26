<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
	$(function() {
		<?php
		 $j = 1;
		 if (isset($school)) {
    		foreach ($school->result() as $schools){ 
    			for ($k=1;$k<=$schools->school_week;$k++){ 
		?>
					$( "#reg_last_date<?php echo $schools->school_year_id.$k; ?>" ).datepicker().on('changeDate', function(e){
						var weekdays = new Array(7);
						weekdays[0] = "Sunday";
						weekdays[1] = "Monday";
						weekdays[2] = "Tuesday";
						weekdays[3] = "Wednesday";
						weekdays[4] = "Thursday";
						weekdays[5] = "Friday";
						weekdays[6] = "Saturday";
						var dates = $(this).datepicker( "getDate" );
						$("#dayname<?php echo $schools->school_year_id.$k; ?> span").html(weekdays[dates.getDay()]);
					});
					$('#reg_time<?php echo $schools->school_year_id.$k; ?>').timepicker({
						
						showLeadingZero: true
					});
		<?php
				}
		?>
				$("#week_submit<?php echo $schools->school_year_id;?>").click(function(){
					//alert('dasd');
					var weeklength = document.edit_enable_week_form<?php echo $j;?>.elements['week[]'].length;
					var j = 1;
					var flag = 0;
					for (i = 0; i < weeklength; i++)
					{
						if(document.edit_enable_week_form<?php echo $j;?>.elements['week[]'][i].checked == true){
							
							if($("#reg_last_date<?php echo $schools->school_year_id;?>"+j).val() == ''){
								$("#errordate<?php echo $schools->school_year_id;?>"+j).show();
								flag = 1;								
							}else{
								$("#errordate<?php echo $schools->school_year_id;?>"+j).hide();
							}
							
						}
						if(document.edit_enable_week_form<?php echo $j;?>.elements['week[]'][i].checked == false){
								$("#errordate<?php echo $schools->school_year_id;?>"+j).hide();
						}
						
						j++;
					}
					if(flag == 1){
						return false;	
					}else{
						return true;
					}
					
				});
		<?php 
				$j++;
			 }
		 } 
		?>
	});
</script>

<div class="grade-rpt list-enlbweek">
  <div id="admin">
    <?php $this->load->view('generic/flash_error'); ?>
    <!-- search box 1 start -->
    <div id="searchbox" class="graderpt_search_box dataTables_wrapper">
      <h5><?php echo $this->lang->line('attendance_deadline_time'); ?></h5>
      <div class="row">
        <div class="col-md-12">
          <?php print form_open('list_enable_week/action_add_time') ."\r\n"; ?>
          <div class="row form-row">
            <div class="col-md-3">
              <label class="form-label"> <?php echo $this->lang->line('am_start_time'); ?> </label>
              <?php print form_input(array('name' => 'am_start', 'id' => 'am_start', 'class' => 'admin_input form-control', 'value'=>$am_start_time)); ?> </div>
            <div class="col-md-3">
              <label class="form-label"> <?php echo $this->lang->line('am_end_time'); ?> </label>
              <?php print form_input(array('name' => 'am', 'id' => 'am', 'class' => 'admin_input form-control', 'value'=>$am_time)); ?> </div>
            <div class="col-md-3">
              <label class="form-label"> <?php echo $this->lang->line('pm_start_time'); ?> </label>
              <?php print form_input(array('name' => 'pm_start', 'id' => 'pm_start', 'class' => 'admin_input form-control', 'value'=>$pm_start_time)); ?> </div>
            <div class="col-md-3">
              <label class="form-label"> <?php echo $this->lang->line('pm_end_time'); ?> </label>
              <?php print form_input(array('name' => 'pm', 'id' => 'pm', 'class' => 'admin_input form-control', 'value'=>$pm_time)); ?> </div>
            <div class="col-md-12"> <?php print form_submit(array('name' => 'add_submit', 'id' => 'add_submit', 'value' 		=> 'Add', 'class' => 'input_submit btn btn-success')) ."\r\n";
		?> </div>
          </div>
          <?php print form_close() ."\r\n"; ?> 
        </div>
      </div>
    </div>
    <!-- search box 1 end -->
    <!-- search box 2 start -->
    <div id="searchbox" class="graderpt_search_box dataTables_wrapper">
      <h5><?php echo $this->lang->line('attendance_activation_time'); ?></h5>
      <div class="row">
        <div class="col-md-12">
          <?php print form_open('list_enable_week/attendance_week_activation_time') ."\r\n"; ?>
          <div class="row form-row">
            <div class="col-md-3">
              <label class="form-label"> <?php echo $this->lang->line('activation_time'); ?> </label>
              <?php print form_input(array('name' => 'activation_time', 'id' => 'activation_time', 'class' => 'admin_input form-control', 'value'=>$activation_time)); ?>
            </div>
            <div class="col-md-12"> <?php print form_submit(array('name' => 'add_submit', 'id' => 'add_submit', 'value' => 'Add', 'class' => 'input_submit btn btn-success')) ."\r\n"; ?>
            </div>
          </div>
          <?php print form_close() ."\r\n"; ?> </div>
      </div>
    </div>
    <!-- search box 2 end -->
    <!-- search box 3 start -->
    <div id="searchbox" class="graderpt_search_box dataTables_wrapper">
      <h5><?php echo $this->lang->line('teacher_attendance_pdf_settings'); ?></h5>
      <div class="row">
        <div class="col-md-12">
          <?php print form_open('list_enable_week/attendance_generate_pdf') ."\r\n"; ?>
          <div class="row form-row">
            <div class="col-md-6">
              <label class="form-label"> <?php echo $this->lang->line('pdf_settings'); ?> </label>
              <div class="col-md-4"><?php echo $this->lang->line('export_type'); ?> :</div>
              <div class="col-md-6">
                <select name="export_type" id="exporttype">
                  <option value="normal" <?php if($pdf_export_type=="normal") echo 'selected="selected"'?>><?php echo $this->lang->line('daily'); ?></option>
                  <option value="black" <?php if($pdf_export_type=="black") echo 'selected="selected"'?>><?php echo $this->lang->line('week_Total'); ?></option>
                </select>
              </div>
            </div>
            <div class="col-md-12"> <?php print form_submit(array('name' => 'add_submit', 'id' => 'add_submit', 'value' => 'Add', 'class' => 'input_submit btn btn-success')) ."\r\n";?> 
            </div>
          </div>
          <?php print form_close() ."\r\n"; ?> </div>
      </div>
    </div>
    <!-- search box 3 end -->
    <div class="grid simple">
      <div class="grid-title">
        <h4><?php echo $this->lang->line('enable_week_p_heading'); ?></h4>
      </div>
      <div class="grid-body">
        <div class="dataTables_wrapper">
          <!--Pagination start -->
          <div class="row">
            <div class="col-md-12 top-pgination">
              <div class="dataTables_paginate paging_bootstrap pagination">
                <?php print $this->pagination->create_links(); ?>
              </div>
              <div class="dataTables_info"> <?php print "Total rows: ". $total_rows; ?> </div>
            </div>
          </div>
          <!--Pagination end -->
          <!--accordion-typ1 start-->
          <div class="accordion-typ1">
            <div class="row">
              <div class="col-xs-12">
                <div class="acc-mainttl">
                  <div class="row"> <span class="meta col-md-3"> <?php echo $this->lang->line('enable_week_p_list_hd_school_name'); ?> </span> <span class="meta col-md-2"> <?php echo $this->lang->line('enable_week_p_list_hd_year'); ?> </span> <span class="meta col-md-3"> <?php echo $this->lang->line('enable_week_p_list_hd_title'); ?> </span> <span class="meta col-md-2"> <?php echo $this->lang->line('enable_week_p_list_hd_type'); ?> </span> <span class="meta col-md-2"> <?php echo $this->lang->line('enable_week_p_list_hd_week'); ?> </span> </div>
                </div>
                <div class="panel-group" id="accordion" data-toggle="collapse">
                  <?php
			$i = 1;
			if (isset($school)) {
			foreach ($school->result() as $schools):
			?>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $j; ?>"> <span class="row"> <span class="col-md-3">
                        <?php if($schools->school_name){echo $schools->school_name;}else{echo '&nbsp;';} ?>
                        </span> <span class="col-md-2">
                        <?php if($schools->school_year){echo $schools->school_year;}else{echo '&nbsp;';} ?>
                        </span> <span class="col-md-3">
                        <?php if($schools->school_year_title){echo $schools->school_year_title;}else{echo '&nbsp;';} ?>
                        </span> <span class="col-md-2">
                        <?php if($schools->school_type){echo $schools->school_type;}else{echo '&nbsp;';} ?>
                        </span> <span class="col-md-2">
                        <?php if($schools->school_week){echo $schools->school_week;}else{echo '&nbsp;';} ?>
                        </span> </span> </a>
                      </h4>
                    </div>
                    <div id="collapse-<?php echo $j; ?>" class="panel-collapse collapse">
                      <div class="panel-body">
                        <div class="info-box enable-week">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="sub-title">
                                <div class="col-md-1">&nbsp;</div>
                                <div class="col-md-3"><?php echo $this->lang->line('enable_week_p_week'); ?></div>
                                <div class="col-md-3"><?php echo $this->lang->line('enable_week_p_date'); ?></div>
                                <div class="col-md-3"><?php echo $this->lang->line('enable_week_p_day'); ?></div>
                                <div class="col-md-2"><?php echo $this->lang->line('no_of_days'); ?></div>
                              </div>
                              <?php 
    						print form_open('list_enable_week/action_enable_week/'. $schools->school_id .'/'. $offset .'/'. $order_by .'/'. $sort_order .'/'. $search, array('id' => 'edit_enable_week_form'.$i, 'class' => 'edit_enable_week_form', 'name' => 'edit_enable_week_form'.$i,)) ."\r\n";
    ?>
                              <ul>
                                <?php
						for ($k=1;$k<=$schools->school_week;$k++){ 
							
							if($dateArr){
								if( isset($dateArr[$schools->school_year_id.$k]) ){
									
									$datevalue = date('m/d/Y',strtotime($dateArr[$schools->school_year_id.$k][$k]));
									$timevalue = $timeArr[$schools->school_year_id.$k][$k];
									$disabled = '';
									$readonly = 'readonly="readonly"';
									$checked = TRUE;
								}else{
									$datevalue = $this->session->flashdata('last_date'.$schools->school_year_id.$k);
									$timevalue = $this->session->flashdata('time'.$schools->school_year_id.$k);
									$disabled = 'disabled="disabled"';
									$readonly = '';
									$checked = FALSE;
								}
							}else{
								$datevalue = $this->session->flashdata('last_date'.$schools->school_year_id.$k);
								$timevalue = $this->session->flashdata('time'.$schools->school_year_id.$k);
								$disabled = 'disabled="disabled"';
								$checked = FALSE;
								$readonly = '';
							}
							
							if($noOfDayArr){
								if( isset($noOfDayArr[$schools->school_year_id.$k]) && $noOfDayArr[$schools->school_year_id.$k][$k] > 0 ){
									$day = $noOfDayArr[$schools->school_year_id.$k][$k];
								}else {
									$day = 5; 
								}
							}
							else {
								$day = 5;
							}
							?>
                                <li>
                                  <div class="col-md-1"> <span class="week-in"> <?php print form_checkbox("week[]", $k, $checked,'onclick="enabledatepick('.$schools->school_year_id.$k.')" id="weekid'.$schools->school_year_id.$k.'"')?> </span> </div>
                                  <div class="col-md-3"><span class="week-in"><?php echo $this->lang->line('enable_week_p_week'); ?> <?php print $k;?></span></div>
                                  <div class="col-md-3">
                                    <input type="text" class="input_text form-control qtip_last_date<?php echo $schools->school_year_id.$k;?>" id="reg_last_date<?php echo $schools->school_year_id.$k;?>" value="<?php echo $datevalue;?>" name="last_date[<?php echo $k;?>]" <?php echo $disabled; ?> <?php echo $readonly; ?>>
                                    <br/>
                                    <label id="errordate<?php echo $schools->school_year_id.$k;?>" class="error hide">Please enter date.</label>
                                  </div>
                                  <div class="col-md-3" id="dayname<?php echo $schools->school_year_id.$k; ?>"> <span class="week-in">
                                    <?php
										if($datevalue){ 
											echo date( 'l',strtotime($datevalue) );
										} 
									?>
                                    </span>
                                  </div>
                                  <div class="col-md-2">
                                    <select name="no_of_day_<?php echo $k;?>">
                                      <?php
									for($p=1;$p<=5;$p++) {
									?>
                                      <option value="<?php echo $p; ?>"<?php if($day == $p){ echo ' selected="selected"'; } ?>><?php echo $p; ?></option>
                                      <?php
									}
									$day = '';
									?>
                                    </select>
                                  </div>
                                </li>
                                <?php
						} 
						?>
                                <li> <?php print form_submit(array('name' => 'add', 'class' => 'submit_button btn btn-success', 'title' => 'update', 'value' => 'Update','id' => 'week_submit'.$schools->school_year_id)); ?> </li>
                              </ul>
                              <?php
							print form_hidden('school_year_id', $schools->school_year_id);
							print form_close() ."\r\n"; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php	
			$i++;
			endforeach;
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
                <?php print $this->pagination->create_links();  ?>
              </div>
              <div class="dataTables_info"> <?php print "Total rows: ". $total_rows; ?> </div>
            </div>
          </div>
          <!--Pagination end -->
        </div>
        <!-- datatable_wrapper end -->
      </div>
      <!-- grid-body end -->
    </div>
    <!-- grid simple end -->
  </div>
  <!-- #admin end-->
</div>
<!-- grade-rpt end-->
