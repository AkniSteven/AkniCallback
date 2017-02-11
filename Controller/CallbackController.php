<?php
namespace AkniCallback\Controller;


use AkniCallback\Model\Callback;

/**
 * Class CallbackController
 * @package AkniCallback\Controller
 */
class CallbackController
{
    /**
     * This is CallbackController object.
     * @var $_instance
     */
    private static $_instance;

    /**
     * This is CallbackModel object.
     * @var callable
     */
    private $_callbackModel;

    /**
     * This is plugin dir.
     * @var $_pluginDir
     */
    private $_pluginDir;
    
    /**
     * CallbackController constructor.
     * @param $pluginDir
     */
    private function __construct( $pluginDir )
    {
        $this->_pluginDir = $pluginDir;
        
        $this->_callbackModel = Callback::getInstance( $this->_pluginDir );
        add_action(
            'wp_ajax_sendCallback',
            [&$this,'sendCallback']
        );
        add_action(
            'wp_ajax_nopriv_sendCallback',
            [&$this,'sendCallback']
        );
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
     * This method works get ajax, and send data to model.
     * @todo This method need custom error handler. This time we don't send any exceptions, success msg always.
     * @return bool
     */
    public function sendCallback()
    {
        $callbackData = [];

        if($_POST['data']){
            parse_str($_POST['data'], $callbackData);
        };
        
        if (!empty($callbackData)) {
            if ($this->_callbackModel->addNewCallback($callbackData)){
                return true;
            }
        }
        return false;
    }

    /**
     * This return self object.
     * @param $pluginDir
     * @return CallbackController
     */
    public static function getInstance($pluginDir) {
        if (empty(self::$_instance)) {
            self::$_instance = new self($pluginDir);
        }
        return self::$_instance;
    }
}