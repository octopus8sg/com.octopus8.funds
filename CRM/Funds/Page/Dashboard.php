<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_Dashboard extends CRM_Core_Page
{

    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Funds Dashboard'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $funding_source_url = CRM_Utils_System::url('civicrm/fund/dashboard_ajax_funding', $urlQry, FALSE, NULL, FALSE);
        $contact_source_url = CRM_Utils_System::url('civicrm/fund/dashboard_ajax_contact', $urlQry, FALSE, NULL, FALSE);
//        $funds_source_url = "";
        $sourceUrl['funding_source_url'] = $funding_source_url;
        $sourceUrl['contact_source_url'] = $contact_source_url;
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

    public function getAjaxFunding()
    {

//        CRM_Core_Error::debug_var('fund_request', $_REQUEST);
//        CRM_Core_Error::debug_var('fund_post', $_POST);

//        $cid = CRM_Utils_Request::retrieve('cid', 'Positive');
        //cid = contact for tabset
//        CRM_Core_Error::debug_var('cid', $cid);

        $contactId = CRM_Utils_Request::retrieve('contact_id', 'String');
//        CRM_Core_Error::debug_var('contact', $contactId);

        $fund_id = CRM_Utils_Request::retrieveValue('fund_id', 'String', null);

        $fund_name = CRM_Utils_Request::retrieveValue('fund_name', 'String', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'code',
            1 => 'name',
            2 => 'start_date',
            3 => 'end_date',
            4 => 'amount',
            5 => 'expenditure',
            6 => 'leftmoney',
            7 => 'residue',];

        $sort = isset($_REQUEST['iSortCol_0'])
            ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0'])
            ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';
//        CRM_Core_Error::debug_var('limit1', $_REQUEST['iSortCol_0']);
//        CRM_Core_Error::debug_var('limit2', $_REQUEST['iSortCol_2']);


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = "";
        $where = " WHERE 1 = 1 ";
        $group_by = "";

//        $isOrQuery = self::isOrQuery();

        $nextParamKey = 3;
        $sql = "
    SELECT SQL_CALC_FOUND_ROWS
    f.id,
    f.code,
    f.name,
    f.amount,
    sum(t.amount) as expenditure,
    f.amount - sum(t.amount) as leftmoney,
    round(( ((f.amount - sum(t.amount))/f.amount) * 100 ),2) as residue,
    concat(round(( ((f.amount - sum(t.amount))/f.amount) * 100 ),2),'%') AS balance,
    f.start_date,
    f.end_date,
    (PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM NOW()), EXTRACT(YEAR_MONTH FROM start_date)) + 1) as spenttime,
    f.contact_id
FROM civicrm_o8_fund f
         INNER JOIN civicrm_contact c on f.contact_id = c.id
         LEFT JOIN civicrm_o8_fund_account a on a.fund_id = f.id
         LEFT JOIN civicrm_o8_fund_transaction t on t.account_id = a.id
";

        if (isset($cid)) {
            if (is_numeric($cid)) {
                if (intval($cid) > 0) {
                    $where .= " AND f.`contact_id` = " . $cid . " ";
                }
            }
        } elseif (isset($contactId)) {
            if (strval($contactId) != "") {
                $where .= " AND f.`contact_id` in (" . $contactId . ") ";
            }
        }

        if (isset($fund_id)) {
            if (strval($fund_id) != "") {
                if (is_numeric($fund_id)) {
                    $where .= " AND (f.`code` like '%" . strval($fund_id) . "%' ";
                    $where .= " OR f.`id` = " . intval($fund_id) . ") ";
                }else{
                    $where .= " AND f.`code` like '%" . strval($fund_id) . "%' ";
                }
            }
        }

        if (isset($fund_name)) {
            if (strval($fund_name) != "") {
                $where .= " AND (f.`name` like '%" . strval($fund_name) . "%' ";
                $where .= " OR f.`description` like '%" . strval($fund_name) . "%') ";
            }
        }



        $endwhere = "";

        if ($sort !== NULL) {
            $endwhere .= " ORDER BY {$sort} {$sortOrder}";
        }
        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $endwhere .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }


//        CRM_Core_Error::debug_var('fund_sql', $sql);
        $group_by = "group by f.id, f.code, f.name, f.amount, f.start_date, f.end_date";
        $sql = $sql . " " . $where . " " . $group_by . $endwhere;
        $dao = CRM_Core_DAO::executeQuery($sql);
