<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_SearchFund extends CRM_Core_Page
{
    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Search Funds'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $funds_source_url = CRM_Utils_System::url('civicrm/fund/fund_ajax', $urlQry, FALSE, NULL, FALSE);
//        $funds_source_url = "";
        $sourceUrl['funds_source_url'] = $funds_source_url;
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

//        CRM_Core_Error::debug_var('fund_request', $_REQUEST);
//        CRM_Core_Error::debug_var('fund_post', $_POST);

        $cid = CRM_Utils_Request::retrieve('cid', 'Positive');
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


        if ($cid) {
            $sortMapper = [
                0 => 'id',
                1 => 'code',
                2 => 'name',
                3 => 'start_date',
                4 => 'end_date',
                5 => 'amount',
            ];
        } else {
            $sortMapper = [
                0 => 'id',
                1 => 'code',
                2 => 'name',
                3 => 'start_date',
                4 => 'end_date',
                5 => 'sort_name',
                6 => 'amount',
            ];
        }

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
      f.id,
      f.code,
      f.name,
      f.description,
      c.sort_name,
      c.organization_name,
      f.start_date,
      f.end_date,
      f.amount,
      f.contact_id
    FROM civicrm_o8_fund f 
    INNER JOIN civicrm_contact c on f.contact_id = c.id
    WHERE 1";

        if (isset($cid)) {
            if (is_numeric($cid)) {
                if (intval($cid) > 0) {
                    $sql .= " AND f.`contact_id` = " . $cid . " ";
                }
            }
        } elseif (isset($contactId)) {
            if (strval($contactId) != "") {
                $sql .= " AND f.`contact_id` in (" . $contactId . ") ";
            }
        }

        if (isset($fund_id)) {
            if (strval($fund_id) != "") {
                $sql .= " AND f.`code` like '%" . strval($fund_id) . "%' ";
//                if (is_numeric($fund_id)) {
//                    $sql .= " OR f.`id` = " . intval($fund_id) . " ";
//                }
            }
        }

        if (isset($fund_name)) {
            if (strval($fund_name) != "") {
                $sql .= " AND f.`name` like '%" . strval($fund_name) . "%' ";
//                $sql .= " OR f.`description` like '%" . strval($fund_name) . "%' ";
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

//        CRM_Core_Error::debug_var('fund_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            if (!empty($dao->contact_id)) {
                $contact = '<a target="_blank" href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->contact_id]) . '">' .
                    $dao->organization_name . '</a>';
            }

            $r_update = CRM_Utils_System::url('civicrm/fund/form',
                ['action' => 'update', 'id' => $dao->id]);
            $r_delete = CRM_Utils_System::url('civicrm/fund/form',
                ['action' => 'delete', 'id' => $dao->id]);
            $update = '<a class="update-fund action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-fund action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $action = "<span>$update $delete</span>";
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->name;
            $rows[$count][] = date_format(date_create($dao->start_date), 'j-M-Y');
            $rows[$count][] = date_format(date_create($dao->end_date), 'j-M-Y');
            if ($cid === null) {
                $rows[$count][] = $contact;
            }
            $rows[$count][] = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($dao->amount);
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
