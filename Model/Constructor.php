<?php

namespace AkniCallback\Model;

use AkniCallback\Controller\CallbackController;
use AkniCallback\Controller\CallbackForm;

/**
 * This is single point of entry plugin.
 * Class Constructor
 * @package AkniCallback\Model
 */
class Constructor
{
    /**
     * Self Constructor object.
     * @var $_instance
     */
    private static $_instance;

    /**
     * This is CallbackController object.
     * @var $callbackController
     */
    public $callbackController;

    /**
     * This is CallbackForm object.
     * @var CallbackPage
     */
    public $callbackForm;

    /**
     * This is pageController object.
     * @var CallbackPage
     */
    public $pages;


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
     * Constructor constructor method.
     * @param $pluginDir
     */
    private function __construct( $pluginDir )
    {
        $this->callback = CallbackController::getInstance($pluginDir);
        $this->form = CallbackForm::getInstance( $pluginDir );
        $this->pages = CallbackPage::getInstance( $pluginDir );
    }

    /**
     * get self object
     * @return Constructor object
     */
    public static function getInstance( $pluginDir ) {
        if (empty(self::$_instance)) {
            self::$_instance = new self( $pluginDir );
        }
        return self::$_instance;
    }

}