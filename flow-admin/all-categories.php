<?php
include_once 'header.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

$cat_list = $obj->listcategories();
$cat_view = $obj->treeView();
?>

<script>
$(document).ready(function(){
	$(".activechk").on('click', function(){
		var val = $(this).val();
		var cat_id = $(this).data('cat-id');
		
		if(val!=''){
			$.ajax({
				url:'action/category.php',
				type:'post',
				data:{action:'active_status', active_val:val, datacat_id:cat_id },
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(resp){
					window.location.href="";
				}
			});
		}
	});

    $(".activestatus").on("click", function () {
        var cid = $(this).attr('data-cid');
        var giftval = $(this).val();
        //console.log(cid+'=='+giftval); return false;
        if(giftval!==''){
            $.ajax({
                url:'action/category.php',
                type:'post',
                data:{action:"giftstatus", status_val:giftval, datacid:cid},
                cache:false,
                beforeSend:function () {
                },
                complete:function () {
                },
                success:function (respdata) {
                    console.log(respdata);
                    window.location.href='';
                }
            });
        }
    });
	
	$(".menu").on('click', function(){
		var data_cat_id = $(this).data('catid');
		var menu_status = $(this).data('menu-status');
		if(data_cat_id!=''){
			$.ajax({
				url:'action/category.php',
				type:'post',
				data:{action:'active_menu', data_catid:data_cat_id, menu_id:menu_status},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(responce){
					window.location.href='';
				}
			});
		}
	});
	
	$(".delte-category").on('click', function(){
		var did = $(this).data('did');
		var parent_id = $(this).data('parent-id');
		
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(did!=''){
				$.ajax({
					url:'action/category.php',
					type:'post',
					data:{action:'delete_category', delid:did, parentids:parent_id},
					cache:false,
					beforeSend:function(){
					},
					complete:function(){
					},
					success:function(responce_value){
						var parsejsonData = jQuery.parseJSON(responce_value);
						if(parsejsonData.status=='true'){
							window.location.href='';
						}else{
							alert('Something went wrong');
						}
					}
				})
				
			}
		}
	});
	
	$(".slot").hide();
	$(".toggleclass").on('click', function(){
		var tid = $(this).attr('id');
		$('.sl'+tid).toggle();
	});
	
});
</script>

<div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-user"></i> All Categories</h2>
                    <a href="sort-parent-category.php" class="menu pull-right label-success label label-default" style="padding:6px 15px 7px; display:block;" id="">
						Sort parent category
				    </a>
				    <a href="sort-on-homepage.php" class="menu pull-right label-success label label-default" id=""  style="margin-right:20px;padding:6px 15px 7px; display:block;">
						Sort home category
				    </a>
                </div>
                <div class="box-content">
                    <table class="table table-striped table-bordered responsive">
                        <thead>
                        <tr>
                            <th>Ctaegory Name</th>
                            <th>Show On Home</th>
                            <th>Active ON</th>
                            <th>Header Menu</th>
                            <th>Gift Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="slots">

						<?php 
						  foreach($cat_view as $post_val){ //print_r($post_val);
							 
							   $cat_id = $post_val['id'];
							   $domain_name = $post_val['sname'];
							   $site = str_replace(',', ',&nbsp;&nbsp;', $domain_name);
							   $sid = $post_val['sid'];
							   $category_name = $post_val['name'];
							   $parentID = $post_val['pid'];
							   $active_val = $post_val['active_val'];
							   $chk = (($active_val == 1) ? 'checked' : '');
							   $chk_val = (($active_val == 1) ? '0' : '1');
							   $chk_menu = (($post_val['menu'] == 1) ? 'Active' : 'Inactive');
							   $menu_class = (($post_val['menu'] == 1) ? 'label-success' : '');
							   $menu_status = (($post_val['menu'] == 1) ? '0' : '1');
							   $slot = (($parentID == 0) ? 'gft' : 'slot');
                              $giftStatusVal = (($post_val["gift_status"] == 1) ? '0' : '1');
                              $checkedGiftStatus = (($post_val["gift_status"] == 1) ? 'checked' : '');
						?>
								<tr class="<?php echo $slot; ?> sl<?php echo $parentID; ?>" id="slot<?php echo $parentID; ?>">
									<?php if($parentID == 0){?>
										<td><a href="javascript:void(0);" class="toggleclass" id="<?php echo $cat_id; ?>"><?php echo $category_name; ?></a></td>
									<?php }else{ ?>
									<td><?php echo $category_name; ?></td><?php } ?>
									<?php if($parentID == 0){ ?>
									<td><input type="checkbox" <?php echo $chk; ?> class="activechk" name="active" value="<?php echo $chk_val; ?>" data-cat-id="<?php echo $cFunc->EncryptClientId($cat_id); ?>"></td>
									<?php }else{ ?>
									<?php echo '<td>&nbsp;</td>'; } ?>
									<td class="center">
									<span class=""><?php echo $site; ?></span>
									</td>
									<td class="center">
										<a href="javascript:void(0);" class="menu" id="menu" data-catid="<?php echo $cat_id; ?>" data-menu-status="<?php echo $menu_status; ?>">
											<span class="<?php echo $menu_class; ?> label label-default"><?php echo $chk_menu; ?></span>
										</a>
									</td>
                                    <td class="center">
                                        <input type="checkbox" <?php echo $checkedGiftStatus; ?> class="activestatus" name="git_status" value="<?php echo $giftStatusVal; ?>" data-cid="<?php echo $cFunc->EncryptClientId($cat_id); ?>">
                                    </td>
									<td class="center">
										<a href="edit-category.php?cat_id=<?php echo $cFunc->EncryptClientId($cat_id);?>"><span class="label-warning label label-default">Edit</span></a>
										<a class="delte-category" href="javascript:void(0);" data-parent-id="<?php echo $parentID; ?>" data-did="<?php echo $cat_id; ?>">
											<span class="label-default label label-danger">Delete</span>
										</a>
										<?php  if($parentID == 0){ ?>
										<a class="" href="sort-category.php?cat_id=<?php echo $cFunc->EncryptClientId($cat_id);?>">
											<span class="label-success label label-default">Sort Category</span>
										</a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/span-->
    </div>

<?php include_once 'footer.php'; ?>
