<?php
require_once('header.php');

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

$all_domain = $obj->fetchAll('op2mro9899_add_domain');

$clos = $obj->getManagername();
$json_data = json_encode($clos, true);

?>

<script type="text/javascript">
$(document).ready(function(){
	

		//~ // Define the string
		//~ var string = '12';
//~ 
		//~ // Encode the String
		//~ var encodedString = btoa(string);
		//~ console.log(encodedString); // Outputs: "SGVsbG8gV29ybGQh"
//~ 
		//~ // Decode the String
		//~ var decodedString = atob(encodedString);
		//~ console.log(decodedString); // Outputs: "Hello World!"


	get_useres();
	function get_useres(){
		var json = <?php echo $json_data; ?>;
		var Html = '';
		$.each(json, function(k,v){
			Html += all_useres(v);
		});
		$("#users").empty().append(Html);
	}
	
	function all_useres(obj){
		var html ='';
		var userlevel = (obj.user_level == 1) ? "Super Admin": "Manager";
		var shop_name = (obj.domain_id == '0') ? "Super Admin" : obj.domain_name;
		var user_status = (obj.user_status == 'Active') ? 'label-default' : 'label-warning';
		var txt = (obj.user_status == 'Active') ? 'Active' : 'Deactive';
		var status = (obj.user_status == 'Active') ? 'Deactive' : 'Active';
		var createDate = obj.created_date;
		
		
		html +='<tr class="alternate" data-row-id="'+obj.id+'">';
		html +='<td>'+obj.user_name+'</td>';
		html +='<td class="center">'+obj.user_email+'</td>';
		html +='<td class="center">'+userlevel+'</td>';
		html +='<td class="center">'+shop_name+'</td>';
		html +='<td class="center">'+createDate+'</td>';
		html +='<td class="center">'+obj.created_ip+'</td>';
		html +='<td class="center">';
		html +='<a href="javascript:void(0);" id="'+obj.id+'" data-status="'+status+'" class="usr-status" data-row-id="'+obj.id+'"><span id="status" class="label-success label '+user_status+'">'+txt+'</span></a>&nbsp;';
		html +='<img class="aloader" style="display:none;" src="img/ajax-loaders/ajax-loader-3.gif" title="img/ajax-loaders/ajax-loader-3.gif">';
		html +='</td>';
		html +='<td class="center"><a data-id="'+obj.id+'" class="delete-row" href="javascript:void(0);"><span class="label-default label label-danger">Delete</span></a></td>';
		html +='<td class="center"><a href="edit-user.php?user_id='+btoa(obj.id)+'" class="edit-site" data-id="'+obj.id+'"><span class="label label-success">Edit</span></a></td>';
		html +='</tr>';
		return html;
	}
	
		function ajaxCallBack(retString){
		  retVal = retString;
		}
	
	//Update status
	$(".usr-status").on('click', function(){
		var row_id = $(this).attr('data-row-id');
		var ustatus = $(this).attr('data-status');
		
		var show_loader = $(this).closest('tr').find(".aloader").show();
		var row = $(this).closest('tr');
		var change = row.find(".alternate").empty().text('ssss');
		if(row_id!=''){
			$.ajax({
				url:'action/user.php',
				type:'POST',
				cache:false,
				data:{
					action:'status',
					rid:row_id,
					status_type:ustatus
				},
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(resJson){
					var json = $.parseJSON(resJson);
					row.find("td:nth-child(7) #status").empty().text(json[0].user_status);
					var req = json[0].user_status;
					if(req == 'Active'){
						row.find("td:nth-child(7) .usr-status").attr('data-status', 'Deactive');
						row.find("td:nth-child(7) #status").removeClass("label-warning").addClass('label-default');
						
					}
					if(req == 'Deactive'){
						row.find("td:nth-child(7) .usr-status").attr('data-status', 'Active');
						row.find("td:nth-child(7) #status").removeClass("label-default").addClass('label-warning');
					}
					$(show_loader).hide();
				}
			})
		}
	});
	
	
    //Delete Row
	$(document).on('click', '.delete-row', function(){
		var alert_msg = "Are you sure you want to delete this row?";
		var chk = confirm(alert_msg);
		if(chk){
			var delID = $(this).attr("data-id");
			var row_ids = $(this).closest('tr').attr('data-row-id');
			var row_id = $(this).closest('tr').addClass('changeColour');
			
			if(delID!=''){
				$.ajax({
					url:'action/user.php',
					type:'POST',
					cache:false,
					data:{
						action:'delete',
						del_id:delID
					},
					beforeSend: function(){
						row_id;
					},
					complete: function(){
					},
					success: function(responce){
						$('.alternate').filter("[data-row-id='" + row_ids + "']").fadeOut(1000, function(){ 
							$(this).remove();
						});
						$("#del-msg").show();
						$('#del-msg').delay(5000).fadeOut('slow');
					}
				});
			}
			
		}
		
	});
	
	
	$("#submit").on('click', function(){
		var errorFlag = true;
		$(".validate").each(function(){
			if($(this).val() == ''){
				$(this).css('border-color', 'red');
				$(this).next(".err").show();
				errorFlag = false;
			}else{
				if(this.id == 'user_email'){
				var chkMail = $("#user_email").val();
				if(isValidEmailAddress(chkMail)==false){
					$(this).css('border-color', 'red');
					$(this).next(".err").show();
					errorFlag = false;
				}
			}
			}
		});
		
		$(".required").on('keypress change',function(){
			if($(this).val() ==''){
				$(this).css('border-color', 'red');
				$(this).parent().find('.error').show();
			}

			if($(this).val()!=''){
				$(this).css('border-color', '');
				$(this).next(".err").hide();
				if(this.id == 'user_email'){
					var chkMail = $("#user_email").val();
					if(isValidEmailAddress(chkMail)==false){
						$(this).css('border-color', 'red');
						$(this).next(".err").show();
						errorFlag = false;
					}
				}
			}
		});
		
		if(errorFlag){
			var userFrm = {"action": "insert_user"};
			userFrm = $("#user-frm").serialize() + "&" + $.param(userFrm);
			$.ajax({
				url:'action/user.php',
				type:'POST',
				cache:false,
				data:userFrm,
				beforeSend: function() {	
					$('.ajax-loaders').show();
				},
				complete: function(){
					$('.ajax-loaders').hide();
				},
				success: function(json_data){
					var Data = $.parseJSON(json_data);
					var html = all_useres(Data[0]);
					$("#users").prepend(html);
					$(".noty_bar").show();
					$('.noty_bar').delay(5000).fadeOut('slow');
					$("#user-frm")[0].reset();
				}
			});
		}
		
	});
});

