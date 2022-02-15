<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_SearchTransaction extends CRM_Core_Page
{
    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Search Transactions'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $transactions_source_url = CRM_Utils_System::url('civicrm/fund/transaction_ajax', $urlQry, FALSE, NULL, FALSE);
//        $transactions_source_url = "";
        $sourceUrl['transactions_source_url'] = $transactions_source_url;
        $this->assign('useAjax', true);
        CRM_Core_Resources::singleton()->addVars('source_url', $sourceUrl);

        // controller form for ajax search
        $controller_data = new CRM_Core_Controller_Simple(
            'CRM_Funds_Form_CommonSearch',
            ts('Funds Filter'),
            NULL,
            FALSE, FALSE, TRUE
        );
        $controller_data->setEmbedded(TRUE);
        $controller_data->run();

        parent::run();
    }


    public function getAjax()
    {

//        CRM_Core_Error::debug_var('transaction_request', $_REQUEST);
//        CRM_Core_Error::debug_var('transaction_post', $_POST);
        /*
         *
            aoData.push({ "name": "transaction_id",
                "value": $('#transaction_id').val() });
            aoData.push({ "name": "case_id",
                "value": $('#transaction_case_id').val() });
            aoData.push({ "name": "contact_id_app",
                "value": $('#contact_id_app').val() });
            aoData.push({ "name": "contact_id_sub",
                "value": $('#contact_id_sub').val() });
            aoData.push({ "name": "account_id",
                "value": $('#transaction_account_id').val() });
            aoData.push({ "name": "component_id",
                "value": $('#transaction_component_id').val() });
            aoData.push({ "name": "dateselect_from",
                "value": $('#transaction_dateselect_from').val() });
            aoData.push({ "name": "dateselect_to",
                "value": $('#transaction_dateselect_to').val() });
            aoData.push({ "name": "status_id",
                "value": $('#transaction_status_id').val() });
         */

        $transaction_id = CRM_Utils_Request::retrieveValue('transaction_id', 'String', null);

        $transaction_name = CRM_Utils_Request::retrieveValue('transaction_name', 'String', null);

        $case_id = CRM_Utils_Request::retrieveValue('case_id', 'CommaSeparatedIntegers', null);

        $account_id = CRM_Utils_Request::retrieveValue('account_id', 'CommaSeparatedIntegers', null);

        $component_id = CRM_Utils_Request::retrieveValue('component_id', 'CommaSeparatedIntegers', null);

        $contact_id_app = CRM_Utils_Request::retrieveValue('contact_id_app', 'CommaSeparatedIntegers', null);

        $contact_id_app = CRM_Utils_Request::retrieveValue('contact_id_sub', 'CommaSeparatedIntegers', null);

        $dateselect_to = CRM_Utils_Request::retrieveValue('dateselect_to', 'String', null);

        try {
            $dateselectto = new DateTime($dateselect_to);
        } catch (Exception $e) {
            $dateselect_to = null;
        }
//        CRM_Core_Error::debug_var('dateselect_to', $dateselect_to);

        $dateselect_from = CRM_Utils_Request::retrieveValue('dateselect_from', 'String', null);
        try {
            $dateselectto = new DateTime($dateselect_from);
        } catch (Exception $e) {
            $dateselect_from = null;
        }

//        $transaction_category_id = CRM_Utils_Request::retrieveValue('transaction_category_id', 'CommaSeparatedIntegers', null);
//        CRM_Core_Error::debug_var('transaction_category_id', $transaction_category_id);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);

        $sortMapper = [
            0 => 'id',
            1 => 'date',
            2 => 'description',
            3 => 'amount',
            5 => 'account_name',
            6 => 'component_name',
            7 => 'cs_name',
            8 => 'ca_name',
            9 => 'case_name',
//            10 => 'amount'
        ];

        $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = '';
        $where = [];

//        $isOrQuery = self::isOrQuery();
// Contact Social Worker
// Contact Approver
        $nextParamKey = 3;
        $sql = "
    SELECT SQL_CALC_FOUND_ROWS
      t.id,
      t.description,
      t.amount,
      concat(cm.code, ': ', cm.name) component_name,
      concat(a.code, ': ', a.name) account_name,
      concat(c.id, ': ', c.subject) case_name,
      cs.sort_name cs_name,
      ca.sort_name ca_name,
      cm.code component_code,
      a.code account_code,
      c.subject case_code,
      t.contact_id_app,
      t.contact_id_sub,
      t.component_id,
      t.account_id,
      t.case_id,
      t.component_id,
      t.contact_id_sub,
      t.contact_id_app
    FROM civicrm_o8_fund_transaction t 
    INNER JOIN civicrm_case c on t.case_id = c.id
    INNER JOIN civicrm_o8_fund_account a on t.account_id = a.id
    INNER JOIN civicrm_o8_fund_component cm on t.component_id = cm.id
    INNER JOIN civicrm_contact cs on t.contact_id_sub = cs.id
    INNER JOIN civicrm_contact ca on t.contact_id_app = ca.id
    WHERE 1";


        if (isset($transaction_id)) {
            if (strval($transaction_id) != "") {
                $sql .= " AND t.`code` like '%" . strval($transaction_id) . "%' ";
                if (is_numeric($transaction_id)) {
                    $sql .= " OR t.`id` = " . intval($transaction_id) . " ";
                }
            }
        }

        if (isset($transaction_name)) {
            if (strval($transaction_name) != "") {
                $sql .= " AND t.`name` like '%" . strval($transaction_name) . "%' ";
                $sql .= " OR t.`description` like '%" . strval($transaction_name) . "%' ";
            }
        }

//        $component_id = CRM_Utils_Request::retrieveValue('component_id', 'CommaSeparatedIntegers', null);
//
//        $contact_id_app = CRM_Utils_Request::retrieveValue('contact_id_app', 'CommaSeparatedIntegers', null);
//
//        $contact_id_app = CRM_Utils_Request::retrieveValue('contact_id_sub', 'CommaSeparatedIntegers', null);


        if (isset($case_id)) {
            if (strval($case_id) != "") {
                if (is_numeric($case_id)) {
                    $sql .= " AND t.`case_id` = " . $case_id . " ";
                } else {
                    $sql .= " AND t.`case_id` in (" . $case_id . ") ";
                }
            }
        }

        if (isset($account_id)) {
            if (strval($account_id) != "") {
                if (is_numeric($account_id)) {
                    $sql .= " AND t.`account_id` = " . $account_id . " ";
                } else {
                    $sql .= " AND t.`account_id` in (" . $account_id . ") ";
                }
            }
        }

        if (isset($component_id)) {
            if (strval($component_id) != "") {
                if (is_numeric($component_id)) {
                    $sql .= " AND t.`component_id` = " . $component_id . " ";
                } else {
                    $sql .= " AND t.`component_id` in (" . $component_id . ") ";
                }
            }
        }


        if (isset($contact_id_app)) {
            if (strval($contact_id_app) != "") {
                if (is_numeric($contact_id_app)) {
                    $sql .= " AND t.`contact_id_app` = " . $contact_id_app . " ";
                } else {
                    $sql .= " AND t.`contact_id_app` in (" . $contact_id_app . ") ";
                }
            }
        }

        if (isset($contact_id_sub)) {
            if (strval($contact_id_sub) != "") {
                if (is_numeric($contact_id_sub)) {
                    $sql .= " AND t.`contact_id_sub` = " . $contact_id_sub . " ";
                } else {
                    $sql .= " AND t.`contact_id_sub` in (" . $contact_id_sub . ") ";
                }
            }
        }

        $month_ago = strtotime("-1 month", time());
        $date_month_ago = date("Y-m-d H:i:s", $month_ago);

        $today = strtotime("+1 day", time());
        $date_today = date("Y-m-d H:i:s", $today);

        if (isset($dateselect_from)) {
            if ($dateselect_from != null) {
                if ($dateselect_from != '') {
                    $sql .= " AND t.`date` >= '" . $dateselect_from . " 00:00:00' ";
                }
            }
        }


        if (isset($dateselect_to)) {
            if ($dateselect_to != null) {
                if ($dateselect_to != '') {
                    $sql .= " AND t.`date` <= '" . $dateselect_to . " 23:59:59' ";
                } else {
                    $sql .= " AND t.`date` <= '" . $date_today . "' ";
                }
            } else {
                $sql .= " AND t.`date` <= '" . $date_today . "' ";
            }
        } else {
            $sql .= " AND t.`date` <= '" . $date_today . "' ";
        }

        if ($sort !== NULL) {
            $sql .= " ORDER BY {$sort} {$sortOrder}";
        }

        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $sql .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }


