<?php
include_once 'header.php';
?>

<style>
	.heading {margin:10px 0;}
	.menulist li {font-weight: bold; margin: 8px 0; list-style:none;}
	.menulist li > ul li { font-weight: normal;}
	.menulist input[type="checkbox"] {margin-right:8px;}
	.menulist > ul {margin: 0; padding: 0;}
</style>
<div class="innerContent">
	<div class="col-lg-12">
    	<div class="heading">menu detail <select style="width:200px; padding:6px; border:1px solid #afafaf; margin-left:10px;"></select></div>
		<div class="heading">menu detail <input type="text" style="width:200px; padding:5px; margin-left:10px;"/></div>
    </div>
	<div class="col-lg-6">
    	<div class="menulist">
        <h2>Categories</h2>
        	<ul>
            	<li><input type="checkbox" /><span>jhj</span></li>
                <li><input type="checkbox" /><span>jhj</span>
                	<ul>
                    	<li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                    </ul>
                </li>
                <li><input type="checkbox" /><span>jhj</span>
                	<ul>
                    	<li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                    </ul>
                </li>
                <li><input type="checkbox" /><span>jhj</span>
                	<ul>
                    	<li><input type="checkbox" /><span>jhj</span></li>
                        <li><input type="checkbox" /><span>jhj</span></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
    	<div class="menulist" style="margin-top:30px;">
        	<h2>Page</h2>
        	<ul>
            	<li><input type="checkbox" /><span>jhj</span></li>
               <li><input type="checkbox" /><span>jhj</span></li>
               <li><input type="checkbox" /><span>jhj</span></li>
               <li><input type="checkbox" /><span>jhj</span></li>
               <li><input type="checkbox" /><span>jhj</span></li>
               <li><input type="checkbox" /><span>jhj</span></li>
               <li><input type="checkbox" /><span>jhj</span></li>
            </ul>
        </div>
    </div>
</div>


<?php include_once 'footer.php'; ?>
