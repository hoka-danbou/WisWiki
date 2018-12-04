<?php
define('IMAGE_UPLOAD_DIR', __DIR__ . '/../data/images');

$allow_content_types = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
];

if(is_uploaded_file($_FILES['file']['tmp_name'])) {
    $file_id = date('Ymdhn') . rand();

    $tmp_name = $_FILES['file']['tmp_name'];

    if(!$ext = array_search( mime_content_type($tmp_name),
        $allow_content_types, true)) {
        echo "file type is not allowed";
        exit;
    }

    $save_file_name = IMAGE_UPLOAD_DIR.'/'.$file_id.'.'.$ext;
    if(! move_uploaded_file($tmp_name, $save_file_name)) {
        echo "file upload error";
        exit;
    }


    $response = array(
        'default' => 'image.php?f='.$file_id.'.'.$ext
    );

    echo json_encode($response);
}
