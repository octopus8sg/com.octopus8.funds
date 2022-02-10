<?php

use CRM_Funds_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Funds_Form_CommonSearch extends CRM_Core_Form
{
    protected $_trnx_statuses;
    protected $_cid; // cid is contact for contact tab. if it's present, contact is freezed

    /**
     * Function to get _cid for tabs
     * @throws CRM_Core_Exception
     */
    public function preProcess()
    {
        parent::preProcess();
        $cid = CRM_Utils_Request::retrieve('cid', 'Positive', $this, FALSE);
        $this->_cid = $cid;

    }


    /**
     * calls all the functions to add controls depending on _cid,
     * @throws CRM_Core_Exception
     */
    public function buildQuickForm()
    {

        // add device type filter
        $statuses = CRM_Core_OptionGroup::values('o8_fund_trxn_status');
        $this->_statuses = $statuses;
        $this->_cid = CRM_Utils_Request::retrieve('cid', 'Positive');
        //
        $this->fund_filter();
        $this->account_filter();
        $this->category_filter();
        $this->component_filter();
        $this->transaction_filter();
//        if ($this->_cid) {
//            $this->fund_filter();
//            $this->account_filter();
//            $this->category_filter();
//            $this->component_filter();
//            $this->transaction_filter();
//        }
        $this->assign('suppressForm', FALSE);
        parent::buildQuickForm();
    }

    function fund_filter()
    {
        // ID or Code
        // Contact (Owner)
        /*
         *
            aoData.push({ "name": "fund_id",
                "value": $('#fund_id').val() });
            aoData.push({ "name": "fund_name",
                "value": $('#fund_name').val() });
            aoData.push({ "name": "contact_id",
                "value": $('#fund_contact_id').val() });
         */

        $this->add(
            'text',
            'fund_id',
            ts('Fund ID or Code'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'fund_name',
            ts('Fund Name or Description'),
            ['size' => 28, 'maxlength' => 128]);

//        CRM_Core_Error::debug_var('cid', $this->_cid);
        $props = ['create' => false,
            'multiple' => true,
            'class' => 'huge',
            'api' =>
                ['params' =>
                    ['contact_type' => 'Organization']
                ]
        ];

        if ($this->_cid) {
            $this->addEntityRef('fund_contact_id', E::ts('Source Organisation (Contact)'),
                $props)->freeze();
        } else {
            $this->addEntityRef('fund_contact_id', E::ts('Source Organisation (Contact)'),
                $props,
                false);
        }
    }

    function account_filter()
    {
        // ID or Code
        // Contact (Owner)
        /*
         *
            aoData.push({ "name": "account_id",
                "value": $('#account_id').val() });
            aoData.push({ "name": "account_name",
                "value": $('#account_name').val() });
            aoData.push({ "name": "account_fund_id",
                "value": $('#account_fund_id').val() });
         */

        $this->add(
            'text',
            'account_id',
            ts('Account ID or Code'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'account_name',
            ts('Account Name or Description'),
            ['size' => 28, 'maxlength' => 128]);

//        CRM_Core_Error::debug_var('cid', $this->_cid);

        $this->addEntityRef('account_fund_id', E::ts('Fund'), [
            'api' => [
                'search_field' => ['id', 'code', 'name', 'description'],
                'label_field' => "name",
                'description_field' => [
                    'code',
                    'description',
                ]
            ],
            'entity' => 'fund',
            'class' => 'huge',
            'create' => false,
            'multiple' => true,
            'placeholder' => ts('- Select Fund -'),
        ], FALSE);

    }

    function category_filter()
    {
        // ID or Code
        // Contact (Owner)
        /*
         *
            aoData.push({ "name": "category_id",
                "value": $('#category_id').val() });
            aoData.push({ "name": "category_name",
                "value": $('#category_name').val() });
         */

        $this->add(
            'text',
            'category_id',
            ts('Category ID or Code'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'category_name',
            ts('Category Name or Description'),
            ['size' => 28, 'maxlength' => 128]);

//        CRM_Core_Error::debug_var('cid', $this->_cid);

    }

    function component_filter()
    {
        // ID or Code
        // Contact (Owner)
        /*
         *
            aoData.push({ "name": "component_id",
                "value": $('#component_id').val() });
            aoData.push({ "name": "component_name",
                "value": $('#component_name').val() });
         */

        $this->add(
            'text',
            'component_id',
            ts('Component ID or Code'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'component_name',
            ts('Component Name or Description'),
            ['size' => 28, 'maxlength' => 128]);

//        CRM_Core_Error::debug_var('cid', $this->_cid);

    }

    function transaction_filter()
    {
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
        // ID or Code
        // Contact (Owner)

        $this->add(
            'text',
            'transaction_id',
            ts('Device Data ID'),
            ['size' => 28, 'maxlength' => 128]);


        $props = ['create' => false, 'multiple' => true, 'class' => 'huge'];
        if ($this->_cid) {
            $this->addEntityRef('contact_id_sub', E::ts('Contact (Social Worker)'),
                false)->freeze();
            $this->addEntityRef('contact_id_app', E::ts('Contact (Approver)'),
                false)->freeze();
        } else {
            $this->addEntityRef('contact_id_sub',
                E::ts('Contact (Social Worker)'), $props);
            //9
            $this->addEntityRef('contact_id_app',
                E::ts('Contact (Approver)'), $props);
        }
        $this->addEntityRef('transaction_case_id', E::ts('Case'), [
            'api' => [
                'search_field' => ['id', 'code', 'name', 'description'],
                'label_field' => "name",
                'description_field' => [
                    'code',
                    'description',
                ]
            ],
            'entity' => 'case',
            'class' => 'huge',
            'create' => false,
            'multiple' => true,
            'placeholder' => ts('- Select Case -'),
        ], FALSE);
        $this->addEntityRef('transaction_account_id', E::ts('Account'), [
            'api' => [
                'search_field' => ['id', 'code', 'name', 'description'],
                'label_field' => "name",
                'description_field' => [
                    'code',
                    'description',
                ]
            ],
            'entity' => 'fund_account',
            'class' => 'huge',
            'create' => false,
            'multiple' => true,
            'placeholder' => ts('- Select Account -'),
        ], FALSE);

        $this->addEntityRef('transaction_account_id', E::ts('Account'), [
            'api' => [
                'search_field' => ['id', 'code', 'name', 'description'],
                'label_field' => "name",
                'description_field' => [
                    'code',
                    'description',
                ]
            ],
            'entity' => 'fund_account',
            'class' => 'huge',
            'create' => false,
            'multiple' => true,
            'placeholder' => ts('- Select Account -'),
        ], FALSE);

        $this->addEntityRef('transaction_component_id', E::ts('Component'), [
            'api' => [
                'search_field' => ['id', 'code', 'name', 'description'],
                'label_field' => "name",
                'description_field' => [
                    'code',
                    'description',
                ]
            ],
            'entity' => 'fund_component',
            'class' => 'huge',
            'create' => false,
            'multiple' => true,
            'placeholder' => ts('- Select Component -'),
        ], FALSE);

        $this->addDatePickerRange('transaction_dateselect',
            'Select Date',
            FALSE,
            NULL,
            "From: ",
            "To: ",
            [],
            '_to',
            '_from'
        );

        $this->add('select', 'transaction_status_id',
            E::ts('Status'),
            $this->_device_types,
            FALSE,
            ['class' => 'huge crm-select2',
                'data-option-edit-path' => 'civicrm/admin/options/o8_device_type',
                'multiple' => TRUE,
                'placeholder' => ts('- Select Status -'),
                'select' => ['minimumInputLength' => 0]
            ]);
    }

    public function setDefaultValues()
    {
        if ($this->_cid) {
            $defaults = [];
//            $defaults['device_contact_id'] =
//                $this->_cid;
//            $defaults['device_data_contact_id'] =
//                $this->_cid;
//            $defaults['alarm_rule_contact_id'] =
//                $this->_cid;
//            $defaults['alarm_contact_id'] =
//                $this->_cid;
//            $defaults['alert_rule_contact_id'] =
//                $this->_cid;
//            $defaults['alert_contact_id'] =
//                $this->_cid;
//            $defaults['chart_contact_id'] =
//                $this->_cid;
        }
        return $defaults;
    }


}
