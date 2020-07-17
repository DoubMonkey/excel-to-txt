<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/15
 * Time: 16:42
 */

/*
File name: /include/zip.php
Author: Horace 2009/04/15
*/
class HZip
{
    /**
     * 添加文件和子目录的文件到zip文件
     * @param string $folder
     * @param ZipArchive $zipFile
     * @param int $exclusiveLength Number of text to be exclusived from the file path.
     */
    private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
        $handle = opendir($folder);
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // 添加子文件夹
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     * Zip a folder (include itself).
     * Usage:
     *   HZip::zipDir('/path/to/sourceDir', '/path/to/out.zip');
     *
     * @param string $sourcePath Path of directory to be zip.
     * @param string $outZipPath Path of output zip file.
     */
    public static function zipDir($sourcePath, $outZipPath)
    {
        $pathInfo = pathInfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];
        $sourcePath=$parentPath.'/'.$dirName;//防止传递'folder' 文件夹产生bug
        $z = new ZipArchive();
        $z->open($outZipPath, ZIPARCHIVE::CREATE);//建立zip文件
        $z->addEmptyDir($dirName);//建立文件夹
        self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        $z->close();
    }
}