<?php
use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_SearchAccount extends CRM_Core_Page
{
    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Search Accounts'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $accounts_source_url = CRM_Utils_System::url('civicrm/fund/account_ajax', $urlQry, FALSE, NULL, FALSE);
//        $accounts_source_url = "";
        $sourceUrl['accounts_source_url'] = $accounts_source_url;
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

//        CRM_Core_Error::debug_var('account_request', $_REQUEST);
//        CRM_Core_Error::debug_var('account_post', $_POST);
        /*
         *
            aoData.push({ "name": "account_id",
                "value": $('#account_id').val() });
            aoData.push({ "name": "account_name",
                "value": $('#account_name').val() });
            aoData.push({ "name": "account_fund_id",
                "value": $('#account_fund_id').val() });
         */

        $account_id = CRM_Utils_Request::retrieveValue('account_id', 'String', null);

        $account_name = CRM_Utils_Request::retrieveValue('account_name', 'String', null);


        $account_type_id = CRM_Utils_Request::retrieveValue('account_type_id', 'CommaSeparatedIntegers', null);
//        CRM_Core_Error::debug_var('account_fund_id', $account_fund_id);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);

        $sortMapper = [
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'type_code'
        ];

        $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = '';
        $where = [];

//        $isOrQuery = self::isOrQuery();

        $nextParamKey = 3;
        $sql = "
    SELECT SQL_CALC_FOUND_ROWS
      a.id,
      a.code,
      a.name,
      a.description,
      concat(f.code, ': ', f.name) type_name,
      f.code type_code,
      a.type_id
    FROM civicrm_o8_fund_account a 
    INNER JOIN civicrm_o8_fund_account_type f on a.type_id = f.id
    WHERE 1";



        if (isset($account_id)) {
            if (strval($account_id) != "") {
                $sql .= " AND a.`code` like '%" . strval($account_id) . "%' ";
                if (is_numeric($account_id)) {
                    $sql .= " OR a.`id` = " . intval($account_id) . " ";
                }
            }
        }

        if (isset($account_name)) {
            if (strval($account_name) != "") {
                $sql .= " AND a.`name` like '%" . strval($account_name) . "%' ";
                $sql .= " OR a.`description` like '%" . strval($account_name) . "%' ";
            }
        }


        if (isset($account_type_id)) {
            if (strval($account_type_id) != "") {
                if (is_numeric($account_type_id)) {
                    $sql .= " AND a.`type_id` = " . $account_type_id . " ";
                } else {
                    $sql .= " AND a.`type_id` in (" . $account_type_id . ") ";
                }
            }
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


//        CRM_Core_Error::debug_var('account_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            if (!empty($dao->type_id)) {
                $fund = '<a href="' . CRM_Utils_System::url('civicrm/fund/accounttype',
                        ['reset' => 1, 'id' => $dao->type_id]) . '">' .
                    $dao->type_name . '</a>';
            }

            $r_update = CRM_Utils_System::url('civicrm/fund/account',
                ['action' => 'update', 'id' => $dao->id]);
            $r_delete = CRM_Utils_System::url('civicrm/fund/account',
                ['action' => 'delete', 'id' => $dao->id]);
            $update = '<a class="update-account action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-account action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $action = "<span>$update $delete</span>";
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->name;
            $rows[$count][] = $fund;
            $rows[$count][] = $dao->description;
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
