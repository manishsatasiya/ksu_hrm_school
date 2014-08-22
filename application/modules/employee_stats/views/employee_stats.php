<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
              <h2>Loading....</h2>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
            </div>
            <div class="modal-body"><div style="text-align:center;"><i class="fa fa-spinner fa fa-6x fa-spin" id="animate-icon"></i></div></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
		 </div>	
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->	
<div class="home-pg">
<div class="row">
	<div class="col-xs-4 m-b-20">
    	<div class="stats">
        	<a href="<?php print base_url(); ?>list_user"><strong><?php echo $staff_count ?></strong> Staff <br />members</a>
        </div>
    </div>
    <div class="col-xs-4 m-b-20">
    	<div class="stats">
        	<a href="<?php print base_url(); ?>list_user"><strong><?php echo $pending_count ?></strong> Pending <br />employees</a>
        </div>
    </div>
    <div class="col-xs-4 m-b-20">
    	<div class="stats">
        	<a href="#"><strong><?php echo $block_count ?></strong> Blocked <br />Accounts</a>
        </div>
    </div>
</div>

<div class="row m-b-20">
	<div class="col-lg-6 white">
    <!-- Teacher status start -->
    	<?php 
		if($campus_data_box && count($campus_data_box)) {
			foreach($campus_data_box as $campus_name=>$_campus_data_box){
			?>
            <div class="simple-box">
                <div class="sub-title">
                    <div class="col-xs-4"><?php echo $campus_name; ?>:</div>
                    <div class="col-xs-2">KSU</div>
                    <div class="col-xs-2">EdEx</div>
                    <div class="col-xs-2">ICEAT</div>
                    <div class="col-xs-2">Total</div>
                 </div>
                <ul>
                    <li data-sync-height="true">
                        <div class="col-xs-4">Teachers eligible for the academic timetable</div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['ksu_el']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['edex_el']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['iceat_el']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['total_el']; ?></div>
                    </li>
                    <li data-sync-height="true">
                        <div class="col-xs-4">Teachers restricted from the academic timetable</div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['ksu_inl']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['edex_inl']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['iceat_inl']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['total_inl']; ?></div>
                    </li>
                    <li data-sync-height="true">
                        <div class="col-xs-4">Company Admin</div>
                        <div class="col-xs-2">-</div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['edex_admin']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['iceat_admin']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['total_admin']; ?></div>
                    </li>
                    <li data-sync-height="true">
                        <div class="col-xs-4">PY admin</div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['ksu_admin']; ?></div>
                    </li>
                    <li data-sync-height="true">
                        <div class="col-xs-4 subtotal">Sub Total (<?php echo $campus_name; ?> Campus)</div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['ksu_total']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['edex_total']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['iceat_total']; ?></div>
                        <div class="col-xs-2"><?php echo $_campus_data_box['total_total']; ?></div>
                    </li>
                </ul>
            </div>
		<?php
			}
		} ?>
        <?php /*?><div class="simple-box">
        	<div class="sub-title">
            	<div class="col-xs-4">Male - Malaz:</div>
                <div class="col-xs-2">KSU</div>
                <div class="col-xs-2">EdEx</div>
                <div class="col-xs-2">ICEAT</div>
                <div class="col-xs-2">Total</div>
             </div>
            <ul>
            	<li data-sync-height="true">
                	<div class="col-xs-4">Teachers eligible for the academic timetable</div>
                    <div class="col-xs-2"><?php echo $ksu_el; ?></div>
                    <div class="col-xs-2"><?php echo $edex_el; ?></div>
                    <div class="col-xs-2"><?php echo $iceat_el; ?></div>
                    <div class="col-xs-2"><?php echo $total_el; ?></div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4">Teachers restricted from the academic timetable</div>
                    <div class="col-xs-2"><?php echo $ksu_inl; ?></div>
                    <div class="col-xs-2"><?php echo $edex_inl; ?></div>
                    <div class="col-xs-2"><?php echo $iceat_inl; ?></div>
                    <div class="col-xs-2"><?php echo $total_inl; ?></div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4">Male Company Admin</div>
                    <div class="col-xs-2">-</div>
                    <div class="col-xs-2"><?php echo $edex_admin; ?></div>
                    <div class="col-xs-2"><?php echo $iceat_admin; ?></div>
                    <div class="col-xs-2"><?php echo $total_admin; ?></div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4">Male PY admin</div>
                    <div class="col-xs-2"><?php echo $ksu_admin; ?></div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4 subtotal">Sub Total (Malaz Campus)</div>
                    <div class="col-xs-2"><?php echo $ksu_total; ?></div>
                    <div class="col-xs-2"><?php echo $edex_total; ?></div>
                    <div class="col-xs-2"><?php echo $iceat_total; ?></div>
                    <div class="col-xs-2"><?php echo $total_total; ?></div>
                </li>
            </ul>
        </div>
        <div class="simple-box">
        	<div class="sub-title" data-sync-height="true">
            	<div class="col-xs-4">Female: Olaysha</div>
                <div class="col-xs-2">KSU</div>
                <div class="col-xs-2">EdEx</div>
                <div class="col-xs-2">ICEAT</div>
                <div class="col-xs-2">Total</div>
             </div>
            <ul>
            	<li data-sync-height="true">
                	<div class="col-xs-4">eachers eligible for the academic timetable</div>
                    <div class="col-xs-2">0</div>
                    <div class="col-xs-2">85</div>
                    <div class="col-xs-2">77</div>
                    <div class="col-xs-2">162</div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4">Teachers restricted from the academic timetable</div>
                    <div class="col-xs-2">0</div>
                    <div class="col-xs-2">0</div>
                    <div class="col-xs-2">0</div>
                    <div class="col-xs-2">0</div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4">Female Company Admin</div>
                    <div class="col-xs-2">0</div>
                    <div class="col-xs-2">10</div>
                    <div class="col-xs-2">28</div>
                    <div class="col-xs-2">38</div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4">Female PY admin</div>
                    <div class="col-xs-2">0</div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4 subtotal">Sub Total ( Main Campus )</div>
                    <div class="col-xs-2">0</div>
                    <div class="col-xs-2">95</div>
                    <div class="col-xs-2">105</div>
                    <div class="col-xs-2">200</div>
                </li>
            </ul>
        </div><?php */?>
    <!-- Teacher status end -->   
    
    <!-- Total employees start -->  
    	<div class="simple-box">
        	<div class="sub-title">
            	<div class="col-xs-12">Total employees</div>
             </div>
            <ul>
            	<li data-sync-height="true">
                	<div class="col-xs-4">Total male teachers</div>
                    <div class="col-xs-2"><?php echo $total_male_teachers; ?></div>
                    <div class="col-xs-4">Total female teachers</div>
                    <div class="col-xs-2"><?php echo $total_female_teachers; ?></div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-4">Total male & female teachers</div>
                    <div class="col-xs-2"><?php echo $total_male_and_female_teachers; ?></div>
                    <div class="col-xs-4">Total male & female admin</div>
                    <div class="col-xs-2"><?php echo $total_male_and_female_admin; ?></div>
                </li>
                <li data-sync-height="true">
                	<div class="col-xs-6">Total employees</div>
                    <div class="col-xs-6"><?php echo $total_staff; ?></div>
                </li>
            </ul>
        </div>
    <!-- Total employees end -->   
    <!-- Departure Stats start -->
    	<div class="simple-box">
        	<div class="sub-title" data-sync-height="true">
            	<div class="col-xs-4">Departure Stats</div>
                <div class="col-xs-4">Staff Count</div>
                <div class="col-xs-4">Departure Reason</div>
             </div>
            <ul>
            	<?php 
				if($departure_stats) {
					foreach($departure_stats->result_array() as $_departure_stat){
				?>
                    <li data-sync-height="true">
                        <div class="col-xs-4"><?php echo $_departure_stat['contractor_name'] ?></div>
                        <div class="col-xs-4"><?php echo $_departure_stat['count'] ?></div>
                        <div class="col-xs-4"><?php echo $_departure_stat['resignation_resons'] ?></div>
                    </li>
                <?php
					}
				} ?>
            </ul>
        </div>
    <!-- Departure Stats start -->
    </div>
    <div class="col-lg-6">
    <!-- LATEST STAFF MEMBERS start -->
    	<div class="simple-box2">
        	<div class="sub-title">
            	<div class="col-xs-12">7 Latest staff members</div>
             </div>
            <ul>
            	<?php 
				if($new_users) {
					foreach($new_users->result_array() as $new_user){
				?>
            	<li>
                	<span class="list-link"><i class="fa fa-user"></i>
                    <strong><?php echo $new_user['first_name'] ?> <?php echo $new_user['last_name'] ?></strong> - ID: <?php echo $new_user['elsd_id'] ?> | <?php echo $new_user['campus_name'] ?>
                    </span>
                    <div class="button-group">
                    <a class="btn btn-primary btn-mini tip" title="Edit" href="<?php print base_url(); ?>list_user/add/<?php echo $new_user['user_id'] ?>" data-target="#myModal" data-toggle="modal"><i class="fa fa-pencil"></i> Edit</a>
                    <a class="btn btn-info btn-mini tip" data-toggle="tooltip" title="View Profile" href="<?php print base_url(); ?>list_user/edit_profile/<?php echo $new_user['user_id'] ?>"><i class="fa fa-list-alt"></i></a>
                    <a class="btn btn-warning btn-mini tip" data-toggle="tooltip" title="Delete" href="#"><i class="fa fa-trash-o"></i></a>
                    </div>
                </li>
                <?php
					}
				} ?>
            </ul>
        </div>
     <!-- LATEST STAFF MEMBERS END -->  
     
     <!-- Staff Count start --> 
     	<div class="simple-box">
        	<div class="sub-title">
            	<div class="col-xs-6">Contractor</div>
                <div class="col-xs-6">Staff Count</div>
             </div>
            <ul>
            	<li>
                	<div class="col-xs-6">KSU</div>
                    <div class="col-xs-6"><?php echo $total_ksu_employees; ?></div>
                </li>
                <li>
                	<div class="col-xs-6">EdEx</div>
                    <div class="col-xs-6"><?php echo $total_edex_employees; ?></div>
                </li>
                <li>
                	<div class="col-xs-6">ICEAT</div>
                    <div class="col-xs-6"><?php echo $total_iceat_employees; ?></div>
                </li>
                <li>
                	<div class="col-xs-6">Total employees</div>
                    <div class="col-xs-6"><?php echo $total_employees; ?></div>
                </li>
            </ul>
        </div>
     <!-- Staff Count END -->
     
     <!-- Nationality Count start -->
     	<div class="simple-box">
        	<div class="sub-title" data-sync-height="true">
            	<div class="col-xs-4">Nationality</div>
                <div class="col-xs-2">Male</div>
                <div class="col-xs-2">Female</div>
                <div class="col-xs-4">Total Count</div>
             </div>
            <ul>
            	<?php 
				if($nationality) {
					foreach($nationality->result_array() as $_nationality){
				?>
            	<li data-sync-height="true">
                	<div class="col-xs-4"><?php echo $_nationality['nationality'] ?></div>
                    <div class="col-xs-2"><?php echo $_nationality['male_count'] ?></div>
                    <div class="col-xs-2"><?php echo $_nationality['female_count'] ?></div>
                    <div class="col-xs-4"><?php echo $_nationality['total'] ?></div>
                </li>
                <?php
					}
				} ?>
            </ul>
        </div>
     <!-- Nationality Count END -->
    </div>
