// ================================================================================================
// common.js v0.1
// http://www.globalphotobooks.com
//
// Copyright 2012 Global Photobooks PTY LTD.
// ================================================================================================
var tabIntervalId;
$(function() {
	
	// apply hover effect on the menu
	$(".navmenu > ul li a").hover(
		function () {
			$(this).css("color", "#743c42");
		},
		function () {
			$(this).css("color", "#2d4653");
		}
	);

	$(".navmenu ul ul li a").hover(
		function () {
			$(this).parent().parent().parent().children().css("color", "#743c42");
			$(this).css("color", "#743c42");
		},
		function () {
			$(this).parent().parent().parent().children().css("color", "#2d4653");
			$(this).css("color", "#2d4653");
		}
	);
	
	
		
	$.each($(".prettyPhoto img"), function(i, image){
		if($(image).parent().prop('tagName') == 'A'){
		    $(image).parent().attr('rel','prettyPhoto[mixed]');
		    $(image).parent().removeAttr('onclick');
		}
	});
	
	$("a[rel^='popup']").each(function() {
		var arrUrl = $(this).attr('rel').split(/[\s-]+/);
		var inlineId = parseInt(arrUrl[arrUrl.length-1]);
		$(this).attr('href', '#inline-'+inlineId);
		//set prettyphoto
		var iWidth  =  $('#inline-'+inlineId).attr('data-width');
		var iHeight = $('#inline-'+inlineId).attr('data-height');
		var dataRel = $('#inline-'+inlineId).attr('data-rel');
		
		$("a[rel='" + dataRel + "']").prettyPhoto({
		//$("a[rel='prettyPhoto[popup_"+ popup_id +"]']").prettyPhoto({
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.50, /* Value between 0 and 1 */
			show_title: true, /* true/false */
			allow_resize: false, /* Resize the photos bigger than viewport. true/false */
			default_width: iWidth,
			default_height: iHeight,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			horizontal_padding: 0, /* The padding on each side of the picture */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: true, /* Automatically start videos: True/False */
			modal: false, /* If set to true, only the close button will close the window */
			deeplinking: true, /* Allow prettyPhoto to update the url to enable deeplinking. */
			deeplinking_separator: '/',
			overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
			callback: function(){}, /* Called when prettyPhoto is closed */
			ie6_fallback: true,
			markup: '<div class="pp_pic_holder"> \
					<div class="ppt">&nbsp;</div> \
					<div class="pp_content_container"> \
					 <div class="pp_left" style="padding-left:0px !important;"> \
					 <div class="pp_right style="padding-left:0px !important;"> \
					  <div class="pp_content"> \
					   <div class="pp_loaderIcon"></div> \
					   <div class="pp_fade"> \
					    <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
					    <div class="pp_hoverContainer"> \
					     <a class="pp_next" href="#">next</a> \
					     <a class="pp_previous" href="#">previous</a> \
					    </div> \
					    <div id="pp_full_res"></div> \
					    <div class="pp_details"> \
					     <div class="pp_nav"> \
					     </div> \
					     <p class="pp_description"></p> \
					    </div> \
					   </div> \
					  </div> \
					 </div> \
					 </div> \
					</div> \
				       </div> \
				       <div class="pp_overlay"></div>',
			gallery_markup: '',
			image_markup: '<img id="fullResImage" src="{path}" />',
			flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
			quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
			iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
			inline_markup: '<div class="pp_inline">{content}</div>',
			custom_markup: '',
			social_tools: false /* html or false to disable */
		});
		
	});
	
	
	
	$(".getstarted").hover(
		function () {
		  $(this).attr('src', 'http://www.momento.com.au/uploads/images/2012/Web%20Re-Design%20Oct%202012/Button_GetStarted_hover.png');
		}, 
		function () {
		  $(this).attr('src', 'http://www.momento.com.au/uploads/images/2012/Web%20Re-Design%20Oct%202012/Button_GetStarted.png');
		}
	);
	
	
	/**
	 * carousel
	 */	
	$('.carousel-tabs-links ul li').on('click', function() {
		$('.carousel-tabs-links ul li').removeClass('active');
		$(this).addClass('active');
		var nth = $(this).index();
		$('.carousel-tabs-content').removeClass('show').addClass('hide');
		$('.carousel-tabs-content').eq(nth).removeClass('hide').addClass('show');
		return false;
	});
	
	// pretty photo
	if($("a[rel^='prettyPhoto']").length > 0){
	//	alert($('a[rel!="prettyPhoto[iframes]"]').length + '-' + $('a[rel="prettyPhoto[iframes]"]').length);
		$("a[rel^='prettyPhoto']").prettyPhoto({
			
			
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.50, /* Value between 0 and 1 */
			show_title: true, /* true/false */
			allow_resize: false, /* Resize the photos bigger than viewport. true/false */
			default_width: 730,
			default_height: 470,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			horizontal_padding: 0, /* The padding on each side of the picture */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: true, /* Automatically start videos: True/False */
			modal: false, /* If set to true, only the close button will close the window */
			deeplinking: true, /* Allow prettyPhoto to update the url to enable deeplinking. */
			deeplinking_separator: '/',
			overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
			callback: function(){}, /* Called when prettyPhoto is closed */
			ie6_fallback: true,
			markup: '<div class="pp_pic_holder"> \
					<div class="ppt">&nbsp;</div> \
					<div class="pp_content_container"> \
					 <div class="pp_left" style="padding-left:0px !important;"> \
					 <div class="pp_right style="padding-left:0px !important;"> \
					  <div class="pp_content"> \
					   <div class="pp_loaderIcon"></div> \
					   <div class="pp_fade"> \
					    <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
					    <div class="pp_hoverContainer"> \
					     <a class="pp_next" href="#">next</a> \
					     <a class="pp_previous" href="#">previous</a> \
					    </div> \
					    <div id="pp_full_res"></div> \
					    <div class="pp_details"> \
					     <div class="pp_nav"> \
					     </div> \
					     <p class="pp_description"></p> \
					    </div> \
					   </div> \
					  </div> \
					 </div> \
					 </div> \
					</div> \
				       </div> \
				       <div class="pp_overlay"></div>',
			gallery_markup: '',
			image_markup: '<img id="fullResImage" src="{path}" />',
			flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
			quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
			iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
			inline_markup: '<div class="pp_inline">{content}</div>',
			custom_markup: '',
			social_tools: false /* html or false to disable */
		});
	
	}
	
	if($("a[rel^='prettyPhoto[mixed]']").length > 0){
		
		$("a[rel^='prettyPhoto[mixed]']").prettyPhoto({
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.50, /* Value between 0 and 1 */
			sizeToContent:22,
			show_title: true, /* true/false */
			allow_resize: false, /* Resize the photos bigger than viewport. true/false */
			default_width: 400,
			default_height: 400,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			horizontal_padding: 0, /* The padding on each side of the picture */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: true, /* Automatically start videos: True/False */
			modal: false, /* If set to true, only the close button will close the window */
			deeplinking: true, /* Allow prettyPhoto to update the url to enable deeplinking. */
			overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
			callback: function(){}, /* Called when prettyPhoto is closed */
			ie6_fallback: true,
			markup: '<div class="pp_pic_holder"> \
					<div class="pp_content_container"> \
					<a class="pp_close" href="#" style="z-index:3000;display:block;float:right;margin-right: -35px;width:70px;height:62px;background:url(/images/guava/btn-close.png) no-repeat center center;"></a>\
					 <div class="pp_left" style="padding-left:0px !important;"> \
					 <div class="pp_right" style="padding-left:0px !important;"> \
					  <div class="pp_content" style="height: 586px !important;padding: 30px 30px;"> \
					   <div class="ppt" style="color: #3F5B6A;font-color: #3F5B6A;font-size:36px;padding: 0px 10px 10px 0px;">&nbsp;</div> \
					   <div class="pp_loaderIcon"></div> \
					   <div class="pp_fade"> \
					    <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
					    <div class="pp_hoverContainer" style="height: 500px !important; width: 500px !important;"> \
					     <a class="pp_next" href="#" style="height:50% !important; width: 12% !important;margin: 130px 0 !important;">next</a> \
					     <a class="pp_previous" href="#">previous</a> \
					    </div> \
					    <div id="pp_full_res"></div> \
					    <div class="pp_details"> \
					     <div class="pp_nav"> \
					     </div> \
					     <p class="pp_description"></p> \
					    </div> \
					   </div> \
					  </div> \
					 </div> \
					 </div> \
					</div> \
				       </div> \
				       <div class="pp_overlay"></div>',
			gallery_markup: '',
			image_markup: '<img id="fullResImage" src="{path}" />',
			flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
			quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
			iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
			inline_markup: '<div class="pp_inline">{content}</div>',
			custom_markup: '',
			social_tools: false /* html or false to disable */
		});

	}
	
	if($("a[rel^='prettyPhoto[frame']").length > 0){	
		$("a[rel^='prettyPhoto[frame']").prettyPhoto({
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.50, /* Value between 0 and 1 */
			show_title: true, /* true/false */
			allow_resize: false, /* Resize the photos bigger than viewport. true/false */
			default_width: 945,
			default_height: 700,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			horizontal_padding: 0, /* The padding on each side of the picture */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: true, /* Automatically start videos: True/False */
			modal: false, /* If set to true, only the close button will close the window */
			deeplinking: true, /* Allow prettyPhoto to update the url to enable deeplinking. */
			overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
			callback: function(){processInterval();}, /* Called when prettyPhoto is closed */
			ie6_fallback: true,
			markup: '<div class="pp_pic_holder"> \
					<div class="ppt">&nbsp;</div> \
					<div class="pp_content_container"> \
					 <div class="pp_left" style="padding-left:0px !important;"> \
					 <div class="pp_right style="padding-left:0px !important;"> \
					  <div class="pp_content"> \
					   <div class="pp_loaderIcon"></div> \
					   <div class="pp_fade"> \
					    <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
					    <div class="pp_hoverContainer"> \
					     <a class="pp_next" href="#">next</a> \
					     <a class="pp_previous" href="#">previous</a> \
					    </div> \
					    <div id="pp_full_res"></div> \
					    <div class="pp_details"> \
					     <div class="pp_nav"> \
					     </div> \
					     <p class="pp_description"></p> \
					    </div> \
					   </div> \
					  </div> \
					 </div> \
					 </div> \
					</div> \
				       </div> \
				       <div class="pp_overlay"></div>',
			gallery_markup: '',
			image_markup: '<img id="fullResImage" src="{path}" />',
			flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
			quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
			iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
			inline_markup: '<div class="pp_inline">{content}</div>',
			custom_markup: '',
			social_tools: false /* html or false to disable */
		});

	}
	

	//alert(tab_style);
	if(typeof tab_style != 'undefined' && tab_style == 'horizontal_box_tab'){
			
			var tabmenus_width = parseInt(945) -25;
			var tab_menus_ids = new Array();
			$(".tab-menus").each(function(){
				tab_menus_ids.push($(this).attr('id'));
			});
			for (var x in tab_menus_ids) {
				var total_tab_menu = $('#'+ tab_menus_ids[x] + ' .tab-menu').length;
				var menu_percentage = 100 / parseInt(total_tab_menu, 10);
				var each_hr_menu_width = (parseInt(tabmenus_width,10) / parseInt(total_tab_menu,10)-parseInt(25,10));
				$('#'+ tab_menus_ids[x] + ' .tab-menu').each(function(){			
					$(this).css({'width': each_hr_menu_width+'px'});
					$(this).css('overflow', 'hidden');
				});
				
			}
			
	}
	$(".menu-status:first").addClass('arrow-up');
	
	
	
	
	
	$(".defult-one-link").click();
	
	if($(".navmenu").find('li.active').parent().parent().prop("tagName") == 'LI'){
		$(".navmenu").find('li.active').parent().parent().addClass('active');
	}
	
	var magnifier = '<img style="width:25%;z-index:9999;" class="hoverimage" src="/images/guava/iconmonstr-magnifier-icon.png">';
	$(magnifier).appendTo(".magnifier");
	
	
	// select the tab to display, if any are set for sub-content
	
	var hash = window.location.hash.substring(1);
	var isPopup = false;
	if(hash.substring(0, 13) == "prettyPhoto[[" || hash.substring(0, 5) == "popup"){
		isPopup = true;
	}
	if (hash != '' && isPopup == false) {
		var elementId = hash.split("-").pop();
		//alert(elementId);
		showScTab(elementId);
	} else {
		//some times prettyphoto deeplink not work to open the popup
		//by manually it will open the popup 
		
		if(isPopup == true && hash.substring(0, 5) == "popup"){
			
			var arrUrl = hash.split(/[\s-]+/);
			var inlineId = parseInt(arrUrl[arrUrl.length-1]);
			$("a[rel^='popup']").each(function() {
				arrUrl = $(this).attr('rel').split(/[\s-]+/);
				hashInlineId = parseInt(arrUrl[arrUrl.length-1]);
				if(inlineId == hashInlineId){
					if(window.location.href.indexOf("show-admin-popup") > -1) {
						//alert("from admin");
					} else{
						$(this).click();
					}
				}
			});
		}else if(isPopup == true && hash.substring(0, 13) == "prettyPhoto[["){
			var rel_inner_val = hash.match(/[^[\]]+(?=])/g)
			$("a[rel='prettyPhoto["+rel_inner_val+"]']").click();			
		}
		
		if ($(".sc-tabs").is("*")) {
			var elementId = $(".sc-tabs li").first().attr("id").split("-").pop();
			showScTab(elementId);
		}
	}
	
	$("#sc-wrapper .sc-contents a").click(function () {
		var href = $(this).attr('href');
		if (href.substring(0, 1) == '#') {
			var elementId = href.split("-").pop();
			showScTab(elementId);
		}
	});
	
	
	// bind sub-content tab pages, if any
	
	$(".sc-tabs li a").click(function (e) {
		
		// disable the default action
		//e.preventDefault();
		
		// get the element ID so we can reference the content to display
		var elementId = $(this).parent("li").attr("id").split("-").pop();
		var hrefArray = $(this).attr("href").slice(1).split("-");
		var title     = hrefArray[0];
		
		showScTab(elementId);
		window.location.hash = title + "-" + elementId;
		
		return false;
	});
	
		
	//if on-click class exist that mean user not logged in
	$("#giftpack_order").on('click', function(){
		if($(".giftpack-option").val() == null){
			alert('Please select a gift card.');
			return false;
		}
		if($("a").hasClass('on-click') == true ){
			$("#giftpack_order_form").attr("action","/client/giftpack/");
		}
		$("#giftpack_order_form").submit();					
	});

	if ($.fn.jqtabs) {
		
		$(".jqtabcontainer").jqtabs(true, '5000');
		//$(".jqtabcontainer").append('<input type="hidden" name="setIntervalId" id="setIntervalId" value="'+tabIntervalId+'">');
	}
	
	//Set teh social ribbon just after the header
	if ($(".ribbon").length > 0) {
		$('#main').prepend('<div id="ribbon_container" style="line-height: 1.1em;position: absolute;width:175px;z-index: 10;"></div>');
		$(".ribbon").appendTo("#ribbon_container");
		setRibbon();
		$(".ribbon").show();
	
		
	}
	$(window).resize(function() {
		if ($(".ribbon").length > 0) setRibbon();
	});
	
	$(".video-play").click(function (e) {
		processInterval();
	});
	
	$(".phone-only-number").keydown(function(event) {
		// Allow: backspace, delete, tab, escape, enter, shift, ctrl
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 16 || event.keyCode == 17 ||
		     // Allow: Ctrl+A
		    (event.keyCode == 65 && event.ctrlKey === true) ||
		     // Allow: home, end, left, right
		    (event.keyCode >= 35 && event.keyCode <= 39)) {
			 // let it happen, don't do anything
			//alert('Only numbers allowed');
			return;
		}
		else {
		    // Ensure that it is a number and stop the keypress
		    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			event.preventDefault();
			
			alert('Only numbers allowed.');
			var txt = $(this).val().replace(/\D+/, '');
			$(this).val(txt);
		    }   
		}
	});
	
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		$('.phone').attr('href','tel:1300799764');
	}else{
		$('.phone').attr('href','/pages/contacts');
	}
	
	$( window ).resize(function() {
		if ($(target_ele).length > 0) {
			var new_left = parseInt($(target_ele).position().left ) + parseInt(nudge_x);
			$('.disneyFD').css('left', new_left+'px');
		}
	});
});

