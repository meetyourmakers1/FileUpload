<?php

function uploadFile($fileInfo,$uploadPath = 'uploads',$allowType = ['jpg','jpeg','png','gif'],$maxSize = 2097152,$flag = true){
    if($fileInfo['error'] > 0){
        switch($fileInfo['error']){
            case 1:
                $message = '上传文件超过了php.ini中upload_max_filesize的值!';
                break;
            case 2:
                $message = '上传文件超过了form中MAX_FILE_SIZE的值!';
                break;
            case 3:
                $message =  '上传文件,部分被上传!';
                break;
            case 4:
                $message = '没有选择上传文件!';
                break;
            case 6:
                $message = '没有找到临时目录!';
                break;
            case 8:
                $message = '系统错误!';
                break;
        }
        exit($message);
    }

    if($flag){
        if(!getimagesize($fileInfo['tmp_name'])){
            exit('上传文件不是图片类型!');
        }
    }

    $extension = pathinfo($fileInfo['name'],PATHINFO_EXTENSION );
    if(!in_array($extension,$allowType)){
        exit('上传文件是非法类型!');
    }

    if($fileInfo['size'] > $maxSize){
        exit('上传文件超过了允许大小!');
    }

    if(!is_uploaded_file($fileInfo['tmp_name'])){
        exit('上传文件不是POST方式上传!');
    }

    if(!file_exists($uploadPath)){
        mkdir($uploadPath,0777,true);
        chmod($uploadPath,0777);
    }

    $uniqueName = md5(uniqid(microtime(true),true)).'.'.$extension;
    $destination = $uploadPath.'/'.$uniqueName;
    if(!@move_uploaded_file($fileInfo['tmp_name'],$destination)){
        exit('上传失败');
    }
    return $destination;
}

$fileInfo = $_FILES['myFile'];
$allowType = array('jpeg','jpg','png','gif','html','txt');
$maxSize = 2097152;

$uploadFileName = uploadFile($fileInfo,'uploads',$allowType,$maxSize,false);

echo $uploadFileName;





