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

//        CRM_Core_Error::debug_var('device_request', $_REQUEST);
//        CRM_Core_Error::debug_var('device_post', $_POST);

        $cid = CRM_Utils_Request::retrieve('cid', 'Positive');
        //cid = contact for tabset
//        CRM_Core_Error::debug_var('cid', $cid);

        $contactId = CRM_Utils_Request::retrieve('contact_id', 'String');
//        CRM_Core_Error::debug_var('contact', $contactId);
        $device_device_id = CRM_Utils_Request::retrieveValue('device_device_id', 'String', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);

        $device_type_id = CRM_Utils_Request::retrieveValue('device_type_id', 'String', null);
//        CRM_Core_Error::debug_var('device_type_id', $device_type_id);

        if ($cid) {
            $sortMapper = [
                0 => 'id',
                1 => 'code',
                2 => 'device_type',
            ];
        } else {
            $sortMapper = [
                0 => 'id',
                1 => 'code',
                2 => 'device_type',
                3 => 'sort_name'
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
      t.id,
      t.code,
      dt.label device_type,
      c.sort_name,
      c.display_name,
      t.contact_id
    FROM civicrm_o8_device_device t 
    INNER JOIN civicrm_contact c on t.contact_id = c.id
    INNER JOIN civicrm_option_value dt on t.device_type_id = dt.value
    INNER JOIN civicrm_option_group gdt on dt.option_group_id = gdt.id 
                                               and gdt.name = 'o8_device_type'    
    WHERE 1";

        if (isset($cid)) {
            if (is_numeric($cid)) {
                if (intval($cid) > 0) {
                    $sql .= " AND t.`contact_id` = " . $cid . " ";
                }
            }
        } elseif (isset($contactId)) {
            if (strval($contactId) != "") {
                $sql .= " AND t.`contact_id` in (" . $contactId . ") ";
            }
        }

        if (isset($device_device_id)) {
            if (strval($device_device_id) != "") {
                $sql .= " AND t.`code` like '%" . strval($device_device_id) . "%' ";
                if (is_numeric($device_device_id)) {
                    $sql .= " OR t.`id` = " . intval($device_device_id) . " ";
                }
            }
        }

        if (isset($device_type_id)) {
            if (strval($device_type_id) != "") {
                if (is_numeric($device_type_id)) {
                    $sql .= " AND t.`device_type_id` = " . $device_type_id . " ";
                } else {
                    $sql .= " AND t.`device_type_id` in (" . $device_type_id . ") ";
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


//        CRM_Core_Error::debug_var('device_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            if (!empty($dao->contact_id)) {
                $contact = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->contact_id]) . '">' .
                    CRM_Contact_BAO_Contact::displayName($dao->contact_id) . '</a>';
            }

            $r_update = CRM_Utils_System::url('civicrm/devices/device',
                ['action' => 'update', 'id' => $dao->id]);
            $r_delete = CRM_Utils_System::url('civicrm/devices/device',
                ['action' => 'delete', 'id' => $dao->id]);
            $update = '<a class="update-device action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-device action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $action = "<span>$update $delete</span>";
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->device_type;
            if ($cid === null) {
                $rows[$count][] = $contact;
            }
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