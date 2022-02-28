<?php

require_once 'funds.civix.php';

// phpcs:disable
use CRM_Funds_ExtensionUtil as E;

// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function funds_civicrm_config(&$config)
{
    _funds_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function funds_civicrm_xmlMenu(&$files)
{
    _funds_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function funds_civicrm_install()
{
    _funds_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function funds_civicrm_postInstall()
{
    _funds_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function funds_civicrm_uninstall()
{
    _funds_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function funds_civicrm_enable()
{
    _funds_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function funds_civicrm_disable()
{
    _funds_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function funds_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL)
{
    return _funds_civix_civicrm_upgrade($op, $queue);
}

/**
 * @param $op
 * @param $objectName
 * @param $objectId
 * @param $objectRef
 * @todo Implements hook_civicrm_post() to send an email.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function funds_civicrm_post($op, $objectName, $objectId, &$objectRef)
{
    /*
     */
    $send_an_email = false; //Set to TRUE for DEBUG only
    if ($objectName != 'FundTransaction') {
        return;
    }
    if ($objectRef->contact_id_app == null) {
        return;
    }
    $approverId = $objectRef->contact_id_app;
    $groupName = 'Activity Email Sender';
    $from = CRM_Contact_BAO_Contact::getPrimaryEmail($objectRef->modified_by);
    $toName = CRM_Contact_BAO_Contact::displayName($approverId);
    $toEmail = CRM_Contact_BAO_Contact::getPrimaryEmail($approverId);
    $id = $objectRef->id;
    $description = $objectRef->description;
    $date = date_format(date_create($objectRef->date), 'j-M-Y');
    $amount = "";
    if (is_numeric($objectRef->amount)) {
        $amount = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($objectRef->amount);
    } else {
        $amount = $objectRef->amount;
    }
    $status_id = $objectRef->status_id;
    $status = CRM_Core_PseudoConstant::getLabel("CRM_Funds_DAO_FundTransaction", "status_id", $status_id);
    $case_id = $objectRef->case_id;
    $caseDetails = "";
    if ($case_id != null) {
        $case = civicrm_api3('Case', 'getsingle', [
            'id' => $case_id,
            'check_permissions' => TRUE,
            'return' => ['subject', 'case_type_id', 'status_id', 'start_date', 'end_date'],
        ]);

        $caseStatuses = CRM_Case_PseudoConstant::caseStatus();
        $caseTypes = CRM_Case_PseudoConstant::caseType('title', FALSE);
        $caseDetails = "<table><tr><td>" . ts('Case Subject') . "</td><td>{$case['subject']}</td></tr>
                                  <tr><td>" . ts('Case Type') . "</td><td>{$caseTypes[$case['case_type_id']]}</td></tr>
                                  <tr><td>" . ts('Case Status') . "</td><td>{$caseStatuses[$case['status_id']]}</td></tr>
                                  <tr><td>" . ts('Case Start Date') . "</td><td>" . CRM_Utils_Date::customFormat($case['start_date']) . "</td></tr>
                                  <tr><td>" . ts('Case End Date') . "</td><td></td></tr>" . CRM_Utils_Date::customFormat($case['end_date']) . "</table>";
    }
    $contact_sub = "";
    $contact_id_sub = $objectRef->contact_id_sub;
    if ($contact_id_sub != null) {
        $contact_sub = CRM_Contact_BAO_Contact::displayName($contact_id_sub);
    }

    $sub_account_id = $objectRef->sub_account_id;
    if ($sub_account_id != null) {
        $sub_account = CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundSubAccount', $sub_account_id, 'code') . ': '
            . CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundSubAccount', $sub_account_id, 'name');
    }

    //    [account_id] => 1
    $account_id = $objectRef->account_id;
    if ($account_id != null) {
        $account = CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundAccount', $account_id, 'code') . ': '
            . CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundAccount', $account_id, 'name');
    }
//    [created_at] => 20220227120005
    $created_at = date_format(date_create($objectRef->created_at), 'j-M-Y');
//    [created_by] => 202
    $created_by = $objectRef->created_by;
    $creater = "";
    if ($created_by != null) {
        $creater = CRM_Contact_BAO_Contact::displayName($created_by);
    }
//    [modified_at] => 20220227120449
    $modified_at = date_format(date_create($objectRef->modified_at), 'j-M-Y');
//    [modified_by] => 202
    $modified_by = $objectRef->modified_by;
    $modifier = "";
    if ($modified_by != null) {
        $modifier = CRM_Contact_BAO_Contact::displayName($modified_by);
    }


//                $rows[$rowNum]['civicrm_o8_fund_transaction_tr_sub_account_id']
//                    = CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundSubAccount', $value, 'code') . ': '
//                    . CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundSubAccount', $value, 'name');

//    CRM_Core_Error::debug_var('op', $op);
//    CRM_Core_Error::debug_var('objectName', $objectName);
//    CRM_Core_Error::debug_var('objectId', $objectId);
//    CRM_Core_Error::debug_var('objectRef', $objectRef);
    $email_sbj = $messagehtml = $message = "";
    if ($op == 'create' && $objectName == 'FundTransaction') {
        $email_sbj .= "Fund Transaction - Added";
    }
    if ($op == 'edit' && $objectName == 'FundTransaction') {
        $email_sbj .= "Fund Transaction - Edited";
    }
    if ($op == 'delete' && $objectName == 'FundTransaction') {
        $email_sbj .= "Fund Transaction - Deleted";
    }
    $r_update = CRM_Utils_System::url('civicrm/fund/transaction',
        ['action' => 'update', 'id' => $id], TRUE);
    $update = '<a class="update-transaction action-item crm-hover-button" target="_blank" href="' .
        $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Update</a>';

    $messagehtml .= "<table>\n";
    $messagehtml .= "<tr><td>ID</td><td>$id</td></tr>\n";
    $messagehtml .= "<tr><td>DATE</td><td>$date</td></tr>\n";
    $messagehtml .= "<tr><td>DESCRIPTION</td><td>$description</td></tr>\n";
    $messagehtml .= "<tr><td>AMOUNT</td><td>$amount</td></tr>\n";
    $messagehtml .= "<tr><td>STATUS</td><td>$status</td></tr>\n";
    $messagehtml .= "<tr><td>CASE</td><td>$caseDetails</td></tr>\n";
    $messagehtml .= "<tr><td>SOCIAL WORKER</td><td>$contact_sub</td></tr>\n";
    $messagehtml .= "<tr><td>SUBACCOUNT</td><td>$sub_account</td></tr>\n";
    $messagehtml .= "<tr><td>ACCOUNT</td><td>$account</td></tr>\n";
    $messagehtml .= "<tr><td>CREATED BY</td><td>$creater</td></tr>\n";
    $messagehtml .= "<tr><td>CREATED AT</td><td>$created_at</td></tr>\n";
    $messagehtml .= "<tr><td>MODIFIED BY</td><td>$modifier</td></tr>\n";
    $messagehtml .= "<tr><td>MODIFIED AT</td><td>$modified_at</td></tr>\n";
    $messagehtml .= "<tr><td>LINK TO VIEW/UPDATE</td><td>$update</td></tr>\n";
    $messagehtml .= "";
    $messagehtml .= "</table>";

    $message = strip_tags($messagehtml);

    $mailParams = [
        'groupName' => $groupName,
        'from' => $from,
        'toName' => $toName,
        'toEmail' => $toEmail,
        'subject' => $email_sbj,
        'text' => $message,
        'html' => $messagehtml,
        'attachments' => null,
    ];
    CRM_Core_Error::debug_var('mailparams', $mailParams);
    if (!CRM_Utils_Mail::send($mailParams)) {
        return FALSE;
    } else {
        return true;
    }

//    }
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function funds_civicrm_managed(&$entities)
{
    _funds_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Add CiviCase types provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function funds_civicrm_caseTypes(&$caseTypes)
{
    _funds_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Add Angular modules provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function funds_civicrm_angularModules(&$angularModules)
{
    // Auto-add module files from ./ang/*.ang.php
    _funds_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function funds_civicrm_alterSettingsFolders(&$metaDataFolders = NULL)
{
    _funds_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function funds_civicrm_entityTypes(&$entityTypes)
{
    _funds_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function funds_civicrm_themes(&$themes)
{
    _funds_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function funds_civicrm_preProcess($formName, &$form) {
//
//}

function authx_civicrm_permission(&$permissions) {
    $permissions['access o8 funds module'] = E::ts('Access o8 Funds Module');
    $permissions['create o8 funds transaction'] = E::ts('Create o8 Funds Transaction');
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function funds_civicrm_navigationMenu(&$menu)
{
    _funds_civix_insert_navigation_menu($menu, '', array(
        'label' => E::ts('Funds'),
        'name' => 'o8_funds',
        'icon' => 'crm-i fa-dropbox',
        'url' => 'civicrm/fund/dashboard',
        'permission' => 'access CiviCRM',
        'navID' => 10,
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Dashboard'),
        'name' => 'o8_funds_dashboard',
        'url' => 'civicrm/fund/dashboard',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Find Funds'),
        'name' => 'o8_funds_search',
        'url' => 'civicrm/fund/search',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Add Fund'),
        'name' => 'o8_funds_add_fund',
        'url' => 'civicrm/fund/form?reset=1&action=add',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Find Account'),
        'name' => 'o8_funds_account_search',
        'url' => 'civicrm/fund/accountsearch',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Add Account'),
        'name' => 'o8_funds_account_add',
        'url' => 'civicrm/fund/account?reset=1&action=add',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Find Category'),
        'name' => 'o8_funds_category_search',
        'url' => 'civicrm/fund/categorysearch',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Add Category'),
        'name' => 'o8_funds_category_add',
        'url' => 'civicrm/fund/category?reset=1&action=add',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Find SubAccount'),
        'name' => 'o8_funds_sub_account_search',
        'url' => 'civicrm/fund/subaccountsearch',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Add SubAccount'),
        'name' => 'o8_funds_sub_account_add',
        'url' => 'civicrm/fund/subaccount?reset=1&action=add',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Find Transaction'),
        'name' => 'o8_funds_transaction_search',
        'url' => 'civicrm/fund/transactionsearch',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Add Transaction'),
        'name' => 'o8_funds_transaction_add',
        'url' => 'civicrm/fund/transaction?reset=1&action=add',
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ));
    _funds_civix_navigationMenu($menu);
    _funds_civix_insert_navigation_menu($menu, 'o8_funds', array(
        'label' => E::ts('Fund Reports'),
        'name' => 'o8_funds_report_funds',
        'url' => CRM_Utils_System::url('civicrm/report/list', ['grp' => 'funds', 'reset' => 1]),
        'permission' => 'access CiviCRM',
        'operator' => 'OR',
        'separator' => 2,
    ));
    _funds_civix_navigationMenu($menu);
}
