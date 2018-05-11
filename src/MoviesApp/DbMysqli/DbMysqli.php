<?php

namespace MoviesApp\DbMysqli;


/**
 * Class DbMysqli
 * @package MoviesApp
 */
class DbMysqli extends \mysqli {

    /**
     * @var
     */
    private $log;

    /**
     * @param string $query
     * @return DbMysqliResult
     */
    public function customQuery($query) {

        $this->real_query($query);
        $result = new DbMysqliResult($this);

        if('' != $this->error && !empty($this->log)) {

            $err = debug_backtrace();
            $err = end($err);
            file_put_contents($this->log, "[".date("Y-m-d H:i:s")."] $err[file] LINE $err[line] :: $this->error\r\n $query\r\n\r\n", FILE_APPEND | LOCK_EX);
        }

        return $result;
    }

    /**
     * @param $filePath
     */
    public function setErrorLog($filePath) {

        $this->log = $filePath;
    }
}
