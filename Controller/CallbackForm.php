<?php

namespace AkniCallback\Controller;

/**
 * Class CallbackForm
 * @package AkniCallback\Controller
 */
class CallbackForm
{

    /**
     * Self specimen.
     * @var $_instance
     */
    private static $_instance;

    /**
     * Page View Object for rendering.
     * @var PageView
     */
    private $_pageView;

    /**
     * Variable path to twig template directory.
     * @var $_pluginDir
     */
    private $_pluginDir;

    /**
     * Default form params.
     * @var $_default
     */
    private $_default;

    /**
     * This is variable for count $_getForm method.
     * @var $_getFormCounter.
     */
    private static $_getFormCounter;

    /**
     * CallbackForm constructor.
     * @param $pluginDir
     */
    private function __construct( $pluginDir )
    {
        $this->setDefault();
        $this->_pluginDir = $pluginDir;
        $this->_pageView = PageView::getInstance($this->_pluginDir);

        add_shortcode(
            'callback', [$this, 'getForm']
        );
        add_action(
            'wp_footer', [&$this, 'addScripts']
        );
    }

    /**
     * This function add scripts for callback forms.
     */
    public function addScripts()
    {
        wp_enqueue_script(
            'jquery.maskedinput', '/wp-content/plugins/AkniCallback/views/public/js/jquery.maskedinput.js', ['jquery']
        );
        wp_enqueue_script(
            'akni_callback', '/wp-content/plugins/AkniCallback/views/public/js/akni_callback.js', ['jquery','jquery.maskedinput']
        );
    }

    /**
     * usort orderSort method for sortFields function.
     * @param $a
     * @param $b
     * @return bool
     */
    private static function orderSort( $a, $b )
    {
        return $a['order'] > $b['order'];
    }

    /**
     * Method for sorting form fields by by order.
     * @param array $arr
     * @return array
     */
    private function sortFields(array $arr)
    {
        uasort($arr, [$this, 'orderSort']);

        return $arr;
    }

    /**
     * this method set default params.
     */
    private final function  setDefault(){
        $this->_default = [
            'content'=>[
                'type' => 'callback',
                'formTitle' => __('Callback'),
                'formDescription' => __('Leave your callback, and we are call you'),
                'thankYouText' => __('Thank you for calling'),
                'submitText' => __('Submit'),
                'url' => site_url() .'/wp-admin/admin-ajax.php',
            ],

            'name' =>  [
                'show' => 1,
                'order' => 1,
                'placeholder' =>__('Name'),
                'class' => 'form-field name',
                'error' => __('Sorry it`s invalid name')
            ],
            'phone' => [
                'show' => 1,
                'order' => 2,
                'placeholder' => __('Phone'),
                'class' => 'form-field tel',
                'error' => __('Sorry its invalid phone'),
            ],
            'email' => [
                'show'  => 1,
                'order' => 3,
                'placeholder' => __('Email'),
                'class' => 'form-field email',
                'error' => __('Sorry its invalid email')
            ],
            'comment' => [
                'show' => 0,
                'order' => 4,
                'placeholder' => __('Comment'),
                'class' => 'form-field',
                'error' => __('Sorry its invalid comment')
            ],
            'company' => [
                'show' => 0,
                'order' => 5,
                'placeholder' => __('Company'),
                'class' => 'form-field',
                'error' => __('Sorry its invalid company')
            ],
        ];
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
     * This function add custom params for form and return it.
     * @param array $params
     * @return array
     */
    private function formateData(array $params) {
        $data = $this->_default;
        if (!empty($params)) {
            foreach ($params as $paramName => $paramValue) {
                if (array_key_exists($paramName, $data)) {
                    if (is_array($paramValue) && is_array($data[$paramName])) {
                        foreach ($paramValue as $k=>$v) {
                            if (array_key_exists($k, $data[$paramName])) {
                                $data[$paramName][$k] = $v;
                            }
                        }
                    } elseif(!is_array($paramValue) && !is_array($data[$paramName])) {
                        $data[$paramName] = $paramValue;
                    }
                }
            }
        }
        return $data;
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
     * Get rendered form with required fields and special params.
     * @param $params
     */
    public function getForm($params = '')
    {
        $data = [];

        if (!empty($this->_default)) {
            if ($params['params']){
                $params = unserialize($params['params']);
                if (is_array($params) && !empty($params)) {
                    $data['form'] = $this->formateData($params);
                } else {
                     $data['form'] = $this->_default;
                }
            }
        }
        
        self::$_getFormCounter ++;
        
        #set form id after adding all special params.
        $data['form']['content']['formID'] = self::$_getFormCounter;
        $data['form'] = $this->sortFields($data['form']);
        $this->_pageView->display('callback_form.twig', $data);
       
    }
}