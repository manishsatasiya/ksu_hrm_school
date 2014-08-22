<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4>My Induction</h4>
			</div>
			<div class="grid-body">
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Curriculum Framework', 'Curriculum_Framework',array('class'=>'form-label')); ?> 
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Curriculum_Framework"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('Oxford iTools & Smart Board', 'Oxford_iTools_Smart_Board',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Oxford_iTools_Smart_Board"]:"" ?> 
					</div>
				</div>
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Educational Technology', 'Educational_Technology',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Educational_Technology"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('The Saudi Learner', 'The_Saudi_Learner',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["The_Saudi_Learner"]:"" ?> 
					</div>
				</div>
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Professional Development', 'Professional_Development',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Professional_Development"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('Classroom Management', 'Classroom_Management',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Classroom_Management"]:"" ?> 
					</div>
				</div>
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Students Affairs', 'Students_Affairs',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Students_Affairs"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('Lesson Planning', 'Lesson_Planning',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Lesson_Planning"]:"" ?> 
					</div>
				</div>
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Academic Administration & Quality', 'Academic_Administration_Quality',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Academic_Administration_Quality"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('New ELSD Portal Training', 'New_ELSD_Portal_Training',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["New_ELSD_Portal_Training"]:"" ?> 
					</div>
				</div>
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Academic HR', 'Academic_HR',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Academic_HR"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('New Headway Plus', 'New_Headway_Plus',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["New_Headway_Plus"]:"" ?> 
					</div>
				</div>
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Assessment', 'Assessment',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Assessment"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('Headway Academic Skills', 'Headway_Academic_Skills',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php (isset($rowdata))?print $rowdata["Headway_Academic_Skills"]:"" ?> 
					</div>
				</div>
				<div class="row form-row">
					<div class="col-md-3"> 
						<?php print form_label('Management Information', 'Management_Information',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php print (isset($rowdata))?$rowdata["Management_Information"]:"" ?> 
					</div>
					<div class="col-md-3"> 
						<?php print form_label('Qskills Orientation', 'Qskills_Orientation',array('class'=>'form-label')); ?>  
					</div>
					<div class="col-md-1"> 	
						<?php print (isset($rowdata))?$rowdata["Qskills_Orientation"]:"" ?> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>