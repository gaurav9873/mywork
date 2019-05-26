<?php
include_once 'header.php';

$obj = new Connectdb();
$custom_obj = new CustomFunctions();

$shop_id = $_SESSION['shop_id'];
$all_slider = $obj->fetchAllRecord('op2mro9899_slider', 'id', $shop_id);

//$api = $custom_obj->genrate_apikey();
//echo $api = '{01BF0B83-FCB6-93B5-7500-C0DB515E9AF9}';
//echo $token = bin2hex(openssl_random_pseudo_bytes(16)).'<br />';


//~ $generate_key = '8302e8318c2ed9cc976c54f45dfcebd3';
//~ 
//~ $jwthelper = new JWT();
//~ 
//~ $key = "pjuJ-Xp;/t0y<.X:#06.]7&M[YWLly.sOa0h|t!{yRnG,B!RF`r}CfNQ{)#w*f";
//~ $token = array("user_id"=> '8302e8318c2ed9cc976c54f45dfcebd3');
//~ 
//~ $jwthelper = JWT::encode($token, $key);
//~ print_r($jwthelper);
//~ echo '<br /><br /><br />';
//~ $decoded = JWT::decode($jwthelper, $key, array('HS256'));
//~ $decoded_array = (array) $decoded;
//~ print_r($decoded_array);
?>
<script>
$(document).ready(function(){
	$(".delClass").on("click", function(){
		var did = $(this).data('did');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(did!=''){
				$.ajax({
					url:'action/slider.php',
					type:'post',
					data:{action:'delete_slider', slider_id:did},
					cache:false,
					beforeSend:function(){},
					complete:function(){},
					success:function(responce){
						window.location.href='';
					}
				});
			}
		}
	});
});
</script>
<div class="row">
		<div class="alert alert-success" id="del-msg" style="display:none;">successfully deleted.</div>
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>All Gallery Category</h2>
                </div>
                <div class="box-content">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Slider Name</th>
                            <th>Created Date</th>
                            <th>Created Ip</th>
                        </tr>
                        </thead>
                        <tbody>
							<?php
								foreach($all_slider as $val){
									$id = $custom_obj->EncryptClientId($val['id']);
									$slider_name = $val['slider_name'];
									$created_date = $val['created_date'];
									$created_ip = $val['created_ip'];
							?>
								<tr class="alternate" data-row-id="">
									<td><?php echo $slider_name; ?></td>
									<td class="center"><?php echo $created_date; ?></td>
									<td class="center"><?php echo $created_ip; ?></td>
									<td class="center">
										<a href="edit-slider.php?slider_id=<?php echo $id; ?>"><span class="label-success label label-default">Edit</span></a>
										<a href="javascript:void(0);" class="delClass" data-did="<?php echo $id; ?>"><span class="label-default label label-danger">Delete</span></a>
									</td>
								</tr>
							<?php } ?>	                 
						</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include_once 'footer.php'; ?>
