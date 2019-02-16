<?php

$fileInfo = $_FILES['myFile'];
$allowType = array('jpg','jpeg','png','gif','wbmp');
$error = $fileInfo['error'];
$maxSize = 1024;

if($fileInfo['error'] == 0){

    if(!getimagesize($fileInfo['tmp_name'])){
        exit('上传文件不是图片类型!');
    }

    $extension = pathinfo($fileInfo['name'],PATHINFO_EXTENSION);
    //$extension = strtolower(end(explode('.',$fileInfo['name'])));
    if(!in_array($extension,$allowType)){
        exit('上传文件非法类型!');
    }

    if($fileInfo['size'] > $maxSize){
        exit('上传文件超过了允许大小!');
    }

    if(!is_uploaded_file($fileInfo['tmp_name'])){
        exit('上传文件不是POST方式上传!');
    }

    $uploadPath = 'uploads';
    if(!file_exists($uploadPath)){
        mkdir($uploadPath,0777,true);
        chmod($uploadPath,0777);
    }

    $uniqueName = md5(uniqid(microtime(true),true)).'.'.$extension;
    $destination = $uploadPath.'/'.$uniqueName;
    if(@move_uploaded_file($fileInfo['tmp_name'],$destination)){
        echo '上传成功!';
    }else{
        echo '上传失败!';
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