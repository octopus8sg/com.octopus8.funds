<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.funds/xml/schema/CRM/Funds/02_FundAccountType.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:ecd10d2fc7615d39f39b8fadfe5eea37)
 */
use CRM_Funds_ExtensionUtil as E;

/**
 * Database access object for the FundAccountType entity.
 */
class CRM_Funds_DAO_FundAccountType extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_fund_account_type';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Fund Account Type ID
   *
   * @var int
   */
  public $id;

  /**
   * AccountType Code
   *
   * @var string
   */
  public $code;

  /**
   * AccountType Name
   *
   * @var string
   */
  public $name;

  /**
   * Optional verbose description of the Account Type.
   *
   * @var text
   */
  public $description;

  /**
   * Date and time the Account Type was created
   *
   * @var timestamp
   */
  public $created_at;

  /**
   * FK to Created Contact
   *
   * @var int
   */
  public $created_by;

  /**
   * Date and time the AccountType was modified
   *
   * @var timestamp
   */
  public $modified_at;

  /**
   * FK to Modified Contact
   *
   * @var int
   */
  public $modified_by;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_o8_fund_account_type';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Fund Account Types') : E::ts('Fund Account Type');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'created_by', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'modified_by', 'civicrm_contact', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique Fund Account Type ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_fund_account_type.id',
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'code' => [
          'name' => 'code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Code'),
          'description' => E::ts('AccountType Code'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund_account_type.code',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => E::ts('AccountType Name'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund_account_type.name',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'add' => NULL,
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Description'),
          'description' => E::ts('Optional verbose description of the Account Type.'),
          'rows' => 2,
          'cols' => 60,
          'where' => 'civicrm_o8_fund_account_type.description',
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => NULL,
        ],
        'created_at' => [
          'name' => 'created_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('AccountType Created Date'),
          'description' => E::ts('Date and time the Account Type was created'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund_account_type.created_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'add' => NULL,
        ],
        'created_by' => [
          'name' => 'created_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Account Type Created Contact'),
          'description' => E::ts('FK to Created Contact'),
          'where' => 'civicrm_o8_fund_account_type.created_by',
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'modified_at' => [
          'name' => 'modified_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Account Type Modified Date'),
          'description' => E::ts('Date and time the AccountType was modified'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund_account_type.modified_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'add' => NULL,
        ],
        'modified_by' => [
          'name' => 'modified_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Account Type Modified Contact'),
          'description' => E::ts('FK to Modified Contact'),
          'where' => 'civicrm_o8_fund_account_type.modified_by',
          'table_name' => 'civicrm_o8_fund_account_type',
          'entity' => 'FundAccountType',
          'bao' => 'CRM_Funds_DAO_FundAccountType',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_fund_account_type', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_fund_account_type', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_code' => [
        'name' => 'index_code',
        'field' => [
          0 => 'code',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_o8_fund_account_type::1::code',
      ],
      'index_name' => [
        'name' => 'index_name',
        'field' => [
          0 => 'code',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_o8_fund_account_type::0::code',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}