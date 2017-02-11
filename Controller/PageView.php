<?php

namespace AkniCallback\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * Class PageView
 * @package AkniCallback\Controller
 */
class PageView
{
    /**
     * Self specimen.
     * @var $_instance
     */
    private static $_instance;

    /**
     * Template path for rendering by twig
     * @var $_templateDir
     */
    protected $_templateDir;

    /**
     * @var Twig_Loader_Filesystem
     * Must be twig loader object
     */
    protected $_loader;

    /**
     * @var Twig_Environment
     * Must be twig environment object
     */
    protected $_twig;

    /**
     * @var $_renderer
     * use this for make something with rendered template
     */
    protected $_renderer;

    private function __construct( $templateDir )
    {
        if( !$this->_templateDir && $templateDir !='' ) {
            $this->_templateDir = $templateDir;
        }

        $this->_loader = new Twig_Loader_Filesystem(
            $this->_templateDir . '/views/templates/'
        );
        $this->_twig = new Twig_Environment($this->_loader);
    }

    /**
     * protect singleton  clone
     */
    private function __clone()
    {

    }

    /**
     * protect singleton __wakeup
     */
    private function __wakeup()
    {

    }

    /**
     * get self object
     * @return CallbackForm object
     */
    public static function getInstance( $pluginDir ) {
        if (empty(self::$_instance)) {
            self::$_instance = new self( $pluginDir );
        }
        return self::$_instance;
    }

    /**
     * This render twig data.
     * @param $template
     * @param array $options
     * @return string
     */
    public function render($template, array $options)
    {
        $this->_renderer = $this->_twig->render(
            $template, $options
        );
        return $this->_renderer;
    }

    /**
     * @param string $template
     * @param array $options
     * show rendered data on frontend
     */
    public function display($template='', array $options = [])
    {
        if ($template !='') {
            $this->render($template, $options);
        }
        echo  $this->_renderer;
    }
}