//        CRM_Core_Error::debug_var('fund_s_sql', $sql);

        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;

        while ($dao->fetch()) {
            $projection = "undefined";
            if (is_numeric($dao->amount)) {
                if (is_numeric($dao->spenttime)) {
                    if (is_numeric($dao->expenditure)) {
                        if ($dao->expenditure > 0) {
                            $sum = $dao->amount;
                            $spentmoney = $dao->expenditure;
                            $spenttime = $dao->spenttime;
                            $monthlyspentmoney = $spentmoney / $spenttime;
                            $projection = round($sum / $monthlyspentmoney) . " MONTH";
                        }
                    }
                }
            }

            if (is_numeric($dao->amount)) {
                $amount = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($dao->amount);
            } else {
                $amount = "0";
            }
            if (is_numeric($dao->leftmoney)) {
                $leftmoney = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($dao->leftmoney);
            } else {
                $leftmoney = "0";
            }
            if (is_numeric($dao->residue)) {
                $balance = ($dao->balance);
            } else {
                $balance = "100%";
            }
            if (is_numeric($dao->expenditure)) {
                $expenditure = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($dao->expenditure);
            } else {
                $expenditure = "0";
            }
            if (!empty($dao->contact_id)) {
                $contact = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->contact_id]) . '">' .
                    $dao->organization_name . '</a>';
            }
            $fund = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/fund/form',
                    ['reset' => 1, 'cid' => $dao->id]) . '">' .
                $dao->code . '</a>';
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->name;
            $rows[$count][] = date_format(date_create($dao->start_date), 'j-M-Y');
            $rows[$count][] = date_format(date_create($dao->end_date), 'j-M-Y');
            $rows[$count][] = $amount;
            $rows[$count][] = $expenditure;
            $rows[$count][] = $leftmoney;
            $rows[$count][] = $balance;
            $rows[$count][] = $projection;
            if ($cid === null) {
                $rows[$count][] = $contact;
            }
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

    public function getAjaxContact()
    {

//        CRM_Core_Error::debug_var('fund_request', $_REQUEST);
//        CRM_Core_Error::debug_var('fund_post', $_POST);

//        $cid = CRM_Utils_Request::retrieve('cid', 'Positive');
        //cid = contact for tabset
//        CRM_Core_Error::debug_var('cid', $cid);

        $contactId = CRM_Utils_Request::retrieve('contact_id', 'String');
//        CRM_Core_Error::debug_var('contact', $contactId);

        $fund_id = CRM_Utils_Request::retrieveValue('fund_id', 'String', null);

        $fund_name = CRM_Utils_Request::retrieveValue('fund_name', 'String', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'code',
            1 => 'name',
            2 => 'start_date',
            3 => 'end_date',
            4 => 'target_cases',
            5 => 'client_current',
            6 => 'client_balance',
            7 => 'social_workers',];

        $sort = isset($_REQUEST['iSortCol_0'])
            ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0'])
            ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';
//        CRM_Core_Error::debug_var('limit1', $_REQUEST['iSortCol_0']);
//        CRM_Core_Error::debug_var('limit2', $_REQUEST['iSortCol_2']);


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = "";
        $where = " WHERE 1 = 1 ";
        $group_by = "";

//        $isOrQuery = self::isOrQuery();

        $nextParamKey = 3;
        $sql = "
    SELECT SQL_CALC_FOUND_ROWS
    f.id,
    f.code,
    f.name,
    f.start_date,
    f.end_date,
    f.target_cases,
    count(a.id) as clients_current,
    count(distinct(t.contact_id_sub)) as social_workers,
    f.contact_id
    FROM civicrm_o8_fund f
         INNER JOIN civicrm_contact c on f.contact_id = c.id
         LEFT JOIN civicrm_o8_fund_account a on a.fund_id = f.id
         LEFT JOIN civicrm_o8_fund_transaction t on t.account_id = a.id
";

        $group_by = "group by f.id, f.code, f.name, f.start_date, f.end_date, f.target_cases";

        if (isset($cid)) {
            if (is_numeric($cid)) {
                if (intval($cid) > 0) {
                    $where .= " AND f.`contact_id` = " . $cid . " ";
                }
            }
        } elseif (isset($contactId)) {
            if (strval($contactId) != "") {
                $where .= " AND f.`contact_id` in (" . $contactId . ") ";
            }
        }

        if (isset($fund_id)) {
            if (strval($fund_id) != "") {

                if (is_numeric($fund_id)) {
                    $where .= " AND (f.`code` like '%" . strval($fund_id) . "%' ";
                    $where .= " OR f.`id` = " . intval($fund_id) . ") ";
                }else{
                    $where .= " AND f.`code` like '%" . strval($fund_id) . "%' ";
                }
            }
        }

        if (isset($fund_name)) {
            if (strval($fund_name) != "") {
                $where .= " AND (f.`name` like '%" . strval($fund_name) . "%' ";
                $where .= " OR f.`description` like '%" . strval($fund_name) . "%') ";
            }
        }


        $endwhere = "";

        if ($sort !== NULL) {
            $endwhere .= " ORDER BY {$sort} {$sortOrder}";
        }
        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $endwhere .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }



        $sql = $sql . " " . $where . " " . $group_by . $endwhere;
        $dao = CRM_Core_DAO::executeQuery($sql);
//        CRM_Core_Error::debug_var('fund_c_sql', $sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            $client_balance = 0;
            $clients_count = $dao->target_cases;
            $clients_current = $dao->clients_current;
            if (is_numeric($dao->target_cases)) {
                if (is_numeric($dao->clients_current)) {
                    $client_balance = $clients_count - $clients_current;
                }
            }
            if (!empty($dao->contact_id)) {
                $contact = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->contact_id]) . '">' .
                    $dao->organization_name . '</a>';
            }
            $fund = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/fund/form',
                    ['reset' => 1, 'cid' => $dao->id]) . '">' .
                $dao->code . '</a>';
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->name;
            $rows[$count][] = date_format(date_create($dao->start_date), 'j-M-Y');
            $rows[$count][] = date_format(date_create($dao->end_date), 'j-M-Y');
            $rows[$count][] = $clients_count;
            $rows[$count][] = $clients_current;
            $rows[$count][] = $client_balance;
            $rows[$count][] = $dao->social_workers;
//            if ($cid === null) {
//                $rows[$count][] = $contact;
//            }
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
