<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_SearchTransaction extends CRM_Core_Page
{

    public function run()
    {
// This part differs for different search pages
        CRM_Utils_System::setTitle(E::ts('Search Transactions'));
        $pageName = 'SearchTransaction';
        $ajaxSourceName = 'transactions_source_url';
        $urlQry['snippet'] = 4;
        $ajaxSourceUrl = CRM_Utils_System::url('civicrm/fund/transaction_ajax', $urlQry, FALSE, NULL, FALSE);
// End this part differs for different search pages

        $sourceUrl[$ajaxSourceName] = $ajaxSourceUrl;
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
        $controller_data->assign('pagename', $pageName);
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
            aoData.push({ "name": "sub_account_id",
                "value": $('#transaction_sub_account_id').val() });
            aoData.push({ "name": "dateselect_from",
                "value": $('#transaction_dateselect_from').val() });
            aoData.push({ "name": "dateselect_to",
                "value": $('#transaction_dateselect_to').val() });
            aoData.push({ "name": "status_id",
                "value": $('#transaction_status_id').val() });
         */
        $currentUserId = CRM_Core_Session::getLoggedInContactID();
        if (CRM_Core_Permission::check('administer CiviCRM')) {
            $isAdmin = TRUE;
        }
        if (CRM_Core_Permission::check('*manage o8connect Funds')) {
            $isApprover = TRUE;
        }
        if (CRM_Core_Permission::check('*manage o8connect Transactions')) {
            $isSocial = TRUE;
        }

        $contactId = CRM_Utils_Request::retrieve('cid', 'Positive');

        $pageName = CRM_Utils_Request::retrieve('pagename', 'String');


        $transaction_id = CRM_Utils_Request::retrieveValue('transaction_id', 'String', null);

        $transaction_name = CRM_Utils_Request::retrieveValue('transaction_name', 'String', null);

        $case_id = CRM_Utils_Request::retrieveValue('case_id', 'CommaSeparatedIntegers', null);

        $fund_id = CRM_Utils_Request::retrieveValue('fund_id', 'CommaSeparatedIntegers', null);

        $account_id = CRM_Utils_Request::retrieveValue('account_id', 'CommaSeparatedIntegers', null);

        $sub_account_id = CRM_Utils_Request::retrieveValue('sub_account_id', 'CommaSeparatedIntegers', null);

        $status_id = CRM_Utils_Request::retrieveValue('status_id', 'CommaSeparatedIntegers', null);

        $contact_id_app = CRM_Utils_Request::retrieveValue('contact_id_app', 'CommaSeparatedIntegers', null);

        $contact_id_sub = CRM_Utils_Request::retrieveValue('contact_id_sub', 'CommaSeparatedIntegers', null);

        $created_by = CRM_Utils_Request::retrieveValue('created_by', 'CommaSeparatedIntegers', null);

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
            4 => 'account_name',
            5 => 'sub_account_name',
            6 => 'cs_name',
            7 => 'ca_name',
            8 => 'cb_name',
            9 => 'case_name',
            10 => 'fund_name',
            11 => 'status_name'
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
      t.date,
      t.amount,
      s.label status_name,
      t.status_id,
      concat(cm.code, ': ', cm.name) sub_account_name,
      concat(a.code, ': ', a.name) account_name,
      concat(c.id, ': ', c.subject) case_name,
      concat(f.code, ': ', f.name) fund_name,
      cs.sort_name cs_name,
      ca.sort_name ca_name,
      cb.sort_name cb_name,
      cm.code sub_account_code,
      a.code account_code,
      c.subject case_code,
      t.contact_id_app,
      t.contact_id_sub,
      t.created_by,
      t.sub_account_id,
      t.account_id,
      t.case_id,
      t.fund_id,
      t.sub_account_id,
      t.contact_id_sub,
      t.contact_id_app
    FROM civicrm_o8_fund_transaction t 
    LEFT JOIN civicrm_case c on t.case_id = c.id
    INNER JOIN civicrm_o8_fund f on t.fund_id = f.id
    LEFT JOIN civicrm_o8_fund_account a on t.account_id = a.id
    LEFT JOIN civicrm_o8_fund_sub_account cm on t.sub_account_id = cm.id
    LEFT JOIN civicrm_contact cs on t.contact_id_sub = cs.id
    LEFT JOIN civicrm_contact ca on t.contact_id_app = ca.id
    LEFT JOIN civicrm_contact cb on t.created_by = cb.id
    INNER JOIN civicrm_option_value s on t.status_id = s.value
    INNER JOIN civicrm_option_group sdt on s.option_group_id = sdt.id 
                                               and sdt.name = 'o8_fund_trxn_status'    
    WHERE 1";


        if (isset($transaction_id)) {
            if (strval($transaction_id) != "") {
                $sql .= " AND t.`code` like '%" . strval($transaction_id) . "%' ";
                if (is_numeric($transaction_id)) {
                    $sql .= " OR t.`id` = " . intval($transaction_id) . " ";
                }
            }
        }

//        CRM_Core_Error::debug_var('pagename', $pageName);
//        CRM_Core_Error::debug_var('contactId', $contactId);
        if (isset($contactId)) {
            if (isset($pageName)) {
                if (strval($pageName) != "") {
                    if (is_numeric($contactId)) {
                        if ($pageName == 'ContactTab') {
                            //contact tab for creator
                            $sql .= " and t.`created_by` = " . intval($contactId) . " ";
                        }
                        if ($pageName == 'OrgTab') {
                            //contact tab for organization, fund contact =
                            $sql .= " and f.`contact_id` = " . intval($contactId) . " ";
                        }
                        if ($pageName == 'SocialTab') {
                            //contact tab for social worker, contact_id_sub =
                            $sql .= " and t.`contact_id_sub` = " . intval($contactId) . " ";
                        }
                        if ($pageName == 'ApproverTab') {
                            //contact tab for financial manager, contact_id_app =
                            $sql .= " and t.`contact_id_app` = " . intval($contactId) . " ";
                        }
                    }
                }
            }
        }

        if (isset($transaction_name)) {
            if (strval($transaction_name) != "") {
                $sql .= " AND t.`name` like '%" . strval($transaction_name) . "%' ";
                $sql .= " OR t.`description` like '%" . strval($transaction_name) . "%' ";
            }
        }

//        $sub_account_id = CRM_Utils_Request::retrieveValue('sub_account_id', 'CommaSeparatedIntegers', null);
//
//        $contact_id_app = CRM_Utils_Request::retrieveValue('contact_id_app', 'CommaSeparatedIntegers', null);
//
//        $contact_id_app = CRM_Utils_Request::retrieveValue('contact_id_sub', 'CommaSeparatedIntegers', null);
        if (!($isAdmin OR $isApprover)) {
            if ($isSocial) {
                $sql .= " AND t.`contact_id_sub` = " . strval($currentUserId) . " ";
            }
        }

        if (isset($case_id)) {
            if (strval($case_id) != "") {
                if (is_numeric($case_id)) {
                    $sql .= " AND t.`case_id` = " . $case_id . " ";
                } else {
                    $sql .= " AND t.`case_id` in (" . $case_id . ") ";
                }
            }
        }

        if (isset($fund_id)) {
            if (strval($fund_id) != "") {
                if (is_numeric($fund_id)) {
                    $sql .= " AND t.`fund_id` = " . $fund_id . " ";
                } else {
                    $sql .= " AND t.`fund_id` in (" . $fund_id . ") ";
                }
            }
        }

        if (isset($status_id)) {
            if (strval($status_id) != "") {
                if (is_numeric($status_id)) {
                    $sql .= " AND t.`status_id` = " . $status_id . " ";
                } else {
                    $sql .= " AND t.`status_id` in (" . $status_id . ") ";
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

        if (isset($sub_account_id)) {
            if (strval($sub_account_id) != "") {
                if (is_numeric($sub_account_id)) {
                    $sql .= " AND t.`sub_account_id` = " . $sub_account_id . " ";
                } else {
                    $sql .= " AND t.`sub_account_id` in (" . $sub_account_id . ") ";
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

        if (isset($created_by)) {
            if (strval($created_by) != "") {
                if (is_numeric($created_by)) {
                    $sql .= " AND t.`created_by` = " . $created_by . " ";
                } else {
                    $sql .= " AND t.`created_by` in (" . $created_by . ") ";
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


        CRM_Core_Error::debug_var('transaction_sql', $sql);

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
            6 => 'sub_account_name',
            7 => 'cs_name',
            8 => 'ca_name',
            9 => 'case_name',
            10 => 'status_name',

             *  */
            $date = date_format(date_create($dao->date), 'j-M-Y');
            $amount = "";
            if (is_numeric($dao->amount)) {
                $amount = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($dao->amount);
            } else {
                $amount = $dao->amount;
            }
            $account = "";
            $subaccount = "";
            $contact_app = "";
            $contact_sub = "";
            $contact_crb = "";
            if (!empty($dao->account_id)) {
                $account = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/fund/account',
                        ['reset' => 1, 'id' => $dao->account_id]) . '">' .
                    $dao->account_name . '</a>';
            }

            if (!empty($dao->fund_id)) {
                $fund = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/fund/form',
                        ['reset' => 1, 'id' => $dao->fund_id]) . '">' .
                    $dao->fund_name . '</a>';
            }

            if (!empty($dao->sub_account_id)) {
                $subaccount = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/fund/subaccount',
                        ['reset' => 1, 'id' => $dao->sub_account_id]) . '">' .
                    $dao->sub_account_name . '</a>';
            }

            if (!empty($dao->contact_id_app)) {
                $contact_app = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->contact_id_app]) . '">' .
                    $dao->ca_name . '</a>';
            }

            if (!empty($dao->created_by)) {
                $contact_crb = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->created_by]) . '">' .
                    $dao->cb_name . '</a>';
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
            $r_view = CRM_Utils_System::url('civicrm/fund/transaction',
                ['action' => 'view', 'id' => $dao->id]);
            $update = '<a class="update-transaction action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-transaction action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $view = '<a class="view-transaction action-item crm-hover-button" target="_blank" href="' . $r_view . '"><i class="crm-i fa-eye"></i>&nbsp;View</a>';
            $action = "<span>$update $delete</span>";
            if (isset($contactId)) {
                $action = "<span>$view</span>";
                if (isset($pageName)) {
                    if (strval($pageName) == "ContactTab") {
                        if (is_numeric($contactId)) {
                            $action = "<span>$view</span>";
                        }
                    }
                    if (strval($pageName) == "SocialTab") {
                        if (is_numeric($contactId)) {
                            $action = "<span>$view $update</span>";
                        }
                    }
                    if (strval($pageName) == "ApproverTab") {
                        if (is_numeric($contactId)) {
                            $action = "<span>$view $update</span>";
                        }
                    }
                    if (strval($pageName) == "OrgTab") {
                        if (is_numeric($contactId)) {
                            $action = "<span>$view</span>";
                        }
                    }
                }
            }
            $rows[$count][] = $dao->id;
            $rows[$count][] = $date;
            $rows[$count][] = $dao->description;
            $rows[$count][] = $amount;
            $rows[$count][] = $account;
            $rows[$count][] = $subaccount;
            $rows[$count][] = $contact_sub;
            $rows[$count][] = $contact_app;
            $rows[$count][] = $contact_crb;
            $rows[$count][] = $dao->case_name;
            $rows[$count][] = $fund;
            $rows[$count][] = $dao->status_name;
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
