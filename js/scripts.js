// JavaScript Document
jQuery(document).ready(function($){
	
	$('body').on('click', 'input[name^="wpc_reset"]', function(){
		$(this).toggleClass('selected');
		var this_parent = $(this).parents('div.wpch_primary');
		this_parent.find('input[name="wpc_reset_action['+$(this).data('item')+']"]').val($(this).hasClass('selected')?'true':'false');
	});
	
	$('.wpch_primary ul li.available').on('click', 'a', function(){
		
		
		var this_parent = $(this).parents('.wpch_primary');

		this_parent.find('.wpch_grid').hide();


		var key = $(this).data('key');
		var not_allow_array = ['nti'];
		var other_wrapper = this_parent.find('.wpch_wrapper_custom.wpch_wrapper_'+key);
		var check_index = not_allow_array.indexOf(key);

		this_parent.find('.wpch_form_wrap').show();
		this_parent.find('input[name^="wpc_reset"]').hide();
		this_parent.find('.wpch_thumbnail, .wpch_form_style, .wpch_thumbnail img, .wpch_thumbnail .preview, .wpch_cf7_wrap').hide();
		$(this).parents().eq(1).find('li.selected').removeClass('selected');
		$(this).parent().addClass('selected');
		var key = $(this).data('key');
		var styles = 'wpc['+key+'][styles]';
		var obj = this_parent.find('select[name="'+styles+'"]');
		if(obj.length>0){
			obj.show();
			this_parent.find('.wpch_cf7_wrap.wpch_'+key+'_form').show();
			obj.val(obj.find('option:first-child').val()).trigger('change');
			window.history.pushState(null, null, 'options-general.php?page=wpc&s='+key);
			this_parent.find('input[name="wpc_reset['+key+']"]').show();
			this_parent.find('.wpch_grid[data-grid="'+key+'"]').show()
		}

		if(check_index !== -1){

			this_parent.find('.wpch_form_wrap').hide();
			other_wrapper.show();
			//console.log(check_index);
			window.history.pushState(null, null, 'options-general.php?page=wpc&s='+key);


		}else{

			this_parent.find('.wpch_wrapper_custom').hide();
		}


		
	});
	$('.wpch_form_style').change(function(){

		var this_parent = $(this).parents('.wpch_primary');

		var obj = $(this).find('option:selected');
		var item_type = $(this).data('obj');
		var forms_str = obj.data('forms')+'';
		var forms = forms_str.split('|');
		var url = obj.data('url');
		
		url = ($.trim(url)?url:obj.data('full'));

		this_parent.find('.wpch_thumbnail img').attr('src', url).show();
		this_parent.find('.wpch_thumbnail .preview').show();
		this_parent.find('.wpch_thumbnail .title').html(obj.data('cap')).show();
		this_parent.find('.wpch_thumbnail').show();
		var selection = this_parent.find('.wpch_forms_selection:visible');
		selection.find('option:selected').prop("selected", false);
		
		if(selection && forms.length>0){


			this_parent.find('input[name="wpc['+item_type+'][forms][]"]').val('');
			
			$.each(forms, function(i, num){

				this_parent.find('input[name="wpc['+item_type+'][forms][]"]').val(num);
				selection.find('option[value="'+num+'"]').prop( "selected", true );
					
			});
		}
		
		if(obj.data('css')=='no')
			this_parent.find('.wpch_buton').hide();
		else
			this_parent.find('.wpch_buton').show();
		
		//console.log(forms);
	});
	$('.wpch_thumbnail .preview').click(function(){
		var this_parent = $(this).parents('.wpch_primary');

		var obj = this_parent.find('.wpch_form_style:visible').find('option:selected');
		
		window.open(obj.data('full'), 'wpch_preview');
	});
	
	if($('.wpc_supported').length>0){

		$.each($('.wpch_primary'), function(){

			$(this).find('.wpc_supported.available:not(.hide)').eq(0).find('a').click();

		});
	}





	function parse_query_string(query) {
	  var vars = query.split("&");
	  var query_string = {};
	  for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split("=");
		// If first entry with this name
		if (typeof query_string[pair[0]] === "undefined") {
		  query_string[pair[0]] = decodeURIComponent(pair[1]);
		  // If second entry with this name
		} else if (typeof query_string[pair[0]] === "string") {
		  var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
		  query_string[pair[0]] = arr;
		  // If third or later entry with this name
		} else {
		  query_string[pair[0]].push(decodeURIComponent(pair[1]));
		}
	  }
	  return query_string;
	}		

	$('.wrap.wpch a.nav-tab').click(function(){
		$(this).siblings().removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.nav-tab-content').hide();
		$('.nav-tab-content').eq($(this).index()).show();
		//window.history.replaceState('', '', wos_obj.this_url+'&t='+$(this).index());			
		
	});				
	
	var query = window.location.search.substring(1);
	var qs = parse_query_string(query);		
	
	if(typeof(qs.t)!='undefined'){
		$('.wrap.wpch a.nav-tab').eq(qs.t).click();
		
	}
	if($('.wrap.wpch').length>0)
	$('.wrap.wpch').show();

	setTimeout(function () {

		$('.wpch_news_ticker').ticker(wpc_obj.nti);

	}, 200)

	$('.clear, .add, .del').css('cursor', 'pointer');

	function reset_counter(){

		var counter = 1;
		$.each($('body .wpch_wrapper_nti').find('.wpch_single_news_row'), function(){

			$(this).find('.wpch_nti_news_heading span').html(' '+counter);
			counter++;

		});

	}

	$("body").on("click", ".add", function () {

		var parent_row = $(this).parents('.wpch_single_news_row');
		var row_clone = parent_row.clone();

		row_clone.find('input').val('');
		row_clone.find('textarea').html('');
		row_clone.find('.wpch_single_news_data').css('display', 'none');

		parent_row.after(row_clone);

		var new_row = parent_row.next();
		new_row.find('.wpch_nti_news_heading').click();

		reset_counter();
	});

	$("body").on("click", ".wpch_nti_news_heading", function () {
		var this_row = $(this).next();
		$('body .wpch_single_news_data').not(this_row).slideUp();

		$(this).next().slideToggle('medium', function() {
			if ($(this).is(':visible'))
				$(this).css('display','flex');
		});

	});

	$("body").on("click", ".btn.wpch_del", function () {
		var all_rows = $('body .wpch_single_news_row');
		var parent_row = $(this).parents('.wpch_single_news_row');

		if(all_rows.length > 1){

		   var new_row = parent_row.next();
			parent_row.find('.wpch_nti_news_heading').click();

			setTimeout(function () {
				new_row.find('.wpch_nti_news_heading').click();
				parent_row.remove();
				reset_counter();
			}, 500);


		}else{

			parent_row.find('input').val('');
			parent_row.find('textarea').html('');

		}
	});
});		