<?php

$fileInfo = $_FILES['myFile'];
$filename = $fileInfo['name'];
$type = $fileInfo['type'];
$tmp_name = $fileInfo['tmp_name'];
$error = $fileInfo['error'];
$size = $fileInfo['size'];


if($error == UPLOAD_ERR_OK){
    if(move_uploaded_file($tmp_name, "uploads/".$filename)){
        echo '文件'.$filename.',上传成功!';
    }else{
        echo '文件'.$filename.',上传失败!';
    }
}else{
    switch($error){
        case 1:
            echo '上传文件超过了php.ini中upload_max_filesize的值!';
            break;
        case 2:
            echo '上传文件超过了form中MAX_FILE_SIZE的值!';
            break;
        case 3:
            echo '上传文件,部分被上传!';
            break;
        case 4:
            echo '没有上传文件!';
            break;
        case 6:
            echo '没有临时目录!';
            break;
        case 8:
            echo '系统错误!';
            break;
    }
}