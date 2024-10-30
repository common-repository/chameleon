<?php

$wpch_news_ticker = get_option('wpch_news_ticker', array());

global $wpc_url;


$nti_title = array_key_exists('title', $wpch_news_ticker) ? $wpch_news_ticker['title']: 'Latest News';
$nti_dom = array_key_exists('nti_dom', $wpch_news_ticker) ? $wpch_news_ticker['nti_dom']: false;
//pree($wpch_news_ticker);exit;
$nti_speed = array_key_exists('speed', $wpch_news_ticker) ? $wpch_news_ticker['speed']: round(0.1, 2);
$nti_bg_color = array_key_exists('bg_color', $wpch_news_ticker) ? $wpch_news_ticker['bg_color']: '#f8f0db';
$nti_title_color = array_key_exists('title_color', $wpch_news_ticker) ? $wpch_news_ticker['title_color']: '#990000';
$nti_content_color = array_key_exists('content_color', $wpch_news_ticker) ? $wpch_news_ticker['content_color']: '#1F527B';

$wpch_news = array_key_exists('news', $wpch_news_ticker) ? $wpch_news_ticker['news'] : array();

$news_text = array_key_exists('text', $wpch_news) ? $wpch_news['text'] : array('');
$news_url = array_key_exists('url', $wpch_news) ? $wpch_news['url'] : array('');

$setting_divider = "<hr style='border:1px solid white'>";
$news_list_divider = "<hr style='border-color: white'>";

$dom_selectors = array(

    '#main'=>'#main',
    '#primary'=>'#primary',
    '#content'=>'#content',
    'body.post-type-archive-product .woocommerce-products-header__title.page-title' => __('WooCommerce Shop Page > Above Page Title','chameleon'),
    'body.post-type-archive-product div.woocommerce-notices-wrapper' => __('WooCommerce Shop Page > Below Page Title','chameleon'),
    'body.tax-product_cat .woocommerce-products-header__title.page-title' => __('WooCommerce Product Category Page > Above Category Name','chameleon'),
    'body.tax-product_cat div.woocommerce-notices-wrapper' => __('WooCommerce Product Category Page > Below Category Name','chameleon'),
    'body.tax-product_cat .woocommerce-products-header__title.page-title, body.post-type-archive-product .woocommerce-products-header__title.page-title' => __('WooCommerce Shop + Category Pages > Above Heading','chameleon'),
    'body.tax-product_cat div.woocommerce-notices-wrapper, body.post-type-archive-product div.woocommerce-notices-wrapper' => __('WooCommerce Shop + Category Pages > Below Heading','chameleon'),
);




?>

