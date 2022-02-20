<?php
use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_SearchCategory extends CRM_Core_Page
{
    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Search Categorys'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $categorys_source_url = CRM_Utils_System::url('civicrm/fund/category_ajax', $urlQry, FALSE, NULL, FALSE);
//        $categorys_source_url = "";
        $sourceUrl['categorys_source_url'] = $categorys_source_url;
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

//        CRM_Core_Error::debug_var('category_request', $_REQUEST);
//        CRM_Core_Error::debug_var('category_post', $_POST);
        /*
         *
            aoData.push({ "name": "category_id",
                "value": $('#category_id').val() });
            aoData.push({ "name": "category_name",
                "value": $('#category_name').val() });
         */

        $category_id = CRM_Utils_Request::retrieveValue('category_id', 'String', null);

        $category_name = CRM_Utils_Request::retrieveValue('category_name', 'String', null);



        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);

        $sortMapper = [
            0 => 'id',
            1 => 'code',
            2 => 'name',
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
      a.description
    FROM civicrm_o8_fund_category a 
    WHERE 1";



        if (isset($category_id)) {
            if (strval($category_id) != "") {
                $sql .= " AND a.`code` like '%" . strval($category_id) . "%' ";
                if (is_numeric($category_id)) {
                    $sql .= " OR a.`id` = " . intval($category_id) . " ";
                }
            }
        }

        if (isset($category_name)) {
            if (strval($category_name) != "") {
                $sql .= " AND a.`name` like '%" . strval($category_name) . "%' ";
                $sql .= " OR a.`description` like '%" . strval($category_name) . "%' ";
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


//        CRM_Core_Error::debug_var('category_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {

            $r_update = CRM_Utils_System::url('civicrm/fund/category',
                ['action' => 'update', 'id' => $dao->id]);
            $r_delete = CRM_Utils_System::url('civicrm/fund/category',
                ['action' => 'delete', 'id' => $dao->id]);
            $update = '<a class="update-category action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-category action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $action = "<span>$update $delete</span>";
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->name;
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