<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" charset="utf-8">
function reload_datatable(){
	<?php
	foreach($grade_type AS $grade_type_id=>$grade_type_data)
	{
		if(!isset($grade_type_exam[$grade_type_id])) continue;
	?>			
		dTable_<?php echo $grade_type_id;?>.fnStandingRedraw();
	<?php
		break;
	}
	?>	
}

$(document).ready(function() {
	$('#export_file').submit( function() {
		var sData = $('input:text').val();
		$("#search_section").val(sData);
		$("#search_section").val(sData);
		$("#export_file").submit();
		return false;
	});
	<?php
	$tabcnt = 0;
	foreach($grade_type AS $grade_type_id=>$grade_type_data)
	{
		if(!isset($grade_type_exam[$grade_type_id])) continue;
			
		$arr_grade_exam = $grade_type_exam[$grade_type_id];
	?>
		dTable_<?php echo $grade_type_id;?> = $('#gridgrade<?php echo $grade_type_id;?>').dataTable({
				bJQueryUI:false,
				bStateSave:true,
				iDisplayLength:15,
				"aLengthMenu": [[15, 30, 45, 100], [15, 30, 45, 100]],
				bProcessing:true,
				bServerSide: true,
				"oLanguage": {"sSearch": "Search Section/Student ID:"},
				"bScrollCollapse": true,
				sAjaxSource: "list_grade/index_json?gtid=<?php echo $grade_type_id;?>",
				"sDom": 'fCl<"clear">rtip',
				"oColVis": {
					"aiExclude": [table_total_col-1]
				},
				aoColumns: [
							{"bSortable": true}, 
							{"bSortable": true}, 
							{"bSortable": true}
							<?php
							$str_grade_type_exam_id = "";
							 foreach($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
							 {
								$str_grade_type_exam_id .= $grade_type_exam_id.",";
								
								if($grade_type_data["attendance_type"] == "examwise")
								{
							?>	
								,{"sName": "exam_status|<?php echo $grade_type_exam_id;?>","bSortable": false}
							<?php	
								}
								
								if($grade_type_exam_data["is_two_marker"] == "Yes")
								{
							?>
								,{"sName": "exam_marks|<?php echo $grade_type_exam_id;?>","bSortable": false}
							<?
								}
								else
								{
							?>
								,{"sName": "exam_marks|<?php echo $grade_type_exam_id;?>","bSortable": false}
							<?
								}
								
								if($grade_type_exam_data["is_show_percentage"] == "Yes")
								{
							?>	
								,{"bSortable": false}
							<?php
								}
								if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
								{
							?>
									,{"sName": "verified|<?php echo $grade_type_exam_id;?>","bSortable": false}
									,{"sName": "lead_verified|<?php echo $grade_type_exam_id;?>","bSortable": false}
							<?php		
								}
							}
							if($grade_type_data["verification_type"] == "common" && $grade_type_data["is_show_verified"] == "Yes")
							{
							?>
							,{"sName": "verified|<?php echo $str_grade_type_exam_id;?>","bSortable": false}
							,{"sName": "lead_verified|<?php echo $str_grade_type_exam_id;?>","bSortable": false}
							<?php
							}
							if($grade_type_data["attendance_type"] == "common")
							{
							?>
							,{"sName": "exam_status|<?php echo $str_grade_type_exam_id;?>","bSortable": false}
							<?php
							}
							if($grade_type_data["show_total_marks"] == "Yes")
							{
							?>
							,{"bSortable": false}
							<?php
							}
							if($grade_type_data["show_grade_range"] == "Y")
							{
							?>
							,{"bSortable": false}
							<?php
							}
							if($grade_type_data["show_total_per"] == "Yes")
							{
							?>
							,{"bSortable": false}
							<?php
							}
							?>
					   ],
				/*"aoColumnDefs": [
					{ "sWidth": "10%", "aTargets": [ -1 ] }
				],
				"bPaginate": true,*/
				sPaginationType: "bootstrap"
				
		});
		 
		dTable_<?php echo $grade_type_id;?>.makeEditable({
			sUpdateURL: function(value, settings)
			{
				return(value); //Simulation of server-side response using a callback function
			},
			sUpdateURL: "list_grade/update_grade?gtid=<?php echo $grade_type_id;?>",
						"aoColumns": [
						null,	
						null,	
						null	
						<?php
						 foreach($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
						 {
						?>
						<?php 
						if($grade_type_data["attendance_type"] == "examwise")
						{
							if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
							{
							?>
								,{
								indicator: 'Saving ...',
								tooltip: 'Click to edit',
								type: 'select',
								onblur: 'cancel',
								submit: 'Save',
											data: "{ 'Select':'Select','Absent':'Absent','Present':'Present','Cheating':'Cheating','Makeup':'Makeup','IELTS':'IELTS'}"
								}
							<?php 
							}
							else
							{
							?>
							,null
							<?php
							}
						}
						if(($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction)) && $grade_type_exam_data["is_two_marker"] != "Yes")
						{
						?>
							,{
							indicator: 'Saving ...',
							tooltip: 'Click to edit',
							type: 'text',
							submit:'Save changes'
							}
						<?php 
						}
						else
						{
						?>
						,null
						<?php
						}
						if($grade_type_exam_data["is_show_percentage"] == "Yes")
						{
						?>
						,null
						<?php 
						}
						if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
						{
						?>
						,null
						,null
						<?php		
						}
						 }
						?>
						<?php 
						if($grade_type_data["verification_type"] == "common" && $grade_type_data["is_show_verified"] == "Yes")
						{
						?>
						,null
						,null
						<?php
						}
						?>
						<?php 
						if($grade_type_data["attendance_type"] == "common")
						{
							if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
							{
							?>
								,{
								indicator: 'Saving ...',
								tooltip: 'Click to edit',
								type: 'select',
								onblur: 'cancel',
								submit: 'Save',
											data: "{ 'Select':'Select','Absent':'Absent','Present':'Present','Cheating':'Cheating','Makeup':'Makeup','IELTS':'IELTS'}"
								}
							<?php 
							}
							else
							{
							?>
							,null
							<?php
							}
						}
						if($grade_type_data["show_total_marks"] == "Yes")
						{
						?>
						,null
						<?php
						}
						if($grade_type_data["show_grade_range"] == "Y")
						{
						?>
						,null
						<?php
						}
						if($grade_type_data["show_total_per"] == "Yes")
						{
						?>
						,null
						<?php
						}
						?>
						],
			fnShowError:function(message, action){
				switch (action) {
					case "update":
						if(message != "success")
							jAlert(message, "Please Enter Grade Updated Reason");
							$("#popup_panel").html("");
						break;
					case "add":
						$("#lblAddError").html(message);
						$("#lblAddError").show();
						break;
				}
			}, 
			fnStartProcessingMode: function () {
				$("#processing_message").dialog();
			},
			fnEndProcessingMode: function () {
				$("#processing_message").dialog("close");
			}
		});
	<?php
	$tabcnt++;
	}
	if($show_total_tab == "Yes")
	{
	?>
	dTable_1111 = $('#example1111').dataTable({
		bJQueryUI:false,
		iDisplayLength:25,
		bProcessing:true,
		bServerSide: true,
		"oLanguage": {"sSearch": "Search Section/Student ID:"},
		"bScrollCollapse": true,
		"sDom": 'fCl<"clear">rtip',
		
		sAjaxSource: "list_grade/index_json?gtid=1111",
		aoColumns: [
					{"bSortable": true}, 
					{"bSortable": true}, 
					{"bSortable": true}, 
					<?php
					foreach($grade_type AS $grade_type_id=>$grade_type_data)
					{
						if($grade_type_data["show_total_marks"] == "Yes")
						{
					?>
						{"bSortable": false}, 
					<?php
						}
						if($grade_type_data["show_grade_range"] == "Y")
						{
					?>
						{"bSortable": false}, 
					<?php
						}
						if($grade_type_data["show_total_per"] == "Yes")
						{	
					?>	
						{"bSortable": false}, 
					<?php	
						}
					}
					?>
					{"bSortable": false}
					<?php
					if($show_grade_range == "Yes")
					{
					?>
					,{"bSortable": false}	
					<?php
					}
					?>
			   ],
		"aoColumnDefs": [
			{ "sWidth": "10%", "aTargets": [ -1 ] }
		],
		"bPaginate": true,
		sPaginationType: "bootstrap"});	
	<?php
	}
	?>
	$('ul.list_grade_tabs li').click(function(){
		var selected = $(this).attr("name");
		$("#search_gradetype").val(selected);
		var table = $.fn.dataTable.fnTables(true);
		if ( table.length > 0 ) {
			$(table).dataTable().fnAdjustColumnSizing();
		}
	});
});
</script>
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
<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4><?php echo $this->lang->line('list_grade'); ?></h4>
              <div class="export_to_excel">
					<form id="export_file" action="list_grade/export_to_excel" target="download_iframe" method="POST">
                    	<div class="col-md-8">
                            <input type="hidden" name="search_section" id="search_section" value="">
                            <input type="hidden" name="search_gradetype" id="search_gradetype" value="">
                        </div>    
                        <div class="col-md-4">
                            <input type="submit" name="submit" value="Export To XLS" class="btn btn-info">
                        </div>
                    </form>		
              </div>
			</div>
			<div class="grid-body ">
				
                <ul class="nav nav-tabs list_grade_tabs" id="tab-01">
                <?php
                $tabcnt = 0;
                foreach($grade_type AS $grade_type_id=>$grade_type_data)
                {
                    ?>
                    <li class="<?php if($tabcnt == 0) { echo 'active'; }?>" name="<?=$tabcnt?>"><a href="#tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>"><?php echo $grade_type_data["grade_type"];?></a></li>
                    <?php
                    $tabcnt++;
                }
                if($show_total_tab == "Yes")
                {
                    ?>
                    <li><a href="#tabs_<?php echo $tabcnt;?>_1111">Totals</a></li>
                    <?php
                }	
                ?>
                </ul>
                <div class="tab-content">
                    <?php
                    $tabcnt = 0;
                    foreach($grade_type AS $grade_type_id=>$grade_type_data)
                    {
                    ?>
                        <div class="tab-pane <?php if($tabcnt == 0) { echo 'active'; }?>" id="tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>">	
                        <?php
                            if(isset($grade_type_exam[$grade_type_id]))
                            {
                                $arr_grade_exam = $grade_type_exam[$grade_type_id];	
                        ?>		
                            <table class="table" id="gridgrade<?php echo $grade_type_id;?>">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Student ID</th>
                                        <th rowspan="2">Section</th>
                                        <th rowspan="2">Student Name</th>
                                        <?php
                                        foreach($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
                                        {
                                            $colspanplus = 0;
                                            if($grade_type_exam_data["is_show_percentage"] == "Yes")
                                                $colspanplus += 1;
                                                
                                            if($grade_type_data["attendance_type"] == "examwise")
                                                $colspanplus += 1;
                                            if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
                                                $colspanplus += 2;	
                                        ?>
                                            <th colspan="<?php echo (1+$colspanplus);?>"><?php echo $grade_type_exam_data["exam_type_name"];?></th>
                                        <?php						
                                        }
                                        
                                        if($grade_type_data["verification_type"] == "common" && $grade_type_data["is_show_verified"] == "Yes")
                                        {
                                        ?>
                                        <th rowspan="2"><?php echo $this->lang->line('sub_att_p_field_verified'); ?></th>
                                        <th rowspan="2">LT.Verify</th>
                                        <?php
                                        }
                                        
                                        if($grade_type_data["attendance_type"] == "common")
                                        {
                                        ?>
                                        <th rowspan="2">A</th>
                                        <?php
                                        }
                                        if($grade_type_data["show_total_marks"] == "Yes")
                                        {
                                        ?>
                                        <th rowspan="2">Total Marks(<?php echo $grade_type_data["total_markes"];?>)</th>
                                        <?php
                                        }
                                        if($grade_type_data["show_grade_range"] == "Y")
                                        {
                                        ?>
                                        <th rowspan="2">Range</th>
                                        <?php
                                        }
                                        if($grade_type_data["show_total_per"] == "Yes")
                                        {
                                        ?>
                                        <th rowspan="2">TOTAL <?php echo $grade_type_data["total_percentage"];?>%</th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                    <tr>
                                        <?php
                                        foreach($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
                                        {
                                            if($grade_type_data["attendance_type"] == "examwise")
                                            {
                                        ?>
                                                <th>A</th>
                                        <?php		
                                            }
                                        ?>
                                                <th><?php echo $grade_type_exam_data["exam_marks"];?></th>
                                        <?php
                                            if($grade_type_exam_data["is_show_percentage"] == "Yes")
                                            {
                                            ?>			
                                                <th><?php echo $grade_type_exam_data["exam_percentage"];?>%</th>
                                        <?php
                                            }
                                        if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
                                        {
                                        ?>		
                                            <th><?php echo $this->lang->line('sub_att_p_field_verified'); ?></th>
                                            <th>LT.Verify</th>
                                        <?php		
                                        }	
                                        }
                                        ?>						
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <?php
                            }
                            else
                            {
                                echo "<div align='center'><b>No Exam Found</b></div>";
                            }
                            ?>
                        </div>
                        <?php
                        $tabcnt++;
                    }
                    if($show_total_tab == "Yes")
                    {
                    ?>
                        <div class="tab-pane" id="tabs_<?php echo $tabcnt;?>_1111">
                            <table class="table" id="example1111">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Section</th>
                                        <th>Student Name</th>
                                        <?php
                                        foreach($grade_type AS $grade_type_id=>$grade_type_data)
                                        {
                                            if($grade_type_data["show_total_marks"] == "Yes")
                                            {
                                        ?>
                                            <th><?php echo $grade_type_data["grade_type"];?> Marks(<?php echo $grade_type_data["total_markes"];?>)</th>
                                        <?php
                                            }
                                            if($grade_type_data["show_grade_range"] == "Y")
                                            {
                                        ?>
                                            <th>Range</th>
                                        <?php
                                            }
                                            if($grade_type_data["show_total_per"] == "Yes")
                                            {
                                        ?>	
                                            <th><?php echo $grade_type_data["grade_type"];?> %(<?php echo $grade_type_data["total_percentage"];?>)</th>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <th>TOTAL 100%</th>
                                        <?php
                                        if($show_grade_range == "Yes")
                                        {
                                        ?>
                                        <th>Range</th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    <?php
                    }
                    ?>
                </div>    
					
			</div>
		</div>
	</div>
</div>