var showScTab = function(scTabId) {
	$("li#sc-li-" + scTabId).parent().find('li').removeClass('active');
	$("li#sc-li-" + scTabId).addClass('active');
	
	$("div#sc-content-" + scTabId).parent().find("div.sc-content").hide();
	$("div#sc-content-" + scTabId).show();
};

var showPopupTab = function(popupId, scTabId) {
	
	$("ul.sc-tabs_" + popupId + " li").removeClass('active');
	$("ul.sc-tabs_" + popupId + " li#sc-li-" + scTabId).addClass('active');
	
	$(".sc-contents_" + popupId + " div.sc-content").hide();
	$(".sc-contents_" + popupId + " div#sc-content-"+ scTabId).show();
};

var livepreviewWrapperSetInPiller = function() {
	var ending_right     = ($(window).width() - ($(".products-grid").offset().left + $(".products-grid").outerWidth()));
	var position = $(".products-grid").position();
	var position_top = parseInt($(".padded-tb").css('padding-top')) + parseInt(position.top);
	//console.log(ending_right + ' : '+ position_top);
	$("#livepreviewWrapper").css('z-index', '10');
	$("#livepreviewWrapper").css('top', position_top);
	$("#livepreviewWrapper").css('right', ending_right);
	$("#livepreviewWrapper").css('display', 'block');
	$("#livepreviewWrapper").sticky({topSpacing:0});
	
	//set height of products-grid when it is google chrome	
	$('#livepreviewWrapper-sticky-wrapper').css('display','inline-block');
	$.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase()); 
	if($.browser.chrome){
		$('.products-grid').css('height', parseInt(parseInt($(".padded-tb").css('padding-top'))*2) + parseInt($(".products-grid").height()));
		$('.cbox-client-menu-main ul li.active a').css('border-bottom','2px solid #fff');
	}
	
	$("#livepreviewWrapper-sticky-wrapper").css({"width" : $('#livepreviewWrapper').width(), "float" : "right", "top" : 0});
	$(window).resize(function() {
		var ending_right     = ($(window).width() - ($(".products-grid").offset().left + $(".products-grid").outerWidth()));
		$("#livepreviewWrapper").css('right',ending_right);
	});
};

