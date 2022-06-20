<?php
use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Form_Report_AccountsDetail extends CRM_Report_Form
{

    protected $_addressField = FALSE;

    protected $_emailField = FALSE;

    protected $_summary = NULL;

//  protected $_customGroupExtends = array('Membership');
    protected $_customGroupGroupBy = FALSE;

    function __construct()
    {
        $this->_columns = array(
            'civicrm_o8_fund_account' => array(
                'dao' => 'CRM_Funds_DAO_FundAccount',
                'fields' => [
                    'ac_id' => [
                        'name' => 'id',
                        'title' => E::ts('Account ID'),
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Unique Account ID'),
                        'required' => TRUE,
                    ],
                    'ac_code' => [
                        'name' => 'code',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Account Code'),
                        'description' => E::ts('Account Code'),
                        'required' => TRUE,
                    ],
                    'type_id' => [
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Type ID'),
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                    'ac_name' => [
                        'name' => 'name',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Account Name'),
                        'description' => E::ts('Account Name'),
                        'required' => TRUE,
                    ],
                    'ac_description' => [
                        'name' => 'description',
                        'type' => CRM_Utils_Type::T_TEXT,
                        'title' => E::ts('Account Description'),
                        'description' => E::ts('Optional verbose description.'),
                    ],
                    'created_at' => [
                        'type' => CRM_Utils_Type::T_TIMESTAMP,
                        'title' => E::ts('Account Created Date'),
                        'description' => E::ts('Date and time the fund was created'),
                    ],
                    'created_by' => [
                        'no_display' => TRUE,
                        'name' => 'created_by',
                        'type' => CRM_Utils_Type::T_INT,
                        'title' => E::ts('Account Created Contact'),
                        'description' => E::ts('FK to Created Contact'),
                    ],
                    'modified_at' => [
                        'name' => 'modified_at',
                        'type' => CRM_Utils_Type::T_TIMESTAMP,
                        'title' => E::ts('Account Modified Date'),
                        'description' => E::ts('Date and time the fund was modified'),
                    ],
                    'modified_by' => [
                        'no_display' => TRUE,
                        'name' => 'modified_by',
                        'type' => CRM_Utils_Type::T_INT,
                        'title' => E::ts('Account Modified Contact'),
                        'description' => E::ts('FK to Modified Contact'),
                    ]
                ],
                'filters' => array(
                    'name' => array(
                        'title' => E::ts('Account Name'),
                        'operator' => 'like',
                    ),
                    'code' => array(
                        'title' => E::ts('Account Code'),
                        'operator' => 'like',
                    ),
                    'description' => array(
                        'title' => E::ts('Account Description'),
                        'operator' => 'like',
                    ),
                    'created_at' => ['title' => ts('Account Created At'),
                        'operatorType' => CRM_Report_Form::OP_DATE],
                    'modified_at' => ['title' => ts('Account Created At'),
                        'operatorType' => CRM_Report_Form::OP_DATE],
                ),
                'order_bys' => [
                    'id' => [
//                        'name' => 'id',
                        'title' => ts('Account ID'),
                        'default' => TRUE,
                        'default_weight' => '1',
                        'default_order' => 'ASC',
                    ],
                    'code' => [
                        'title' => ts('Account Code'),
                    ],
                    'created_at' => [
                        'title' => ts('Account Created At'),
                    ],
                    'modified_at' => [
                        'title' => ts('Account Modified At'),
                    ],
                ],

                'grouping' => 'account-fields',
            ),
            'civicrm_created' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => array(
                    'created_sort_name' => array(
                        'name' => 'sort_name',
                        'title' => E::ts('Created By (Contact)'),

                    ),
                    'id' => array(
                        'name' => 'id',
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ),
                ),
                'filters' => array(
                    'created_sort_name' => array(
                        'name' => 'sort_name',
                        'title' => E::ts('Created By'),
                        'operator' => 'like',
                    ),
                    'created_id' => array(
                        'no_display' => TRUE,
                    ),
                ),
                'grouping' => 'account-fields',
            ),
            'civicrm_modified' => array(
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => array(
                    'modified_sort_name' => array(
                        'name' => 'sort_name',
                        'title' => E::ts('Modified By (Contact)'),
                    ),
                    'id' => array(
                        'name' => 'id',
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ),
                ),
                'filters' => array(
                    'modified_sort_name' => array(
                        'name' => 'sort_name',
                        'title' => E::ts('Modified By'),
                        'operator' => 'like',
                    ),
                    'id' => array(
                        'no_display' => TRUE,
                    ),
                ),
                'grouping' => 'account-fields',
            ),
        );
//    $this->_groupFilter = TRUE;
//    $this->_tagFilter = TRUE;
        parent::__construct();
    }

    function preProcess()
    {
        $this->assign('reportTitle', E::ts('Funds Detail Report'));
        parent::preProcess();
    }

    function from()
    {
        $this->_from = NULL;

        $this->_from = "
         FROM  civicrm_o8_fund_account {$this->_aliases['civicrm_o8_fund_account']} {$this->_aclFrom}
               LEFT JOIN civicrm_contact {$this->_aliases['civicrm_created']}
                          ON {$this->_aliases['civicrm_created']}.id =
                             {$this->_aliases['civicrm_o8_fund_account']}.created_by
               LEFT JOIN civicrm_contact {$this->_aliases['civicrm_modified']}
                          ON {$this->_aliases['civicrm_modified']}.id =
                             {$this->_aliases['civicrm_o8_fund_account']}.modified_by
                             
                             "



        ;


//    $this->joinAddressFromContact();
//    $this->joinEmailFromContact();
    }

    /**
     * Add field specific select alterations.
     *
     * @param string $tableName
     * @param string $tableKey
     * @param string $fieldName
     * @param array $field
     *
     * @return string
     */
    function selectClause(&$tableName, $tableKey, &$fieldName, &$field)
    {
        return parent::selectClause($tableName, $tableKey, $fieldName, $field);
    }

    /**
     * Add field specific where alterations.
     *
     * This can be overridden in reports for special treatment of a field
     *
     * @param array $field Field specifications
     * @param string $op Query operator (not an exact match to sql)
     * @param mixed $value
     * @param float $min
     * @param float $max
     *
     * @return null|string
     */
    public function whereClause(&$field, $op, $value, $min, $max)
    {
        return parent::whereClause($field, $op, $value, $min, $max);
    }

    function alterDisplay(&$rows)
    {
        // custom code to alter rows
        $entryFound = FALSE;
        $checkList = array();
//        CRM_Core_Error::debug_var('rows', $rows);
        foreach ($rows as $rowNum => $row) {

            if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
                // not repeat contact display names if it matches with the one
                // in previous row
                $repeatFound = FALSE;
                foreach ($row as $colName => $colVal) {
                    if (CRM_Utils_Array::value($colName, $checkList) &&
                        is_array($checkList[$colName]) &&
                        in_array($colVal, $checkList[$colName])
                    ) {
                        $rows[$rowNum][$colName] = "";
                        $repeatFound = TRUE;
                    }
                    if (in_array($colName, $this->_noRepeats)) {
                        $checkList[$colName][] = $colVal;
                    }
                }
            }

            if (array_key_exists('civicrm_contact_sort_name', $row) &&
                $rows[$rowNum]['civicrm_contact_sort_name'] &&
                array_key_exists('civicrm_contact_id', $row)
            ) {
                $url = CRM_Utils_System::url("civicrm/contact/view",
                    'reset=1&cid=' . $row['civicrm_contact_id'],
                    $this->_absoluteUrl
                );
                $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
                $rows[$rowNum]['civicrm_contact_sort_name_hover'] = E::ts("View Contact Summary for this Contact.");
                $entryFound = TRUE;
            }

            if (!$entryFound) {
                break;
            }
        }
    }

}
