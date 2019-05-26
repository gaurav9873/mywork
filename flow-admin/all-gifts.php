<?php
include_once 'header.php';

$obj =  new ConnectDb();
$cFunc = new CustomFunctions();

$fetchByOrder = $obj->fetchByOrder('op2mro9899_gifts', 'gift_order');
?>

<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://necolas.github.com/normalize.css/2.0.1/normalize.css">  

<script>
	
	
$(document).ready(function(){

	$( "#sortable" ).sortable();

	$(document).on('click','#save-reorder',function(){
		var list = new Array();
		$('#sortable').find('.ui-state-default').each(function(){
			var id=$(this).attr('data-id');    
			list.push(id);
		});
		var data=JSON.stringify(list);

		$.ajax({
			url:'action/gift.php',
			type:'POST',
			data: {action:'updateOrder',data:data},
			cache:false,
			beforeSend:function(){
			},
			complete:function(){
			},
			success:function(resp){
				if(resp=='saved'){
					window.location.href='';
				}
			}
		});

	});
	
	$(".delClass").on('click', function(){
		var delID = $(this).attr('data-did');
		if(delID!=''){
			$.ajax({
				url:'action/gift.php',
				type:'POST',
				data:{action:'deleteGift',gift_id:delID},
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(responce){
					alert(responce);
				}
			});
		}
	});

});


</script>

<div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>All Gifts</h2>
                </div>
                <div class="box-content">
					<button class="btn btn-default" id="save-reorder">Save</button><br /><br />
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Gift Name</th>
                            <th>Price</th>
                            <th>Disscount Price</th>
                            <th>Created Date</th>
                            <th>Created IP</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
							
						<?php 
						   foreach($fetchByOrder as $post_val){
							   $gift_id = $post_val['id'];
							   $gift_name = $post_val['gift_name'];
							   $regular_price = $post_val['regular_price'];
							   $disccount_price = $post_val['disccount_price'];
							   $created_date = $post_val['created_date'];
							   $created_ip = $post_val['created_ip'];
						?>
							
                        <tr class="ui-state-default" data-id="<?php echo $gift_id; ?>">
                            <td><?php echo $gift_name; ?></td>
                            <td class="center"><?php echo $regular_price; ?></td>
                            <td class="center"><?php echo $disccount_price; ?></td>
                            <td class="center"><?php echo $created_date; ?></td>
                            <td class="center"><?php echo $created_ip; ?></td>
                            <td class="center">
								<a href="edit-gift.php?gift_id=<?php echo $cFunc->EncryptClientId($gift_id); ?>"><span class="label-success label label-default">Edit</span></a>
								<a href="javascript:void(0);" class="delClass" data-did="<?php echo $gift_id; ?>"><span class="label-default label label-danger">Delete</span></a>
							</td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <!--<ul class="pagination pagination-centered">
                        <li><a href="#">Prev</a></li>
                        <li class="active">
                            <a href="#">1</a>
                        </li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">Next</a></li>
                    </ul>-->
                </div>
            </div>
        </div>
    </div><!--/span-->

<?php include_once 'footer.php'; ?>
