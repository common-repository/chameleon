<?php

    $ticker_settings = array(

        'speed' => 0.1,           // The speed of the reveal
        'controls' => false,
        'titleText' => 'Latest News',
        'direction' => 'ltr', // Ticker direction - current options are 'ltr' or 'rtl',
        'pauseOnItems' => 2000,    // The pause on a news item before being replaced
        'fadeInSpeed' => 600,      // Speed of fade in animation
        'fadeOutSpeed'=> 300,      // Speed of fade out animation
    );

    $unique_id = uniqid('js-news');

    $wpch_news_ticker = get_option('wpch_news_ticker', array());

    $wpch_news = array_key_exists('news', $wpch_news_ticker) ? $wpch_news_ticker['news']: ''  ;

    $nti_bg_color = array_key_exists('bg_color', $wpch_news_ticker) ? $wpch_news_ticker['bg_color']: '#f8f0db';
    $nti_title_color = array_key_exists('title_color', $wpch_news_ticker) ? $wpch_news_ticker['title_color']: '#990000';
    $nti_content_color = array_key_exists('content_color', $wpch_news_ticker) ? $wpch_news_ticker['content_color']: '#1F527B';


    $news_text = array_key_exists('text', $wpch_news) ? $wpch_news['text'] : array('');
    $news_url = array_key_exists('url', $wpch_news) ? $wpch_news['url'] : array('');

?>


<ul id="wpch_news_ticker" class="wpch_news_ticker js-hidden">
    <?php if(!empty($news_text)):


    $counter = 0;
    for ($i = 0 ; $i < sizeof($news_text); $i++):

    $class = $i === 0 ? 'clear' : 'del';

    $url = array_key_exists($i, $news_url) ? $news_url[$i]: '';
    $news = array_key_exists($i, $news_text) ? $news_text[$i]: '';
    $news_array = explode('|', $news);


    if(sizeof($news_array) > 1){

        $news = current($news_array);
        $url = end($news_array);

    }



    ?>
    <li class="news-item"><a href="<?php echo esc_url($url); ?>" target="_blank"><?php echo stripslashes($news); ?></a></li>

    <?php endfor; ?>
    <?php endif; ?>



</ul>



