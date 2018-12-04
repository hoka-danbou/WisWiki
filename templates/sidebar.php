<div id="sidebar">
<h1 style="text-align:center;"><a href="./"><img src="images/logo.png"></a></h1>
<ul>
<li><a href="./">メインページ</a></li>
</ul>

<?php echo App::get_sidebar_content(); ?>

<div style="padding:5px;">
<h1 class="uk-heading-line"><span>新規作成</span></h1>
<form action="./edit.php" method="get">
  <input type="text" name="n" value="" class="uk-input" placeholder="ページ名">
  <input type="submit" class="uk-button uk-button-default uk-button-small" value="作成">
</form>
</div>

</div>
