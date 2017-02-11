<?php

namespace AkniCallback\Model;

use AkniCallback\Controller\PageView;

/**
 * This class need to send callback emails.
 * Class Mailer
 * @package AkniCallback\Model
 */
class Mailer
{
    /**
     * This is Mailer object.
     * @var $_instance
     */
    private static $_instance;

    /**
     * @var $_senderName
     */
    private $_senderName;

    /**
     * @var $_senderEmail
     */
    private $_senderEmail;

    /**
     * @var $_recipient
     */
    private $_recipient;

    /**
     * Plugin dir path.
     * @var $_pluginDir
     */
    private $_pluginDir;

    /**
     * PageView obj.
     * @var $_pageView
     */
    private $_pageView;
    
    private function __construct($_pluginDir)
    {
        $this->_pluginDir = $_pluginDir;
        $this->_pageView = PageView::getInstance($this->_pluginDir);;
        $options = (array) get_option('akni-callback-settings');
        if ($options['sender_email'] !='') {
            $this->_senderEmail = $options['sender_email'];
        } else {
            $this->_senderEmail = get_option('admin_email');
        }
        
        if ($options['sender_name'] !='') {
            $this->_senderName = $options['sender_name'];
        } else {
            $this->_senderName = '';
        }

        if ($options['recipient']) {
            $this->_recipient = $options['recipient'];
        }else {
            $this->_recipient = '';
        }

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
     * @param array $data
     * @return string
     */
    private function getMailBody(array $data)
    {
        $body = $this->_pageView->render('mail.twig', ["data"=>$data]);
        if ($body) {
            return $body;
        }
        return "";
    }

    /**
     * This get self object.
     * @param $pluginDir
     * @return Mailer
     */
    public static function getInstance( $pluginDir ) {
        if (empty(self::$_instance)) {
            self::$_instance = new self($pluginDir);
        }
        return self::$_instance;
    }

    /**
     * Callback sendMail method
     * @todo This method need custom error handler. This time we don't send any exceptions, success msg always.
     * @param array $data
     * @return bool
     */
    public function sendMail(array $data)
    {
        if (!empty($data)) {
            $mailBody = $this->getMailBody($data);
            if ($this->_senderEmail != '' && $this->_recipient != '' && $mailBody !='') {
                $headers[] = "Content-type: text/html; charset=utf-8";
                do_action('plugins_loaded');
                $headers[] = "From:{$this->_senderName} <{$this->_senderEmail}>";
                $headers[] = "Callback";
                $send = wp_mail( $this->_recipient, 'callback', $mailBody, $headers);
                return  $send;
            }
        }
        return false;
    }
}