<?php

/**
 * web application create tool
 * 
 * @usage php ibc.php <app-path>
 * @version 1.0
 * @date 14/3/10
 * @author yueqian.sinaapp.com
 */

/**
 * get path a and path b relative path, a->b
 */
function getRelativePath ($a, $b)
{
    echo $a."\n".$b."\n";
    $patha = explode('/', $a);
    $pathb = explode('/', $b);
     
    $counta = count($patha) - 1;
    $countb = count($pathb) - 1;
     
    $path = "../";
    if ($countb > $counta) 
    {
        while ($countb > $counta) 
        {
            $path .= "../";
            $countb --;
        }
    }
     
    // 寻找第一个公共结点
    for ($i = $countb - 1; $i >= 0;) 
    {
        if ($patha[$i] != $pathb[$i]) 
        {
            $path .= "../";
            $i --;
        } 
        else 
        { // 判断是否为真正的第一个公共结点，防止出现子目录重名情况
            for ($j = $i - 1, $flag = 1; $j >= 0; $j --) 
            {
                if ($patha[$j] == $pathb[$j]) 
                    continue;
                else 
                {
                    $flag = 0;
                    break;
                }
            }
             
            if ($flag)
                break;
            else
                $i++;
        }
    }

    for ($i += 1; $i <= $counta; $i ++) 
        $path .= $patha[$i] . "/";

    return $path;
}

/**
 * create directory
 */
function create_singledir($path, $mode=0755)
{
    if(!file_exists($path))
    {
        if(@mkdir($path, $mode)!==false)    
            echo("  mkdir $path\n");
        else
        {
            echo("Error: can't create directory $path, please check it's parent owner.\n");
            exit();
        }
    }
}
/**
 *
 */
function create_dir($path, $mode=0755)
{
    if(is_array($path))
    {
        foreach($path as $p)
            create_singledir($p, $mode);
    }
    else
        create_singledir($path, $mode);
}

if(isset($argv[1]))
{
    $path=rtrim(getcwd().'/'.$argv[1], '/');
    
    printf("Create a Web application under '%s'? (yes|no) [no]:", $path);
    $line = trim(fgets(STDIN)); // 从 STDIN 读取一行
    if($line!='yes' && $line!='Yes')
        return;
    //index
    create_dir($path);
    
    //replace ib path
    $line=file(dirname(__FILE__).'/cli/index.php'); //read index.php one line one key
    $line[4]='$ib='."dirname(__FILE__).'/".getRelativePath(dirname(__FILE__), realpath($path))."ib.php';\n";
    if(file_put_contents($path.'/index.php', implode('', $line)))
        echo("generate index.php\n");
    else
        die("Error: can't create file index.php, please check it's parent owner.\n");

    //config
    if(copy(dirname(__FILE__).'/cli/config.php', $path.'/config.php'))
        echo("generate config.php\n");
    else
        die("Error: can't create file config.php, please check it's parent owner.\n");

    //public
    create_dir(array($path.'/public', $path.'/public/css', $path.'/public/js', $path.'/public/images'));

    //protected
    create_dir(array($path.'/protected', $path.'/protected/views', $path.'/protected/models', $path.'/protected/controllers'));

    if(copy(dirname(__FILE__).'/cli/SiteController.php', $path.'/protected/controllers/SiteController.php'))
        echo("generage protected/controllers/SiteController.php\n");
    else
        die("Error: can't create file protected/controllers/SiteController.php, please check it's parent owner.\n");
    create_dir($path.'/protected/runtime', 0777);


}
else
{
    echo("Error: the Web application location is not specified.".PHP_EOL.PHP_EOL."USAGE".PHP_EOL."php ibc.php <app-path>".PHP_EOL.PHP_EOL."DESCRIPTION".PHP_EOL." This command generates an Web Application at the specified location.".PHP_EOL.PHP_EOL."PARAMETERS".PHP_EOL." * app-path: required, the directory where the new application will be created.".PHP_EOL);

}
