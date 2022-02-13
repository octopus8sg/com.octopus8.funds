<?php
use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_SearchComponent extends CRM_Core_Page 
{
    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Search Components'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $components_source_url = CRM_Utils_System::url('civicrm/fund/component_ajax', $urlQry, FALSE, NULL, FALSE);
//        $components_source_url = "";
        $sourceUrl['components_source_url'] = $components_source_url;
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

//        CRM_Core_Error::debug_var('component_request', $_REQUEST);
//        CRM_Core_Error::debug_var('component_post', $_POST);
        /*
         *
            aoData.push({ "name": "component_id",
                "value": $('#component_id').val() });
            aoData.push({ "name": "component_name",
                "value": $('#component_name').val() });
         */

        $component_id = CRM_Utils_Request::retrieveValue('component_id', 'String', null);

        $component_name = CRM_Utils_Request::retrieveValue('component_name', 'String', null);


//        $component_category_id = CRM_Utils_Request::retrieveValue('component_category_id', 'CommaSeparatedIntegers', null);
//        CRM_Core_Error::debug_var('component_category_id', $component_category_id);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);

        $sortMapper = [
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'category_code'
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
      concat(c.code, ': ', c.name) category_name,
      c.code category_code,
      a.fund_category_id
    FROM civicrm_o8_fund_component a 
    INNER JOIN civicrm_o8_fund_category c on a.fund_category_id = c.id
    WHERE 1";



        if (isset($component_id)) {
            if (strval($component_id) != "") {
                $sql .= " AND a.`code` like '%" . strval($component_id) . "%' ";
                if (is_numeric($component_id)) {
                    $sql .= " OR a.`id` = " . intval($component_id) . " ";
                }
            }
        }

        if (isset($component_name)) {
            if (strval($component_name) != "") {
                $sql .= " AND a.`name` like '%" . strval($component_name) . "%' ";
                $sql .= " OR a.`description` like '%" . strval($component_name) . "%' ";
            }
        }


        if (isset($component_category_id)) {
            if (strval($component_category_id) != "") {
                if (is_numeric($component_category_id)) {
                    $sql .= " AND a.`fund_category_id` = " . $component_category_id . " ";
                } else {
                    $sql .= " AND a.`fund_category_id` in (" . $component_category_id . ") ";
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


//        CRM_Core_Error::debug_var('component_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            if (!empty($dao->fund_category_id)) {
                $category = '<a href="' . CRM_Utils_System::url('civicrm/fund/category',
                        ['reset' => 1, 'id' => $dao->fund_category_id]) . '">' .
                    $dao->category_name . '</a>';
            }

            $r_update = CRM_Utils_System::url('civicrm/fund/component',
                ['action' => 'update', 'id' => $dao->id]);
            $r_delete = CRM_Utils_System::url('civicrm/fund/component',
                ['action' => 'delete', 'id' => $dao->id]);
            $update = '<a class="update-component action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-component action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $action = "<span>$update $delete</span>";
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->name;
            $rows[$count][] = $category;
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

