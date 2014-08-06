$(document).ready(function(){	
	$('select').each(function(index){
		var select_option_count = $(this).children().length;
		
		if(select_option_count > 10){
			if(!$(this).hasClass('select2')){
				$(this).addClass('select2');
			}
		}else{
			if(!$(this).hasClass('select2-wrapper')){
			$(this).addClass('select2-wrapper');
				
			}
		}
	});
	
	$('select.select2').select2();	
	$(".select2-wrapper").select2({minimumResultsForSearch: -1});
	
	$('select').change(function(){
		$(this).removeClass('error');
		$(this).next('span.error').remove();
	});
});

/*$.validator.setDefaults({
	submitHandler: function() { return true }
});*/

$(document).ready(function() {
	
	// validate signup form on keyup and submit
	$("#add_school_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			school_name: "required",
			principal: "required",
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			school_name: "Please enter your school name",
			principal: "Please enter your principal",
			email: "Please enter a valid email address"
		}
	});
	
	// validate add school year form on keyup and submit
	$("#add_school_year_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			school_id: {
				comboboxNotNone: true
			},
			school_year:{
				digits: true,
				required:true,
				minlength: 4,
				maxlength: 4
			},
			school_week:{
				lessthan: true,
				digits: true,
				required:true
			},
			school_year_title:{
				required:true
			}
		},
		messages: {
			school_id: "Please enter your school name",
			school_year_title: "Please enter school year title"
		}
	});
	
	// validate add school year form on keyup and submit
	$("#add_student_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			section_id: {
				comboboxNotNone: true
			},
			first_name:{				
				required:true
			},
			last_name:{				
				required:true
			},
			email:{				
				required:true,
				email: true
			},
			username:{				
				required:true
			},
			password:{				
				required:true
			},
			password_confirm: {
			  	equalTo: "#reg_password"
			},
			student_uni_id: {
				required:true
			}
		},
		messages: {
			section_id: "Please enter section name",
			first_name: "Please enter your first name",
			last_name: "Please enter your last name",
			email: "Please enter your email",
			username: "Please enter username",
			password: "Please enter password",
			student_uni_id: "Please enter student university id."
		}
	});
	
	// validate add school year form on keyup and submit
	$("#add_teacher_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			name_suffix: {
				comboboxNotNone: true
			},
			first_name:{				
				required:true
			},
			last_name:{				
				required:true
			},
			email:{				
				required:true,
				email: true
			},
			username:{				
				required:true
			},			
			password:{				
				required:true
			},
			password_confirm: {
				required:true,
			  	equalTo: "#reg_password"
			}
		},
		messages: {			
			first_name: "Please enter your first name",
			last_name: "Please enter your last name",
			email: "Please enter your email",
			birth_date: "Please enter your brith date",
			birth_place: "Please enter your brith place",
			username: "Please enter username",
			password: "Please enter password"
		}
	});
	
	// validate add school year form on keyup and submit
	$("#add_course_category_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			category_title: {
				required: true
			}
		},
		messages: {			
			category_title: "Please enter course category title"
		}
	});
	
	// validate add school year form on keyup and submit
	$("#add_course_section_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			section_title: {
				required: true
			}
		},
		messages: {			
			section_title: "Please enter course section"
		}
	});
	// validate add school year form on keyup and submit
	$("#add_course_class_room_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			class_room_title: {
				required: true
			}
		},
		messages: {			
			class_room_title: "Please enter course class room"
		}
	});
	// validate add school year form on keyup and submit
	$("#add_courses_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			
			course_title:{				
				required: true
			},
		max_hours:{				
			required:true,
			digits: true,
			lessthan: true
		}
			
		},
		messages: {			
			course_title: "Please enter course title"
		}
	});
	
	// validate add school year form on keyup and submit
	$("#add_courses_subject_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			school_id: {
				comboboxNotNone: true
			},
			school_year_id:{				
				comboboxNotNone: true
			},
			subject_title:{				
				required:true
			},
			subject_code:{				
				required:true
			}
		},
		messages: {			
			subject_title: "Please enter subject title",
			subject_code: "Please enter subject code"
		}
	});
	
	// validate add school year form on keyup and submit
	$("#add_course_class_form").validate({
		submitHandler: function(form) {
			form.submit();
		},
		rules: {
			
			school_year_id:{				
				comboboxNotNone: true
			},
			course_id:{				
				comboboxNotNone: true
			},
			category_id:{				
				comboboxNotNone: true
			},
			primary_teacher_id:{				
				comboboxNotNone: true
			},
			class_room_id:{				
				comboboxNotNone: true
			},
			section_id:{				
				comboboxNotNone: true
			}
		}
	});
	
	$.validator.addMethod('comboboxNotNone', function(value, element) {
        return (value != '0');
    }, 'Please select an option.');
	
	$.validator.addMethod('lessthan', function(value, element) {
        return (value > '0');
    }, 'Please enter valid value.');

	// validate signup form on keyup and submit
	$("#signupForm").validate({
		rules: {
			firstname: "required",
			lastname: "required",
			username: {
				required: true,
				minlength: 2
			},
			password: {
				required: true,
				minlength: 5
			},
			confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			topic: {
				required: "#newsletter:checked",
				minlength: 2
			},
			agree: "required"
		},
		messages: {
			firstname: "Please enter your firstname",
			lastname: "Please enter your lastname",
			username: {
				required: "Please enter a username",
				minlength: "Your username must consist of at least 2 characters"
			},
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			confirm_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			email: "Please enter a valid email address",
			agree: "Please accept our policy"
		}
	});
	
	$("#add_student_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			section_id: {
				comboboxNotNone: true
			},
			first_name:{				
				required:true
			},
			last_name:{				
				required:true
			},
			email:{				
				required:true,
				email: true
			},
			username:{				
				required:true
			},
			password:{				
				required:true
			},
			password_confirm: {
			  	equalTo: "#reg_password"
			},
			student_uni_id: {
				required:true
			}
		},
		messages: {
			section_id: "Please enter section name",
			first_name: "Please enter your first name",
			last_name: "Please enter your last name",
			email: "Please enter your email",
			username: "Please enter username",
			password: "Please enter password",
			student_uni_id: "Please enter student university id."
		}
	});
	//code to hide topic selection, disable for demo
	//var newsletter = $("#newsletter");
	// newsletter topics are optional, hide at first
	//var inital = newsletter.is(":checked");
	//var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
	//var topicInputs = topics.find("input").attr("disabled", !inital);
	// show when newsletter is checked
	/*newsletter.click(function() {
		topics[this.checked ? "removeClass" : "addClass"]("gray");
		topicInputs.attr("disabled", !this.checked);
	});*/
	
	$("#add_campus_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						parent.reload_datatable();
						$('#myModal').delay(1000).modal('hide');
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			campus_name: {
				required: true
			},
			campus_location: {
				required: true
			}
		},
		messages: {
			campus_name: "Please enter campus name",
			campus_location: "Please enter campus location"
		}
	});
	
	$("#add_school_year_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			school_id: {
				comboboxNotNone: true
			},
			school_year: {
				digits: true,
				required:true,
				minlength: 4,
				maxlength: 4
			},
			school_week:{
				lessthan: true,
				digits: true,
				required:true
			},
			school_year_title:{			
				required:true
			}
		},
		messages: {
			school_id: "Please enter your school name",
			school_year_title: "Please enter school year title"	
		}
	});
	
	if(CI.controller_name == 'list_student')
	{
		$(function() {
			$( "#reg_student_schedule_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: 'c-50:c+0'
			});
		});
	}
		
	$("#add_teacher_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			elsd_id: {
				required: true
			},
			first_name:{				
				required:true
			},
			last_name:{				
				required:true
			},
			email:{				
				required:true,
				email: true
			},
			username:{				
				required:true
			},			
			password:{				
				required:true
			},
			password_confirm: {
				required:true,
				equalTo: "#reg_password"
			}
		},
		messages: {
			elsd_id: "Please enter ELSD ID",
			first_name: "Please enter your first name",
			last_name: "Please enter your last name",
			email: "Please enter your email",
			username: "Please enter username",
			password: "Please enter password"
		}
	});
	
	$("#add_course_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			course_title:{				
				required: true
			},
			max_hours:{				
				required:true,
				digits: true,
				lessthan: true
			}
		},
		messages: {
			course_title: "Please enter course title"
		}
	});
	
	$("#add_section_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			section_title: {
				required: true
			}
		},
		messages: {
			section_title: "Please enter course section"
		}
	});
	
	$("#add_class_room_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			class_room_title: {
				required: true
			}
		},
		messages: {
			class_room_title: "Please enter course class room"
		}
	});
	
	$("#add_course_class_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
					}
				}
			});
			
		},
		rules: {
			
			school_year_id:{				
				comboboxNotNone: true
			},
			course_id:{				
				comboboxNotNone: true
			},
			primary_teacher_id:{				
				comboboxNotNone: true
			},
			class_room_id:{				
				comboboxNotNone: true
			},
			section_id:{				
				comboboxNotNone: true
			}
		}
	});
	
	$("#add_grade_type_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			grade_type: {
				required: true
			},
			total_percentage: {
				required: true,
				number: true
			},
			total_percentage: {
				number: true
			}
		},
		messages: {
			grade_type: "Please enter grade type",
			total_percentage: "Please enter percentage"
		}
	});
	
	$("#add_grade_type_exam_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			exam_type_name: {
				required: true
			},
			exam_marks: {
				number: true
			},
			exam_percentage: {
				number: true
			},
			
		},
		messages: {
			exam_type_name: "Please enter grade exam type"
		}
	});
	
	
	$("#add_grade_range_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			grade_min_range: {
				required: true
			},
			grade_max_range: {
				required: true
			},
			grade_name: {
				required: true
			}
		},
		messages: {
			grade_min_range: "Please enter minimum ragne",
			grade_max_range: "Please enter maximum range",
			grade_name: "Please enter grade"
		}
	});
	
	$("#add_role_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			user_roll_name: {
				required: true
			}
		},
		messages: {
			user_roll_name: "Please enter role."
		}
	});
	
	if(CI.controller_name == 'list_user')
	{
		$(function() {
			$( "#reg_birth_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: 'c-50:c+0'
			});
		});
				
		$(function() {
			$( "#start_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: 'c-50:c+0'
			});
		});
		
		$(function() {
			$( "#end_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: 'c-50:c+0'
			});
		});
	}	
	$("#add_user_form_datatable").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
					}
				}
			});
			
		},
		rules: {
			user_roll_id: {
				comboboxNotNone: true
			},
			first_name:{				
				required:true
			},
			email:{				
				required:true,
				email: true
			},
			username:{				
				required:true
			},			
			password:{				
				required:true
			},
			password_confirm: {
				required:true,
				equalTo: "#reg_password"
			}
		},
		messages: {
			user_roll_id: "Please select role",
			first_name: "Please enter your first name",
			email: "Please enter your email",
			username: "Please enter username",
			password: "Please enter password"
		}
	});
	
	if(CI.controller_name == 'workshops')
	{
		$(function() {
			$( "#reg_start_date" ).datepicker({
				format: 'D dd MM yyyy'
			});
		});
		
		$('#reg_time').timepicker({});
	}
	
	$("#add_workshop_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			presenter: {
				comboboxNotNone: true
			},
			title:{				
				required:true
			},
			attendee_limit:{				
				required:true
			},
			topic:{				
				required:false,
			},
			venue:{				
				required:false
			},
			time:{				
				required:true
			},
			start_date: {
				required:true
			},
			semester: {
				comboboxNotNone: true
			},
			workshop_type_id: {
				comboboxNotNone: true
			},
			status: {
				comboboxNotNone: true
			}			
		},
		messages: {
			presenter: "Please selct presenter",
			title: "Please enter your title",
			attendee_limit: "Please enter attendee limit",
			time: "Please enter time",
			start_date: "Please select date",
			semester: "Please select semester",
			workshop_type_id: "Please select required",
			status: "Please select status"
		}
	});
	
	$("#add_attendee_form").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						parent.reload_datatable();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
					}
				}
			});
		},
		rules: {
			attendee: {
				comboboxNotNone: true
			},
			workshop_id: {
				comboboxNotNone: true
			}			
		},
		messages: {
			attendee: "Please selct attendee",
			workshop_id: "Please select workshop"
		}
	});
	
	$("#add_contractors_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			contractor:{				
				required:true
			}			
		},
		messages: {
			contractor: "Please enter contractor"
		}
	});
	
	$("#add_workshop_type_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			type:{				
				required:true
			}			
		},
		messages: {
			type: "Please enter workshop type"
		}
	});
	
	$("#add_duties_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			user_roll_id: {
				comboboxNotNone: true
			},
			duties:{				
				required:true
			}			
		},
		messages: {
			user_roll_id: "Please select role",
			duties: "Please enter duties"
		}
	});
	
	//add_profile Validations
	var $validator = $("#add_profile").validate({
		  rules: {
			status: {
		      required: true,
		    }, 
		    first_name: {
		      required: true
		    },
			last_name: {
		      required: true
		    },
			gender: {
		      required: true,
		    },
			username: {
		      required: true,
		      email: true,
		    },
			password: {
		      required: true,
		    },
			password_confirm: {
		      required: true,
			  equalTo: "#password_confirm"
		    },
			user_roll_id: {
		      required: true,
		    },
			system_roll_id: {
		      required: true,
		    },
			returning: {
		      required: true,
		    },
			contractor: {
		      required: true,
		    },
			cell_phone: {
		      required: true,
		    }
		  },
		  messages: {
			status: "Please select status",
			first_name:"Please enter first name",
			last_name:"Please enter last anme",
			gender:"Please select gender",
			username:"Please enter login email",
			password:"Please enter login password",
			user_roll_id:"Please select KSU role",
			system_roll_id:"Please select System role",
			returning:"Please select returning employee",
			contractor:"Please select contracor",
			cell_phone:"Please enter your mobile"
		  },
		  errorPlacement: function(label, element) {
				$('<span class="arrow"></span>').insertBefore(element);
				$('<span class="error"></span>').insertAfter(element).append(label)
			}
		});

	$('#rootwizard').bootstrapWizard({
	  		'tabClass': 'form-wizard',
			onTabClick: function(tab, navigation, index) {return false;},
	  		'onNext': function(tab, navigation, index) {
			
	  			var $valid = $("#add_profile").valid();
	  			if(!$valid) {
	  				$validator.focusInvalid();
	  				return false;
	  			}
				else{
					$('#rootwizard').find('.form-wizard').children('li').eq(index-1).addClass('complete');
					$('#rootwizard').find('.form-wizard').children('li').eq(index-1).find('.step').html('<i class="fa fa-check"></i>');	
				}
	  		}
	 });
	
	//edit_profile Validations
	var $edit_validator = $("#edit_profile").validate({
		  rules: {
			status: {
		      required: true,
		    }, 
		    first_name: {
		      required: true
		    },
			last_name: {
		      required: true
		    },
			gender: {
		      required: true,
		    },
			username: {
		      required: true,
		      email: true,
		    },
			user_roll_id: {
		      required: true,
		    },
			system_roll_id: {
		      required: true,
		    },
			returning: {
		      required: true,
		    },
			contractor: {
		      required: true,
		    },
			cell_phone: {
		      required: true,
		    }
		  },
		  messages: {
			status: "Please select status",
			first_name:"Please enter first name",
			last_name:"Please enter last anme",
			gender:"Please select gender",
			username:"Please enter login email",
			user_roll_id:"Please select KSU role",
			system_roll_id:"Please select System role",
			returning:"Please select returning employee",
			contractor:"Please select contracor",
			cell_phone:"Please enter your mobile"
		  },
		  errorPlacement: function(label, element) {
				$('<span class="arrow"></span>').insertBefore(element);
				$('<span class="error"></span>').insertAfter(element).append(label)
			}
		});

	$('#edit_rootwizard').bootstrapWizard({
	  		'tabClass': 'form-wizard',
	  		'onNext': function(tab, navigation, index) {
			
	  			var $valid = $("#edit_profile").valid();
	  			if(!$valid) {
	  				$edit_validator.focusInvalid();
	  				return false;
	  			}
				else{
					$('#rootwizard').find('.form-wizard').children('li').eq(index-1).addClass('complete');
					$('#rootwizard').find('.form-wizard').children('li').eq(index-1).find('.step').html('<i class="fa fa-check"></i>');	
				}
	  		}
	 });
	
	$("#add_user_qualifications").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						location.reload();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			qualification_id: {
				comboboxNotNone: true
			},
			subject_related:{
				comboboxNotNone: true
			},
			date:{			
				required:true
			}
		},
		messages: {
			qualification_id: "Please select Qualification",
			subject_related: "Please select English Related",
			date: "Please select Date"
		}
	});
	
	$("#add_user_certificate").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						location.reload();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			certificate_id: {
				comboboxNotNone: true
			},
			date:{			
				required:true
			}
		},
		messages: {
			qualification_id: "Please select certificate",
			date: "Please select Date"
		}
	});
	
	$("#add_user_experience").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						location.reload();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			company: {
				required:true
			},
			position:{
				required:true
			},
			start_date:{			
				required:true
			},
			end_date:{
				required:true
			},
			departure_reason:{			
				required:true
			}
		},
		messages: {
			company: "Please enter company",
			position: "Please enter position",
			start_date: "Please select start date",
			end_date: "Please select end ate",
			departure_reason: "Please enter leaving reason"
		}
	});
	
	$("#add_emergency_contact").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						location.reload();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			name: {
				required:true
			},
			relation:{
				required:true
			},
			contact_number:{			
				required:true
			}
		},
		messages: {
			name: "Please enter name",
			relation: "Please enter relation",
			contact_number: "Please enter contact number"
		}
	});
	
	$("#add_appointment").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(data){
					if(data == ''){
						$('#myModal').delay(1000).modal('hide');
						location.reload();
					}else{
						$(".containerfdfdf").show();
						$(".containerfdfdf").html(data);
						$('.containerfdfdf').find('p').css('color', 'red');
						$('.containerfdfdf').find('p').css('background-image', 'url("images/delete.png")');
						$('.containerfdfdf').find('p').css('background-repeat', 'no-repeat');
						$('.containerfdfdf').find('p').css('padding-left', '20px');
						
					}
				}
			});
			
		},
		rules: {
			date: {
				required:true
			},
			time:{
				required:true
			},
			details:{			
				required:true
			}
		},
		messages: {
			date: "Please select date",
			time: "Please enter time",
			details: "Please enter details"
		}
	});
	
	$("#add_document").validate({
		rules: {
			roll_id: {
				comboboxNotNone: true
			},
			name:{
				required:true
			},
			file:{			
				required:true
			}
		},
		messages: {
			roll_id: "Please select roll",
			name: "Please enter name",
			file: "Please select file"
		}
	});
	
	$("#add_contracted_numbers_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			contractor_id: {
				comboboxNotNone: true
			},
			campus_id: {
				comboboxNotNone: true
			},
			user_roll_id: {
				comboboxNotNone: true
			},
			contracted_numbers:{				
				required:true
			}			
		},
		messages: {
			contractor_id: "Please select contractor",
			campus_id: "Please select campus",
			user_roll_id: "Please select role",
			contracted_numbers: "Please enter number"
		}
	});
	
	$(function() {
		$("#date").datepicker();
		$(document).on('change','#date', function (ev) {
			//alert('fsf');
			//alert($(this).attr('name'));
			$(this).removeClass('error');
			$(this).next('span.error').remove();
		});
		
		$("#date_year").datepicker( {
			format: " yyyy",
			viewMode: "years", 
			minViewMode: "years"
		});
	});
	
	var $add_emp_validator = $("#add_employee").validate({
		  rules: {
			status: {
		      required: true,
		    }, 
		    first_name: {
		      required: true
		    },
			last_name: {
		      required: true
		    },
			gender: {
		      comboboxNotNone: true
		    },
			email: {
		      required: true,
		      email: true,
		    },
			birth_date: {
		      required: true,
		    },
			nationality: {
			  //required: true,
		      comboboxNotNone: true,
		    },
			cell_phone: {
		      required: true,
		    }
		  },
		  messages: {
			status: "Please select status",
			first_name:"Please enter first name",
			last_name:"Please enter last name",
			gender:"Please select gender",
			username:"Please enter email",
			birth_date:"Please select birthdate",
			nationality:"Please select nationality",
			cell_phone:"Please enter mobile"
		  },
		  errorPlacement: function(label, element) {
				//$('<span class="arrow"></span>').insertBefore(element);
				$('<span class="error"></span>').insertAfter(element).append(label)
			}
		});
	$("#add_employee").on( "submit", function() {
		
		var hasError = false;
		var first_error_input_id = '';
		$("#add_employee #references li,#add_employee #experiences li,#add_employee #certificates li,#add_employee #qualifications li").find('input[type="text"],select').each(function(){

			if(typeof $(this).closest('div.select2-container').html() == 'undefined') {
				if($(this).val() == ''){
					
					//$(this).before('<span class="error"><label for="" generated="true" class="error">This field is required.</label></span>');
					if(!$(this).hasClass('error')){
						$(this).addClass('error');
					}
					hasError = true;
					if(first_error_input_id == ''){
						first_error_input_id = $(this);
					}
				}else{
					$(this).removeClass('error');
				}
			}
		});
		if(hasError) {
			var $valid = $("#add_employee").valid();
	  		if($valid) {
				first_error_input_id.focus();
			}
			return false;
		}
		//alert('ttttt');
		//return false;
	});
		
	$("#add_obs_comment").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			comment:{				
				required:true
			}			
		},
		messages: {
			comment: "Please enter comment."
		}
	});
	
	$("#add_obs_score").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			score:{				
				comboboxNotNone: true
			},
			et_score:{				
				comboboxNotNone: true
			}
		},
		messages: {
			score: "Please select Observation Score.",
			et_score: "Please select EdTech Score."
		}
	});
	
	$("#add_nationality_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			nationality:{				
				required:true
			}			
		},
		messages: {
			duties: "Please enter nationality"
		}
	});
	
	$("#add_qualification_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			qualification:{				
				required:true
			}			
		},
		messages: {
			duties: "Please enter qualification"
		}
	});
	
	$("#add_department_form_datatable").validate({
		submitHandler: function(form) {
			//form.submit();
			jQuery(form).ajaxSubmit({
				success: function(data){
					$('#myModal').delay(1000).modal('hide');
					parent.reload_datatable();
				}
			});
		},
		rules: {
			department_name:{				
				required:true
			}			
		},
		messages: {
			duties: "Please enter department name"
		}
	});
	
});

function check_edit_school_form(id){
	// validate add school year form on keyup and submit
	
	return false;
}