//        CRM_Core_Error::debug_var('transaction_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            /*
            0 => 'id',
            1 => 'date',
            2 => 'description',
            3 => 'amount',
            5 => 'account_name',
            6 => 'component_name',
            7 => 'cs_name',
            8 => 'ca_name',
            9 => 'case_name',

             *  */
            $date = date_format(date_create($dao->date), 'j-M-Y');
            $amount = "";
            if (is_numeric($dao->amount)) {
                $amount = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($dao->amount);
            } else {
                $amount = $dao->amount;
            }
            $account = "";
            $component = "";
            $contact_app = "";
            $contact_sub = "";
            if (!empty($dao->account_id)) {
                $account = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/fund/account',
                        ['reset' => 1, 'id' => $dao->account_id]) . '">' .
                    $dao->account_name . '</a>';
            }

            if (!empty($dao->component_id)) {
                $component = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/fund/component',
                        ['reset' => 1, 'id' => $dao->component_id]) . '">' .
                    $dao->component_name . '</a>';
            }

            if (!empty($dao->contact_id_app)) {
                $contact_app = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->contact_id_app]) . '">' .
                    $dao->ca_name . '</a>';
            }

            if (!empty($dao->contact_id_sub)) {
                $contact_sub = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->contact_id_sub]) . '">' .
                    $dao->cs_name . '</a>';
            }

            $r_update = CRM_Utils_System::url('civicrm/fund/transaction',
                ['action' => 'update', 'id' => $dao->id]);
            $r_delete = CRM_Utils_System::url('civicrm/fund/transaction',
                ['action' => 'delete', 'id' => $dao->id]);
            $update = '<a class="update-transaction action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-transaction action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $action = "<span>$update $delete</span>";
            $rows[$count][] = $dao->id;
            $rows[$count][] = $date;
            $rows[$count][] = $dao->description;
            $rows[$count][] = $amount;
            $rows[$count][] = $account;
            $rows[$count][] = $component;
            $rows[$count][] = $contact_sub;
            $rows[$count][] = $contact_app;
            $rows[$count][] = $dao->case_name;
            $rows[$count][] = $action;
            $count++;
        }

        $searchRows = $rows;
        $iTotal = 0;
        if (is_countable($searchRows)) {
            $iTotal = sizeof($searchRows);
        }
        $hmdatas = [
            'data' => $searchRows,
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
        ];
        if (!empty($_REQUEST['is_unit_test'])) {
            return $hmdatas;
        }
        CRM_Utils_JSON::output($hmdatas);
    }


}
