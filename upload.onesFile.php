<?php

function getFiles(){
    $i=0;
    $files = [];
    foreach($_FILES as $file){
        if(is_string($file['name'])){
            $files[$i]=$file;
            $i++;
        }elseif(is_array($file['name'])){
            foreach($file['name'] as $key=>$val){
                $files[$i]['name']=$file['name'][$key];
                $files[$i]['type']=$file['type'][$key];
                $files[$i]['tmp_name']=$file['tmp_name'][$key];
                $files[$i]['error']=$file['error'][$key];
                $files[$i]['size']=$file['size'][$key];
                $i++;
            }
        }
    }
    return $files;

}
function uploadFile($fileInfo,$uploadPath = 'uploads',$allowType = ['jpg','jpeg','png','gif'],$maxSize = 2097152,$flag = true){
    if($fileInfo['error'] === UPLOAD_ERR_OK){
        $message = [];
        if($flag){
            if(@!getimagesize($fileInfo['tmp_name'])){
                $message['error'] = $fileInfo['name'].'不是图片类型!';
            }
        }

        $extension = strtolower(pathinfo($fileInfo['name'],PATHINFO_EXTENSION));
        if(!in_array($extension,$allowType)){
            $message['error'] = $fileInfo['name'].'是非法文件类型!';
        }

        if($fileInfo['size'] > $maxSize){
            $message['error'] = $fileInfo['name'].'超过了允许大小!';
        }

        if(!is_uploaded_file($fileInfo['tmp_name'])){
            $message['error'] = $fileInfo['name'].'不是POST方式上传!';
        }

        if($message){
            return $message;
        };

        if(!file_exists($uploadPath)){
            mkdir($uploadPath,0777,true);
            chmod($uploadPath,0777);
        }

        $uniqueName = md5(uniqid(microtime(true),true)).'.'.$extension;
        $destination = $uploadPath.'/'.$uniqueName;
        if(!move_uploaded_file($fileInfo['tmp_name'],$destination)){
            $message['error'] = $fileInfo['name'].'上传失败';
        }else{
            $message['desctination'] = $destination;
        }
        return $message;

    }else{
        switch($fileInfo['error']){
            case 1:
                $message['error'] = '上传文件超过了php.ini中upload_max_filesize的值!';
                break;
            case 2:
                $message['error'] = '上传文件超过了form中MAX_FILE_SIZE的值!';
                break;
            case 3:
                $message['error'] =  '上传文件,部分被上传!';
                break;
            case 4:
                $message['error'] = '没有上传文件!';
                break;
            case 6:
                $message['error'] = '没有临时目录!';
                break;
            case 8:
                $message['error'] = '系统错误!';
                break;
        }
        return $message;
    }
}

$files=getFiles();
$message = [];
foreach($files as $file){
    $message[] = uploadFile($file);
}
print_r($message);