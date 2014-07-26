<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="admin" class="attendance_pdf_sheet">
	<!--search box start-->
        <div id="searchbox" class="ksu_search_box dataTables_wrapper">
    		<div class="row">
        		<div class="col-md-12">
	<form name="form_main" method="post">
	<?php
	if($this->session->userdata('role_id') != '3' && $this->session->userdata('role_id') != '4')
	{?>
		<div class="pdf_box1">
		<?
		if(count($school_campus) > 0 && ($this->session->userdata('campus_id') == 0 || $this->session->userdata('campus_id') == ""))
		{
		?>
        <div class="row form-row">
        <div class="col-md-3">
        <label class="form-label"><?php echo $this->lang->line('campus'); ?></label>           <select name="campus" onchange="getSection(this.value);">
					<option value=""><?php echo $this->lang->line('get_pdf_p_field_all'); ?></option>
					<?php 		 
					if(count($school_campus) > 0) {
						foreach($school_campus as $campus_drop){
							echo '<option value="'.$campus_drop['campus_id'].'">'.$campus_drop['campus_name'].'</option>';
						} 	
					}?>
			</select>     
        </div>
        <?php 
		}
		?>
        <div id="section_box" class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_section'); ?> 
        </label>
        <input name="section" id="section" type="text" class="form-control">   
        </div>
        
        <div class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_week'); ?>
        </label>
        	<select name="week">
			<?php for($i=1;$i<=15;$i++) { 
			echo '<option value="'.$i.'">'.$i.'</option>';
			 } ?>
			</select>   
        </div>
        
        <div class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_export_type'); ?>
        </label>
        	<select name="export_type" id="exporttype">
			<option value="normal"><?php echo $this->lang->line('get_pdf_p_field_daily'); ?></option>
			<option value="black"><?php echo $this->lang->line('get_pdf_p_field_week_total'); ?></option>
			</select>  
        </div>
        </div>
        
        <div class="row form-row">
        <div class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_att_date_title'); ?>
         </label>
         <input type="text" name="day6" id="day6" class="form-control" />
        </div>
        <div id="d1" class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_day'); ?> 1
        </label>
        <input type="text" name="day1" id="day1" class="form-control" />
        </div>
        <div id="d2" class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_day'); ?> 2
        </label>
        <input type="text" name="day2" id="day2" />
        </div>
        <div id="d3" class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_day'); ?> 3
        </label>
        <input type="text" name="day3" id="day3" class="form-control"  />
        </div>
        
        </div>
        
        <div class="row form-row">
        <div id="d4" class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_day'); ?> 4
        </label>
        <input type="text" name="day4" id="day4" class="form-control" />
        </div>
        <div id="d5" class="col-md-3">
        <label class="form-label">
		<?php echo $this->lang->line('get_pdf_p_field_day'); ?> 5
        </label>
        <input type="text" name="day5" id="day5" class="form-control" />
        </div>
        </div>
	</div>
	<?php
	}
	if($this->session->userdata('role_id') == '3')
	{ ?>
		<input type="hidden" name="week" value="<?php echo $pdf_week?>" />
		<input type="hidden" name="am_start" value="<?php echo $pdf_am_start?>" />
		<input type="hidden" name="am_end" value="<?php echo $pdf_am_end?>" />
		<input type="hidden" name="pm_start" value="<?php echo $pdf_pm_start?>" />
		<input type="hidden" name="pm_end" value="<?php echo $pdf_pm_end?>" />
		<input type="hidden" name="export_type" value="<?php echo $pdf_export_type?>" />
		<input type="hidden" name="day1" value="<?php echo $pdf_day1?>" />
		<input type="hidden" name="day2" value="<?php echo $pdf_day2?>" />
		<input type="hidden" name="day3" value="<?php echo $pdf_day3?>" />
		<input type="hidden" name="day4" value="<?php echo $pdf_day4?>" />
		<input type="hidden" name="day5" value="<?php echo $pdf_day5?>" />
		<input type="hidden" name="day6" value="<?php echo $pdf_date_title?>" />
		<?php
	}
	?>
</form>
				</div>
			</div> 
	</div>
 	<!--search box end-->

	<div class="grid simple">
    <div class="grid-title"><h4><?php echo $this->lang->line('get_pdf_p_heading'); ?></h4></div>
    <div class="grid-body">
    <div class="dataTables_wrapper">
	<div class="campus_box_holder">
    	<div class="row">
		<?php 		 
		if(count($school_campus) > 0) {
			foreach($school_campus as $campus){
				if($this->session->userdata('role_id') == '3'){
					if($this->session->userdata('campus_id') != $campus['campus_id'])
						continue;
				}
			?>
                <div class="col-md-6 p-b-15 m-b-15" id="<?php echo $campus['campus_id']; ?>">
                <div class="tiles p-t-5 p-b-5 p-l-25 ">
					<h4 class="text-black"><?php echo $campus['campus_name']; ?></h4>
				</div>
                <div class="tiles grey center padding-20">
				<?php 
				if($this->session->userdata('role_id') != '3'){ ?>
					<a  href="downloads/pdfreports/<?php echo strtolower($campus['campus_name']); ?>.pdf" target="_blank" class="input_submit btn btn-success" ><?php echo $this->lang->line('get_pdf_p_field_download_pdf'); ?></a>
				<?php } ?>
				<a onclick="gen_pdf(<?php echo $campus['campus_id']; ?>,'<?php echo strtolower($campus['campus_name']); ?>');" href="#" class="input_submit btn btn-info" <?php	if($this->session->userdata('role_id') == '3') { echo 'style="float:none"'; } ?>  ><?php echo $this->lang->line('get_pdf_p_field_generate_pdf'); ?></a>
				</div>
                </div>		
			<?php  
			} 	
		}?>
        </div>
	</div>
    </div>
    </div>
    </div>
    
</div>
<div class="clear"></div>