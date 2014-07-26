<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link href="<?php print base_url(); ?>assets/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" media="screen"/>
<script type="text/javascript" src="<?php print base_url(); ?>assets/plugins/fullcalendar/fullcalendar.min.js"></script>

<script type="text/javascript" src="<?php print base_url(); ?>assets/js/calender.js"></script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">Loading....</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->		

<div class="row" style="max-height:600px;">
    <div class="tiles row tiles-container red no-padding">
      <div class="col-md-4 tiles red no-padding">
        <div class="tiles-body">
          <div class="calender-options-wrapper">
            <h3 class="text-white semi-bold" id="calender-current-day"></h3>
            <h2 class="text-white" id="calender-current-date"></h2>
            <div class="events-wrapper">
              <div class="events-heading">My next 7 appointments</div>
              <ul>
              <?php
			  if(count($next_appointments) > 0) {
			  	foreach($next_appointments as $appointment) {
					?>
                    <li>
                        <span class="event-date with-month">
                        <div class="event-date"><?= date("d",strtotime( $appointment['event_day']))?></div><br> <div class="event-month"><?= date("F",strtotime( $appointment['event_day'])) ?></div>
                        </span>
                        
                        <h4><?= ucwords( $appointment['title']) ?> | <?= $appointment['time'] ?> </h4>
                        <span> <?= date("D, d F Y",strtotime( $appointment['event_day'])) ?>  <a href="<?= base_url() ?>/shared/delete_appointment/<?= $appointment['id'] ?>" class="icon-trash confirm float-right with-tooltip" title="cancel appointment"></a></span>
                        <?php
                        if($appointment['type'] == 'work')
                        	echo '<span class="ribbon tiny"><span class="ribbon-inner green-gradient">Work</span></span>';
                        else
                        	echo '<span class="ribbon tiny"><span class="ribbon-inner blue-gradient">Private</span></span>';
                        ?>
                    </li>
                    <?php 
				}
			  }
			  ?>
              </ul>
              
              <div class="events-heading">My next workshops</div>
              <ul>
              <?php
			  if(count($user_workshops) > 0) {
			  	foreach($user_workshops as $workshop) {
					?>
                    <li>
                        <span class="event-date with-month">
                        <div class="event-date"><?= date("d",strtotime( $workshop['event_day']))?></div><br> <div class="event-month"><?= date("F",strtotime( $workshop['event_day'])) ?></div>
                        </span>
                        
                        <h4><?= ucwords( $workshop['title']) ?> | <?= $workshop['time'] ?> </h4>
                        <span>Venue: <?= $workshop['venue'] ?>  |  <?= date("D, d F Y",strtotime( $workshop['event_day'])) ?>  <a href="<?= base_url() ?>/shared/delete_appointment/<?= $workshop['workshop_id'] ?>" class="icon-trash confirm float-right with-tooltip" title="cancel appointment"></a></span>
                        <?php
                        if($workshop['workshop_type_id'] == 3)
                        	echo '<span class="ribbon tiny"><span class="ribbon-inner green-gradient">PD</span></span>';
                        elseif($workshop['workshop_type_id'] == 1)
                        	echo '<span class="ribbon tiny"><span class="ribbon-inner blue-gradient">Opt</span></span>';
                        ?>
                    </li>
                    <?php 
				}
			  }
			  ?>
              </ul>
             
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8 tiles white no-padding">
        <div class="tiles-body">
          <div class="full-calender-header">
            <div class="pull-left">
              <div class="btn-group ">
                <button class="btn btn-success" id="calender-prev"><i class="fa fa-angle-left"></i></button>
                <button class="btn btn-success" id="calender-next"><i class="fa fa-angle-right"></i></button>
              </div>
            </div>
            <div class="pull-right">
              <div data-toggle="buttons-radio" class="btn-group">
              	<a href="<?php echo base_url(); ?>schedule/add_appointment/" class="btn btn-sm btn-medium btn-primary " data-target="#myModal" data-toggle="modal">Add Appointment <i class="fa fa-plus"></i></a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div id='calendar'></div>
        </div>
      </div>
    </div>
</div>