</div>

<div class="row" >
		<!-- BEGIN WORLD MAP WIDGET - CRAFT MAP -->
        <div class="col-md-8 col-vlg-8 m-b-20">
          <div class="row tiles-container " data-sync-height="true">
            <div class="col-md-7 col-vlg-8 col-sm-8 no-padding -height" >
              <iframe class="craft-map-container" src="<?php print base_url(); ?>/employee_stats/get_nationality_map"></iframe>
              <div class="clearfix"></div>
            </div>
            <div class="col-md-5 col-vlg-4 col-sm-4 no-padding" >
              <div class="tiles black" >
                <div class="tiles-body">
                  <h5 class="text-white"><span class="semi-bold">Employee</span> Nationality</h5>
                  <div class="m-t-40">
                    <div class="widget-stats">
                      <div class="wrapper"> <span class="item-title">All</span> <span class="item-count animate-number semi-bold" data-value="<?= $staff_count ?>" data-animation-duration="700">0</span> </div>
                    </div>
                    <div class="widget-stats">
                      <div class="wrapper"> <span class="item-title">Yearly</span> <span class="item-count animate-number semi-bold" data-value="<?=$employee_state_year->cnt;?>" data-animation-duration="700">0</span> </div>
                    </div>
                    <div class="widget-stats ">
                      <div class="wrapper last"> <span class="item-title">Monthly</span> <span class="item-count animate-number semi-bold" data-value="<?=$employee_state_month->cnt;?>" data-animation-duration="700">0</span> </div>
                    </div>
                    
                  </div>
                </div>
                <div id="chart" style="height:123px"> </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END WORLD MAP WIDGET - CRAFT MAP -->
		
		<!-- BEGIN REALTIME SALES GRAPH -->
		<div class="col-md-4 col-vlg-4 m-b-20 ">
			<div class="tiles white added-margin">
			  <div class="row ">
				<div class="p-t-35 p-l-45">
				<div class="col-md-5 col-sm-5 no-padding">
				  <h5 class="no-margin">Student</h5>
				  <h4><span class="item-count animate-number semi-bold" data-value="<?=$student_all_count?>" data-animation-duration="700">0</span></h4>
				</div>
				<div class="col-md-3 col-sm-3 no-padding">
				  <p class="semi-bold">Male</p>
				  <h4><span class="item-count animate-number semi-bold" data-value="<?=$student_male_count?>" data-animation-duration="700">0</span></h4>
				</div>
				<div class="col-md-3 col-sm-3 no-padding">
				  <p class="semi-bold">Female</p>
				  <h4><span class="item-count animate-number semi-bold" data-value="<?=$student_female_count?>" data-animation-duration="700">0</span></h4>
				</div>
				<div class="clearfix"></div>
			  </div>
			  </div>
			  <h5 class="semi-bold m-t-30 m-l-30">Latest Student</h5>
			  <table class="table no-more-tables m-t-20 m-l-20 m-b-30">
				<thead style="display:">
				  <tr>
					<th style="width:">Student ID</th>
					<th style="width:">Name</th>
                    <th style="width:">Date</th>
				  </tr>
				</thead>
				<tbody>
                	<?php 
					if($latest_student) {
						foreach($latest_student->result_array() as $student){
					?>
                      <tr>
                        <td class="v-align-middle bold text-success"><?php echo $student['student_uni_id'] ?></td>
                        <td class="v-align-middle"><span class="muted"><?php echo $student['first_name'] ?> <?php echo $student['last_name'] ?></span> </td>
                        <td class="v-align-middle bold text-success"><?php echo make_dp_date($student['created_date']); ?></td>
                      </tr>
                      <?php
					  	}
					} ?>	
				</tbody>
			  </table>
			  <div id="sales-graph"> </div>
			</div>
        </div>
		<!-- END REALTIME SALES GRAPH -->
	  </div>
</div>