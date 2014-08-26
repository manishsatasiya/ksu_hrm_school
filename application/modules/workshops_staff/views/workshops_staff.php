<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="row-fluid">
  <div class="span12">
    <div class="grid simple ">
      <div class="grid-title">
        <h4>Weekly workshops</h4>
      </div>
      <div class="grid-body generaltab">
            <div class="m-b-15 emply-info">
				<?php
				if ($this->session->flashdata('message')) {
					print "<br><div class=\"alert alert-error\">". $this->session->flashdata('message') ."</div>";
				}
				
            	if(!empty($workshops)){
					$i = 1;
					foreach($workshops as $workshop) { 
					?>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="sub-title">Workshop  <?=$i?></div>
                          <ul>
                            <li class="emply-info1"> Department: <?= $workshop['workshop_type']; ?> </li>
                            <li>
                              <div class="col-md-3">Workshop: <?= $workshop['title']; ?></div>
                              <div class="col-md-3">Presenter: <?= $workshop['presenter_name']; ?></div>
                              <div class="col-md-3">Date: <?= $workshop['start_date']; ?></div>
                              <div class="col-md-3">Time: <?= $workshop['time']; ?></div>
                            </li>
                            <li>
                              <div class="col-md-3">Venue: <?= $workshop['venue']; ?></div>
                              <div class="col-md-3">Attendee Limit: <?= $workshop['attendee_limit']; ?></div>
                              <div class="col-md-3">Registered: <?= $workshop['registered']; ?></div>
                              <div class="col-md-3">
							  <?php
							  if($workshop['spaces'] == 0)
							  {
							  ?>
								<small class="label label-important">Full</small>
							  <?php
							  }
							  else
							  {
							  ?>
								<a href="<?php echo base_url(); ?>workshops_staff/signup/<?php echo $workshop['workshop_id']; ?>" class="btn btn-small btn-success">Sign up</a>
							  <?php
							  }
							  ?>							  
							  </div>
                            </li>                           
                          </ul>
                        </div>
                      </div>
            	<?php
						$i++;
					}
				}else {
					echo 'No workshop found.';
				}?>
            </div>
      </div>
    </div>
  </div>
</div>
