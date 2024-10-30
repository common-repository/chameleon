<?php defined( 'ABSPATH' ) or die( __('No script kiddies please!', 'chameleon') );
	
	
	//add_action('after_wpch_form_wrap', 'after_wpch_form_wrap_news_ticker');
	
	if(!function_exists('after_wpch_form_wrap_news_ticker')){
		function after_wpch_form_wrap_news_ticker(){
	
			global $wpc_dir;
	
			$template_path = $wpc_dir.'templates/nti/wpch_news_ticker.php';
			
			if(isset($_POST['wpch_news_ticker_submit']) && isset($_POST['wpch_news_ticker'])){
	
				if (
					! isset( $_POST['wpc_valid_f'] )
					|| ! wp_verify_nonce( $_POST['wpc_valid_f'], 'wpc_valid_a' )
				) {
	
					print __('Sorry, your nonce did not verify.');
					exit;
	
				} else {
	
						$wpch_news_ticker = sanitize_wpc_data($_POST['wpch_news_ticker']);
						update_option('wpch_news_ticker', $wpch_news_ticker);
	
					}
			}
			
			//echo file_exists($template_path);
						
			if(file_exists($template_path)){
				include_once ($template_path);
			}
	
	
		}
	}
	
	add_shortcode('WPCH_NEWS_TICKER', 'wpch_news_ticker_callback');
	
	if(!function_exists('wpch_news_ticker_callback')){
		function wpch_news_ticker_callback(){
	
			global $wpc_dir;
	
			ob_start();
	
			$shortcode_template_path = $wpc_dir.'/templates/nti/shortcode.php';
	
			if(file_exists($shortcode_template_path)){
	
				include_once($shortcode_template_path);
	
			}
	
			$ret = ob_get_contents();
			ob_clean();
	
			return $ret;
	
		}
	}
	
	if(!function_exists('wpch_render_news_ticker')){
	
		function wpch_render_news_ticker(){
	
			$wpch_news_ticker = get_option('wpch_news_ticker', array());
			$nti_dom = array_key_exists('nti_dom', $wpch_news_ticker) ? $wpch_news_ticker['nti_dom']: false;
	
		   
	
			if($nti_dom !== false){

				?>
                <script src='<?php echo wpch_news_ticker_css_js('js', true); ?>?t=<?php echo time(); ?>' id='wpch_nti'></script>
                <link rel="stylesheet"  href="<?php echo wpch_news_ticker_css_js('css'); ?>?t=<?php echo time(); ?>" media="all" />

                <?php
	
	
			}
	
		}
	}
	
	add_action("wp_footer", 'wpch_render_news_ticker', 100);


	function chameleon_valid_layouts(){
		global $wpc_supported, $wpdb;
		
		$ret = array();
		
		if(!empty($wpc_supported)){
			//wpc_pree($wpc_supported);
			foreach($wpc_supported as $key=>$arr){
				$forms_array = array();
				
				if($arr['installed']==1 && $arr['activated']==1){}else{ continue; }
				
				$ret[$key]['list'] = array();
				switch($key){
					
					case 'cf7':
						$args = array(
							'posts_per_page'   => -1,
							'offset'           => 0,
							'orderby'          => 'title',
							'order'            => 'ASC',
							'post_type'        => 'wpcf7_contact_form',
							'post_status'      => 'publish',
							'suppress_filters' => true 
						);
						$forms_array = get_posts( $args );

						
						
					break;
					
					case 'gf':

						$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gf_form WHERE is_active=1");
						
						if(!empty($res)){
			
							foreach($res as $garr){
								$obj = new stdClass;
								$obj->post_title = $garr->title;
								$obj->ID = $garr->id;
								$forms_array[] = $obj;
							}
				
						}

					break;
					
					
				}
				
				$ret[$key]['action_txt'] = $arr['action_txt'];
				$ret[$key]['link'] = $arr['link'];
				$ret[$key]['list'] = $forms_array;
			}
		}
		
		return $ret;
	}
		
	function sanitize_wpc_data( $input ) {
		if(is_array($input)){		
			$new_input = array();	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?sanitize_wpc_data($val):sanitize_text_field( $val ));
			}			
		}else{
			$new_input = sanitize_text_field($input);			
			if(stripos($new_input, '@') && is_email($new_input)){
				$new_input = sanitize_email($new_input);
			}
			if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
				$new_input = sanitize_url($new_input);
			}			
		}	
		return $new_input;
	}		
	
	/*
	function wpc_third_party_support(){
		
		global $wpc_plugins_activated, $wpc_all_plugins;
		
		if(
				array_key_exists('alphabetic-pagination/index.php', $wpc_all_plugins)
			&&
				in_array('alphabetic-pagination/index.php', $wpc_plugins_activated)
		){
		
			$wp_chameleon = get_option( 'wp_chameleon', array());
			$wp_chameleon = (is_array($wp_chameleon)?$wp_chameleon:array());
			$wp_chameleon['ap'] = isset($wp_chameleon['ap'])?$wp_chameleon['ap']:array();
			$wp_chameleon['apt'] = isset($wp_chameleon['apt'])?$wp_chameleon['apt']:array();
			
			if(isset($_REQUEST['ap_style'])){
				$wp_chameleon['ap'] = array($_REQUEST['ap_style']=> (!empty($_REQUEST['ap_style']) ? array('enabled') : array()));
			}
			if(isset($_REQUEST['ap_template'])){
				$wp_chameleon['apt'] = array($_REQUEST['ap_template']=> (!empty($_REQUEST['ap_template']) ? array('enabled') : array()));
			}
			
			update_option( 'wp_chameleon', sanitize_wpc_data($wp_chameleon));
			
				
		}
	}
	*/
		
	function wpc_third_party_support(){
			
		global $wpc_plugins_activated, $wpc_all_plugins;
	
		$wp_chameleon = get_option( 'wp_chameleon', array());
		$wp_chameleon = (is_array($wp_chameleon)?$wp_chameleon:array());
		$wp_chameleon_background = get_option( 'wp_chameleon_background', array());
		$wp_chameleon_background = (is_array($wp_chameleon_background)?$wp_chameleon_background:array());
	
		if(
				array_key_exists('alphabetic-pagination/index.php', $wpc_all_plugins)
			&&
				in_array('alphabetic-pagination/index.php', $wpc_plugins_activated)
		){
		
			$wp_chameleon['ap'] = isset($wp_chameleon['ap'])?$wp_chameleon['ap']:array();
			$wp_chameleon['apt'] = isset($wp_chameleon['apt'])?$wp_chameleon['apt']:array();
			/*
			if(isset($_REQUEST['ap_style']) && !empty($_REQUEST['ap_style'])){		
				$wp_chameleon['ap'] = array($_REQUEST['ap_style']=>array('enabled'));
			}	
			if(isset($_REQUEST['ap_template']) && !empty($_REQUEST['ap_template'])){		
				$wp_chameleon['apt'] = array($_REQUEST['ap_template']=>array('enabled'));
			}*/
			if(isset($_REQUEST['ap_style'])){
				$wp_chameleon['ap'] = array($_REQUEST['ap_style']=> (!empty($_REQUEST['ap_style']) ? array('enabled') : array()));
			}
			if(isset($_REQUEST['ap_template'])){
				$wp_chameleon['apt'] = array($_REQUEST['ap_template']=> (!empty($_REQUEST['ap_template']) ? array('enabled') : array()));
			}
			
			update_option( 'wp_chameleon', sanitize_wpc_data($wp_chameleon));
			
				
		}
	
		if(
			array_key_exists('woo-coming-soon/index.php', $wpc_all_plugins)
		&&
			in_array('woo-coming-soon/index.php', $wpc_plugins_activated)
		&&
			isset($_REQUEST['cs_style'])
		){
	
			if(!isset($_REQUEST['woo_cs_nonce_field']) || !wp_verify_nonce($_REQUEST['woo_cs_nonce_field'], 'woo_cs_nonce_action')){
	
				print_r(__('Sorry, your nonce did not verify.', 'chameleon'));
				wp_die();
	
			}else{
	
				$short = 'cs';
	
				$wp_chameleon[$short] = isset($wp_chameleon[$short])?$wp_chameleon[$short]:array();
				$wp_chameleon_background[$short] = isset($wp_chameleon_background[$short])?$wp_chameleon_background[$short]:array();
	
				if(isset($_REQUEST['cs_style'])){
	
					$cs_posted_style = sanitize_wpc_data($_REQUEST['cs_style']);
	
					$wp_chameleon[$short] = array($cs_posted_style => (!empty($cs_posted_style) ? array('enabled') : array()));					
				}
	
	
				if(isset($_REQUEST['cs_bg_attachment'])){
	
					$wp_chameleon_background[$short][$cs_posted_style] = sanitize_wpc_data($_REQUEST['cs_bg_attachment']);
				}
	
				if(function_exists('woo_cs_render_home_file')){
					woo_cs_render_home_file();
				}			
	
			}
				
		}
	
		update_option( 'wp_chameleon', sanitize_wpc_data($wp_chameleon));
		update_option( 'wp_chameleon_background', sanitize_wpc_data($wp_chameleon_background));
	
		
	}	
	
	function chameleon_filter_data($data) {
		
		$ret = array();
		if(!empty($data)){
			global $wpc_supported, $wpc_assets_loaded;
			$chameleon_valid_layouts = chameleon_valid_layouts();

			foreach($data as $key=>$arr){
				
				$last_node = $wpc_supported[$key]['last_node'];				
				$arr['forms'] = (array_key_exists('forms', $arr) && is_array($arr['forms'])?$arr['forms']:array());
				if(array_key_exists($key, $wpc_supported)){
					$valid_ids = array();
					if(!empty($chameleon_valid_layouts[$key]['list'])){
						foreach($chameleon_valid_layouts[$key]['list'] as $obj){
							$valid_ids[] = $obj->ID;
						}
					}
					
					$ret[$key] = array();
					$style = array_key_exists('styles', $arr)? $arr['styles']: '';
					if(array_key_exists($key, $wpc_assets_loaded) && array_key_exists($style, $wpc_assets_loaded[$key])){
						$ret[$key]['styles'] = $style;
						$ret[$key]['forms'] = array();
						$arr['forms']= array_map('intval', $arr['forms']);
						
						if(!empty($arr['forms'])){
							if($last_node){
								$ret[$key]['forms'] = array('enabled');
							}else{
								$matching = array_intersect($valid_ids, $arr['forms']);
								$ret[$key]['forms'] = $matching;
							}
						}
					}
					
					
				}
			}
			//pree($ret);exit;
		}
	 
		return $ret;
	}	



	function wpc_pre($data){
		if(isset($_GET['debug'])){
			wpc_pree($data);
		}
	}	 
		

	function wpc_pree($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';	
		
	}	 




	function chameleon_menu()
	{


		global $wpc_data, $wpc_pro;
		$pname = $wpc_data['Name'].' '.($wpc_pro?__('Pro', 'chameleon'):'');
		add_options_page($pname, $pname, 'install_plugins', 'wpc', 'wpc');
		


	}
	
	function chameleon_search($needle,$haystack) {
		foreach($haystack as $key=>$value) {
			$current_key=$key;
			if($needle===$value OR (is_array($value) && chameleon_search($needle,$value) !== false)) {
				return $current_key;
			}
		}
		return false;
	}
		
	function chameleon_recursive_removal(&$array, $val)
	{
		if(is_array($array))
		{
			foreach($array as $key=>&$arrayElement)
			{
				if(is_array($arrayElement))
				{
					chameleon_recursive_removal($arrayElement, $val);
				}
				else
				{
					if($arrayElement == $val)
					{
						unset($array[$key]);
					}
				}
			}
		}
	}	
	
	function chameleon_super_unique($array)
	{
		$result = array();
		
		if(!is_array($array))
		return $result;
		
		$serialize = array_map("serialize", $array);

		if(!is_array($serialize))
		return $result;
		
		$for_unserialize = array_unique($serialize);
		
		if(!is_array($for_unserialize))
		return $result;		
		
		$result = array_map("unserialize", $for_unserialize);
		
		foreach ($result as $key => $value)
		{
			if ( is_array($value) )
			{
				$result[$key] = chameleon_super_unique($value);
			}
		}
		
		return $result;
	}

	function wpc(){ 
	
		if ( !current_user_can( 'install_plugins' ) )  {

			wp_die( __( 'You do not have sufficient permissions to access this page.', 'chameleon' ) );

		}

		global $wpdb, $wpc_dir, $wpc_pro, $wpc_data; 

		
		include($wpc_dir.'inc/wpc_settings.php');
		
	}	



	
	

	function chameleon_plugin_links($links) { 
		global $wpc_premium_link, $wpc_pro;
		
		$settings_link = '<a href="options-general.php?page=wpc">'.__('Settings', 'chameleon').'</a>';
		
		if($wpc_pro){
			array_unshift($links, $settings_link); 
		}else{
			 
			$wpc_premium_link = '<a href="'.esc_url($wpc_premium_link).'" title="'.__('Go Premium', 'chameleon').'" target=_blank>'.__('Go Premium', 'chameleon').'</a>'; 
			array_unshift($links, $settings_link, $wpc_premium_link); 
		
		}
		
		
		return $links; 
	}
	
	function register_wpc_scripts() {
		
			
		if (is_admin ()){


		    $pages_array = array('wpc');

		    if(isset($_GET['page']) && in_array($_GET['page'], $pages_array)){

                wp_enqueue_media ();

                wp_enqueue_script(
                    'wpc-popper-scripts',
                    plugins_url('js/popper.min.js', dirname(__FILE__)),
                    array('jquery'),
                    date('Yhmi'),
                    true
                );

                wp_enqueue_script(
                    'wpc-bs-scripts',
                    plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
                    array('jquery'),
                    date('Yhmi'),
                    true
                );
                wp_enqueue_style( 'wpc-bs-style', plugins_url('css/bootstrap.min.css', dirname(__FILE__)), array(), date('Yhmi'));
                wp_enqueue_style( 'wpc-style', plugins_url('css/admin-styles.css', dirname(__FILE__)), array(), date('Yhmi'));

		    }





		}else{



					


			wp_enqueue_style( 'wpc-style', plugins_url('css/front-styles.css', dirname(__FILE__)), array(), date('Yhmi'));

			
			
		}


        $wpch_news_ticker = get_option('wpch_news_ticker', array());


        $nti_title = array_key_exists('title', $wpch_news_ticker) ? $wpch_news_ticker['title']: __('Latest News', 'chameleon');
        $nti_speed = array_key_exists('speed', $wpch_news_ticker) ? $wpch_news_ticker['speed']: round(0.1, 2);

        if(!strlen($nti_title)){
            $nti_title = __('Latest News', 'chameleon');
        }

        if(!$nti_speed){
            $nti_speed = rount(0.1, 2);
        }

        $settings_array = array(
            'nti' => array(

                'controls' => false,
                'titleText' => $nti_title,
                'speed' => $nti_speed,

            )
        );


        wp_enqueue_style( 'wpc-nti-style', plugins_url('css/nti/ticker.css', dirname(__FILE__)), array(), date('Yhmi'));
        wp_enqueue_script(
            'wpc-nti-scripts',
            plugins_url('js/nti/ticker.js', dirname(__FILE__)),
            array('jquery'),
            date('Yhmi'),
            true
        );

        wp_localize_script('wpc-nti-scripts', 'wpc_obj', $settings_array);

		wp_enqueue_script(
			'wpc-scripts',
			plugins_url('js/scripts.js', dirname(__FILE__)),
			array('jquery'),
			date('Yhmi'),
			true
		);
		
	
	} 
	
	add_action('wp', function(){ wp_chameleon('functions'); });
	add_action('widgets_init', function(){ wp_chameleon('functions'); });
	
	function wp_chameleon_css_scripts(){
		
		global $wpc_supported, $wpc_assets_loaded;
		
		$wp_chameleon = get_option( 'wp_chameleon');
		$wp_chameleon = loading_addon_personalization($wp_chameleon);	
			
		$css = array();
		$scripts = array();
		if(is_array($wp_chameleon) && !empty($wp_chameleon)){
			foreach($wp_chameleon as $key=>$data){
						
				if(!empty($data)){
					foreach($data as $style=>$forms){					
						//pree($forms);
						
						if(!empty($forms)){
							foreach($forms as $form){
								//pree($form);
								//pree($key);
								if((is_numeric($form) || in_array($form, array('enabled'))) && array_key_exists($key, $wpc_assets_loaded)){
									$css[$key][$form] = ($wpc_assets_loaded[$key][$style]['styles']);
									$scripts[$key][$form] = ($wpc_assets_loaded[$key][$style]['functions']);
									//pree($wpc_assets_loaded[$key]);							
								}
							}
						}elseif(array_key_exists($key, $wpc_supported) && $wpc_supported[$key]['last_node']){
							//pree($key);
							//$css[$key][$style] = $wpc_assets_loaded[$key][$style]['styles'];
						}
				
					}
				}
			}
		}		
		//pre($css);
		return array('css'=>$css, 'scripts'=>$scripts);
	}
		
	function wp_chameleon($trigger=''){
		
		//pree($trigger);
		$trigger = ($trigger?$trigger:'footer');
		global $wpc_assets_loaded, $wpc_supported;
	
		if(empty($wpc_assets_loaded)){
			chameleon_loading_assets();
		}
		
		//pree($wpc_assets_loaded);
		
		//$link_css = array();
		//$link_scripts = array();
		//pree($wp_chameleon);
		//pree($wpc_assets_loaded);
		//pree($wpc_supported);
		$chameleon_css_scripts = wp_chameleon_css_scripts();	
		//pre($chameleon_css_scripts);	
		extract($chameleon_css_scripts);
		//exit;
		//pree($scripts);exit;
		//pre($css);
		//pre($scripts);
		
		if(!empty($css)): 
	
		if($trigger=='footer'): ?><style type="text/css" media="all"><?php endif;

			$ch_upload_dir = wp_upload_dir();
			$ch_upload_basedir = $ch_upload_dir['basedir'];
			$ch_upload_url = $ch_upload_dir['baseurl'];

//			pree($upload_dir);

            $allowed_keys = array(
                'cf7', 'gf'
            );

			foreach($css as $key=>$forms): 
				if(!empty($forms)){
					//pree($forms);
					foreach($forms as $form_id=>$styles){
						if(!empty($styles)){
							
							//pree($styles);

                            $ch_upload_dir_path = $ch_upload_basedir."/chameleon/$key/form_$form_id";
                            if(in_array($key, $allowed_keys)){
                                if(!file_exists($ch_upload_dir_path)){
                                    $mk_status = @mkdir($ch_upload_dir_path, 0777, true);
                                }
						    }

							foreach($styles as $name=>$path){
								
								//pree($name);pree($path);
								
						        $ch_upload_file_path = $ch_upload_dir_path."/$name.css";

								
								if($form_id>0){
									switch($key){
										case 'cf7':
										
											if($trigger=='footer'):

                                                if(!file_exists($ch_upload_file_path)){

                                                    $cf7_content = str_replace(array('body', '.wpcf7{', '.wpcf7 '), array('.ignore_it body', 'div[id^="wpcf7-f'.$form_id.'"].wpcf7{', 'div[id^="wpcf7-f'.$form_id.'"].wpcf7 '), file_get_contents($path));
                                                    @file_put_contents($ch_upload_file_path, $cf7_content);
                                                }
                                                $ch_upload_file_url = str_replace($ch_upload_basedir, $ch_upload_url, $ch_upload_file_path);
                                                $link_css[] = $ch_upload_file_url;

											endif;
											
										break;
										case 'gf':
											
											if($trigger=='footer'):

                                                if(!file_exists($ch_upload_file_path)){

                                                    $cf7_content = str_replace(array('body', '.gform_wrapper'), array('.ignore_it body', '#gform_wrapper_'.$form_id.'.gform_wrapper'), file_get_contents($path));
                                                    @file_put_contents($ch_upload_file_path, $cf7_content);

                                                }
                                                $ch_upload_file_url = str_replace($ch_upload_basedir, $ch_upload_url, $ch_upload_file_path);
                                                $link_css[] = $ch_upload_file_url;

											endif;
											
										break;
									}
								}elseif($wpc_supported[$key]['last_node']){
									//pree($wpc_supported);
									//pree($key);
									switch($key){
										case 'wc':
										case 'ap':
										case 'apt':
										case 'bp':
											if($trigger=='footer'):
												global $ap_css_links;
												$ap_css_links = (is_array($ap_css_links)?$ap_css_links:array());
												//pre($ap_css_links);
												if(!empty($ap_css_links)){
													foreach($ap_css_links as $css_path){
														$link_css[] = $css_path;
													}
												}else{
													$link_css[] = $path;
												}
											endif;	
										break;
										
										case 'twentytwentyone':
										case 'twentytwenty':
										case 'twentynineteen':
										case 'twentyseventeen':
										case 'twentysixteen':
										case 'twentyfifteen':
										case 'twentyfourteen':
										case 'twentythirteen':
										case 'twentytwelve':
										case 'twentyeleven':
										case 'twentyten':
											//pree($wpc_supported[$key]);
											//pree($path);
											//pree($key);
											if($wpc_supported[$key]['activated']){
												if($trigger=='footer'):
													$link_css[] = $path;
												elseif($trigger=='functions'):
													if(array_key_exists('functions', $scripts[$key][$form_id])){
														$link_scripts[] = $scripts[$key][$form_id]['functions'];	
													}
												endif;		
												
											}
										break;
									}									
								}
							}
						}
					}
				}					
			endforeach; if($trigger=='footer'): ?></style><?php endif;
	
			endif;

			//pre($link_css);//exit;
			
			if(!empty($link_css) && $trigger=='footer'){
				$dir = plugin_dir_path( dirname(__FILE__) );
				$purl = plugin_dir_url( dirname(__FILE__) );
				//pree($dir);
				//pree($purl);
				foreach($link_css as $link){
					$link = str_replace($dir, $purl, $link);
					//pree($link);
?><link rel="stylesheet" href="<?php echo esc_url($link.'?t='.time()); ?>" media="all" /><?php					
				}
			}
			if($trigger=='footer'){/*
?>
<style type="text/css">

</style>
<?php	*/		
			}
			//pree($trigger);pree($link_scripts);
			if($trigger=='functions' && !empty($link_scripts)){
				foreach($link_scripts as $link_script){
					if(file_exists($link_script)){
						include_once($link_script);
					}
				}
			}
			


	}
	
	function get_chameleon(){

	}
	
	function loading_addon_personalization($wp_chameleon){
		
		global $wpc_supported, $wpc_assets_loaded;
		
		if($wpc_supported['bp']['activated']){
			//pree($wpc_assets_loaded['bp']);
			if(
				bp_displayed_user_id()
			){
				$bpc_selected = get_user_meta(bp_displayed_user_id(), 'bpc_theme', true);
				if($bpc_selected!=''){
					$wp_chameleon['bp'] = array($bpc_selected=>array('enabled'));					
				}								
			}
				
		}
		
		return $wp_chameleon;
	}
	
    function bpc_user_nav_item() {
        global $bp;
     
        $args = array(
                'name' => __('Themes', 'chameleon'),
                'slug' => 'bpc',
                'default_subnav_slug' => 'bpc',
                'position' => 100,
                'show_for_displayed_user' => false,
                'screen_function' => 'bpc_user_nav_item_screen',
                'item_css_id' => 'bpc'
        );
     
        bp_core_new_nav_item( $args );
    }
    add_action( 'bp_setup_nav', 'bpc_user_nav_item', 99 );
	
    function bpc_user_nav_item_screen() {
        add_action( 'bp_template_content', 'bpc_screen_content' );
        bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }
	
    function bpc_screen_content() {
		
		global $wpc_assets_loaded, $wpc_dir, $wpc_url;
		
		$slug = 'buddypress';
		$short = 'bp';
		$bp = $wpc_assets_loaded[$short];
		$bpc_selected = get_user_meta(bp_displayed_user_id(), 'bpc_theme', true);
		
		
		if(!empty($bp)){
?>
		<ul class="bpc_list">
<?php			
			foreach($bp as $style=>$assets){  $cap = wpc_capitalize($style); 
			
			$wpc_previews = wpc_previews($slug, $style, $assets, $short);
			extract($wpc_previews);
				
?>
		<li <?php echo ($bpc_selected==$style?'class="bpc_activated"':''); ?>>
        	<a title="Click here to activate <?php echo esc_attr($cap); ?> theme" href="?bpc=<?php echo esc_url($style); ?>">
            	<img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($cap); ?>" />
                <h4><?php echo esc_html($cap); ?></h4>
            </a>
        </li>
<?php							
				
			}
?>
		</ul>
<?php			
		}
     
     
    }			
	
	function personalizing_addons(){
		
		global $wpc_supported, $wpc_assets_loaded;
		
		if($wpc_supported['bp']['activated']){
			//pree($wpc_assets_loaded['bp']);
			if(
						is_user_logged_in() 
					&&
						bp_displayed_user_id()==get_current_user_id()	
					&& 
						isset($_GET['bpc']) 
					&& 
						array_key_exists($_GET['bpc'], $wpc_assets_loaded['bp'])
			){
				
				

				
				update_user_meta(get_current_user_id(), 'bpc_theme', sanitize_wpc_data($_GET['bpc']));
				//exit;
			}
			
		}
		
	}
	
    function chameleon_loading_assets(){

		global $wpc_supported, $wpc_assets, $wpc_assets_loaded, $wpc_pro;
		
		
		
		//pree($wpc_supported);pree($wpc_assets);
		if(!empty($wpc_supported)){
			foreach($wpc_supported as $key=>$data){
				$dir = $wpc_assets.$key;
				//pree($dir);
				if(is_dir($dir)){
					//pree($dir);
					$dir_iterator = new RecursiveDirectoryIterator($dir);
					//pree($dir_iterator);
					foreach ($dir_iterator as $name=>$obj) {
						//pree($name);
						if(!in_array($obj->getfileName(), array('.', '..'))){
							if(is_dir($obj->getpathName())){
								$for_version_dir = $dir.'/'.$obj->getfileName();
								//pree($for_version_dir);//exit;
								$folders = array();
								//pree($for_version_dir);
								if ($dh = opendir($for_version_dir)) {
									while (($file = readdir($dh)) !== false) {
										//pree($file);
										if(!in_array($file, array('.', '..')) && is_dir($for_version_dir.'/'.$file)){
											$folders[] = $file;	
										}
									}
									closedir($dh);
								}							
								//pree($folders);exit;
								//pree($folders);
								$styles = $images = $scripts = $fonts = $templates = $functions = array();
								
								if(!empty($folders)){
									foreach($folders as $folder){
										//pree($folder);
										$style_dir = $dir.'/'.$obj->getfileName().'/'.$folder;
										
										
			
										if(is_dir($style_dir)){
											$gd = $style_dir.'/*.*';
											$gd_glob = glob($gd);
											//pree($gd_glob);
											foreach($gd_glob as $file){
												
												$name = basename($file);
												$ext = explode('.', $name);
												$name_only = current($ext);
												$ext = end($ext);
												$ext = strtolower($ext);
												$pro_file = str_replace('/assets/', '/pro/assets/', $file);
												
												switch($ext){
													case 'jpg':
													case 'jpeg':
													case 'png':
													case 'gif':
													case 'bmp':
														$images[$name_only] = $file;
													break;
													case 'js':														
														$scripts[$name_only] = $file;
													break;
													case 'css':
														$styles[$name_only] = ($wpc_pro && file_exists($pro_file)?$pro_file:$file);
													break;
													case 'ttf':
														$fonts[$name_only] = $file;
													break;
													case 'html':
														$templates[$name_only] = $file;
													break;	
													case 'php':
														$functions[$name_only] = $file;
													break;											
													
													
												}
											}
										}
									}
								}
								//pree($functions);
								$wpc_assets_loaded[$key][$obj->getfileName()] = array(
									'styles' => $styles,
									'images' => $images,
									'scripts' => $scripts,
									'fonts' => $fonts,
									'templates' => $templates,
									'functions' => $functions,
								);
								//pree($wpc_assets_loaded);exit;
							}
							
						}
					}
					
				}
				//pree($wpc_assets_loaded);
				//exit;
				
			}
			//pree($wpc_assets_loaded);exit;
			//
			personalizing_addons();
		}
	}
	function wpc_capitalize($name){
		return ucwords(str_replace(array('-'), ' ', $name)); 
	}
	
	function wpc_slug_to_short($slug){
		
		$ret = array();
		
		global $wpc_supported;
		
		if(!empty($wpc_supported)){
			foreach($wpc_supported as $short=>$data){
				if($slug==$data['slug']){
					$ret[] = $short;
				}
			}
		}
		
		return $ret;
	}


	
	function wpc_previews($slug, $name, $data, $short=''){
		global $wpc_dir, $wpc_url, $wpc_counter, $wpc_supported;
		
		//pree($slug.' > '.$name.' > '.$short.' > '.$wpc_supported[$short]['activated']);
		
		$github_slug = (array_key_exists('github', $wpc_supported[$short])?$wpc_supported[$short]['github']:$slug);
		
		//pree("$slug, $name");
		//pree($data);
		
		
		//$shorts = wpc_slug_to_short($slug);
		//pree($shorts);
		
		
		/*if(!empty($shorts)){
			foreach($shorts as $key){
				pree($slug.' > '.$key.' > '.$wpc_supported[$key]['activated']);
			}
		}*/

		
		
		$preview = 'https://raw.githubusercontent.com/uxglow/'.$github_slug.'/master/'.$name.'/1.0/';
		//pree($preview);
		$remote_screenshot = $preview.'screenshot.png';
		$thumb = $remote_thumb = $preview.'thumb.png';
		//wpc_pree($data);
		
		if(isset($data['images']['thumb']) && file_exists($data['images']['thumb'])){
			$thumb = str_replace($wpc_dir, $wpc_url, $data['images']['thumb']);
		}elseif(isset($data['images']['screenshot']) && file_exists($data['images']['screenshot'])){
			$thumb = str_replace($wpc_dir, $wpc_url, $data['images']['screenshot']);
		}elseif($wpc_supported[$short]['activated']){
			$thumb = $remote_screenshot;
			$style_name = (array_key_exists($name, $data['styles']) ? $data['styles'][$name] : '');
			$path = str_replace($name.'/css/'.$name.'.css', $name.'/images/', $style_name);
			$data['images']['thumb'] = $path.'thumb.png';
			$data['images']['screenshot'] = $path.'screenshot.png';
			
			if($wpc_counter<20){
				
				if(!file_exists($data['images']['thumb'])){
					@copy($remote_thumb, $data['images']['thumb']);				
				}
				if(!file_exists($data['images']['screenshot'])){
					@copy($remote_screenshot, $data['images']['screenshot']);				
				}
				$wpc_counter++;
			}
		}		
		$ret = array('remote_screenshot'=>$remote_screenshot, 'remote_thumb'=>$remote_thumb, 'thumb'=>$thumb);
		
		//pree($ret);
		
		return $ret;
	}

	function wpc_get_active_supported(){
        global $wpc_supported;

        $active_key_array = array();
        if(!empty($wpc_supported)){
            foreach($wpc_supported as $key => $data){

                if((!$data['installed'] || !$data['active'] || !$data['activated'])){

                    continue;
                }
                $active_key_array[] = $key;

            }
        }

        return $active_key_array;
    }


	function wpc_get_admin_html($active = true){
        global $wpc_supported, $wpc_assets_loaded, $wpc_url, $wpc_plugins_activated, $wpc_pro, $wpc_premium_link;
        $chameleon_valid_layouts = chameleon_valid_layouts();
        $wp_chameleon = get_option( 'wp_chameleon');
        $wpc_theme = wp_get_theme();
        $active_keys = wpc_get_active_supported();

        ?>


            <div class="wpch_primary">
                <h6 class="sub_heading" style="float:right"><?php _e('Supported Plugins', 'chameleon'); ?></h6>

                <?php //pree($wpc_plugins_activated); ?>
                <?php //pree($wpc_supported); ?>
                <?php if(!empty($wpc_supported)): ksort($wpc_supported); ?>
                    <ul>
                        <?php foreach($wpc_supported as $key=>$data): ?>
                            <?php
                            $skip_block = ($active && !in_array($key, $active_keys)) || (!$active && in_array($key, $active_keys));

                            //if($data['installed']):

                                if($skip_block){
                                    continue;
                                }

                            ?>

                            <li title="<?php echo esc_attr($data['name']); ?>" class="wpc_supported <?php echo ($data['installed']?'installed':'').' '.(isset($data['class'])?$data['class']:'').' '.($data['active']?'available':'upcoming'); ?>">
                                <a data-key="<?php echo esc_attr($key); ?>">
                                    <span><?php echo esc_html($data['active']?'':__('Coming Soon!', 'chameleon')); ?></span>
                                    <img src="<?php echo plugins_url( 'images/'.$data['icon'], dirname(__FILE__) ); ?>"  alt="<?php echo esc_attr($data['name']); ?>" />
                                    <h4><?php echo esc_html($data['name']); ?></h4>
                                </a>
                            </li>
                            <?php //endif; ?>
                        <?php endforeach; ?>
                    </ul>

                <?php endif; ?>



                <div class="wpch_form_wrap">
                    <?php wp_nonce_field( 'wpc_valid_a', 'wpc_valid_f' ); ?>




                    <?php if(!empty($wpc_assets_loaded)): //pree($wpc_assets_loaded);?>

                        <?php
                        //pree($wpc_assets_loaded);
                        foreach($wpc_assets_loaded as $item=>$types): //pree($data['styles']);
                            //pree($types);
                            $slug = $wpc_supported[$item]['slug'];
                            $skip_block = ($active && !in_array($item, $active_keys)) || (!$active && in_array($item, $active_keys));
                            if($skip_block){
                                continue;
                            }
                            ?>
                            <a data-grid="<?php echo esc_attr($item); ?>" href="<?php echo esc_url('http://guavapattern.com/chameleon/?load='.$item); ?>" title="<?php _e('Click here to view all styles', 'chameleon'); ?>" class="wpch_grid" target="_blank"></a>

                            <input title="<?php _e('Click here to reset to default', 'chameleon'); ?>" data-item="<?php echo esc_attr($item); ?>" name="wpc_reset[<?php echo esc_attr($item); ?>]" type="button" value="<?php _e('Reset', 'chameleon'); ?>" class="wpch_btn_reset hide" />
                            <input name="wpc_reset_action[<?php echo esc_attr($item); ?>]" type="hidden" value="" />
                            <select class="wpch_form_style" name="wpc[<?php echo esc_attr($item); ?>][styles]" size="2" data-obj="<?php echo esc_attr($item); ?>">

                                <?php if(!empty($types)): ksort($types); ?>

                                    <?php foreach($types as $name=>$data): $cap = wpc_capitalize($name);

                                        $wpc_previews = wpc_previews($slug, $name, $data, $item);
                                        extract($wpc_previews);

                                        ?>
                                        <option data-css="<?php echo (isset($data['styles'][$name])?'yes':'no'); ?>" data-cap="<?php echo esc_attr($cap); ?>" value="<?php echo esc_attr($name); ?>" data-forms="<?php echo (isset($wp_chameleon[$item][$name])?implode('|', $wp_chameleon[$item][$name]):''); ?>" data-url="<?php echo esc_url($thumb); ?>" data-full="<?php echo esc_url($remote_screenshot); ?>"><?php echo esc_html($cap).' '.(isset($wp_chameleon[$item][$name])?'('.count($wp_chameleon[$item][$name]).')':''); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php endforeach; ?>

                    <?php endif; ?>


                    <div class="wpch_thumbnail">
                        <span class="title">&nbsp;</span>
                        <img src="" />
                        <span class="preview"></span>
                    </div>


                    <?php if(!empty($wpc_supported)): //pree($wpc_supported); ?>
                        <?php foreach($wpc_supported as $key=>$data):

                            $skip_block = ($active && !in_array($key, $active_keys)) || (!$active && in_array($key, $active_keys));
                            if($skip_block){
                                continue;
                            }
                            //pree($data); ?>
                            <div class="wpch_cf7_wrap wpch_<?php echo esc_attr($key); ?>_form">
                                <?php if($data['installed'] && $data['activated']): ?>



                                    <?php


                                    $forms_array = $chameleon_valid_layouts[$key]['list'];
                                    $create_form = $chameleon_valid_layouts[$key]['link'];
                                    $create_form_label = $chameleon_valid_layouts[$key]['action_txt'];
                                    $items = $wpc_supported[$key]['items'];
                                    $last_node = $wpc_supported[$key]['last_node'];
									
									//pree(count($items));
									//pree($last_node);


                                    if($items){
                                        if(!empty($forms_array)): ?>
                                            <span><a href="<?php echo esc_url($create_form); ?>" target="_blank"><?php echo esc_html($create_form_label); ?></a></span>
                                            <b><?php _e('or', 'chameleon'); ?></b>
                                            <h3><?php _e('Select Forms', 'chameleon'); ?></h3>
                                            <select class="wpch_cf7 wpch_forms_selection" name="wpc[<?php echo esc_attr($key); ?>][forms][]" multiple="multiple">
                                                <?php foreach($forms_array as $form): $cap = ucwords(str_replace(array('-'), ' ', $form->post_title));  ?>
                                                    <option value="<?php echo esc_attr($form->ID); ?>" data-cap="<?php echo esc_attr($cap); ?>"><?php echo esc_html($cap); ?></option>
                                                <?php endforeach; ?>

                                            </select><br />
                                            <small><?php _e('Hold ctrl key for multiple selection', 'chameleon'); ?>.</small>
                                            <div class="wpch_buton">
                                                <input class="button button-primary wpch_apply" type="submit" name="apply" value="<?php _e('Apply', 'chameleon'); ?>"/>
                                            </div>
                                        <?php else: ?>
                                            <center><?php _e('No items found.', 'chameleon'); ?><br /><br />


                                                <span><a href="<?php echo esc_url($create_form); ?>" target="_blank"><?php echo esc_html($create_form_label); ?></a></span>
                                            </center>
                                        <?php
                                        endif;
                                    }else{
                                        ?>

                                        <?php if($last_node): ?>
                                            <input type="hidden" name="wpc[<?php echo esc_attr($key); ?>][forms][]" value="enabled" />
                                            <div class="wpch_buton">
                                                <input class="button button-primary wpch_apply" type="submit" name="apply" value="<?php echo esc_attr($create_form_label); ?>"/>
                                            </div>
                                        <?php else: ?>
                                            <center>
                                                <span><a href="<?php echo esc_url($create_form); ?>" target="_blank"><?php echo esc_html($create_form_label); ?></a></span>
                                            </center>
                                        <?php endif; ?>
                                        <?php
                                    }
                                    ?>


                                <?php elseif($data['installed']): ?>
                                    <center>
                                        <?php echo esc_html($data['name']); ?> <?php _e('is not activated.', 'chameleon'); ?><br /><br />


                                        <span><a href="<?php echo ($data['activate'].''.$data['slug']); ?>" target="_blank"><?php _e('Activate', 'chameleon'); ?> <?php echo esc_html($data['name']); ?></a></span>
                                    </center>
                                <?php else: ?>

                                    <center>
                                        <?php echo esc_html($data['name']); ?> <?php _e('is not installed.', 'chameleon'); ?><br /><br />



                                        <span><a href="<?php echo esc_url($data['install'].urlencode( $data['name'])); ?>" target="_blank"><?php _e('Install', 'chameleon'); ?> <?php echo esc_html($data['name']); ?></a></span>
                                    </center>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>



                </div>

                <?php //do_action('after_wpch_form_wrap');

                if($active && function_exists('after_wpch_form_wrap_news_ticker')){
                    after_wpch_form_wrap_news_ticker();
                }
                ?>

            </div>



        <?php

    }


    if(!function_exists('wpch_news_ticker_css_js')){
        function wpch_news_ticker_css_js($type, $refresh = false){

            if($type != 'css' && $type != 'js'){
                return '';
            }

            global $wpc_dir;

            $file_name = ($type == 'css' ? 'nti_styles' : 'nti_scripts');

            $ch_upload_dir = wp_upload_dir();
            $ch_upload_basedir = $ch_upload_dir['basedir'];
            $ch_upload_url = $ch_upload_dir['baseurl'];

            $ch_upload_dir_path = $ch_upload_basedir."/chameleon/nti/$type";
            $ch_upload_file_path = $ch_upload_dir_path."/$file_name.$type";
            $ch_upload_file_url = str_replace($ch_upload_basedir, $ch_upload_url, $ch_upload_file_path);
            if(file_exists($ch_upload_file_path) && !$refresh){

                return $ch_upload_file_url;
            }


            if(!file_exists($ch_upload_dir_path)){
                $mk_status = @mkdir($ch_upload_dir_path, 0777, true);
            }


            ob_start();

            $css_template_path = $wpc_dir."/templates/nti/$file_name.php";

            if(file_exists($css_template_path)){
                include_once($css_template_path);
            }

            $ret = ob_get_contents();
            ob_clean();

            file_put_contents($ch_upload_file_path, $ret);
            return $ch_upload_file_url;

        }
    }