$(document).on("click", "div.tab-menu",function() {
	var content_id = $(this).attr('id').split('_');
	content_id = content_id[1];
	var popup_id = $(this).attr('data-popupid');
	$('div.tab-menu').css({'cursor':'pointer'});
	$(this).css({'cursor':'default'});
	$('div.tab-content_'+ popup_id).hide();
	$('div#tab_content_'+popup_id+'_'+content_id).show();
	
	return false;
});

$(document).on("click", "a.pp_close", function(){ 
	
	$.prettyPhoto.close();
	
	//$(window).location.href.replace(/#.*/,'');
	removeHash();
	return false;
});

$(document).on("click", "div img.hoverimage", function(){ 
	$(this).parent().parent().find('a .livepreviewImage').click();
});


var removeHash =  function () { 
  if(window.location.hash) 
  {
    var scrollV, scrollH, loc = window.location;
    if ("pushState" in history)
        history.pushState("", document.title, loc.pathname + loc.search);
    else {
        // Prevent scrolling by storing the page's current scroll offset
        scrollV = document.body.scrollTop;
        scrollH = document.body.scrollLeft; 
        loc.hash = "";

        // Restore the scroll offset, should be flicker free
        document.body.scrollTop = scrollV;
        document.body.scrollLeft = scrollH;
    }
  }
}
var setRibbon = function(){
	var position = $('header').offset();
	var header_width = $('header').width();
	var left_pos = parseInt(parseInt(position.left)+parseInt(header_width)-175) +'px';
	$('#ribbon_container').css('left',left_pos);
}
var processInterval = function(){
	if($(".jqtabcontainer").length > 0){
		//alert(tabIntervalId);
		if(parseInt(tabIntervalId) > 0){
			clearInterval(tabIntervalId);
			tabIntervalId = 0;
		}else{
			$(".jqtabcontainer").jqtabs(true, '5000');
		}
	}
}

