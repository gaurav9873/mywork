<?php
include_once 'header.php';

$obj =  new ConnectDb();
$custom_obj = new CustomFunctions();

$parent_category = $obj->get_parent_category("1");
?>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://necolas.github.com/normalize.css/2.0.1/normalize.css">  

<script>
$(document).ready(function(){
	$( "#sortable" ).sortable();
	
	$("#save-reorder").on('click', function(){
		var list = new Array();
		$('#sortable').find('.ui-state-default').each(function(){
			var id=$(this).attr('data-id');    
			list.push(id);
		});
		var data=JSON.stringify(list);
		
		$.ajax({
			url:'action/category.php',
			type:'POST',
			data: {action:'sort_parent_category',data:data},
			cache:false,
			beforeSend:function(){
			},
			complete:function(){
			},
			success:function(resp){
				//console.log(resp);
				if(resp=='saved'){
					window.location.href='all-categories.php';
				}
			}
		});
		
	});
	
});
</script>

<div class="box col-md-6">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>Parent Categories</h2>
                </div>
                <div class="box-content">
					<button class="btn btn-default" id="save-reorder">Save</button><br /><br />
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Category Name</th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
							<?php 
								foreach($parent_category as $pval){
									$cat_id = $pval->cat_id;
									$category_name = $pval->category_name;
									$parent_category = $pval->parent_category;
									$cat_order = $pval->cat_order;
							?>
								<tr class="ui-state-default" data-id="<?php echo $cat_id; ?>">
									<td><?php echo $category_name; ?></td> 
								</tr>
							<?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


<?php include_once 'footer.php'; ?>