<div class="container-fluid wpch_wrapper_custom wpch_wrapper_nti" style="display: none; clear: both">





    <div class="row">


        <div class="col-md-12 pl-0">

            <div class="row mb-3 mt-5">

                <div class="h4 col-md-4">
                    <img src="<?php echo esc_url($wpc_url.'images/nti-caption.jpg'); ?>"  />
                </div>

                <div class="col-md-8 mt-2 mt-md-0 text-right">
                    <img src="<?php echo esc_url($wpc_url.'images/nti_sample.png'); ?>" class="img-thumbnail" />
                </div>

            </div>





            <div class="h5">
                <?php _e('Settings', 'chameleon'); ?>
            </div>

            <div class="row">



                <div class="col-md-3">
                    <input type="text" title="<?php _e('News Ticker Title', 'chameleon'); ?>" class="form-control" id="wpch_news_title"  name="wpch_news_ticker[title]" value="<?php echo esc_attr($nti_title); ?>">
                </div>

                <div class="col-md-4">

                    <?php if(!empty($dom_selectors)): ?>

                        <select class="nti_dom" name="wpch_news_ticker[nti_dom]" id="nti_dom" title="<?php _e('This is the HTML element where the News Ticker will be placed into.','chameleon'); ?>">
                            <option value=""><?php _e('Select DOM','chameleon'); ?></option>

                            <?php foreach($dom_selectors as $dom=>$dom_text): ?>
                                <option value="<?php echo $dom; ?>" <?php selected( $dom, $nti_dom ); ?>><?php echo esc_attr($dom_text); ?></option>
                            <?php endforeach; ?>

                        </select>


                    <?php endif; ?>

                </div>

                <div class="col-3 col-md-1 mt-3 mt-md-0">
                    <input type="number" title="<?php _e('Speed', 'chameleon'); ?>" step="0.1" min="0.1" max="1" name="wpch_news_ticker[speed]" class="form-control" id="wpch_news_speed"  value="<?php echo esc_attr($nti_speed); ?>">
                </div>

                <div class="col-3 col-md-1 mt-3 mt-md-0">
                    <input type="color" title="<?php _e('Background Color', 'chameleon'); ?>" class="nti_color_input form-control p-0" id="wpch_news_bg_color" name="wpch_news_ticker[bg_color]"  value="<?php echo esc_attr($nti_bg_color); ?>">
                </div>


                <div class="col-3 col-md-1 mt-3 mt-md-0">
                    <input type="color" title="<?php _e('Title Color', 'chameleon'); ?>" class="nti_color_input form-control p-0" id="wpch_news_title_color" name="wpch_news_ticker[title_color]"  value="<?php echo esc_attr($nti_title_color); ?>">
                </div>

                <div class="col-3 col-md-1 mt-3 mt-md-0">
                    <input type="color" title="<?php _e('Content Color', 'chameleon'); ?>" class="nti_color_input form-control p-0" id="wpch_news_content_color" name="wpch_news_ticker[content_color]"  value="<?php echo esc_attr($nti_content_color); ?>">
                </div>
                
                <img class="wpch_nti_colors" src="<?php echo esc_url($wpc_url.'images/nti-colors.png'); ?>"  />

            </div>


            <div class="h5 mt-5">                
				<?php _e('Tickers List', 'chameleon'); ?>
            </div>


            <div class="row pr-md-3">
                <div class="col-md-8 wpch_nti_news_list">


            <?php if(!empty($news_text)):


                $counter = 0;
                for ($i = 0 ; $i < sizeof($news_text); $i++):

                $class = ($i === 0 ? 'clear' : 'del');

            ?>

                    <div class="wpch_single_news_row mb-3">

                        <div class="wpch_nti_news_heading"   style="cursor: pointer" title="<?php _e('Click to expand/collapse this news', 'chameleon'); ?>"><?php _e('News Text', 'chameleon'); ?><span> <?php echo ($i+1);?></span></div>
                        <div class="row mt-2 wpch_single_news_data" style="display: none">

                                <div class="col-md-10 ml-0">


                                    <div class="form-group col-md-12 pl-0 position-relative" >
                                        <span class="dashicons dashicons-welcome-widgets-menus text-dark position-absolute" style="top: 5px; right: 25px; font-size: 25px;"></span>
                                        <textarea class="form-control" rows="5" id="subject" name="wpch_news_ticker[news][text][]" style="padding-right: 45px;"><?php echo (array_key_exists($i, $news_text) ? $news_text[$i]: ''); ?></textarea>
                                    </div>
                                    <div class="form-group col-md-12 pl-0 position-relative">
                                        <span class="dashicons dashicons-admin-links text-dark position-absolute nti_pre_input_icon"></span>
                                        <input style="padding-left: 35px;" type="url" title="<?php _e('News Link', 'chameleon'); ?>" class="form-control" id="date_time" name="wpch_news_ticker[news][url][]" value="<?php echo array_key_exists($i, $news_url) ? $news_url[$i]: ''?>">
                                    </div>

                                </div>

                                <div class="col-md-2 pl-0">

                                    <div class="form-group">
                                        <div class="btn btn-success btn-sm wpch_add add" id="add_row">+</div>
                                        <div class="btn btn-danger wpch_del btn-sm <?php echo esc_attr($class); ?>">-&nbsp;</div>
                                    </div>

                                </div>

                                <div class="col-md-11">
                                    <?php echo $news_list_divider; ?>
                                </div>

                        </div>

                    </div>


            <?php endfor; ?>
            <?php endif; ?>


                </div>

                <div class="col-md-4 wpch_nti_siderbar text-dark" style="min-height: 400px;">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group">
                                <li class="list-group-item mr-0">
                                    <div class="h6"><?php _e('News Ticker Shortcode', 'chameleon');?></div>
                                    <ul class="list-group">
                                        <li class="list-group-item mr-0">
                                            [WPCH_NEWS_TICKER]
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <iframe style="width: 90%; height: auto; margin: 0 auto; display: block;" src="https://www.youtube.com/embed/mLHXelNdVdE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <button type="submit" name="wpch_news_ticker_submit" class="btn btn-primary"><?php _e('Update Settings', 'chameleon') ?></button>
                </div>

            </div>
        </div>

    </div>

</div>