<?php

/**
 * 上线脚本文件，执行本文件之后可以更新代码到指定的目录中
 *
 * contact me by email with wangbaike168@qq.com if you have any question, good luck with you !
 *
 * @filename           online.php
 * @package		Netbeans 8.0.2
 * @author		wbk
 * @link		http://www.baikeshuo.cn
 * @datetime          2018-10-27 21:23:43
 * @updatetime        2018-10-28 08:11
 * 
 * 使用方法：php online.php 项目标示 需要上线的tag名字
 */
outPut('begin');

//代码仓库目录名称，默认为当前目录内的子目录 例如：m-huitour
$sourcePath = isset($argv[1]) ? $argv[1] : '';

//判断仓库代码目录是否存在
if ($sourcePath === '' || !is_dir($sourcePath)) {
    outPut('sourcePath not found');
    exit;
}

//git的tag名称 例：v1.1.1
$tag = isset($argv[2]) ? $argv[2] : '';
//判断仓库代码目录是否存在
if ($tag === '') {
    outPut('tag is required');
    exit;
}

//仓库=>web目录地址对应表
$projectToWeb = array(
    //项目标示  => from 本地的git目录   to 要发布的路径即web路径
    'm-huitour' => array('from' => '/alidata/project/m-huitour', 'to' => '/alidata/www/m-huitour')
);
//判断仓库代码目录是在本文件内备案
if (!isset($projectToWeb[$sourcePath])) {
    outPut($sourcePath . ' not in allow list');
    exit;
}

//代码源目录
$sourceDir = $projectToWeb[$sourcePath]['from'];

//目标目录
$targetDir = $projectToWeb[$sourcePath]['to'];

//更新代码，切换到指定的tag
outPut('update code ...');
$execResult1 = shell_exec('cd ' . $sourceDir . ' ;sudo git pull;git checkout ' . $tag);
outPut($execResult1);
outPut('ok');

//备份目标代码
$backDir = $targetDir . '_backup' . date('Ymd', time());
if (is_dir($targetDir)) {
    outPut('backup webDir to ' . $backDir);
    $execResult2 = shell_exec('sudo mv ' . $targetDir . ' ' . $backDir);
    if ($execResult2) {
        outPut($execResult2);
        exit;
    }
    outPut('ok');
}

//复制当前代码到web目录中，保留每个目录的属性
outPut('copy code to webDir');
$execResult3 = shell_exec('sudo cp -prb ' . $sourceDir . ' ' . $targetDir);
outPut($execResult3);
if ($execResult3) {
    outPut($execResult3);
    exit;
}
outPut('ok');

//拷贝过去的.git目录
outPut('del .git');
$execResult4 = shell_exec('sudo rm -rf ' . $targetDir . '/.git');
outPut($execResult4);
if ($execResult4) {
    outPut($execResult4);
    exit;
}
outPut('ok');

//删除备份目录
if ($backDir != '' && is_dir($backDir)) {
    outPut('del backup');
    $execResult5 = shell_exec('sudo rm -rf ' . $backDir);
    if ($execResult5) {
        outPut($execResult5);
        exit;
    }
    outPut('ok');
}

outPut('success done');

//输入内容
function outPut($msg)
{
    echo $msg . PHP_EOL;
}
