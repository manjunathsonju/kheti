<div class="fb-like-box" data-href="https://www.facebook.com/<?php echo $_GET['account_fb']; ?>" data-width="240" data-height="240" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="true"></div>

 <!-- Facebook Api -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- //end Facebook Api --> 

