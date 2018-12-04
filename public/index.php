<?php
require_once('../lib/init.php');
$name = $name = App::get_requeset_page_name();

if(Item::count(['conditions' => ['name = ?', $name]]) == 0) {
    //Not Found
}else{
    $item = Item::find_by_name($name);

    $lm = LinkMaker::load($item->text);
    $item->text = $lm->make();
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include('../templates/html_header.php'); ?>
</head>
<body>

<?php include('../templates/sidebar.php'); ?>

<div id="main">

    <div class="uk-navbar-container" uk-navbar>
        <div class="uk-navbar-left">
            <div class="uk-navbar-item">
                <?php if(isset($item) && LockManager::is_editable($item->id, session_id())) { ?>
                <span>他の人が編集しています</span>
                <?php }else{ ?>
                <form action="./edit.php" method="get">
                    <button class="uk-button uk-button-default" id="edit" type="submit">編集</button>
                    <input type="hidden" name="n" value="<?php echo $name; ?>">
                </form>
                <?php } ?>
            </div>
        </div>
        <div class="uk-navbar-right">
            <div class="uk-navbar-item">
                <?php include('../templates/search_box.php'); ?>
            </div>
        </div>
    </div>
    
    <article class="uk-article">
    
        <h1 class="uk-heading-divider"><?php echo $name; ?> </h1>
    
        <?php if(isset($item)) { ?>
            <p id="content" class="uk-article-meta">
                <?php echo $item->text; ?>
            </p>
        <?php }else{ ?>
            <h2>現在この名前の項目はありません</h2>
            <p>[<?php echo $name; ?>]という項目を<a href="./edit.php?n=<?php echo $name; ?>">新規作成する</a>。
        <?php } ?>

    </article>

</div>

<div style="clear:both"></div>

<script>
</script>
</body>
</html>
