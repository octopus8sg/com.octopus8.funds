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
function funds_civicrm_config(&$config) {
  _funds_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function funds_civicrm_xmlMenu(&$files) {
  _funds_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function funds_civicrm_install() {
  _funds_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function funds_civicrm_postInstall() {
  _funds_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function funds_civicrm_uninstall() {
  _funds_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function funds_civicrm_enable() {
  _funds_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function funds_civicrm_disable() {
  _funds_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function funds_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _funds_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function funds_civicrm_managed(&$entities) {
  _funds_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Add CiviCase types provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function funds_civicrm_caseTypes(&$caseTypes) {
  _funds_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Add Angular modules provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function funds_civicrm_angularModules(&$angularModules) {
  // Auto-add module files from ./ang/*.ang.php
  _funds_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function funds_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _funds_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function funds_civicrm_entityTypes(&$entityTypes) {
  _funds_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function funds_civicrm_themes(&$themes) {
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

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function funds_civicrm_navigationMenu(&$menu) {
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