function isValidEmailAddress(emailAddress){
	var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
	return pattern.test(emailAddress);
}

</script>
<div class="noty_bar noty_theme_default noty_layout_top noty_information" id="noty_information_1480425196141" style="cursor: pointer; display: none;">
	<div class="noty_message"><span class="noty_text">Usere save successfully in our database</span></div></div>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>Add Useres</h2>
            </div>
            
            <div class="box-content">
				<div class="alert alert-info" style="display:none;">Domain added successfully</div>
					<form name="user-frm" id="user-frm" action="" role="form" method="post">
						<div class="form-group">
							<label for="name">User Name</label>
							<input class="form-control validate" id="user_name" name="user_name"  placeholder="Enter Site User Name" type="text">
							<ul class="err" style="display:none;"><li>Please enter user name.</li></ul>
						</div>
						
						<div class="form-group">
							<label for="email">Email address</label>
							<input class="form-control validate" id="user_email" name="user_email" placeholder="Enter email" type="email">
							<ul class="err" style="display:none;"><li>Please enter valid email address.</li></ul>
						</div>
						
						<div class="form-group">
							<label for="password">Password</label>
							<input class="form-control validate" id="user_password" name="user_password" placeholder="Password" type="password">
							<ul class="err" style="display:none;"><li>Please enter password.</li></ul>
						</div>
						
						<div class="form-group">
							<select name="user_level" id="user_level" class="form-control validate">
								<option value="">Select User Level</option>
								<option value="1">Super Admin</option>
								<option value="2">Manager</option>
							</select>
							<ul class="err" style="display:none;"><li>Please select user level.</li></ul>
						</div>
						
						<div class="form-group">
							<select name="domain_id" id="domain_id" class="form-control">
								<option value="">Select Shop Name</option>
								<?php 
									foreach($all_domain as $site_name){
										$domain_name = $site_name['domain_name'];
										$site_id = $site_name['site_id'];
										echo '<option value="'.$site_id.'">'.$domain_name.'</option>';
									}
								?>
							</select>
						</div>
						
						<button type="button" class="btn btn-default" id="submit">Submit</button>
						<ul class="ajax-loaders" style="display:none;"><li><img src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif"></li></ul>
					</form>
            </div>
        </div>
    </div>
    <!--/span-->
</div>


<div class="row">
	
		<div class="alert alert-success" id="del-msg" style="display:none;">successfully deleted.</div>

        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>Combined All</h2>
                </div>
                <div class="box-content">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
							<th>Username</th>
							<th>User email</th>
							<th>User Role</th>
							<th>Shop Name</th>
							<th>Created Date</th>
							<th>Created IP</th>
							<th>Status</th>
							<th>Delete</th>
							<th>Edit</th>
                        </tr>
                        </thead>
                        <tbody id="users">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!--/span-->
<?php require('footer.php'); ?>
