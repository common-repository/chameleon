<?php defined( 'ABSPATH' ) or die( __('No script kiddies please!', 'chameleon') );
	if ( !current_user_can( 'install_plugins' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'chameleon' ) );
	}


	global $wpc_supported, $wpc_assets_loaded, $wpc_url, $wpc_plugins_activated, $wpc_pro, $wpc_premium_link;
	$youtube = array ( 'L9azlLO3-rE', '-FuZt32IUqM', 'Wy9CnWZZCO0' );



	$chameleon_valid_layouts = chameleon_valid_layouts();

	$wpc = isset( $_POST['wpc'] ) ? (array) $_POST['wpc'] : array();


   	if (!empty($wpc)) {


		$wpc = chameleon_filter_data($wpc);

		if (
				! isset( $_POST['wpc_valid_f'] )
				|| ! wp_verify_nonce( $_POST['wpc_valid_f'], 'wpc_valid_a' )
			) {

			   print __('Sorry, your nonce did not verify.', 'chameleon');
			   exit;

			} else {

				$wpc_reset_action = sanitize_wpc_data($_POST['wpc_reset_action']);
				//wpc_pree($wpc);
				$wp_chameleon = get_option( 'wp_chameleon');




				foreach($wpc as $key=>$data){

				    if(array_key_exists('styles', $wpc[$key])){

                        $wpc[$key][$wpc[$key]['styles']] = array();

                    }

					if(array_key_exists('forms', $wpc[$key]) && !empty($wpc[$key]['forms'])){
						foreach($wpc[$key]['forms'] as $form){
							chameleon_recursive_removal($wp_chameleon[$key], $form);
						}
					}

                    if(array_key_exists('styles', $wpc[$key])) {

                        $wpc[$key][$wpc[$key]['styles']] = array_key_exists('forms', $wpc[$key]) ? $wpc[$key]['forms'] : array();
                    }

					unset($wpc[$key]['styles']);
					unset($wpc[$key]['forms']);

				}
				//pree($wp_chameleon);exit;
				$wp_chameleon = chameleon_super_unique($wp_chameleon);

				//pree($wp_chameleon);exit;
				$result = array_merge_recursive($wp_chameleon, $wpc);


				if(!empty($wpc_reset_action)){
					foreach($wpc_reset_action as $key=>$bool){
						if($bool=='true'){
							$result[$key] = array();
						}
					}
				}
				//wpc_pree($result);//wpc_pree($wpc_reset_action);
				//exit;

				//
				update_option( 'wp_chameleon', $result);
			}


	}



	//pree($wpc_assets_loaded);

	$wp_chameleon = get_option( 'wp_chameleon');
	$wpc_theme = wp_get_theme();
	//pree($wpc_dir);
//	pree($wpc_theme);exit;
	//pree($wp_chameleon);
	//pree($wpc_supported);



?>



<div class="wrap wpch">

	<?php if(!$wpc_pro): ?>
    <a title="<?php _e('Click here to download pro version', 'chameleon'); ?>" style="background-color: #333;    color: #fff !important;    padding: 2px 30px;    cursor: pointer;    text-decoration: none;    font-weight: bold;    right: 0;    position: absolute;    top: 0;    box-shadow: 1px 1px #ddd;" href="https://shop.androidbubbles.com/download/" target="_blank"><?php _e('Already a Pro Member?', 'chameleon'); ?></a>
    <?php endif; ?>


    <div class="head_area">
    <h2><span class="dashicons dashicons-welcome-widgets-menus"></span><?php echo esc_html($wpc_data['Name']).' '.($wpc_pro?__('Pro', 'chameleon'):''); ?> (<?php echo esc_html($wpc_data['Version']); ?>) - <?php _e('Settings', 'chameleon'); ?> </h2>

    </div>


    <h2 class="nav-tab-wrapper">
        <a class="nav-tab nav-tab-active"><?php _e("Styles","chameleon"); ?></a>
        <a class="nav-tab"><?php _e("How it works?","chameleon"); ?></a>
    </h2>






    <form class="nav-tab-content wpch_form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">

        <div class="container-fluid">

            <div class="row mt-3">
                <div class="col-md-12 pl-0">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <?php _e('Active', 'chameleon') ?> <?php _e('Plugins', 'chameleon') ?>
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <?php if(function_exists('wpc_get_admin_html')){wpc_get_admin_html();} ?>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <?php _e('In Active', 'chameleon') ?> <?php _e('Plugins', 'chameleon') ?>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <?php if(function_exists('wpc_get_admin_html')){wpc_get_admin_html(false);} ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </form>


    <form class="nav-tab-content hide" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
	<span class="wpch_help"><a href="https://wordpress.org/support/plugin/chameleon" target="_blank"><?php _e('need help?', 'chameleon'); ?></a></span>


   <?php if(!empty($youtube)){ ?>

        <ul class="wpch_v_tutorials">

      <?php foreach( $youtube as $id ) { ?>

            <li>
            	<iframe width="150" height="100" src="<?php echo esc_url('https://www.youtube.com/embed/'.$id); ?>" frameborder="0" allowfullscreen></iframe>
            </li>

		<?php } ?>

        </ul>

   <?php } ?>

        <ul class="wpch_tutorials">
        	<li>
            	<h3><?php _e('Resources', 'chameleon'); ?></h3>
            </li>
        	<li>
            	<a href="https://plugins.svn.wordpress.org/chameleon/assets/guide.pdf" target="_blank"><?php _e('About Chameleon', 'chameleon'); ?></a>
            </li>
            <?php if(!$wpc_pro): ?>
        	<li>
            	<a href="<?php echo esc_url($wpc_premium_link); ?>" target="_blank"><?php _e('Get Premium Styles', 'chameleon'); ?></a>
            </li>
            <?php endif; ?>

        </ul>


	</form>
    <p class="description"><?php echo esc_html($wpc_data['Description']); ?></p>

</div>

<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($){
		<?php if(isset($_GET['s']) && $_GET['s']!=''): ?>
		if($('.wpc_supported').length>0){
			var wp_intv = setInterval(function(){
				$('.wpc_supported a[data-key="<?php echo sanitize_wpc_data($_GET['s']); ?>"]').click();
				clearInterval(wp_intv);
			}, 1000);
		}
		<?php endif; ?>
	});
</script>
<style type="text/css">
	#wpcontent {
		background-color: maroon;
	}
	.wp-submenu.wp-submenu-wrap li.current{
		background-color: maroon;
	}
	.wp-submenu.wp-submenu-wrap li.current a.current{
		color:#fff;
	}
	#footer-thankyou{
		display:none;
	}
	.woocommerce-message, .update-nag, #message, .notice.notice-error, .error.notice, div.notice, div.fs-notice, div.wrap div.updated{ display:none !important; }
</style>