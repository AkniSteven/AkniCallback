<?php
namespace AkniCallback\Model;

/**
 * Class AbstractDb
 * @package AkniCallback\Model
 */
abstract class AbstractDb
{
    /**
     * Create plugin table with fields.
     * @param $name
     * @param $fields
     * @return mixed
     */
    abstract protected function createPluginTable( $name, $fields );

    /**
     * Insert data into table
     * @param array $data
     * @return bool
     */
    abstract public function insertIntoTable( array $data );

    /**
     * Select data from table.
     * @param $fields
     * @param string $where
     * @param string $special
     * @return mixed
     */
    abstract  function selectFromTable( $fields, $where='',  $special = '' );

    /**
     * Update table data field with var id.
     * @param $id
     * @param $field
     * @param $value
     * @return mixed
     */
    abstract  function updateTableData( $id, $field , $value );

    /**
     * delete table data by field id.
     * @param $id
     * @return bool
     */
    abstract  function deleteTableData( $id );

}