<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-md-12">
    <div class="grid simple">
        <div class="grid-title">
            <h4>Questionnaire</h4>
        </div>
        <div class="grid-body ">
        	<div class="row">
                <div class="col-md-6">
                	<h4>Please note that your preferred choice is only an indication. 
 		The final decision of which period you can leave will be confirmed through your company after approval from the ELSD.<br><br></h4>

 		<h4>All staff must return to work on 17 August 2014</h4>
                </div>
                <div class="col-md-6">
                	<?php print form_open('questionnaire', array('id' => '','name'=>'')) ."\r\n"; ?>
                	<fieldset class="fieldset">
                        <legend class="legend">Vacation Survey:</legend>
                    <?php 
					if( $submitted == 2) { ?>
                    <div class="row form-row">
                        <div class="col-md-12">
                            <?php print form_label('Are you returning for the next academic year', 'academic_returning',array('class'=>'form-label')); ?>
                            <?php print form_dropdown('academic_returning',array('1'=>'Yes','2'=>'No'),$this->session->flashdata('academic_returning'),'id="academic_returning" class="select2 form-control" placeholder="Select Answer"'); ?>
                        </div>
                    </div>
            		<div class="row form-row">
                        <div class="col-md-12">
                            <?php print form_label('What is you prefered holiday date', 'holiday_pref',array('class'=>'form-label')); ?>
                            <?php print form_dropdown('holiday_pref',array('0' => 'N/A','1'=>'8th June - 7 July','2'=>'9th July - 16th August'),$this->session->flashdata('holiday_pref'),'id="holiday_pref" class="select2 form-control" placeholder="Select Period"'); ?>
                        </div>
                    </div>
                    <p>NB: The Eid vacation dates are 22nd July - 2nd August and they included in the second holiday</p>
                    <div class="row form-row">
                        <div class="col-md-12">               	
                            <input type="submit" name="submit" value="Submit" class="btn btn-success btn-cons">
                        </div>
                    </div>
                    <?php
					}else {
                    	echo '<p>Your preference has been recorded</p>';
                    }
					?>
                    </fieldset>
                    </form>
                </div>
            </div>
		</div>
	</div>
    </div>
</div>