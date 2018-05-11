<?php

namespace MoviesApp\Common;


/**
 * Class View
 * @package MoviesApp
 */
class View
{
    /**
     * Default template
     */
    const DEFAULT_FILE_EXTENSION = '.phtml';
    /**
     * @var
     */
    private $templatesDirectory;

    /**
     * @var string
     */
    private $fileExtension;
    /**
     * @var
     */
    private $action;

    /**
     * @param $templatesDirectory
     */
    public function __construct($templatesDirectory, $fileExtension = self::DEFAULT_FILE_EXTENSION)
    {
        $this->templatesDirectory = $templatesDirectory;
        $this->fileExtension = $fileExtension;
    }

    /**
     * @return mixed
     */
    public function getTemplatesDirectory()
    {
        return $this->templatesDirectory;
    }

    /**
     * @param mixed $templatesDirectory
     */
    public function setTemplatesDirectory($templatesDirectory)
    {
        $this->templatesDirectory = $templatesDirectory;
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param $templateName
     * @param array $vars
     */
    public function render($templateName, $vars = [])
    {
        extract($vars, EXTR_SKIP);
        unset($vars);
        include $this->getTemplatesDirectory() . '/' . $templateName . $this->getFileExtension();
    }
}
