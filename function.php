<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/15
 * Time: 16:27
 */

function z_addDir2Zip($dir, $zip){
    $handler = opendir($dir); //打开当前文件夹由$dir指定
    while(( $filename = readdir($handler) ) !== false ){
        if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..'，不要对他们进行操作
            if(is_dir( $dir . '/' . $filename )){// 如果读取的某个对象是文件夹，则递归
                z_addDir2Zip( $dir . "/" . $filename, $zip);
            }else{ //将文件加入zip对象
                $zip->addFile($dir."/".$filename);
            }
        }
    }
    @closedir($dir);
}
function z_zipdir( $dir, $zipfile ){
    $zip = new ZipArchive();
    if($zip->open($zipfile, ZipArchive::OVERWRITE)=== TRUE){
        z_addDir2Zip( $dir, $zip ); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
        $zip->close(); //关闭处理的zip文件
    }
}