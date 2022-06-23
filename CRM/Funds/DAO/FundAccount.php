<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.funds/xml/schema/CRM/Funds/03_FundAccount.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:c22417b463d11571ffdeb0c341d5c6cb)
 */
use CRM_Funds_ExtensionUtil as E;

/**
 * Database access object for the FundAccount entity.
 */
class CRM_Funds_DAO_FundAccount extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_fund_account';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Fund ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Account Code
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $code;

  /**
   * Account Name
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * Optional verbose description of the account.
   *
   * @var string|null
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $description;

  /**
   * Date and time the account was created
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_at;

  /**
   * FK to Created Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_by;

  /**
   * Date and time the account was modified
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_at;

  /**
   * FK to Modified Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_by;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_o8_fund_account';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Fund Accounts') : E::ts('Fund Account');
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
          'description' => E::ts('Unique Fund ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_fund_account.id',
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
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
          'description' => E::ts('Account Code'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund_account.code',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
          'localizable' => 0,
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => E::ts('Account Name'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund_account.name',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
          'localizable' => 0,
          'add' => NULL,
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Description'),
          'description' => E::ts('Optional verbose description of the account.'),
          'rows' => 2,
          'cols' => 60,
          'where' => 'civicrm_o8_fund_account.description',
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => NULL,
        ],
        'created_at' => [
          'name' => 'created_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Account Created Date'),
          'description' => E::ts('Date and time the account was created'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund_account.created_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
          'localizable' => 0,
          'add' => NULL,
        ],
        'created_by' => [
          'name' => 'created_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Account Created Contact'),
          'description' => E::ts('FK to Created Contact'),
          'where' => 'civicrm_o8_fund_account.created_by',
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'modified_at' => [
          'name' => 'modified_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Account Modified Date'),
          'description' => E::ts('Date and time the account was modified'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund_account.modified_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
          'localizable' => 0,
          'add' => NULL,
        ],
        'modified_by' => [
          'name' => 'modified_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Account Modified Contact'),
          'description' => E::ts('FK to Modified Contact'),
          'where' => 'civicrm_o8_fund_account.modified_by',
          'table_name' => 'civicrm_o8_fund_account',
          'entity' => 'FundAccount',
          'bao' => 'CRM_Funds_DAO_FundAccount',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_fund_account', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_fund_account', $prefix, []);
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
        'sig' => 'civicrm_o8_fund_account::1::code',
      ],
      'index_name' => [
        'name' => 'index_name',
        'field' => [
          0 => 'code',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_o8_fund_account::0::code',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
