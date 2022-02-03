<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.funds/xml/schema/CRM/Funds/05_FundTransaction.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:4775d79b647d84361a8ddd4d944e2125)
 */
use CRM_Funds_ExtensionUtil as E;

/**
 * Database access object for the FundTransaction entity.
 */
class CRM_Funds_DAO_FundTransaction extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_fund_transaction';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Fund Transaction ID
   *
   * @var int
   */
  public $id;

  /**
   * Transaction Data Time
   *
   * @var datetime
   */
  public $date;

  /**
   * Optional verbose description of the transaction.
   *
   * @var text
   */
  public $description;

  /**
   * Amount of the transaction.
   *
   * @var float
   */
  public $amount;

  /**
   * FK to civicrm_file
   *
   * @var int
   */
  public $file_id;

  /**
   * @var int
   */
  public $status_id;

  /**
   * FK to civicrm_case
   *
   * @var int
   */
  public $case_id;

  /**
   * FK to Contact
   *
   * @var int
   */
  public $contact_id_sub;

  /**
   * FK to Contact
   *
   * @var int
   */
  public $contact_id_app;

  /**
   * FK to civicrm_o8_fund_component
   *
   * @var int
   */
  public $component_id;

  /**
   * FK to civicrm_o8_fund_account
   *
   * @var int
   */
  public $account_id;

  /**
   * Date and time the transaction was created
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
   * Date and time the transaction was modified
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
    $this->__table = 'civicrm_o8_fund_transaction';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Fund Transactions') : E::ts('Fund Transaction');
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'file_id', 'civicrm_file', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'case_id', 'civicrm_case', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'contact_id_sub', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'contact_id_app', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'component_id', 'civicrm_o8_fund_component', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'account_id', 'civicrm_o8_fund_account', 'id');
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
          'description' => E::ts('Unique Fund Transaction ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_fund_transaction.id',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'date' => [
          'name' => 'date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Date'),
          'description' => E::ts('Transaction Data Time'),
          'required' => TRUE,
          'import' => TRUE,
          'where' => 'civicrm_o8_fund_transaction.date',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Description'),
          'description' => E::ts('Optional verbose description of the transaction.'),
          'rows' => 2,
          'cols' => 60,
          'where' => 'civicrm_o8_fund_transaction.description',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => NULL,
        ],
        'amount' => [
          'name' => 'amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Amount'),
          'description' => E::ts('Amount of the transaction.'),
          'required' => TRUE,
          'precision' => [
            20,
            2,
          ],
          'import' => TRUE,
          'where' => 'civicrm_o8_fund_transaction.amount',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Amount"),
          ],
          'add' => NULL,
        ],
        'file_id' => [
          'name' => 'file_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Attachement File ID'),
          'description' => E::ts('FK to civicrm_file'),
          'required' => TRUE,
          'where' => 'civicrm_o8_fund_transaction.file_id',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_File',
          'html' => [
            'label' => E::ts("File"),
          ],
          'add' => NULL,
        ],
        'status_id' => [
          'name' => 'status_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Status'),
          'where' => 'civicrm_o8_fund_transaction.status_id',
          'default' => '1',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'o8_fund_trxn_status',
            'optionEditPath' => 'civicrm/admin/options/o8_fund_trxn_status',
          ],
          'add' => NULL,
        ],
        'case_id' => [
          'name' => 'case_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Case'),
          'description' => E::ts('FK to civicrm_case'),
          'where' => 'civicrm_o8_fund_transaction.case_id',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'FKClassName' => 'CRM_Case_DAO_Case',
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Case"),
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_case',
            'keyColumn' => 'id',
            'labelColumn' => 'subject',
          ],
          'add' => NULL,
        ],
        'contact_id_sub' => [
          'name' => 'contact_id_sub',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Contact (Social Worker)'),
          'description' => E::ts('FK to Contact'),
          'where' => 'civicrm_o8_fund_transaction.contact_id_sub',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'contact_id_app' => [
          'name' => 'contact_id_app',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Contact (Approver)'),
          'description' => E::ts('FK to Contact'),
          'where' => 'civicrm_o8_fund_transaction.contact_id_app',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'component_id' => [
          'name' => 'component_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Component'),
          'description' => E::ts('FK to civicrm_o8_fund_component'),
          'where' => 'civicrm_o8_fund_transaction.component_id',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'FKClassName' => 'CRM_Funds_DAO_FundComponent',
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Component"),
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_o8_fund_component',
            'keyColumn' => 'id',
            'labelColumn' => 'title',
          ],
          'add' => NULL,
        ],
        'account_id' => [
          'name' => 'account_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Account'),
          'description' => E::ts('FK to civicrm_o8_fund_account'),
          'where' => 'civicrm_o8_fund_transaction.account_id',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'FKClassName' => 'CRM_Funds_DAO_FundAccount',
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Account"),
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_o8_fund_account',
            'keyColumn' => 'id',
            'labelColumn' => 'title',
          ],
          'add' => NULL,
        ],
        'created_at' => [
          'name' => 'created_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Transaction Created Date'),
          'description' => E::ts('Date and time the transaction was created'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund_transaction.created_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'add' => NULL,
        ],
        'created_by' => [
          'name' => 'created_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Fund Created Contact'),
          'description' => E::ts('FK to Created Contact'),
          'where' => 'civicrm_o8_fund_transaction.created_by',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'modified_at' => [
          'name' => 'modified_at',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Transaction Modified Date'),
          'description' => E::ts('Date and time the transaction was modified'),
          'required' => FALSE,
          'where' => 'civicrm_o8_fund_transaction.modified_at',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
          'localizable' => 0,
          'add' => NULL,
        ],
        'modified_by' => [
          'name' => 'modified_by',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Fund Modified Contact'),
          'description' => E::ts('FK to Modified Contact'),
          'where' => 'civicrm_o8_fund_transaction.modified_by',
          'table_name' => 'civicrm_o8_fund_transaction',
          'entity' => 'FundTransaction',
          'bao' => 'CRM_Funds_DAO_FundTransaction',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_fund_transaction', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_fund_transaction', $prefix, []);
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
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
