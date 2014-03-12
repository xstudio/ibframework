<?php
/**
 * file upload 
 *
 * @filesource
 * @version 1.0
 * @date 12/08/07
 */

/**
 * 单文件上传类，使用于表单文件的上传
 *
 * <code>
 * <?php
 * $up=new FileUpload('./uploads'); //初始化上传
 *
 * if($up->upload('pic'))
 *     //文件名格式year.mounth.day.hour.minute.second.random(100, 999)
 *     echo($up->getNewFileName()); //上传成功，输出文件名, 长度21位
 * else
 *     echo($up->getError()); //否则输出错误信息
 * </code>
 */
class FileUpload
{
    private $path;      //upload path
    private $type;      //file type
    private $maxSize;   
    private $fileSize;  
    private $fileType;  
    private $fileName;  
    private $tempName;  
    private $newFileName;   
    private $errorNum;      //error number
    private $errorMsg='';   //error info  
    
    /**
     * @param string $path 上传路径（相对路径）
     * @param array $type 允许上传的文件类型
     * @param int $maxSize 文件最大大小（字节）
     */
    function __construct($path='./upload/',$type=array('jpg','png','gif'),$maxSize=2000000)
    {
        $this->path=$path;
        $this->type=$type;
        $this->maxSize=$maxSize;
    }

    /**
     * Upload file 
     * @param string $name input's type==file的name
     * @return boolean file is uploaded
     */
    function upload($name)
    {
        $result=false;
        $errorNum=$_FILES[$name]['error'];
        $types=explode('.',$_FILES[$name]['name']);
        $this->fileType=strtolower($types[count($types)-1]);
        $this->fileName=$_FILES[$name]['name'];
        $this->tempName=$_FILES[$name]['tmp_name'];
        $this->fileSize=$_FILES[$name]['size'];
        if($this->checkPath())
        {
            if($this->checkFileSize()&&$this->checkFileType())
            {
                $this->setNewFileName();
                if($this->getNewFile())
                    $result=true;
            }
        }
        if($result==false)
            $this->errorMsg=$this->getError();
        return $result;
    }
    /**
     * get new file name
     */
    public function getNewFileName(){
        return $this->newFileName;
    }

    /**
     * get error info
     */
    public function getError()
    {
        $errorStr="<span style=\"color:red\">File {$this->fileName} upload error<br/></span>";
        switch($this->errorNum)
        {
            case 1:
                $errorStr.='filesize>upload_max_filesize in php.ini seted';
                break;
            case 2:
                $errorStr.='filesize>HTML form MAX_FILE_SIZE';
                break;
            case 3:
                $errorStr.='file uploaded only a little';
                break;
            case 4:
                $errorStr.='none file uploaded';
                break;
            case -1:
                $errorStr.='file type invalid';
                break;
            case -2:
                $errorStr.="file size is too large";
                break;
            case -3:
                $errorStr.='upload directory create fail';
                break;
            case -4:
                 $errorStr.='file copy fail';
                break;
            default:
                $errorStr.='unknown error';
        }
        return $errorStr;
    }

    private function checkPath()
    {
        if(!file_exists($this->path))
        {
            if(!@mkdir($this->path,0755))
            {
                $this->errorNum=-3;
                return false;
            }
        }
        return true;
    }
    
    private function checkFileType()
    {
        if(!in_array($this->fileType,$this->type))
        {
           $this->errorNum=-1;
            return false;
        }
        return true;
    }

    private function checkFileSize()
    {
        if($this->fileSize>$this->maxSize)
        {
            $this->errorNum=-2;
            return false;
        }
        return true;
    }
    /**
     * set new file name
     * file name format:year.mounth.day.hour.minute.second.random
     */
    private function setNewFileName()
    {
        $fname=date('YmdHis').rand(100,999);
        $this->newFileName=$fname.'.'.$this->fileType;
    }
    /**
     * copy file
     */
    private function getNewFile()
    {
        $this->path=rtrim($this->path,'/').'/'.$this->newFileName;
        if(!move_uploaded_file($this->tempName,$this->path))
        {
            $this->errorNum=-4;
            return false;
        }
        return true;
    }
}
