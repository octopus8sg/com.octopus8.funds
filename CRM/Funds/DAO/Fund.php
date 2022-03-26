<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.funds/xml/schema/CRM/Funds/01_Fund.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:ebbcfceba686500215feb0eaa6c126ae)
 */
use CRM_Funds_ExtensionUtil as E;

/**
 * Database access object for the Fund entity.
 */
class CRM_Funds_DAO_Fund extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_fund';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Fund ID
   *
   * @var int
   */
  public $id;

  /**
   * Fund Code
   *
   * @var string
   */
  public $code;

  /**
   * FK to Contact
   *
   * @var int
   */
  public $contact_id;

  /**
   * Fund Name
   *
   * @var string
   */
  public $name;

  /**
   * Target Cases
   *
   * @var int
   */
  public $target_cases;

  /**
   * Fund Start Date
   *
   * @var datetime
   */
  public $start_date;

  /**
   * Fund End Date
   *
   * @var datetime
   */
  public $end_date;

  /**
   * Starting Amount of the fund.
   *
   * @var float
   */
  public $amount;

  /**
   * Residue Amount of the fund.
   *
   * @var float
   */
  public $residue;

  /**
   * Spent Amount of the fund.
   *
   * @var float
   */
  public $expenditure;

  /**
   * Balance % amount of the fund.
   *
   * @var float
   */
  public $balance;

  /**
   * Optional verbose description of the fund.
   *
   * @var text
   */
  public $description;

  /**
   * Date and time the fund was created
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
   * Date and time the fund was modified
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
    $this->__table = 'civicrm_o8_fund';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Funds') : E::ts('Fund');
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'contact_id', 'civicrm_contact', 'id');
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
          'where' => 'civicrm_o8_fund.id',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
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
          'description' => E::ts('Fund Code'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.code',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'add' => NULL,
        ],
        'contact_id' => [
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to Contact'),
          'where' => 'civicrm_o8_fund.contact_id',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => E::ts('Fund Name'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.name',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'add' => NULL,
        ],
        'target_cases' => [
          'name' => 'target_cases',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Target Cases'),
          'description' => E::ts('Target Cases'),
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.target_cases',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'add' => NULL,
        ],
        'start_date' => [
          'name' => 'start_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Start Date'),
          'description' => E::ts('Fund Start Date'),
          'required' => TRUE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.start_date',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Start Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'end_date' => [
          'name' => 'end_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('End Date'),
          'description' => E::ts('Fund End Date'),
          'required' => TRUE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.end_date',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Start Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'amount' => [
          'name' => 'amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Amount'),
          'description' => E::ts('Starting Amount of the fund.'),
          'required' => TRUE,
          'precision' => [
            20,
            2,
          ],
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.amount',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Amount"),
          ],
          'add' => NULL,
        ],
        'residue' => [
          'name' => 'residue',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Residue'),
          'description' => E::ts('Residue Amount of the fund.'),
          'precision' => [
            20,
            2,
          ],
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.residue',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Residue"),
          ],
          'add' => NULL,
        ],
        'expenditure' => [
          'name' => 'expenditure',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Expenditure'),
          'description' => E::ts('Spent Amount of the fund.'),
          'precision' => [
            20,
            2,
          ],
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.expenditure',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Expenditure"),
          ],
          'add' => NULL,
        ],
        'balance' => [
          'name' => 'balance',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Balance'),
          'description' => E::ts('Balance % amount of the fund.'),
          'precision' => [
            20,
            2,
          ],
          'import' => TRUE,
          'where' => 'civicrm_o8_fund.balance',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Balance"),
          ],
          'add' => NULL,
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Description'),
          'description' => E::ts('Optional verbose description of the fund.'),
          'rows' => 2,
          'cols' => 60,
          'where' => 'civicrm_o8_fund.description',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => NULL,
        ],
        'created_at' => [
          'name' => 'created_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Fund Created Date'),
          'description' => E::ts('Date and time the fund was created'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund.created_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'add' => NULL,
        ],
        'created_by' => [
          'name' => 'created_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Fund Created Contact'),
          'description' => E::ts('FK to Created Contact'),
          'where' => 'civicrm_o8_fund.created_by',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'modified_at' => [
          'name' => 'modified_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Fund Modified Date'),
          'description' => E::ts('Date and time the fund was modified'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund.modified_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
          'localizable' => 0,
          'add' => NULL,
        ],
        'modified_by' => [
          'name' => 'modified_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Fund Modified Contact'),
          'description' => E::ts('FK to Modified Contact'),
          'where' => 'civicrm_o8_fund.modified_by',
          'table_name' => 'civicrm_o8_fund',
          'entity' => 'Fund',
          'bao' => 'CRM_Funds_DAO_Fund',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_fund', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_fund', $prefix, []);
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
        'sig' => 'civicrm_o8_fund::1::code',
      ],
      'index_name' => [
        'name' => 'index_name',
        'field' => [
          0 => 'code',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_o8_fund::0::code',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
