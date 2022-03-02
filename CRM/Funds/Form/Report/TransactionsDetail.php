<?php

use CRM_Funds_ExtensionUtil as E;


class CRM_Funds_Form_Report_TransactionsDetail extends CRM_Report_Form
{

    protected $_addressField = FALSE;

    protected $_emailField = FALSE;

    protected $_summary = NULL;

//  protected $_customGroupExtends = array('Membership');
    protected $_customGroupGroupBy = FALSE;

    function __construct()
    {
        $this->_columns = [
            'civicrm_o8_fund_transaction' => [
                'dao' => 'CRM_Funds_DAO_FundTransaction',
                'fields' => [
                    'tr_id' => [
                        'name' => 'id',
                        'title' => E::ts('Transaction ID'),
                        'type' => CRM_Utils_Type::T_INT,
                        'required' => TRUE,
                    ],
                    'fund_id' => [
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Fund ID'),
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                    'tr_date' => [
                        'name' => 'date',
                        'title' => E::ts('Transaction Date'),
                        'type' => CRM_Utils_Type::T_DATE,
                        'default' => TRUE],
                    'tr_amount' => [
                        'name' => 'amount',
                        'type' => CRM_Utils_Type::T_MONEY,
                        'title' => E::ts('Transaction Amount'),
                        'default' => TRUE,
                    ],
                    'tr_description' => [
                        'name' => 'description',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Transaction Description'),
                        'required' => TRUE,
                    ],
                    'tr_account_id' => [
                        'name' => 'account_id',
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Account ID'),
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                    'tr_status_id' => [
                        'name' => 'status_id',
                        'title' => E::ts('Status'),
                        'required' => TRUE,
                    ],
                    'tr_case_id' => [
                        'name' => 'case_id',
                        'title' => E::ts('Case'),
                        'required' => TRUE,
                    ],
                    'tr_contact_id_sub' => [
                        'name' => 'contact_id_sub',
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                    'tr_contact_id_app' => [
                        'name' => 'contact_id_app',
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                    'tr_sub_account_id' => [
                        'name' => 'sub_account_id',
                        'title' => E::ts('SubAccount'),
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('SubAccount'),
//                        'no_display' => TRUE,
//                        'required' => TRUE,
                    ],
                ],
                'filters' => [
                    'tr_date' => [
                        'name' => 'date',
                        'title' => E::ts('Transaction Date'),
                        'operatorType' => CRM_Report_Form::OP_DATE],
                    'tr_description' => [
                        'name' => 'description',
                        'title' => E::ts('Transaction Description'),
                        'operator' => 'like',
                    ],
                    'tr_amount' => [
                        'name' => 'amount',
                        'title' => E::ts('Transaction Amount'),
                    ],
                    'tr_status_id' => [
                        'name' => 'status_id',
                        'title' => E::ts('Transaction Status'),
                        'operatorType' => CRM_Report_Form::OP_MULTISELECT,
                        'options' => CRM_Core_PseudoConstant::get('CRM_Funds_DAO_FundTransaction', 'status_id'),
                    ],
                ],
                'order_bys' => [
                    'tr_id' => [
                        'name' => 'id',
                        'title' => ts('Transaction ID'),
                        'default' => TRUE,
                        'default_weight' => '1',
                        'default_order' => 'ASC',
                    ],
                    'tr_date' => [
                        'name' => 'date',
                        'title' => ts('Transaction Date'),
                    ],
                    'amount' => [
                        'title' => ts('Transaction Amount'),
                    ],
                    'created_at' => [
                        'title' => ts('Transaction Created At'),
                    ],
                    'modified_at' => [
                        'title' => ts('Transaction Modified At'),
                    ],
                ],

                'grouping' => 'transaction-fields',
            ],
            'civicrm_o8_fund' => [
                'dao' => 'CRM_Funds_DAO_Fund',
                'fields' => [
                    'id' => [
                        'name' => 'id',
                        'title' => E::ts('Fund ID'),
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Unique Fund ID'),
//                        'required' => TRUE,
                    ],
                    'fund_code' => [
                        'name' => 'code',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Fund Code'),
                        'description' => E::ts('Fund Code'),
                        'required' => TRUE,
                    ],
                    'fund_contact_id' => [
                        'name' => 'contact_id',
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Source Organisation (Contact)'),
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                    'fund_name' => [
                        'name' => 'name',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Fund Name'),
                        'description' => E::ts('Fund Name'),
                        'required' => TRUE,
                    ],
                    'target_cases' => [
                        'name' => 'target_cases',
                        'type' => CRM_Utils_Type::T_INT,
                        'title' => E::ts('Fund Target Cases'),
                        'description' => E::ts('Target Cases'),
//                        'default' => TRUE,
                    ],
                    'start_date' => [
                        'name' => 'start_date',
                        'type' => CRM_Utils_Type::T_DATE,
                        'title' => E::ts('Fund Start Date'),
                        'description' => E::ts('Fund Start Date'),
//                        'default' => TRUE,
                    ],
                    'end_date' => [
                        'name' => 'end_date',
                        'type' => CRM_Utils_Type::T_DATE,
                        'title' => E::ts('Fund End Date'),
                        'description' => E::ts('Fund End Date'),
//                        'default' => TRUE,

                    ],
                    'fund_description' => [
                        'name' => 'description',
                        'type' => CRM_Utils_Type::T_TEXT,
                        'title' => E::ts('Fund Description'),
                        'description' => E::ts('Optional verbose description of the fund.'),
                    ],
                    'fund_amount' => [
                        'name' => 'amount',
                        'type' => CRM_Utils_Type::T_MONEY,
                        'title' => E::ts('Fund Amount'),
                        'description' => E::ts('Starting Amount of the fund.'),
                        'default' => TRUE,
                    ],
                ],
                'filters' => [
                    'fund_name' => [
                        'name' => 'name',
                        'title' => E::ts('Fund Name'),
                        'operator' => 'like',
                    ],
                    'fund_code' => [
                        'name' => 'code',
                        'title' => E::ts('Fund Code'),
                        'operator' => 'like',
                    ],
                    'fund_description' => [
                        'name' => 'description',

                        'title' => E::ts('Fund Description'),
                        'operator' => 'like',
                    ],
                    'start_date' => [
                        'name' => 'start_date',
                        'title' => E::ts('Start Date'),
                        'operatorType' => CRM_Report_Form::OP_DATE],
                    'end_date' => [
                        'name' => 'end_date',
                        'title' => E::ts('End Date'),
                        'operatorType' => CRM_Report_Form::OP_DATE],
                    'fund_amount' => [
                        'name' => 'amount',
                        'title' => ts('Fund Amount'),
                    ],
                ],
                'order_bys' => [
                    'fund_code' => [
                        'name' => 'code',
                        'title' => ts('Fund Code'),
                        'default' => TRUE,
                        'default_weight' => '0',
                        'default_order' => 'ASC',
                    ],
                    'start_date' => [
                        'title' => ts('Fund Start Date'),
                    ],
                    'end_date' => [
                        'title' => ts('Fund End Date'),
                    ],
                    'target_cases' => [
                        'title' => ts('Target Cases'),
                    ],
                    'fund_amount' => [
                        'name' => 'amount',
                        'title' => ts('Amount'),
                    ],
                ],
                'grouping' => 'fund-fields',
            ],
            'civicrm_o8_fund_account' => [
                'dao' => 'CRM_Funds_DAO_FundAccount',
                'fields' => [
                    'ac_id' => [
                        'name' => 'id',
                        'title' => E::ts('Account ID'),
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Unique Account ID'),
//                        'required' => TRUE,
                    ],
                    'ac_code' => [
                        'name' => 'code',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Account Code'),
                        'description' => E::ts('Account Code'),
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
                ],
                'filters' => [
                    'ac_name' => [
                        'name' => 'name',
                        'title' => E::ts('Account Name'),
                        'operator' => 'like',
                    ],
                    'ac_code' => [
                        'name' => 'code',
                        'title' => E::ts('Account Code'),
                        'operator' => 'like',
                    ],
                    'ac_description' => [
                        'name' => 'description',
                        'title' => E::ts('Account Description'),
                        'operator' => 'like',
                    ],
                ],
                'order_bys' => [
                    'ac_id' => [
                        'name' => 'id',
                        'title' => ts('Account ID'),
                    ],
                    'ac_code' => [
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
            ],
            'civicrm_o8_fund_sub_account' => [
                'dao' => 'CRM_Funds_DAO_FundSubAccount',
                'fields' => [
                    'sac_id' => [
                        'name' => 'id',
                        'title' => E::ts('SubAccount ID'),
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Unique SubAccount ID'),
//                        'required' => TRUE,
                    ],
                    'sac_code' => [
                        'name' => 'code',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('SubAccount Code'),
                        'description' => E::ts('Account Code'),
                        'required' => TRUE,
                    ],
                    'sac_category_id' => [
                        'name' => 'fund_category_id',
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Category ID'),
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                    'sac_name' => [
                        'name' => 'name',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('SubAccount Name'),
                        'description' => E::ts('SubAccount Name'),
                        'required' => TRUE,
                    ],
                    'sac_description' => [
                        'name' => 'description',
                        'type' => CRM_Utils_Type::T_TEXT,
                        'title' => E::ts('SubAccount Description'),
                        'description' => E::ts('Optional verbose description.'),
                    ],
                ],
                'filters' => [
                    'sac_name' => [
                        'name' => 'name',
                        'title' => E::ts('SubAccount Name'),
                        'operator' => 'like',
                    ],
                    'sac_code' => [
                        'name' => 'code',
                        'title' => E::ts('SubAccount Code'),
                        'operator' => 'like',
                    ],
                    'sac_description' => [
                        'name' => 'description',
                        'title' => E::ts('SubAccount Description'),
                        'operator' => 'like',
                    ],
                ],
                'order_bys' => [
                    'sac_id' => [
                        'name' => 'id',
                        'title' => ts('SubAccount ID'),
                    ],
                    'sac_code' => [
                        'title' => ts('SubAccount Code'),
                    ],
                ],
                'grouping' => 'sub-account-fields',
            ],
            'civicrm_o8_fund_category' => [
                'dao' => 'CRM_Funds_DAO_FundCategory',
                'fields' => [
                    'cat_id' => [
                        'name' => 'id',
                        'title' => E::ts('Category ID'),
                        'type' => CRM_Utils_Type::T_INT,
//                        'required' => TRUE,
                    ],
                    'cat_code' => [
                        'name' => 'code',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Category Code'),
                        'required' => TRUE,
                    ],
                    'cat_name' => [
                        'name' => 'name',
                        'type' => CRM_Utils_Type::T_STRING,
                        'title' => E::ts('Category Name'),
                        'description' => E::ts('Category Name'),
                        'required' => TRUE,
                    ],
                    'cat_description' => [
                        'name' => 'description',
                        'type' => CRM_Utils_Type::T_TEXT,
                        'title' => E::ts('Category Description'),
                        'description' => E::ts('Optional verbose description.'),
                    ],
                ],
                'filters' => [
                    'cat_name' => [
                        'name' => 'name',
                        'title' => E::ts('Category Name'),
                        'operator' => 'like',
                    ],
                    'cat_code' => [
                        'name' => 'code',
                        'title' => E::ts('Category Code'),
                        'operator' => 'like',
                    ],
                    'cat_description' => [
                        'name' => 'description',
                        'title' => E::ts('Category Description'),
                        'operator' => 'like',
                    ],
                ],
                'order_bys' => [
                    'cat_id' => [
                        'name' => 'id',
                        'title' => ts('Category ID'),
                    ],
                    'cat_code' => [
                        'title' => ts('Category Code'),
                    ],
                ],
                'grouping' => 'category-fields',
            ],
            'civicrm_o8_case_contact' => [
                'dao' => 'CRM_Case_DAO_CaseContact',
                'fields' => [
                    'cc_id' => [
                        'name' => 'id',
                        'title' => E::ts('CaseContact ID'),
                        'type' => CRM_Utils_Type::T_INT,
                        'no_display' => TRUE,
//                        'required' => TRUE,
                    ],
                    'cc_case_id' => [
                        'name' => 'code',
                        'type' => CRM_Utils_Type::T_INT,
                        'title' => E::ts('Case'),
                        'no_display' => TRUE,
//                        'required' => TRUE,
                    ],
                    'cc_contact_id' => [
                        'type' => CRM_Utils_Type::T_INT,
                        'description' => E::ts('Case Contact'),
                        'no_display' => TRUE,
//                        'required' => TRUE,
                    ],
                ],
                'grouping' => 'case-fields',
            ],
            'civicrm_o8_fund_case_contact' => [
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => [
                    'cc_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Contact (Case Worker)'),
                    ],
                    'id' => [
                        'name' => 'id',
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                ],
                'filters' => [
                    'cc_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Contact (Case Worker)'),
                        'operator' => 'like',
                    ],
                    'id' => [
                        'no_display' => TRUE,
                    ],
                ],
                'grouping' => 'case-fields',
            ],
            'civicrm_sub' => [
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => [
                    'sub_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Contact (Social Worker)'),

                    ],
                    'id' => [
                        'name' => 'id',
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                ],
                'filters' => [
                    'sub_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Contact (Social Worker)'),
                        'operator' => 'like',
                    ],
                    'sub_id' => [
                        'no_display' => TRUE,
                    ],
                ],
                'grouping' => 'transaction-fields',
            ],
            'civicrm_app' => [
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => [
                    'app_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Contact (Approver)'),
                    ],
                    'id' => [
                        'name' => 'id',
                        'no_display' => TRUE,
                        'required' => TRUE,
                    ],
                ],
                'filters' => [
                    'app_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Contact (Approver)'),
                        'operator' => 'like',
                    ],
                    'id' => [
                        'no_display' => TRUE,
                    ],
                ],
                'grouping' => 'transaction-fields',
            ],
        ];
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
         FROM civicrm_o8_fund_transaction {$this->_aliases['civicrm_o8_fund_transaction']}
               LEFT JOIN civicrm_o8_fund_account {$this->_aliases['civicrm_o8_fund_account']}
                          ON {$this->_aliases['civicrm_o8_fund_transaction']}.account_id =
                             {$this->_aliases['civicrm_o8_fund_account']}.id
               LEFT JOIN  civicrm_o8_fund {$this->_aliases['civicrm_o8_fund']} {$this->_aclFrom}
                          ON {$this->_aliases['civicrm_o8_fund_transaction']}.fund_id =
                             {$this->_aliases['civicrm_o8_fund']}.id
               LEFT JOIN civicrm_o8_fund_sub_account {$this->_aliases['civicrm_o8_fund_sub_account']}
                          ON {$this->_aliases['civicrm_o8_fund_transaction']}.sub_account_id =
                             {$this->_aliases['civicrm_o8_fund_sub_account']}.id
               LEFT JOIN civicrm_o8_fund_category {$this->_aliases['civicrm_o8_fund_category']}
                          ON {$this->_aliases['civicrm_o8_fund_sub_account']}.fund_category_id =
                             {$this->_aliases['civicrm_o8_fund_category']}.id
               LEFT JOIN civicrm_case_contact {$this->_aliases['civicrm_o8_case_contact']}
                          ON {$this->_aliases['civicrm_o8_fund_transaction']}.case_id =
                             {$this->_aliases['civicrm_o8_case_contact']}.case_id
               LEFT JOIN civicrm_contact {$this->_aliases['civicrm_o8_fund_case_contact']}
                          ON {$this->_aliases['civicrm_o8_case_contact']}.contact_id =
                             {$this->_aliases['civicrm_o8_fund_case_contact']}.id
               LEFT JOIN civicrm_contact {$this->_aliases['civicrm_sub']}
                          ON {$this->_aliases['civicrm_o8_fund_transaction']}.contact_id_sub =
                             {$this->_aliases['civicrm_sub']}.id
               LEFT JOIN civicrm_contact {$this->_aliases['civicrm_app']}
                          ON {$this->_aliases['civicrm_o8_fund_transaction']}.contact_id_app =
                             {$this->_aliases['civicrm_app']}.id
                             
