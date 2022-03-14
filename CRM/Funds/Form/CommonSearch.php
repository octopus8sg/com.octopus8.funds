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
    protected $_pagename; // pagename is a name of a page for running a filter

    /**
     * Function to get _cid for tabs
     * @throws CRM_Core_Exception
     */
    public function preProcess()
    {
        parent::preProcess();
        $cid = CRM_Utils_Request::retrieve('cid', 'Positive', $this, FALSE);
        $this->_cid = $cid;
        $pagename = $this->get_template_vars('pagename');
        $this->_pagename = $pagename;

        //        CRM_Core_Error::debug_var('pagename1', $pagename);
//        CRM_Core_Error::debug_var('allvars1', $this->get_template_vars());
    }


    /**
     * calls all the functions to add controls depending on _cid,
     * @throws CRM_Core_Exception
     */
    public function buildQuickForm()
    {
        $pagename = $this->_pagename;

//        CRM_Core_Error::debug_var('allvars2', $this->get_template_vars());

        // add device type filter
        $statuses = CRM_Core_OptionGroup::values('o8_fund_trxn_status');
        $this->_trnx_statuses = $statuses;
        $this->_cid = CRM_Utils_Request::retrieve('cid', 'Positive');
        //
        $transactionPages = [
            'SearchTransaction',
            'OrgTab',
            'ApproverTab',
            'SocialTab',
        ];
        $fundPages = [
            'SearchFund',
        ];
        $accountPages = [
            'SearchAccount',
        ];
        $accountTypePages = [
            'SearchAccountType',
        ];
        $subAccountPages = [
            'SearchSubAccount',
        ];
        $categoryPages = [
            'SearchCategory',
        ];
        if (in_array($pagename, $fundPages)) {
            $this->fund_filter();
        }
        if (in_array($pagename, $accountPages)) {
            $this->account_filter();
        }
        if (in_array($pagename, $accountTypePages)) {
            $this->account_type_filter();
        }
        if (in_array($pagename, $subAccountPages)) {
            $this->sub_account_filter();
        }
        if (in_array($pagename, $categoryPages)) {
            $this->category_filter();
        }
        if (in_array($pagename, $transactionPages)) {
            $this->transaction_filter();
        }

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
            ts('Fund Code'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'fund_name',
            ts('Fund Name'),
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

        $this->addEntityRef('account_account_type_id', E::ts('Type'), [
            'api' => [
                'search_fields' => ['code', 'name'],
//                'extra' => ['code', 'name'],
//                'search_field' => 'code',
                'description_field' => [
                    'code',
                    'description',
                ],
                'label_field' => "name",
                'params' => []
            ],
            'select' => ['minimumInputLength' => 1],
            'entity' => 'fund_account_type',
            'class' => 'huge',
            'create' => false,
            'multiple' => true,
            'add_wildcard' => false,
            'placeholder' => ts('- Select Type -'),
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

    function account_type_filter()
    {
        // ID or Code
        // Contact (Owner)
        /*
         *
            aoData.push({ "name": "account_type_id",
                "value": $('#account_type_id').val() });
            aoData.push({ "name": "account_type_name",
                "value": $('#account_type_name').val() });
         */

        $this->add(
            'text',
            'account_type_id',
            ts('Account Type ID or Code'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'account_type_name',
            ts('Account Type Name or Description'),
            ['size' => 28, 'maxlength' => 128]);

//        CRM_Core_Error::debug_var('cid', $this->_cid);

    }

    function sub_account_filter()
    {
        // ID or Code
        // Contact (Owner)
        /*
         *
            aoData.push({ "name": "sub_account_id",
                "value": $('#sub_account_id').val() });
            aoData.push({ "name": "sub_account_name",
                "value": $('#sub_account_name').val() });
         */

        $this->add(
            'text',
            'sub_account_id',
            ts('SubAccount ID or Code'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'sub_account_name',
            ts('SubAccount Name or Description'),
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
            aoData.push({ "name": "sub_account_id",
                "value": $('#transaction_sub_account_id').val() });
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
            ts('Transaction ID'),
            ['size' => 28, 'maxlength' => 128]);


        $props = ['create' => false, 'multiple' => true, 'class' => 'huge'];
        if ($this->_cid) {
            if ($this->_pagename == 'SocialTab') {
                $this->addEntityRef('contact_id_sub', E::ts('Contact (Social Worker)'),
                    false)->freeze();
            } else {
                $this->addEntityRef('contact_id_sub', E::ts('Contact (Social Worker)'),
                    false);

            }
            if ($this->_pagename == 'ApproverTab') {
                $this->addEntityRef('contact_id_app', E::ts('Contact (Approver)'),
                    false)->freeze();
            } else {
                $this->addEntityRef('contact_id_app', E::ts('Contact (Approver)'),
                    false);
            }
            if ($this->_pagename == 'OrgTab') {
                $this->addEntityRef('contact_id_app', E::ts('Contact (Approver)'),
                    false);
                $this->addEntityRef('contact_id_sub', E::ts('Contact (Social Worker)'),
                    false);
            }
        } else {
            $this->addEntityRef('contact_id_sub',
                E::ts('Contact (Social Worker)'), $props);
            //9
            $this->addEntityRef('contact_id_app',
                E::ts('Contact (Approver)'), $props);
        }
        $this->addEntityRef('transaction_case_id', E::ts('Case'), [
            'api' => [
//                'search_field' => ['id', 'code', 'name', 'description'],
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
        //todo
        $this->addEntityRef('transaction_account_id', E::ts('Account'), [
            'api' => [
                'search_fields' => ['code', 'name'],
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

        $this->addEntityRef('transaction_sub_account_id', E::ts('SubAccount'), [
            'api' => [
                'search_field' => ['code', 'name'],
                'label_field' => "name",
                'description_field' => [
                    'code',
                    'description',
                ]
            ],
            'entity' => 'fund_sub_account',
            'class' => 'huge',
            'create' => false,
            'multiple' => true,
            'placeholder' => ts('- Select SubAccount -'),
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
            $this->_trnx_statuses,
            FALSE,
            ['class' => 'huge crm-select2',
                'data-option-edit-path' => 'civicrm/admin/options/o8_fund_trxn_status',
                'multiple' => TRUE,
                'placeholder' => ts('- Select Status -'),
                'select' => ['minimumInputLength' => 0]
            ]);

        if ($this->_pagename == 'OrgTab') {
            $this->addEntityRef('transaction_fund_id', E::ts('My Fund'), [
                'api' => [
                    'search_fields' => ['code', 'name'],
//                'extra' => ['contact_id.organisation_name'],
//                'search_field' => 'code',
                    'description_field' => [
                        'code',
                        'description'
                    ],
                    'label_field' => "name",
                    'params' => ['contact_id' => $this->_cid]
                ],
                'select' => ['minimumInputLength' => 0],
                'entity' => 'fund',
                'class' => 'huge',
                'create' => false,
                'multiple' => true,
                'add_wildcard' => false,
                'placeholder' => ts('- Select Fund -'),
            ], FALSE);
        } else {
            $this->addEntityRef('transaction_fund_id', E::ts('Fund'), [
                'api' => [
                    'search_fields' => ['code', 'name'],
//                'extra' => ['code', 'name'],
//                'search_field' => 'code',
                    'description_field' => [
                        'code',
                        'description',
                    ],
                    'label_field' => "name",
                    'params' => []
                ],
                'select' => ['minimumInputLength' => 1],
                'entity' => 'fund',
                'class' => 'huge',
                'create' => false,
                'multiple' => true,
                'add_wildcard' => false,
                'placeholder' => ts('- Select Fund -'),
            ], FALSE);
        }

    }

    public function setDefaultValues()
    {
        if ($this->_cid) {
            $defaults = [];
            if ($this->_pagename == 'ApproverTab') {
                $defaults['contact_id_app'] =
                    $this->_cid;

            }
            if ($this->_pagename == 'SocialTab') {
                $defaults['contact_id_sub'] =
                    $this->_cid;
            }
        }
        return $defaults;
    }


}