/** 
 * function to display fairydust
 */
function displayDisney(target_element_class, disney_width, disney_height, image_path, setoutside){

	var $div = $('<div />').appendTo('body');
	var target_element = $( '.'+target_element_class);

	if(target_element.length == 0) return false;

	var position = target_element.position();
	var top = (parseInt(position.top) + parseInt(target_element.height()/2));
	var left = (parseInt(position.left) + parseInt(target_element.width()) - (setoutside == false? disney_width: 0));
	$div.attr('id', 'disney_logo');
	$div.html('<img src="'+image_path+'" style="width:'+disney_width+'px; height: '+disney_height+'px;">');
	$div.css({'position': 'absolute', 'z-index':100,'top': top, 'left':left});
}

/**
 * display fairydust on the page
 * 
 * @param string target
 * @param string image_url
 * @param json options
 * @return void
 */
function fairydust(target, image_url, options) {
	
	// default options
	var defaults = {
		nudge_x   : 0,
		nudge_y   : 0,
		background: false,
		between   : [],
		url       : ''
	}
	options = $.extend({}, defaults, options || {}); 

	// get the target element we want to reference the position of the fairydust
	var target_element = $(target);
	if (target_element.length == 0) return false;

	// determine if to display as background
	if (options.background == true) {
		target_element.css({ 'background': "url(" + image_url + ")" });
		return;
	}
	
	// place the fairydust between two elements
	if (options.between.length > 0) {
		
		//alert(fairydust_between_position('#logo', '.navmenu'));
		
		var fairydust = $('<img/>', {
			src: image_url,
			'data-fairydust-between': options.between,
			css: {
				'position': 'absolute',
				'z-index' : 100,
				'top'     : parseInt(options.nudge_y), 
				'left'    : 10
			}
		});

		fairydust.appendTo('body');
		return;
	}
	
	// build the fairydust element and append to the body
	var fairydust = $('<div>', {
		css: {
			'position': 'absolute',
			'z-index' : 100,
			'top'     : parseInt(target_element.position().top ) + parseInt(options.nudge_y), 
			'left'    : parseInt(target_element.position().left) + parseInt(options.nudge_x)
		},
		'class' :'disneyFD'
	});
	
	fairydust.html($('<img>', { src: image_url }));
	
	// add link if required, else just a standard image
	if (options.url.length > 0) {
		fairydust.find('img').wrap($('<a>', { href: options.url })).parent();
	}
	
	fairydust.appendTo('body');
}

function fairydust_between_position (from, to) {
	
	var element_from = $(from);
	var element_to   = $(to);
	if (element_from.length == 0 || element_to.length == 0) return false;
	
	
	
	console.log(element_from.position().left);
	console.log(element_from.width());
	console.log(element_to.position().left);
	
	return (
		
		parseInt((parseInt(element_from.position().left) + parseInt(element_from.width()))) + parseInt(parseInt(element_to.position().left) - (parseInt(element_from.position().left) + parseInt(element_from.width())))) / 2;
	
}