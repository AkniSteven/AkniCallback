<?php

namespace AkniCallback\Model;

/**
 * This class need for create plugin pages.
 * Class CallbackPage
 * @package AkniCallback\Controller
 */
class CallbackPage
{
    /**
     * This is CallbackPage object.
     * @var $_instance
     */
    private static $_instance;

    /**
     * This is CallbackModel object.
     * @var $_callbackModel
     */
    private $_callbackModel;

    /**
     * Plugin dir path.
     * @var $_pluginDir
     */
    private $_pluginDir;

    /**
     * Error msg
     * @var string|void
     */
    private $_error;

    /**
     * CallbackPage constructor.
     * @param $pluginDir
     */
    private function __construct( $pluginDir )
    {
        $this->_pluginDir = $pluginDir;
        $this->_error = __('This page is under construction');
        $this->_callbackModel = Callback::getInstance( $pluginDir );

        add_action('admin_menu', [ &$this, 'addAdminPages' ]);
        add_action('admin_init', [ &$this, 'registerSettings' ]);
        add_action('admin_footer', [ &$this, 'addCallbackAdminStyles']);
        add_action('admin_footer', [ &$this, 'addCallbackAdminScripts']);
    }
    
    public function addCallbackAdminScripts()
    {
        wp_enqueue_script(
            'callback-admin-script', '/wp-content/plugins/AkniCallback/views/public/js/callback-admin.js',  ['jquery']
        );
    } 
    
    public function addCallbackAdminStyles()
    {
        wp_register_style(
            'callback-admin',  '/wp-content/plugins/AkniCallback/views/public/css/callback-admin.css'
        );
        wp_enqueue_style('callback-admin');
    }
    
    /**
     * this is clone action.
     */
    private function __clone()
    {
        //protect from clone

    }

    /**
     * this is wakeup action.
     */
    private function __wakeup()
    {
        //protect from wakeup

    }

    /**
     * This method  register custom settings.
     */
    public function registerSettings() {
        register_setting(
            'akni-callback-settings', 'akni-callback-settings'
        );
    }

    /**
     * Method for creating Admin pages.
     */
    public function addAdminPages()
    {
        add_menu_page(
            __('Callback'),
            __('Callback'),
            'manage_options',
            'callback',
            [&$this, 'displayPages']
        );
        add_menu_page(
            __('Callback Settings'),
            __('Callback Settings'),
            'manage_options',
            'callback-settings',
            [&$this, 'displayPages']
        );

    }

    /**
     * This method display admin pages.
     * @return bool
     */
    public function displayPages()
    {
        if ($_REQUEST['page']) {
            $path = $this->_pluginDir. '/Pages/' . $_REQUEST['page'] . '.php';
            if (file_exists($path)) {
                require_once  $path;
                return true;
            }
        }
        echo $this->_error;
        return false;
    }

    /**
     * This method need to get callbackControllerObject.
     * @param $pluginDir
     * @return CallbackPage
     */
    public static function getInstance( $pluginDir ) {
        if (empty(self::$_instance)) {
            self::$_instance = new self( $pluginDir );
        }
        return self::$_instance;
    }
}