<?php

use CRM_Funds_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Funds_Form_Transaction extends CRM_Core_Form
{

    protected $_id;

    protected $_myentity;

    public function getDefaultEntity()
    {
        return 'FundTransaction';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_fund_transaction';
    }

    public function getEntityId()
    {
        return $this->_id;
    }

    /**
     * Preprocess form.
     *
     * This is called before buildForm. Any pre-processing that
     * needs to be done for buildForm should be done here.
     *
     * This is a virtual function and should be redefined if needed.
     */
    public function preProcess()
    {
        parent::preProcess();

        $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this);
        $this->assign('action', $this->_action);

        $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE);
        CRM_Utils_System::setTitle('Add Fund Transaction');
        if ($this->_id) {
            CRM_Utils_System::setTitle('Edit Fund Transaction');
            $entities = civicrm_api4('FundTransaction', 'get',
                ['where' => [
                    ['id', '=', $this->_id]],
                    'limit' => 1
                ]
            );
            if (!empty($entities)) {
                $this->_myentity = $entities[0];
            }
            $this->assign('myentity', $this->_myentity);

            $session = CRM_Core_Session::singleton();
            $session->replaceUserContext(CRM_Utils_System::url('civicrm/fund/transaction',
                ['id' => $this->getEntityId(), 'action' => 'update']));
        }
    }


    public function buildQuickForm()
    {
        $this->assign('id', $this->getEntityId());
        $this->add('hidden', 'id'); // 1
        $editfields = TRUE;
        if ($this->_action != CRM_Core_Action::DELETE) {
//            $props = ['api' => ['params' => ['contact_type' => 'Organization']]];
//            1 - id
//            2 - date
//            3 - description
//            4 - amount
//            5 - attachment
//            6 - status
//            7 - case_id
//            8 - contact_id_sub
//            9 - contact_id_app
//            10 - component_id
//            11 - account_id
//            12 - created_by
//            13 - created_on
//            14 - updated_by
//            15 - updated _on
            //2
            $this->add('datepicker', 'date',
                E::ts('Date: '), CRM_Core_SelectValues::date(NULL, 'Y-m-d H:i:s'), TRUE, ['time' => FALSE]);
            //3
            $noteAttrib = CRM_Core_DAO::getAttribute('CRM_Core_DAO_Note');
            $this->add('textarea', 'description', ts('Description'), $noteAttrib['note'], TRUE);

            //4
            $this->add('text', 'amount', ts('Amount'), ['size' => 8, 'maxlength' => 8], TRUE);
            $this->addRule('amount', ts('Please enter a valid money value (e.g. %1).', [1 => CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency('9.99')]), 'money');

            //5
            // add attachments part - can be added only if editfields is true
            if ($editfields) {
                CRM_Core_BAO_File::buildAttachment($this,
                    'civicrm_o8_fund_transaction',
                    $this->_id, 3, true
                );
            } else {
                $currentAttachmentInfo = CRM_Core_BAO_File::getEntityFile('civicrm_o8_fund_transaction', $this->_id);
                $this->assign('currentAttachmentInfo', $currentAttachmentInfo);
            }
            //6 todo add options in Updater and in postProcess
            $statuses = CRM_Core_OptionGroup::values('o8_fund_trxn_status');
            $this->add('select', 'status_id',
                E::ts('Status'),
                $statuses,
                TRUE, ['class' => 'huge crm-select2',
//                    'data-option-edit-path' => 'civicrm/admin/options/o8_fund_trxn_status'
                ])->freeze();

            //7 case
            $this->addEntityRef('case_id', E::ts('Case'), [
                'entity' => 'case',
                'class' => 'huge',
                'placeholder' => ts('- Select Case -'),
//                'multiple' => TRUE,
            ], TRUE);

            //8
            $props = [];
            $this->addEntityRef('contact_id_sub',
                E::ts('Contact (Social Worker)'), $props, TRUE);
            //9
            $this->addEntityRef('contact_id_app',
                E::ts('Contact (Approver)'), $props, TRUE);


            //10
            $this->addEntityRef('component_id', E::ts('Component'), [
                'entity' => 'fund_component',
                'api' => [
                    'search_field' => ['id', 'code', 'name', 'description'],
                    'label_field' => "name",
                    'description_field' => [
                        'code',
                        'description',
                    ]
                ],
                'class' => 'huge',
                'placeholder' => ts('- Select Component -'),
//                'multiple' => TRUE,
            ], TRUE);

            //11
            $this->addEntityRef('account_id', E::ts('Account'), [
                'entity' => 'fund_account',
                'api' => [
                    'search_field' => ['id', 'code', 'name', 'description'],
                    'label_field' => "name",
                    'description_field' => [
                        'code',
//                        'description',
                        'fund_id.code',
                        'fund_id.name',
                    ]
                ],
                'class' => 'huge',
                'placeholder' => ts('- Select Account -'),
//                'multiple' => TRUE,
            ], TRUE);

//            $this->add('text', 'amount', ts('Amount'));
//            $this->addRule('amount', ts('Please enter a valid amount.'), 'money');
            // todo will be changed by transaction api or by hook?

            $this->addButtons([
                [
                    'type' => 'upload',
                    'name' => E::ts('Submit'),
                    'isDefault' => TRUE,
                ],
            ]);
        } else {
            CRM_Utils_System::setTitle('Delete Fund');
            $this->addButtons([
                ['type' => 'submit', 'name' => E::ts('Delete'), 'isDefault' => TRUE],
                ['type' => 'cancel', 'name' => E::ts('Cancel')]
            ]);
        }

        parent::buildQuickForm();
    }

    /**
     * This virtual function is used to set the default values of various form
     * elements.
     *
     * @return array|NULL
     *   reference to the array of default values
     */
    public function setDefaultValues()
    {
        if ($this->_myentity) {
            $defaults = $this->_myentity;
        } else {
            $defaults['status_id'] = 1;
            $defaults['date'] = date("Y-m-d H:i:s");
        }
        return $defaults;
    }

    public function postProcess()
    {
        if ($this->_action == CRM_Core_Action::DELETE) {
            civicrm_api4('Fund', 'delete', ['where' => [['id', '=', $this->_id]]]);
            CRM_Core_Session::setStatus(E::ts('Removed The Fund'), E::ts('Fund'), 'success');
        } else {

            $values = $this->controller->exportValues();
            $fileparams = $values;
//            CRM_Core_Error::debug_var('values', $values);

            $action = 'create';
            if ($this->getEntityId()) {
                $params['id'] = $this->getEntityId();
                $action = 'update';
            }
//            $params['entity_table'] = 'civicrm_o8_fund';
//            $params['entity_id'] = $this->getEntityId();
            $values['entity_table'] = 'civicrm_o8_fund_transaction';
            $values['entity_id'] = $this->getEntityId();
//            $params['name'] = $values['name'];
//            $params['code'] = $values['code'];
//            $params['target_cases'] = $values['target_cases'];
//            $params['amount'] = $values['amount'];
//            $params['start_date'] = $values['start_date'];
//            $params['end_date'] = $values['end_date'];
//            $params['contact_id'] = $values['contact_id'];
            // add attachments as needed
            if ($action == 'update') {
                $attach = CRM_Core_BAO_File::formatAttachment($values, $values, 'civicrm_o8_fund_transaction', $this->getEntityId());
                $values['modified_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['modified_date'] = date('YmdHis');
                $values['status_id'] = 1; //status values to constants?
//                CRM_Core_Error::debug_var('attach', $attach);
            } else {
                $values['modified_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['modified_date'] = date('YmdHis');
                $values['created_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['created_date'] = date('YmdHis');
                if (!isset($values['status_id'])) {
                    $values['status_id'] = 1;
                }
                $attach = CRM_Core_BAO_File::formatAttachment($values, $values, 'civicrm_o8_fund_transaction');
//                CRM_Core_Error::debug_var('attach', $attach);
            }
            CRM_Funds_BAO_FundTransaction::create($values);
//            $result = civicrm_api4('Fund', $action, ['values' => $params]);
            CRM_Core_Session::setStatus(ts('Your Transaction has been saved.'), ts('Saved'), 'success');
        }
        parent::postProcess();
    }

}
