<?php

namespace AkniCallback\Model;

/**
 * This is class that work with db.
 * Class Db
 * @package AkniCallback\Model
 */
class Db extends AbstractDb
{

    /**
     * Self object.
     * @var $_instance
     */
    private static $_instance;

    /**
     * Table name without prefix
     * @var $_tableName
     */
    private $_tableName;

    /**
     * Fields to create db
     * @var $_fields
     */
    private $_fields;

    /**
     * Db constructor.
     * @param $_tableName
     * @param $fields
     */
    private function __construct( $_tableName, $fields )
    {
        $this->_tableName = $_tableName;
        $this->_fields = $fields;
        $this->createPluginTable($this->_tableName, $this->_fields);
    }

    /**
     * Clone method.
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
     * This method need to get
     * @param $_tableName
     * @param $fields
     * @return Db object
     */
    public static function getInstance( $_tableName, $fields )
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self($_tableName, $fields);
        }
        return self::$_instance;
    }


    /**
     * Create plugin table, if not exists.
     * @param $name
     * @param $fields
     * @return bool
     */
    protected function createPluginTable($name, $fields)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . $name;
        $fields = implode(",", $this->_fields);
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

            $sql = "CREATE TABLE " . $table_name . "( $fields ){$charset_collate};";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            return true;
        }
        return false;
    }

    /**
     * This create insert variables.
     * @param array $data
     * @return array
     */
    private function createInsert( array $data )
    {
        $data['status'] = 0;
        $data['date'] = date('Y-m-d  H:i:s', time());

        $insertData = [
            'fields' => '',
            'mask' => '',
            'values' => ''
        ];
        $keys = array_keys($data);

        $countKeys = count($keys);

        for ($i = 0; $i < $countKeys; $i++) {
            $insertData['mask'][]= '%s';

        }

        foreach ($data as $item) {
            $insertData['values'][] = esc_sql($item);
        }

        $insertData['mask'] = implode (",", $insertData['mask']);
        $insertData['fields'] = implode(",", $keys);

        return $insertData;

    }

    /**
     * This function insert data into table.
     * @todo This method need custom error handler in future. This time we don't send any exceptions.
     * @param array $data
     * @return bool
     */
    public function insertIntoTable( array $data )
    {
        global $wpdb;

        $table_name = $wpdb->prefix . $this->_tableName;
        if (!empty($data)) {
            $insertData = $this->createInsert($data);
            if ($insertData['fields'] !='' && !empty ($insertData['values'])) {
                $insert =  $wpdb->query(
                    $wpdb->prepare(
                        "INSERT INTO  $table_name ({$insertData['fields']}) VALUES ({$insertData['mask']});",
                        $insertData['values']
                    ));
                if ($insert) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    public function selectFromTable( $fields, $where = '', $special = '' )
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->_tableName;
        if ($where === '') {
            $sql = "SELECT {$special} {$fields} FROM  $table_name";
        } else {
            $sql = "SELECT {$special} {$fields} FROM  $table_name WHERE {$where}";
        }
        $wpdb->query($sql);
        $result = $wpdb->get_results($sql, ARRAY_A);
        return $result;
    }

    /**
     * Update table data field with var id.
     * @param $id
     * @param $field
     * @param $value
     * @return false|int
     */
    public  function updateTableData( $id , $field, $value )
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->_tableName;
        $sql = "UPDATE {$table_name} SET {$field} = {$value} WHERE id={$id}";
        return $wpdb->query($sql);
    }

    /**
     * delete table data by field id.
     * @param $id
     * @return bool
     */
    public  function deleteTableData( $id )
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->_tableName;
        $sql = "DELETE FROM {$table_name} WHERE id={$id}";
        $wpdb->query($sql);
    }
}