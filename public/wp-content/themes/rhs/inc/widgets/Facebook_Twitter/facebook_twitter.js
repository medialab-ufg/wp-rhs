//Facebook and Twitter Widget
jQuery(document).ready(function($) {
    $(window).bind("load resize", function(){  
        setTimeout(function() {
            var container_width = $('#sidebar').width();    
            $('.facebook_twitter .facebook').html('<div class="fb-page" ' +  'data-href="http://www.facebook.com/RedeHumanizasus/"' + 
            'data-width="' + container_width + '" data-small-header="true" data-adapt-container-width="false" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/RedeHumanizasus/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/RedeHumanizasus/">Rede HumanizaSUS</a></blockquote></div>');
            FB.XFBML.parse(); 
            $('.facebook_twitter .twitter').html('<a href="https://twitter.com/redehumanizasus" class="twitter-follow-button" data-show-count="false">Follow @redehumanizasus</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>');
        }, 100); 
    }); 
}); //Fim Facebook and Twitter Widget