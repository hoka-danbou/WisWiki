<?php
require_once('../lib/init.php');
$items = Item::find('all');
?>
<html>
<head>
<?php include('../templates/html_header.php'); ?>
<style>
li {
    border: 1px solid #dddddd;
    width: 14%;
    float: left;
    list-style: none;
    margin-left: 5px;
}
li h1 {
    text-align: center;
    background-color: #ddffff;
    margin: 0;
    font-size: 16pt;
}
li div.description {
    vertical-align: top;
    height: 200px; !important;
    overflow-y: auto;
}
</style>
</head>
<body>
<ul>
    <?php foreach($items as $i) {
    $lm = LinkMaker::load($i->text);
    $i->text = $lm->make();
    ?>
    <li>
        <h1><a href="./<?php echo $i->name; ?>"><?php echo $i->name; ?></a></h1>
        <div class="description"><?php echo substr($i->text, 0, 300); ?></div>
    </li>
    <?php } ?>
</ul>
</body>
</html>
