<?php

$wpch_news_ticker = get_option('wpch_news_ticker', array());
$nti_dom = array_key_exists('nti_dom', $wpch_news_ticker) ? $wpch_news_ticker['nti_dom']: false;

$script = 'var wpch_news_ticker = `'.wpch_news_ticker_callback().'`;
			   jQuery(document).ready(function($) {
				
                    setTimeout(function(){	
                            
                            $("'.$nti_dom.'").prepend(wpch_news_ticker);
                                                        
                    }, 100);
	    	});';

echo $script;