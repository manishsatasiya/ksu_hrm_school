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
<div class="page-title">
	<h3>Dashboard </h3>
</div>
<div id="container">
	<div class="row">	 
		<div class="col-md-3 col-vlg-3 col-sm-6">
			<div class="tiles green added-margin  m-b-20">
              <div class="tiles-body">
			  <div class="controller"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                <div class="tiles-title text-black">Course Class</div>
                <?php
				if(count($course_class) > 0) { 
					$i = 1;
					foreach($course_class as $campus=>$course_class_count) {?>
			         <div class="widget-stats">
                      <div class="wrapper <?php if(count($course_class) == $i){ echo 'last'; } else { echo 'transparent'; } ?>"> 
						<span class="item-title"><?=$campus?></span> <span class="item-count animate-number semi-bold" data-value="<?=$course_class_count?>" data-animation-duration="700">0</span>
					  </div>
                    </div>
                 <?php
				 	$i++;
				 	}
				 } ?>   
			  </div>			
			</div>	
		</div>
		<div class="col-md-3 col-vlg-3 col-sm-6">
			<div class="tiles blue added-margin  m-b-20">
              <div class="tiles-body">
			  <div class="controller"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                <div class="tiles-title text-black">Teachers </div>
			         <div class="widget-stats">
                      <div class="wrapper transparent"> 
						<span class="item-title">Male</span> <span class="item-count animate-number semi-bold" data-value="<?=$total_male_teachers?>" data-animation-duration="700">0</span>
					  </div>
                    </div>
                    <div class="widget-stats">
                      <div class="wrapper last">
						<span class="item-title">Female</span> <span class="item-count animate-number semi-bold" data-value="<?=$total_female_teachers?>" data-animation-duration="700">0</span> 
					  </div>
                    </div>
			  </div>			
			</div>	
		</div>
		<div class="col-md-3 col-vlg-3 col-sm-6">
			<div class="tiles purple added-margin  m-b-20">
              <div class="tiles-body">
			  <div class="controller"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                <div class="tiles-title text-black">Number of students </div>
			         <div class="widget-stats">
                      <div class="wrapper transparent"> 
						<span class="item-title">Male</span> <span class="item-count animate-number semi-bold" data-value="<?=$student_male_count?>" data-animation-duration="700">0</span>
					  </div>
                    </div>
                    <div class="widget-stats">
                      <div class="wrapper last">
						<span class="item-title">Female</span> <span class="item-count animate-number semi-bold" data-value="<?=$student_female_count?>" data-animation-duration="700">0</span> 
					  </div>
                    </div>
			  </div>			
			</div>	
		</div>	
		<div class="col-md-3 col-vlg-3 col-sm-6">
			<div class="tiles red added-margin  m-b-20">
              <div class="tiles-body">
			  <div class="controller"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                <div class="tiles-title text-black">Number of Staff  </div>
			         <div class="widget-stats">
                      <div class="wrapper transparent"> 
						<span class="item-title">Male</span> <span class="item-count animate-number semi-bold" data-value="<?=$total_male_staff?>" data-animation-duration="700">0</span>
					  </div>
                    </div>
                    <div class="widget-stats">
                      <div class="wrapper last">
						<span class="item-title">Female</span> <span class="item-count animate-number semi-bold" data-value="<?=$total_female_staff?>" data-animation-duration="700">0</span> 
					  </div>
                    </div>
			  </div>			
			</div>	
		</div>		
	 </div>

	<div class="row" >
		<!-- BEGIN WORLD MAP WIDGET - CRAFT MAP -->
        <div class="col-md-8 col-vlg-8 m-b-20">
          <div class="row tiles-container " data-sync-height="true">
            <div class="col-md-7 col-vlg-8 col-sm-8 no-padding -height" >
              <iframe class="craft-map-container" src="<?php print base_url(); ?>employee_stats/get_nationality_map"></iframe>
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
						foreach($latest_student as $student){
					?>
                      <tr>
                        <td class="v-align-middle bold text-success"><?php echo $student['student_uni_id'] ?></td>
                        <td class="v-align-middle"><span class="muted"><?php echo $student['name'] ?></span> </td>
                        <td class="v-align-middle bold text-success"><?php echo make_dp_date($student['date']); ?></td>
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