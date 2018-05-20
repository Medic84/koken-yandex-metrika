<?php

class KokenYandexMetrika extends KokenPlugin {

	function __construct()
	{
		$this->require_setup = true;
		$this->register_hook('before_closing_head', 'render_head');
		$this->register_hook('after_opening_body', 'render_body');
	}
	
	function render_body()
	{
	    $counterId = intval($this->data->counter_id);
	    $noIndex = $this->data->no_index ? '?ut=noindex' : '';
	    
	    echo sprintf('<noscript><div><img src="https://mc.yandex.ru/watch/%d%s" style="position:absolute; left:-9999px;" alt="" /></div></noscript>', $counterId, $noIndex);
	}

	function render_head()
	{
		$counterId = intval($this->data->counter_id);
		$noIndex = $this->data->no_index ? ', ut:"noindex"' : '';
		
		$cdn = $this->data->alternate_cdn
			? 'https://cdn.jsdelivr.net/npm/yandex-metrica-watch/watch.js'
			: 'https://mc.yandex.ru/metrika/watch.js';
		
		$mainCode = sprintf('try { w.yaCounter%d = new Ya.Metrika({ id:%1$d, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true%s }); } catch(e) { }', $counterId, $noIndex);
        
        if ($this->data->async_code) {
            echo sprintf('<script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { %s }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "%s"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script>', $mainCode, $cdn);
        } else {
            echo sprintf('<script src="%s" type="text/javascript"></script> <script type="text/javascript"> %s </script>', $cdn, $mainCode);
        }
	}
}