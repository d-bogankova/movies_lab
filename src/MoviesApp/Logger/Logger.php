<?php

namespace MoviesApp\Logger;


/**
 * Class Logger
 * @package MoviesApp
 */
class Logger
{
    const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var
     */
    private $filePath;

    /**
     * @param $filePath
     */
    function __construct($filePath)
    {
        $this->setfilePath($filePath);
    }

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @param $message
     * @param bool|false $display
     */
    public function writeLog($message, $display=false)
    {
        $message = '[ ' . date(self::DEFAULT_DATE_FORMAT) . ' ] ' . $message . PHP_EOL;

        $dir = dirname($this->filePath);

        if (!is_dir($dir)){

            mkdir($dir, 0775, true);
        }

        file_put_contents($this->filePath, $message, FILE_APPEND);

        if(isset($argv[0]) && $display==true || isset($_SERVER['argv'])){

            echo $message;
        }
    }
}
