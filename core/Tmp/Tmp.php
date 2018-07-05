<?php

namespace core\Tmp;

use core\Files\AppFiles;
use core\Files\Images\Images;
use core\Functions;

class Tmp extends AppFiles
{

    private $tmpDir;

    public function __construct()
    {
        parent::__construct();
        $this->tmpDir = $this->tmpPath . $this->userDir;
    }

    public function CreateTmpFolder()
    {
        if (!file_exists($this->tmpDir))
        {
            mkdir($this->tmpDir, 0777, true);
        }
        return true;
    }

    public function uploadTmp($fileObject)
    {
        //die($this->tmpPath . basename($this->formatImageName()));
        return move_uploaded_file($fileObject->getTmpName(), $this->tmpDir . basename($fileObject->getName()));
    }

    public function deleteTmpFile($filename)
    {
        $filePath = $this->tmpDir . $filename;
        unlink($filePath);
    }

    public function deleteTmpFolder()
    {
        if(file_exists($this->tmpDir))
        {
            Functions::rrmdir($this->tmpDir);
        }
    }
}