                             ";


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
        $checkList = [];
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

            if (array_key_exists('civicrm_o8_fund_transaction_tr_status_id', $row)) {
                if ($value = $row['civicrm_o8_fund_transaction_tr_status_id']) {
                    $rows[$rowNum]['civicrm_o8_fund_transaction_tr_status_id']
                        = CRM_Core_PseudoConstant::getLabel("CRM_Funds_DAO_FundTransaction", "status_id", $value);
                }
                $entryFound = TRUE;
            }

            if (array_key_exists('civicrm_o8_fund_transaction_tr_case_id', $row)) {
                if ($value = $row['civicrm_o8_fund_transaction_tr_case_id']) {
                    $rows[$rowNum]['civicrm_o8_fund_transaction_tr_case_id']
                        = CRM_Core_DAO::getFieldValue('CRM_Case_BAO_Case', $value, 'subject');
                }
                $entryFound = TRUE;
            }

            if (array_key_exists('civicrm_o8_fund_transaction_tr_sub_account_id', $row)) {
                if ($value = $row['civicrm_o8_fund_transaction_tr_sub_account_id']) {
                    $rows[$rowNum]['civicrm_o8_fund_transaction_tr_sub_account_id']
                        = CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundSubAccount', $value, 'code') . ': '
                        . CRM_Core_DAO::getFieldValue('CRM_Funds_BAO_FundSubAccount', $value, 'name');
                }
                $entryFound = TRUE;
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
