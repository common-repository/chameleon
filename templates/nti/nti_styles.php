<?php


$wpch_news_ticker = get_option('wpch_news_ticker', array());
$wpch_news = array_key_exists('news', $wpch_news_ticker) ? $wpch_news_ticker['news']: ''  ;

$nti_bg_color = array_key_exists('bg_color', $wpch_news_ticker) ? $wpch_news_ticker['bg_color']: '#f8f0db';
$nti_title_color = array_key_exists('title_color', $wpch_news_ticker) ? $wpch_news_ticker['title_color']: '#990000';
$nti_content_color = array_key_exists('content_color', $wpch_news_ticker) ? $wpch_news_ticker['content_color']: '#1F527B';



?>


.ticker-wrapper.has-js,
.ticker-wrapper.has-js .ticker,
.ticker-wrapper.has-js .ticker-title,
.ticker-wrapper.has-js .ticker-content,
.ticker-wrapper.has-js .ticker-swipe,
.ticker-wrapper.has-js .ticker-swipe span
{
    background-color: <?php echo esc_html($nti_bg_color);?>;
}


@media screen and (max-width: 780px){

    .ticker-wrapper.has-js{
        width: 100%;
    }

    .ticker-wrapper.has-js .ticker{
        width: 90%;
    }
}

.ticker-wrapper.has-js{
    height: 45px;
}

.ticker,
.ticker-swipe{
    height: 32px;
}


.ticker-wrapper.has-js .ticker-title{
    color: <?php echo esc_html($nti_title_color); ?>;
    padding-top: 10px;
}

.ticker-wrapper.has-js .ticker-content{
    padding-top: 13px;
}

.ticker-wrapper.has-js .ticker-content,
.ticker-wrapper.has-js .ticker-content a{

    color: <?php echo esc_html($nti_content_color); ?>;
    text-decoration: none;
    -webkit-box-shadow: inset 0 -1px 0 transparent;
    box-shadow: inset 0 -1px 0 transparent;

}



.ticker-wrapper.has-js .ticker-swipe span{
    border-bottom-color: <?php echo esc_html($nti_content_color);?>;
    height: 22px;
}
