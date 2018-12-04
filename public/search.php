<?php
require_once('../lib/init.php');

$query = $_GET['q'];
$query = $_GET['q'];
if(empty($query)) {
    App::redirect('/');
}


$items = Item::find_by_name($query);
if(!empty($items)) {
    App::redirect('/'.$query);
    exit();
}
$items = Item::all(['conditions' => ['name like ?', '%'.$query.'%']]);

?>
<html>
<head>
<?php include('../templates/html_header.php'); ?>
</head>
<h1>検索結果</h1>

<?php include('../templates/search_box.php'); ?>

<p>ページ「<?php echo $query; ?>」を<a href="./edit.php?n=<?php echo $query; ?>">新規作成</a>しましょう。
<?php if(count($items) == 0) { ?>
   <p>お探しのページは見つかりませんでした</p> 
<?php }else{ ?>

    <p>「<?php echo $query; ?>」を含む検索結果</p>
    
    <ul class="uk-list uk-list-striped">
        <?php foreach($items as $i) { ?>
            <li>
                <h2><a href="./<?php echo $i->name; ?>"><?php echo $i->name; ?></a></h2>
                <p><?php echo substr($i->text,0,400); ?>
            </li>
        <?php } ?>
    </ul>

<?php } ?>
</body>
</html>
