<?php
/**
 * data split page
 *
 * @version 1.0
 * @date 12/08/07
 *
 */

class Page
{
    private $pageSize;	//single page count
    private $pageNow;	//now page count
    private $pageCount;	//total page count
    private $rowCount;	//total record count
    private $uri;       
    private $and;

    public function __construct($pageSize=10,$rowCount=0,$path='')
    {
        $this->pageSize=$pageSize;
        $this->rowCount=$rowCount;
        $this->uri=$this->getUri($path);
        $this->and=strlen(strstr($this->uri,'?'))>1?'&':'';
        $this->pageCount=ceil($this->rowCount/$this->pageSize);
        $this->pageNow=!empty($_GET['p'])?$_GET['p']:1;
    }
    private function getUri($path)
    {
        $url=$_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"], '?')?'':"?").$path;
        $parse=parse_url($url);
        if(isset($parse["query"]))
        {
            parse_str($parse['query'],$params);
            unset($params["p"]);
            $url=$parse['path'].'?'.http_build_query($params);
        }
        return $url;
    }
    private function firstPage()
    {
        $html='';
        if($this->pageNow!=1)
            $html.="<a href='".$this->uri.$this->and."p=1'>first</a>";
        return $html;
    }
    private function lastPage()
    {
        $html='';
        if($this->pageNow!=$this->pageCount&&$this->pageCount>0)
            $html.="<a href='".$this->uri.$this->and."p=".$this->pageCount."'>end</a>";
        return $html;
    }
    private function prePage()
    {
        $html='';
        if($this->pageNow!=1&&$this->pageCount>0)
            $html.="<a href='".$this->uri.$this->and."p=".($this->pageNow-1)."'><< prev</a>";
        return $html;
    }
    private function nextPage()
    {
        $html='';
        if($this->pageNow!=$this->pageCount&&$this->pageCount>0)
            $html.="<a href='".$this->uri.$this->and."p=".($this->pageNow+1)."'>next >></a>";
        return $html;
    }
    private function pageList()
    {
        $html='';
        for($i=1;$i<=$this->pageCount;$i++)
        {
            if($i==$this->pageNow)
                $html.='<span>'.$i.'</span>';
            else
                $html.="<a href='".$this->uri.$this->and."p=".$i."'>".$i."</a>";
        }
        return $html;
    }
    private function pageInfo()
    {
        if($this->pageCount>0)
            return $this->pageNow.'/'.$this->pageCount.'page';
    }
    public function limitPage()
    {
        $page=$this->pageSize*($this->pageNow-1);
        return 'limit '.$page.','.$this->pageSize;
    }

    public function getPageInfo($display=array(0,1,2,3,4,5))
    {
        $str[0]=$this->firstPage();
        $str[1]=$this->prePage();
        $str[2]=$this->pageList();
        $str[3]=$this->nextPage();
        $str[4]=$this->lastPage();
        $str[5]=$this->pageInfo();
        $html='';
        foreach($display as $index)
            $html.=$str[$index];
        return $html;
    }

}	
