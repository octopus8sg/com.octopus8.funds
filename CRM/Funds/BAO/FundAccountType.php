<?php
use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_BAO_FundAccountType extends CRM_Funds_DAO_FundAccountType {

  /**
   * Create a new FundAccountType based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Funds_DAO_FundAccountType|NULL
   *
  public static function create($params) {
    $className = 'CRM_Funds_DAO_AccountType';
    $entityName = 'FundAccountType';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
