<?php
session_start();
require_once('../lib/init.php');
$name = App::get_requeset_page_name();
$no_save = isset($_POST['no_save']);

if(Item::count(['conditions' => ['name = ?', $name]]) == 0) {
    $item = new Item();
    $item->name = $name;
}else{
    $item = Item::find_by_name($name);
}

if(isset($_POST['text']) && !$no_save) {
    $item->text = $_POST['text'];
    $item->save();
}

//ロックを削除
if(isset($item)) {
    LockManager::unlock($item->id);
}

//リダイレクト
$redirect_to = $no_save ? '/'.$name : '/edit.php?n='.$name;
App::redirect($redirect_to);
