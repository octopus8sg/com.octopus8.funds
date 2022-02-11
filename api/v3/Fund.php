<?php

use CRM_Funds_ExtensionUtil as E;


/**
 * $params[$request['search_field']] = ['LIKE' => ($request['add_wildcard'] ? '%' : '') . $request['input'] . '%'];
 *
 */
/**
 * Get fund list parameters.
 *
 * @param array $request
 * @see _civicrm_api3_generic_getlist_params
 *
 */
function _civicrm_api3_fund_getlist_params(&$request)
{
//    CRM_Core_Error::debug_var('request3', $request);
    $params = [];
    if ($request['input']) {
        if ($request['search_fields']) {
            $search_fields = $request['search_fields'];
            CRM_Core_Error::debug_var('search_fields', $search_fields);
            if (sizeof($search_fields) > 0) {

                foreach ($search_fields as $search_field) {
//                CRM_Core_Error::debug_var('search_field', $search_field);
                    $params[$search_field] = ['LIKE' => ($request['add_wildcard'] ? '%' : '') . $request['input'] . '%'];
                }
                if (isset($request['search_field'])) {
                    if (!in_array($request['search_field'], $search_fields)) {
                        $search_fields[] = $request['search_field'];
                    };
                }
                $request['params']['options']['or'] = [$search_fields];
            }
        }
    }
    $request['params'] += $params;
    _civicrm_api3_generic_getlist_params($request);
//    CRM_Core_Error::debug_var('request4', $request);
}

/**
 * Fund.create API specification (optional).
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_fund_create_spec(&$spec)
{
    // $spec['some_parameter']['api.required'] = 1;
}

/**
 * Fund.create API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_fund_create($params)
{
    return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'Fund');
}

/**
 * Fund.delete API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_fund_delete($params)
{
    return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * Fund.get API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_fund_get($params)
{
    return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params, TRUE, 'Fund');
}
