$(window).load(function(){
		$('.BannerBox').find('img').each(function(){
			var imgClass = (this.width/this.height > 1) ? 'wide' : 'tall';
			$(this).addClass(imgClass);
		});
	});
	
	jQuery(function() {
		jQuery('#bannerscollection_zoominout_opportune').bannerscollection_zoominout({
			skin: 'opportune',
			responsive:true,
			width100Proc:true,
			width:1920,
			height:500,
			circleRadius:8,
			circleLineWidth:4,
			circleColor:"#ffffff", //849ef3
			circleAlpha:50,
			behindCircleColor: "#000000",
			behindCircleAlpha: 20,
			thumbsWrapperMarginTop:25,
			scrollSlideDuration:0.8,
			scrollSlideEasing:'easeOutQuad',
			fadeSlides:true,
			autoPlay:5		
		});	
	});
	
	
	$(document).ready(function() {
		
		$('#slides').superslides({
			play: 4000,
			inherit_width_from: '.wide-container',
			inherit_height_from: '.wide-container',
			hashchange: false
		});
		
		$("#content-slider").lightSlider({
			loop:true,
			keyPress:true,
			slideMargin:40,
			item:6
		});
		$('#image-gallery').lightSlider({
			gallery:true,
			item:1,
			thumbItem:5,
			slideMargin: 0,
			speed:500,
			auto:false,
			loop:true,
			onSliderLoad: function() {
				$('#image-gallery').removeClass('cS-hidden');
			}  
		});
		$('.shareLinks a').click(function(){
			$('.shareLinks ul').toggle();
		});
		
		$("#searchPin").click(function(){
			$(".ppSelect").addClass("addressSelect");
		});
		
		
		$(document).bind( "mouseup touchend", function(e){
			var container = $('.searchField');
			var dcontainer = $('.track');
			if(!dcontainer.has(e.target).length && !container.is(e.target) && container.has(e.target).length === 0){
				container.slideUp();
			}
		});
		
		
		
		$(".clickOnSearch").click(function(){		
			$('.searchField').show();
		});
		
		$("#searchProduct").on("click", function(){
			var search_string = $(".search-txt").val();
			if (search_string != undefined && search_string != null) {
				window.location = 'search.php?sq=' + search_string;
			}
		});
		

		//~ $('.nav').on('click', 'li', function(){
			//~ $('.nav li').removeClass('active');
			//~ $(this).addClass('active');
		//~ });
		
		$(".navbar-nav .arrowso").click(function(e) {
            $(this).next(".dropdown-menu").toggle();
        });
		
	});
