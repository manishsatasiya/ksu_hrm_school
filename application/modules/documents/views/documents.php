<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-md-12">
    <div class="grid simple">
        <div class="grid-title">
            <h4>Documents</h4>
        </div>
        <div class="grid-body ">
            <p>Click / Right click on the link to save the document you wish to download.</p>
        
            <table class="table">
            <thead>
            <th>Human Resources:</th>
            </thead>
            <tbody>
            	<?php 
				if(count($all_documents['human_resources']) > 0 ) {
					foreach($all_documents['human_resources'] as $document) {
				?>
                    <tr>
                        <td><a href="<?php echo base_url().$document['file']; ?>" target="_blank"><?php echo $document['name']; ?></a>  | <a href="<?php echo base_url().'documents/add_document/'.$document['document_id']; ?>">Edit</a></td>
                    </tr>
                <?php
					}
				}else {
				?>
                	<tr>
                        <td>No document found.</td>
                    </tr>
                <?php
				} ?>
            </tbody>           
            </table>
        
            <table class="table margin-top">
            <thead>
            <th>Assessment:</th>
            </thead>
            <tbody>
                <?php 
				if(count($all_documents['assessment']) > 0 ) {
					foreach($all_documents['assessment'] as $document) {
				?>
                    <tr>
                        <td><a href="<?php echo base_url().$document['file']; ?>" target="_blank"><?php echo $document['name']; ?></a></td>
                    </tr>
                <?php
					}
				}else {
				?>
                	<tr>
                        <td>No document found.</td>
                    </tr>
                <?php
				} ?>
            </tbody>           
            </table>
        
            <table class="table margin-top">
            <thead>
            <th colspan="2">Professional Development:</th>
            </thead>
            <tbody>
                <?php 
				if(count($all_documents['professional_development']) > 0 ) {
					foreach($all_documents['professional_development'] as $document) {
				?>
                    <tr>
                        <td><a href="<?php echo base_url().$document['file']; ?>" target="_blank"><?php echo $document['name']; ?></a></td>
                    </tr>
                <?php
					}
				}else {
				?>
                	<tr>
                        <td>No document found.</td>
                    </tr>
                <?php
				} ?>
            </tbody>           
            </table>
        
            <table class="table margin-top">
            <thead>
            <tr>
                <th colspan="2">Curriculum:</th>
            </tr>            
            </thead>
            <tbody>
        	<tr>
                <td>
                	<table>
                    	<tr><th>Quarter 2:</th></tr>
                        <?php 
						if(count($all_documents['curriculum_quarter_2']) > 0 ) {
							foreach($all_documents['curriculum_quarter_2'] as $document) {
						?>
							<tr>
								<td><a href="<?php echo base_url().$document['file']; ?>" target="_blank"><?php echo $document['name']; ?></a></td>
							</tr>
						<?php
							}
						}else {
						?>
							<tr>
								<td>No document found.</td>
							</tr>
						<?php
						} ?>
                    </table>
                </td>
                <td>
                	<table>
                    	<tr><th>Quarter 2:</th></tr>
                        <?php 
						if(count($all_documents['curriculum_quarter_4']) > 0 ) {
							foreach($all_documents['curriculum_quarter_4'] as $document) {
						?>
							<tr>
								<td><a href="<?php echo base_url().$document['file']; ?>" target="_blank"><?php echo $document['name']; ?></a></td>
							</tr>
						<?php
							}
						}else {
						?>
							<tr>
								<td>No document found.</td>
							</tr>
						<?php
						} ?>
                    </table>
                </td>
            </tr>	
        	<tr>
                <?php 
				if(count($all_documents['curriculum']) > 0 ) {
					foreach($all_documents['curriculum'] as $document) {
				?>
					<tr>
						<td colspan="2"><a href="<?php echo base_url().$document['file']; ?>" target="_blank"><?php echo $document['name']; ?></a></td>
					</tr>
				<?php
					}
				}else {
				?>
					<tr>
						<td colspan="2">No document found.</td>
					</tr>
				<?php
				} ?>
            </tr>
            </tbody>           
            </table>
		</div>
	</div>
    </div>
</div>