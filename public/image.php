<?php

$file_id = $_GET['f'];
$tmp = explode('.', $file_id);
$ext = array_pop($tmp);

header('Content-Type: image/'.$ext);
readfile('../data/images/'.$file_id);
