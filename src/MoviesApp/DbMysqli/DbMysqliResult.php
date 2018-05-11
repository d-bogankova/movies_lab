<?php

namespace MoviesApp\DbMysqli;

/**
 * Class DbMysqliResult
 * @package MoviesApp
 */
class DbMysqliResult extends \mysqli_result {

    /**
     * @return array
     */
    public function fetchList() {

        $result = array();

        $this->data_seek(0);

        while($row = $this->fetch_array(MYSQLI_NUM)) {

            $result[] = $row[0];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function fetchDropdown() {

        $result = array();

        while ($row = $this->fetch_array(MYSQLI_NUM)) {

            $result[$row[0]] = $row[1];
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function fetchValue() {

        $row = $this->fetch_array(MYSQLI_NUM);

        return $row[0];
    }

    /**
     * @return array
     */
    public function fetchAll() {

        $result = array();

        $this->data_seek(0);

        while ($row = $this->fetch_assoc()) {

            $result[] = $row;
        }

        return $result;
    }
}
