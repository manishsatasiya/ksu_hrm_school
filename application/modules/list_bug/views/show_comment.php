<script type="text/javascript" src="<?php print base_url(); ?>js/jQuery/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/custom.js"></script>
<?php 
if($systembug_data){
	foreach ($systembug_data->result() as $systembug_datas):
?>	
<div class="author-meta">
	<h2><?php echo $systembug_datas->bug_title;?></h2>
	<p><?php echo $systembug_datas->description;?></p>
</div>
<?php
	endforeach;
} 
?>
<div class="list_bug_comment">
	<h2 id="cntcomm">Comments (<?php echo $count_comment; ?>)</h2>
	<div class="post-content" id="post-content">   
		<?php
		if($bugcomment_data){
			foreach ($bugcomment_data->result() as $bugcomment_datas): 
		?>     
		<div class="post-body">
			<div>                                    
				<span class="name"><?php echo $bugcomment_datas->first_name;?></span>
				<span class="time-ago">(<?php echo time_ago($bugcomment_datas->created_date);?>)</span>
			</div>    
			<div class="post-message"> 
				<p><?php echo $bugcomment_datas->comment;?></p>                            
			</div>        
		</div>
		<?php
			endforeach;
		} 
		?>
		
	</div>
	<div class="post_comment_box">
		<form action="" method="post" id="frmcomment" name="frmcomment">
		<div class="comment_box">	
			<textarea rows="5" cols="90" name="comment" id="comment"></textarea>
		</div>
		<div clss="left">
			<button class="btn submit" type="button" onclick="submitpost(<?php echo $system_bug_id; ?>);">Post</button>
		</div>
		</form>	
	</div>
</div>
