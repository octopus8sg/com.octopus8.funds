<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_BAO_Fund extends CRM_Funds_DAO_Fund
{

    /**
     * Create a new Fund based on array-data
     *
     * @param array $params key-value pairs
     * @return CRM_Funds_DAO_Fund|NULL
     *
     * */

    public static function create($params)
    {
        $className = 'CRM_Funds_DAO_Fund';
        $entityName = 'Fund';
        $hook = empty($params['id']) ? 'create' : 'edit';


        CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);

        $instance = new $className();
        $values = array_intersect_key($params, self::getSupportedFields());
        if (isset($params['id']) && empty($params['id'])) {
            unset($params['id']);
        }
        $instance->copyValues($params);
        $instance->save();
        CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);
        // check and attach and files as needed
        CRM_Core_BAO_File::processAttachment($params,
            'civicrm_o8_fund',
            $instance->id
        );

//      $transaction->commit();

        return $instance;
    }

    /**/

}
