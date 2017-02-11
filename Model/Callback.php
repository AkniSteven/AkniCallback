<?php

namespace AkniCallback\Model;

use AkniCallback\Helper\Data;

/**
 * This use for work with callback db.
 * Class Callback
 * @package AkniCallback\Model
 */
class Callback
{
    /**
     * Callback object.
     * @var $_instance
     */
    private static $_instance;

    /**
     * Db object.
     * @var Db
     */
    private $_db;

    /**
     * Mailer object.
     * @var $_mailer
     */
    private $_mailer;

    /**
     * Plugin table name.
     * @var $_table_name
     */
    private $_table_name;

    /**
     * Fields to create table.
     * @var $_fields
     */
    private $_fields;

    /**
     * This are fields names for select/insert.
     * @var $_allFields
     */
    private $_allFields;

    /**
     * This is plugin dir.
     * @var $_pluginDir
     */
    private $_pluginDir;

    /**
     * Callback constructor.
     * @param $pluginDir
     */
    private function __construct(  $pluginDir )
    {
        $this->_pluginDir = $pluginDir;
        $this->setDefault();
        $this->_db = Db::getInstance($this->_table_name, $this->_fields);
        $this->_mailer = Mailer::getInstance(  $pluginDir );
    }

    /**
     * clone method.
     */
    private function __clone()
    {

    }

    /**
     * wakeup method.
     */
    private function __wakeup()
    {

    }

    /**
     * this method set default params.
     */
    private final function setDefault()
    {
        $this->_table_name = 'akni_callback';
        $this->_fields = [
            "id int NOT NULL AUTO_INCREMENT",
            "type text NOT NULL",
            "name text NOT NULL",
            "phone text NOT NULL",
            "email text NOT NULL",
            "comment text NOT NULL",
            "company text NOT NULL",
            "status int default 0",
            "info text NOT NULL",
            "date  datetime NOT NULL",
            "UNIQUE KEY id (id)"
        ];
        $this->_allFields = [
            'id',
            'type',
            'name',
            'phone',
            'email',
            'comment',
            'company',
            'status',
            'info',
            'date'
        ];
    }

    /**
     * This method need to create single object.
     * @return Callback object
     */
    public static function getInstance( $pluginDir )
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self( $pluginDir);
        }
        return self::$_instance;
    }

    /**
     * This method clear data before adding.
     * @param array $data
     * @return array
     */
    private function clearData(array $data)
    {
        if (!empty($data)) {
            foreach ($data as &$item) {
                $item = Data::clearString($item);
            }
        }
        return $data;
    }

    /**
     * remove fields, that are not in array.
     * @param array $data
     * @return array
     */
    private function prepareToInsert(array $data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $item) {
                if (!in_array($key, $this->_allFields)) {
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }

    /**
     *  This method work with new callback.
     * @todo This method need custom error handler in future. This time we don't send any exceptions.
     * @param array $data
     * @return bool
     */
    public function addNewCallback(array $data)
    {
        $data = $this->prepareToInsert($this->clearData($data));

        if (!empty($data)) {
            if ($this->_db->insertIntoTable($data)) {
                if ($this->_mailer->sendMail($data)) {
                    return true;
                };
            }
        }
        return false;
    }

    /**
     * This method get all forms types.
     * @return array|mixed|null|object
     */
    public function getCallbackTypes()
    {
        $types = $this->_db->selectFromTable('type', '', 'DISTINCT');

        return $types;
    }

    /**
     * This method need to select forms data by type.
     * @param $type
     * @return array|mixed|null|object
     */
    public function getCallbackContentByType($type)
    {
        $fields = Data::unsetArrayValue('type', $this->_allFields);
        $fields = implode(",", $fields);
        $where = "type = '$type'";
        $content = $this->_db->selectFromTable($fields, $where, '');

        return $content;
    }

    /**
     * Update callback status
     * @param $id
     * @param $status
     * @return false|int
     */
    public function updateStatus($id, $status)
    {
        return $this->_db->updateTableData( $id,'status', $status);
    }

    /**
     * This method delete callback with id $id.
     * @param $id
     * @return bool
     */
    public function deleteCallback( $id )
    {
        return $this->_db->deleteTableData($id);
    }
}