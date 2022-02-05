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
        CRM_Core_Error::debug_var('step1', 1);
        $className = 'CRM_Funds_DAO_Fund';
        CRM_Core_Error::debug_var('step1', 2);
        $entityName = 'Fund';
        CRM_Core_Error::debug_var('step1', 3);
        $hook = empty($params['id']) ? 'create' : 'edit';
        CRM_Core_Error::debug_var('step1', 4);
        CRM_Core_Error::debug_var('hook', $hook);

        CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
        CRM_Core_Error::debug_var('step1', 5);
        $instance = new $className();
        CRM_Core_Error::debug_var('step1', 6);
        $values = array_intersect_key($params, self::getSupportedFields());
        if (isset($params['id']) && empty($params['id'])) {
            unset($params['id']);
        }
        $instance->copyValues($params);
        CRM_Core_Error::debug_var('step1', 7);
        CRM_Core_Error::debug_var('instance', $instance);
        $instance->save();
        CRM_Core_Error::debug_var('step1', 8);
        CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);
        // check and attach and files as needed
        CRM_Core_Error::debug_var('step1', 9);
        CRM_Core_BAO_File::processAttachment($params,
            'civicrm_o8_fund',
            $instance->id
        );
        CRM_Core_Error::debug_var('step1', 10);

//      $transaction->commit();

        return $instance;
    }

    /**/

}
