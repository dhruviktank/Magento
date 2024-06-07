<?php

class Ccc_Filemanager_Model_Filemanager extends Varien_Data_Collection_Filesystem
{
    public function __construct()
    {
        parent::__construct();
    }
    protected function _generateRow($filename)
    {
        $baseDir = $this->_targetDirs;
        $pathInfo = pathinfo($filename);
        $pathInfo['created_date'] = date("Y-m-d H:i:s", filectime($filename));

        $system_path = array_values($baseDir)[0];
        $pattern = '/' . preg_quote($system_path, "\\") . '/';
        $pathInfo['system_path'] = $system_path;

        $dirname = preg_replace($pattern, '', $pathInfo['dirname'], 1);
        $pathInfo['dirname'] = empty($dirname) ? "\\" : $dirname;
        $pathInfo['fullpath'] = $filename;
        return $pathInfo;
    }
}
