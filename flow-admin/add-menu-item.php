<?php
include_once 'header.php';
?>

<script src="http://luke.sno.wden.co.uk/assets/js/jquery-ui.js"></script>
<!--<script src="http://luke.sno.wden.co.uk/assets/js/sortable.js"></script>-->
<script src="https://johnny.github.io/jquery-sortable/js/jquery-sortable.js"></script>
<script>
$(document).ready(function(){
  var group = $("ol.serialization").sortable({
  group: 'serialization',
  delay: 500,
  onDrop: function ($item, container, _super) {
    var data = group.sortable("serialize").get();

    var jsonString = JSON.stringify(data, null, ' ');
	
	//console.log(jsonString);

    $('#serialize_output2').text(jsonString);
    _super($item, container);
  }
});
});
</script>

<style>
.span4 {
  width: 300px; }
ol.vertical {
  margin: 0 0 9px 0;
  min-height: 10px; }

  ol.vertical li {
    display: block;
    margin: 5px;
    padding: 5px;
    border: 1px solid #cccccc;
    color: #0088cc;
    background: #eeeeee; }
    
    
     ol.vertical li.placeholder {
    position: relative;
    margin: 0;
    padding: 0;
    border: none; }
   
    ol.vertical li.placeholder:before {
      position: absolute;
      content: "";
      width: 0;
      height: 0;
      margin-top: -5px;
      left: -5px;
      top: -4px;
      border: 5px solid transparent;
      border-left-color: red;
      border-right: none; }
      
      ol.nested_with_switch li, ol.simple_with_animation li, ol.serialization li, ol.default li {
  cursor: pointer; }

    
</style>

<div class="row">

<div class="span4">
              <ol class="serialization vertical">
                <li data-id="1" data-name="Item 1">Item 1</li>
                <li data-id="2" data-name="Item 2">Item 2</li>
                <li data-id="3" data-name="Item 3">Item 3</li>
                <li data-id="4" data-name="Item 4">Item 4
					  <ol>
						<li data-id="4-1" data-name="Item 3.1">Item 3.1</li>
						<li data-id="4-2" data-name="Item 3.2">Item 3.2</li>
						<li data-id="4-3" data-name="Item 3.3">Item 3.3</li>
						<li data-id="4-4" data-name="Item 3.4">Item 3.4</li>
						<li data-id="4-5" data-name="Item 3.5">Item 3.5</li>
						<li data-id="4-6" data-name="Item 3.6">Item 3.6</li>
					  </ol>
                </li>
                <li data-id="5" data-name="Item 5">Item 5</li>
                <li data-id="6" data-name="Item 6">Item 6</li>
              </ol>
            </div>

</div>

<pre id="serialize_output2"></pre>


<?php include_once 'footer.php'; ?>
