<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<script type="text/javascript" src="<?php print base_url(); ?>js/grid/observation_scores.js"></script>

<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4>Observation Scores</h4>
			</div>
			<div class="grid-body ">
				<table class="table" id="grid_observations">
					<thead>
						<tr>
                        	<th><?php echo $this->lang->line('teacher_p_full_name'); ?></th>
                            <th><?php echo $this->lang->line('elsid'); ?></th>
                            <th>Observation Date</th>
                            <th>Observer</th>
                            <th>Score</th>
							<th>Notes</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
                       		<th><?php echo $this->lang->line('teacher_p_full_name'); ?></th>
                            <th><?php echo $this->lang->line('elsid'); ?></th>
                            <th>Observation Date</th>
                            <th>Observer</th>
                            <th>Score</th>
							<th>Notes</th>
						</tr>
					</tfoot>
					<tbody>
					
					</tbody>
				</table>
			</div>    
		</div>
	</div>
</div>