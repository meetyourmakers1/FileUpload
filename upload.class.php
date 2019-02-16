<?php
class upload{
    protected $fileName;
    protected $uploadPath;
    protected $allowMime;
    protected $allowType;
    protected $maxSize;

    protected $imgFlag;
    protected $fileInfo;
    protected $extension;
    protected $uniqueName;
    protected $destination;
    protected $error;

    public function __construct($fileName='myFile',$uploadPath='./uploads',$allowMime=['image/jpeg','image/png','image/gif'],$allowType=['jpg','jpeg','png','gif'],$maxSize=5242880,$imgFlag=true){
        $this->fileName = $fileName;
        $this->uploadPath = $uploadPath;
        $this->allowMime = $allowMime;
        $this->allowType = $allowType;
        $this->maxSize = $maxSize;

        $this->imgFlag = $imgFlag;
        $this->fileInfo = $_FILES[$this->fileName];
    }

    protected function checkError(){
        if(!is_null($this->fileInfo)){
            if($this->fileInfo['error'] > 0){
                switch ($this->fileInfo['error']) {
                    case 1:
                        $this->error = '上传文件超过了php.ini中upload_max_filesize的值!';
                        break;
                    case 2:
                        $this->error = '上传文件超过了form中MAX_FILE_SIZE的值!';
                        break;
                    case 3:
                        $this->error = '上传文件部分被上传!';
                        break;
                    case 4:
                        $this->error = '没有选择上传文件!';
                        break;
                    case 6:
                        $this->error = '没有找到临时目录!';
                        break;
                    case 7:
                        $this->error = '文件不可写!';
                        break;
                    case 8:
                        $this->error = '系统错误!';
                        break;
                }
                return false;
            } else {
                return true;
            }
        }else{
            $this->error = '上传文件信息错误';
            return false;
        }

    }

    protected function checkMime(){
        if(!in_array($this->fileInfo['type'],$this->allowMime)){
            $this->error = '上传文件不允许的文件类型!';
            return false;
        }
        return true;
    }

    protected function checkType(){
        $this->extension = strtolower(pathinfo($this->fileInfo['name'],PATHINFO_EXTENSION));
        if(!in_array($this->extension,$this->allowType)){
            $this->error = '上传文件不允许的扩展名!';
            return false;
        }
        return true;
    }

    protected function checkSize(){
        if($this->fileInfo['size']>$this->maxSize){
            $this->error = '上传文件超过了允许大小!';
            return false;
        }
        return true;
    }

    protected function checkHTTPPost(){
        if(!is_uploaded_file($this->fileInfo['tmp_name'])){
            $this->error = '上传文件不是HTTP POST方式上传!';
            return false;
        }
        return true;
    }

    protected function checkTrueImg(){
        if($this->imgFlag){
            if(!@getimagesize($this->fileInfo['tmp_name'])){
                $this->error = '上传文件不是真实的图片类型!';
                return false;
            }
            return true;
        }
    }

    protected function checkUploadPath(){
        if(!file_exists($this->uploadPath)){
            mkdir($this->uploadPath,0777,true);
        }
    }

    protected function getUniqueName(){
        return md5(uniqid(microtime(true),true));
    }

    protected function showError(){
        exit('<span style="color:red">'.$this->error.'</span>');
    }

    public function uploadFile()
    {
        if ($this->checkError() && $this->checkMime() && $this->checkType() && $this->checkSize() && $this->checkHTTPPost() && $this->checkTrueImg()) {
            $this->checkUploadPath();
            $this->uniqueName = $this->getUniqueName();
            $this->destination = $this->uploadPath.'/'.$this->uniqueName.'.'.$this->extension;
            if (@move_uploaded_file($this->fileInfo['tmp_name'], $this->destination)) {
                return $this->destination;
            } else {
                $this->error = '上传失败';
                $this->showError();
            }
        } else {
            $this->showError();
        }
    }
}

$upload = new upload();
$destination = $upload->uploadFile();
echo $destination;

