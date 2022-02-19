<?php
use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Form_Report_FundsSummary extends CRM_Report_Form {

    protected $_charts = [
    ];

    /**
     * To what frequency group-by a date column
     *
     * @var array
     */
    protected $_groupByDateFreq = [
        'MONTH' => 'Month',
        'YEARWEEK' => 'Week',
        'DATE' => 'Day',
        'QUARTER' => 'Quarter',
        'YEAR' => 'Year',
        'FISCALYEAR' => 'Fiscal Year',
    ];

    /**
     * This report has been optimised for group filtering.
     *
     * @var bool
     * @see https://issues.civicrm.org/jira/browse/CRM-19170
     */
    protected $groupFilterNotOptimised = FALSE;

    /**
     * Use the generic (but flawed) handling to implement full group by.
     *
     * Note that because we are calling the parent group by function we set this to FALSE.
     * The parent group by function adds things to the group by in order to make the mysql pass
     * but can create incorrect results in the process.
     *
     * @var bool
     */
    public $optimisedForOnlyFullGroupBy = FALSE;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->_columns = [
                'civicrm_contact' => [
                    'dao' => 'CRM_Contact_DAO_Contact',
                    'fields' => array_merge(
                        $this->getBasicContactFields(),
                        [
                            'sort_name' => [
                                'title' => ts('Contact Name'),
                                'no_repeat' => TRUE,
                            ],
                        ]
                    ),
                    'filters' => $this->getBasicContactFilters(['deceased' => NULL]),
                    'grouping' => 'contact-fields',
                    'group_bys' => [
                        'id' => ['title' => ts('Contact ID')],
                        'sort_name' => [
                            'title' => ts('Contact Name'),
                        ],
                    ],
                ],
                'civicrm_o8_fund' => [
                    'dao' => 'CRM_Funds_DAO_Fund',
                    //'bao'           => 'CRM_Contribute_BAO_Fund',
                    'fields' => [
                        'amount' => [
                            'title' => ts('Fund Amount Stats'),
                            'default' => TRUE,
                            'statistics' => [
                                'count' => ts('Funds'),
                                'sum' => ts('Fund Aggregate'),
                                'avg' => ts('Fund Avg'),
                            ],
                        ],
                    ],
                    'grouping' => 'contri-fields',
                    'filters' => [
                        'start_date' => ['operatorType' => CRM_Report_Form::OP_DATE],
                        'end_date' => ['operatorType' => CRM_Report_Form::OP_DATE],
                        'amount' => [
                            'title' => ts('Fund Amount'),
                        ],
                        'total_sum' => [
                            'title' => ts('Fund Aggregate'),
                            'type' => CRM_Report_Form::OP_INT,
                            'dbAlias' => 'civicrm_fund_amount_sum',
                            'having' => TRUE,
                        ],
                        'total_count' => [
                            'title' => ts('Fund Count'),
                            'type' => CRM_Report_Form::OP_INT,
                            'dbAlias' => 'civicrm_fund_amount_count',
                            'having' => TRUE,
                        ],
                        'total_avg' => [
                            'title' => ts('Fund Avg'),
                            'type' => CRM_Report_Form::OP_INT,
                            'dbAlias' => 'civicrm_fund_amount_avg',
                            'having' => TRUE,
                        ],
                    ],
                    'group_bys' => [
                        'start_date' => [
                            'frequency' => TRUE,
                            'default' => TRUE,
                            'chart' => TRUE,
                        ],
                    ],
                ],

            ];



       parent::__construct();
    }

    /**
     * Set select clause.
     */
    public function select() {
        $select = [];
        $this->_columnHeaders = [];
        foreach ($this->_columns as $tableName => $table) {
            if (array_key_exists('group_bys', $table)) {
                foreach ($table['group_bys'] as $fieldName => $field) {
                    if (!empty($this->_params['group_bys'][$fieldName])) {
                        switch (CRM_Utils_Array::value($fieldName, $this->_params['group_bys_freq'])) {
                            case 'YEARWEEK':
                                $select[] = "DATE_SUB({$field['dbAlias']}, INTERVAL WEEKDAY({$field['dbAlias']}) DAY) AS {$tableName}_{$fieldName}_start";
                                $select[] = "YEARWEEK({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "WEEKOFYEAR({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Week Beginning');
                                break;

                            case 'YEAR':
                                $select[] = "MAKEDATE(YEAR({$field['dbAlias']}), 1)  AS {$tableName}_{$fieldName}_start";
                                $select[] = "YEAR({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "YEAR({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Year Beginning');
                                break;

                            case 'FISCALYEAR':
                                $config = CRM_Core_Config::singleton();
                                $fy = $config->fiscalYearStart;
                                $fiscal = self::fiscalYearOffset($field['dbAlias']);

                                $select[] = "DATE_ADD(MAKEDATE({$fiscal}, 1), INTERVAL ({$fy['M']})-1 MONTH) AS {$tableName}_{$fieldName}_start";
                                $select[] = "{$fiscal} AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "{$fiscal} AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Fiscal Year Beginning');
                                break;

                            case 'MONTH':
                                $select[] = "DATE_SUB({$field['dbAlias']}, INTERVAL (DAYOFMONTH({$field['dbAlias']})-1) DAY) as {$tableName}_{$fieldName}_start";
                                $select[] = "MONTH({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "MONTHNAME({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Month Beginning');
                                break;

                            case 'DATE':
                                $select[] = "DATE({$field['dbAlias']}) as {$tableName}_{$fieldName}_start";
                                $field['title'] = ts('Date');
                                break;

                            case 'QUARTER':
                                $select[] = "STR_TO_DATE(CONCAT( 3 * QUARTER( {$field['dbAlias']} ) -2 , '/', '1', '/', YEAR( {$field['dbAlias']} ) ), '%m/%d/%Y') AS {$tableName}_{$fieldName}_start";
                                $select[] = "QUARTER({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "QUARTER({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = 'Quarter';
                                break;
                        }
                        if (!empty($this->_params['group_bys_freq'][$fieldName])) {
                            $this->_interval = $this->_params['group_bys_freq'][$fieldName];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_start"]['title'] = $field['title'];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_start"]['type'] = $field['type'];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_start"]['group_by'] = $this->_params['group_bys_freq'][$fieldName];

                            // just to make sure these values are transferred to rows.
                            // since we need that for calculation purpose,
                            // e.g making subtotals look nicer or graphs
                            $this->_columnHeaders["{$tableName}_{$fieldName}_interval"] = ['no_display' => TRUE];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_subtotal"] = ['no_display' => TRUE];
                        }
                    }
                }
            }

            if (array_key_exists('fields', $table)) {
                foreach ($table['fields'] as $fieldName => $field) {
                    if (!empty($field['required']) ||
                        !empty($this->_params['fields'][$fieldName])
                    ) {
                        // only include statistics columns if set
                        if (!empty($field['statistics'])) {
                            foreach ($field['statistics'] as $stat => $label) {
                                $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['title'] = $label;
                                $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['type'] = $field['type'];
                                $this->_statFields[] = "{$tableName}_{$fieldName}_{$stat}";
                                switch (strtolower($stat)) {
                                    case 'sum':
                                        $select[] = "SUM({$field['dbAlias']}) as {$tableName}_{$fieldName}_{$stat}";
                                        break;

                                    case 'count':
                                        $select[] = "COUNT({$field['dbAlias']}) as {$tableName}_{$fieldName}_{$stat}";
                                        $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['type'] = CRM_Utils_Type::T_INT;
                                        break;

                                    case 'avg':
                                        $select[] = "ROUND(AVG({$field['dbAlias']}),2) as {$tableName}_{$fieldName}_{$stat}";
                                        break;
                                }
                            }
                        }
                        else {
                            $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
                            $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = $field['type'] ?? NULL;
                            $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'] ?? NULL;
                        }
                    }
                }
            }
        }

        $this->_selectClauses = $select;
        $this->_select = "SELECT " . implode(', ', $select) . " ";
    }

    /**
     * Set form rules.
     *
     * @param array $fields
     * @param array $files
     * @param CRM_Report_Form_Contribute_Summary $self
     *
     * @return array
     */
    public static function formRule($fields, $files, $self) {
        // Check for searching combination of display columns and
        // grouping criteria
        $ignoreFields = ['amount', 'sort_name'];
        $errors = $self->customDataFormRule($fields, $ignoreFields);

        if (empty($fields['fields']['amount'])) {
            foreach ([
                         'count_value',
                         'sum_value',
                         'avg_value',
                     ] as $val) {
                if (!empty($fields[$val])) {
                    $errors[$val] = ts("Please select the Amount Statistics");
                }
            }
        }

        return $errors;
    }

    /**
     * Set from clause.
     *
     * @param string $entity
     *
     * @todo fix function signature to match parent. Remove hacky passing of $entity
     * to acheive unclear results.
     */
    public function from($entity = NULL) {

        $this->setFromBase('civicrm_contact');

        $this->_from .= "
             INNER JOIN civicrm_o8_fund   {$this->_aliases['civicrm_o8_fund']}
                     ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_o8_fund']}.contact_id 
             ";

        //for contribution batches

    }

    /**
     * Set group by clause.
     */
    public function groupBy() {
        parent::groupBy();

        $isGroupByFrequency = !empty($this->_params['group_bys_freq']);

        if (!empty($this->_params['group_bys']) &&
            is_array($this->_params['group_bys'])
        ) {

            if (!empty($this->_statFields) &&
                (($isGroupByFrequency && count($this->_groupByArray) <= 1) || (!$isGroupByFrequency)) &&
                !$this->_having
            ) {
                $this->_rollup = " WITH ROLLUP";
            }
            $groupBy = [];
            foreach ($this->_groupByArray as $key => $val) {
                if (strpos($val, ';;') !== FALSE) {
                    $groupBy = array_merge($groupBy, explode(';;', $val));
                }
                else {
                    $groupBy[] = $this->_groupByArray[$key];
                }
            }
            $this->_groupBy = "GROUP BY " . implode(', ', $groupBy);
        }
        else {
            $this->_groupBy = "GROUP BY {$this->_aliases['civicrm_contact']}.id";
        }
        $this->_groupBy .= $this->_rollup;
    }

    /**
     * Store having clauses as an array.
     */

    /**
     * Set statistics.
     *
     * @param array $rows
     *
     * @return array
     *
     * @throws \CRM_Core_Exception
     */
    public function statistics(&$rows) {
        $statistics = parent::statistics($rows);

        $group = ' GROUP BY ' . implode(', ', $this->_groupByArray);

        $this->from();
        $this->customDataFrom();

        // Ensure that Extensions that modify the from statement in the sql also modify it in the statistics.
        CRM_Utils_Hook::alterReportVar('sql', $this, $this);

        $contriQuery = "
      COUNT({$this->_aliases['civicrm_o8_fund']}.amount )        as civicrm_o8_fund_amount_count,
      SUM({$this->_aliases['civicrm_o8_fund']}.amount )          as civicrm_o8_fund_amount_sum,
      ROUND(AVG({$this->_aliases['civicrm_o8_fund']}.amount), 2) as civicrm_o8_fund_amount_avg
      {$this->_from} {$this->_where}
    ";


        $contriSQL = "SELECT {$contriQuery} {$group} {$this->_having}";
        $contriDAO = CRM_Core_DAO::executeQuery($contriSQL);
        $this->addToDeveloperTab($contriSQL);
        $currencies = $currAmount = $currAverage = $currCount = [];
        $totalAmount = $average = $mode = $median = [];
        $softTotalAmount = $softAverage = $averageCount = $averageSoftCount = [];
        $softCount = $count = 0;
        while ($contriDAO->fetch()) {
            if (!isset($currAmount[$contriDAO->currency])) {
                $currAmount[$contriDAO->currency] = 0;
            }
            if (!isset($currCount[$contriDAO->currency])) {
                $currCount[$contriDAO->currency] = 0;
            }
            if (!isset($currAverage[$contriDAO->currency])) {
                $currAverage[$contriDAO->currency] = 0;
            }
            if (!isset($averageCount[$contriDAO->currency])) {
                $averageCount[$contriDAO->currency] = 0;
            }
            $currAmount[$contriDAO->currency] += $contriDAO->civicrm_o8_fund_amount_sum;
            $currCount[$contriDAO->currency] += $contriDAO->civicrm_o8_fund_amount_count;
            $currAverage[$contriDAO->currency] += $contriDAO->civicrm_o8_fund_amount_avg;
            $averageCount[$contriDAO->currency]++;
            $count += $contriDAO->civicrm_o8_fund_amount_count;

            if (!in_array($contriDAO->currency, $currencies)) {
                $currencies[] = $contriDAO->currency;
            }
        }

        foreach ($currencies as $currency) {
            $totalAmount[] = CRM_Utils_Money::format($currAmount[$currency], $currency) .
                " (" . $currCount[$currency] . ")";
            $average[] = CRM_Utils_Money::format(($currAverage[$currency] / $averageCount[$currency]), $currency);
        }

        $groupBy = "\n{$group}, {$this->_aliases['civicrm_o8_fund']}.amount";
        $orderBy = "\nORDER BY civicrm_o8_fund_amount_count DESC";
        $modeSQL = "SELECT MAX(civicrm_o8_fund_amount_count) as civicrm_o8_fund_amount_count,
      SUBSTRING_INDEX(GROUP_CONCAT(amount ORDER BY mode.civicrm_o8_fund_amount_count DESC SEPARATOR ';'), ';', 1) as amount
      FROM (SELECT {$this->_aliases['civicrm_o8_fund']}.amount as amount,
    {$contriQuery} {$groupBy} {$orderBy}) as mode";

//        $mode = $this->calculateMode($modeSQL);
//        $median = $this->calculateMedian();

        $currencies = $currSoftAmount = $currSoftAverage = $currSoftCount = [];

        if (1) {
            $statistics['counts']['amount'] = [
                'title' => ts('Total Amount'),
                'value' => implode(',  ', $totalAmount),
                'type' => CRM_Utils_Type::T_STRING,
            ];
            $statistics['counts']['count'] = [
                'title' => ts('Total Funds'),
                'value' => $count,
            ];
            $statistics['counts']['avg'] = [
                'title' => ts('Average'),
                'value' => implode(',  ', $average),
                'type' => CRM_Utils_Type::T_STRING,
            ];
//            $statistics['counts']['mode'] = [
//                'title' => ts('Mode'),
//                'value' => implode(',  ', $mode),
//                'type' => CRM_Utils_Type::T_STRING,
//            ];
//            $statistics['counts']['median'] = [
//                'title' => ts('Median'),
//                'value' => implode(',  ', $median),
//                'type' => CRM_Utils_Type::T_STRING,
//            ];
        }
        return $statistics;
    }

    /**
     * Build chart.
     *
     * @param array $original_rows
     */


    /**
     * Alter display of rows.
     *
     * Iterate through the rows retrieved via SQL and make changes for display purposes,
     * such as rendering contacts as links.
     *
     * @param array $rows
     *   Rows generated by SQL, with an array for each row.
     */
    public function alterDisplay(&$rows) {
        $entryFound = FALSE;
        foreach ($rows as $rowNum => $row) {
            // make count columns point to detail report
            if (!empty($this->_params['group_bys']['start_date']) &&
                !empty($row['civicrm_o8_fund_date']) &&
                CRM_Utils_Array::value('civicrm_o8_fund_start_date', $row) &&
                !empty($row['civicrm_o8_fund_start_date_subtotal'])
            ) {

                $dateStart = CRM_Utils_Date::customFormat($row['civicrm_o8_fund_start_date'], '%Y%m%d');
                $endDate = new DateTime($dateStart);
                $dateEnd = [];

                list($dateEnd['Y'], $dateEnd['M'], $dateEnd['d']) = explode(':', $endDate->format('Y:m:d'));

                switch (strtolower($this->_params['group_bys_freq']['start_date'])) {
                    case 'month':
                        $dateEnd = date("Ymd", mktime(0, 0, 0, $dateEnd['M'] + 1,
                            $dateEnd['d'] - 1, $dateEnd['Y']
                        ));
                        break;

                    case 'year':
                        $dateEnd = date("Ymd", mktime(0, 0, 0, $dateEnd['M'],
                            $dateEnd['d'] - 1, $dateEnd['Y'] + 1
                        ));
                        break;

                    case 'fiscalyear':
                        $dateEnd = date("Ymd", mktime(0, 0, 0, $dateEnd['M'],
                            $dateEnd['d'] - 1, $dateEnd['Y'] + 1
                        ));
                        break;

                    case 'yearweek':
                        $dateEnd = date("Ymd", mktime(0, 0, 0, $dateEnd['M'],
                            $dateEnd['d'] + 6, $dateEnd['Y']
                        ));
                        break;

                    case 'quarter':
                        $dateEnd = date("Ymd", mktime(0, 0, 0, $dateEnd['M'] + 3,
                            $dateEnd['d'] - 1, $dateEnd['Y']
                        ));
                        break;
                }
//                $url = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/contact/view',
//                        ['reset' => 1, 'cid' => $dao->contact_id]) . '">' .
//                    $dao->organization_name . '</a>';

//                $url = CRM_Report_Utils_Report::getNextUrl('contribute/detail',
//                    "reset=1&force=1&receive_date_from={$dateStart}&receive_date_to={$dateEnd}",
//                    $this->_absoluteUrl,
//                    $this->_id,
//                    $this->_drilldownReport
//                );
                $url = "";
                $rows[$rowNum]['civicrm_o8_fund_start_date_link'] = $url;
                $rows[$rowNum]['civicrm_o8_fund_start_date_hover'] = ts('List all transaction(s) for this date unit.');
                $entryFound = TRUE;
            }

            // make subtotals look nicer
            if (array_key_exists('civicrm_o8_fund_start_date_subtotal', $row) &&
                !$row['civicrm_o8_fund_start_date_subtotal']
            ) {
                $this->fixSubTotalDisplay($rows[$rowNum], $this->_statFields);
                $entryFound = TRUE;
            }

            // convert display name to links
            if (array_key_exists('civicrm_contact_sort_name', $row) &&
                array_key_exists('civicrm_contact_id', $row)
            ) {
                $url = CRM_Report_Utils_Report::getNextUrl('contribute/detail',
                    'reset=1&force=1&id_op=eq&id_value=' . $row['civicrm_contact_id'],
                    $this->_absoluteUrl, $this->_id, $this->_drilldownReport
                );
                $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
                $rows[$rowNum]['civicrm_contact_sort_name_hover'] = ts("Lists detailed contribution(s) for this record.");
                $entryFound = TRUE;
            }



            // If using campaigns, convert campaign_id to campaign title


            // convert batch id to batch title
            if (!empty($row['civicrm_batch_batch_id']) && !in_array('Subtotal', $rows[$rowNum])) {
                $rows[$rowNum]['civicrm_batch_batch_id'] = $this->getLabels($row['civicrm_batch_batch_id'], 'CRM_Batch_BAO_EntityBatch', 'batch_id');
                $entryFound = TRUE;
            }

            $entryFound = $this->alterDisplayAddressFields($row, $rows, $rowNum, 'contribute/detail', 'List all contribution(s) for this ') ? TRUE : $entryFound;
            $entryFound = $this->alterDisplayContactFields($row, $rows, $rowNum, 'contribute/detail', 'List all contribution(s) for this ') ? TRUE : $entryFound;

            // skip looking further in rows, if first row itself doesn't
            // have the column we need
            if (!$entryFound) {
                break;
            }
        }
    }

    /**
     * Calculate mode.
     *
     * Note this is a slow query. Alternative is extended reports.
     *
     * @param string $sql
     * @return array|null
     */
    protected function calculateMode($sql) {
        $mode = [];
        $modeDAO = CRM_Core_DAO::executeQuery($sql);
        while ($modeDAO->fetch()) {
            if ($modeDAO->civicrm_o8_fund_amount_count > 1) {
                $mode[] = CRM_Utils_Money::format($modeDAO->amount, $modeDAO->currency);
            }
            else {
                $mode[] = 'N/A';
            }
        }
        return $mode;
    }

    /**
     * Calculate mode.
     *
     * Note this is a slow query. Alternative is extended reports.
     *
     * @return array|null
     */
    protected function calculateMedian() {
        $sql = "{$this->_from} {$this->_where}";
        $currencies = CRM_Core_OptionGroup::values('currencies_enabled');
        $median = [];
        foreach ($currencies as $currency => $val) {
            $midValue = 0;
            $where = "AND {$this->_aliases['civicrm_contribution']}.currency = '{$currency}'";
            $rowCount = CRM_Core_DAO::singleValueQuery("SELECT count(*) as count {$sql} {$where}");

            $even = FALSE;
            $offset = 1;
            $medianRow = floor($rowCount / 2);
            if ($rowCount % 2 == 0 && !empty($medianRow)) {
                $even = TRUE;
                $offset++;
                $medianRow--;
            }

            $medianValue = "SELECT {$this->_aliases['civicrm_contribution']}.total_amount as median
             {$sql} {$where}
             ORDER BY median LIMIT {$medianRow},{$offset}";
            $medianValDAO = CRM_Core_DAO::executeQuery($medianValue);
            while ($medianValDAO->fetch()) {
                if ($even) {
                    $midValue = $midValue + $medianValDAO->median;
                }
                else {
                    $median[] = CRM_Utils_Money::format($medianValDAO->median, $currency);
                }
            }
            if ($even) {
                $midValue = $midValue / 2;
                $median[] = CRM_Utils_Money::format($midValue, $currency);
            }
        }
        return $median;
    }

}
