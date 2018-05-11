<?php

namespace MoviesApp\Curl;

/**
 * Class Curl
 * @package MoviesApp
 */
class Curl {

    /**
     * Default user agent
     */
    const DEFAULT_USERAGENT = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36';

    /**
     * Resource handler.
     * @var resource
     */
    private $resource;

    /**
     * Instance options storage.
     * @var array
     */
    private $options;

    /**
     * Error log filepath.
     * @var
     */
    private $log;

    /**
     * Stores debugging information
     * @var
     */
    private $info;

    /**
     * Class constructor.
     * @param array $options
     */
    public function __construct($options = array()) {
        $this->resource = curl_init();
        $this->options = array();

        if (is_array($options)) {
            $this->options = $options;
        }

        if(is_resource($this->resource)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sets cURL options.
     * @param $options
     * @return array
     */
    public function setOptions($options) {

        if (is_array($options)) {
            $this->options = $options;
        }

        return $this->options;
    }

    /**
     * Gets cURL info.
     * @return mixed
     */
    public function getInfo() {
        return curl_getinfo($this->resource);
    }

    /**
     * Sets error log filepath.
     * @param $filepath
     */
    public function setErrorLog($filepath) {
        $this->log = $filepath;
    }

    /**
     * Executes HTTP request
     * @param $options
     * @param null $cookiefile
     * @return bool|mixed
     */
    public function execute($options, $cookiefile = null) {

        if (!is_resource($this->resource)) {
            return false;
        }

        if (empty($this->options) && is_string($options)) {

            $options = array(
                CURLOPT_USERAGENT => self::DEFAULT_USERAGENT,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_URL => $options,
            );
        }

        if (!empty($cookiefile)) {

            $params[CURLOPT_COOKIEFILE] = $cookiefile;
            $params[CURLOPT_COOKIEJAR] = $cookiefile;
        }

        $options = array_replace($this->options, $options);
        curl_setopt_array($this->resource, $options);
        $response = curl_exec($this->resource);
        $this->info = curl_getinfo($this->resource);
        $this->info['errno'] = curl_errno($this->resource);
        $this->info['error'] = curl_error($this->resource);

        if (!is_null($this->log) && ('' != $this->info['errno'] || '' != $this->info['error'])) {

            file_put_contents($this->log, sprintf("CURL: %s %s %s", $this->info['http_code'], $this->info['error'], $this->info['url']));
        }

        return ($response);
    }

    /**
     * Provides file download
     * @param $options
     * @param $filepath
     * @param null $cookiefile
     * @throws \Exception
     */
    public function download($options, $filepath, $cookiefile = null) {

        if (is_string($options)) {

            $options = array(
                CURLOPT_USERAGENT => self::DEFAULT_USERAGENT,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_URL => $options,
                CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            );
        }

        if (!empty($filepath)) {

            $options[CURLOPT_FILE] = fopen($filepath, "w+");
        }

        if (!is_resource($options[CURLOPT_FILE])) {

            throw new \Exception("File path is not valid: $filepath.");
        }

        $this->execute($options, $cookiefile);
    }

    /**
     * Returns last executed request info
     * @return mixed
     */
    public function getLastRequestInfo() {

        return $this->info;
    }
}