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

    protected $_cid;

    protected $_contact_id;

    protected $_isNew;

    protected $_isPendingApproval;

    protected $_isApproved;

    protected $_isRejected;

    protected $_isAdmin;

    protected $_isApprover;

    protected $_isSocial;

    protected $_acceptButtonName;

    protected $_changeitButtonName;

    protected $_reviewButtonName;

    protected $_rejectButtonName;

    protected $_withdrawButtonName;

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
        $this->_isNew = TRUE;
        $this->_cid = CRM_Utils_Request::retrieve('cid', 'Positive', $this, FALSE);;
        $currentUserId = CRM_Core_Session::getLoggedInContactID();
        $this->_contact_id = $currentUserId;
        if (CRM_Core_Permission::check('administer CiviCRM')) {
            $this->_isAdmin = TRUE;
        }

        if ($this->_id) {
            CRM_Utils_System::setTitle('Edit Fund Transaction');
            $entities = civicrm_api4('FundTransaction', 'get',
                ['where' => [
                    ['id', '=', $this->_id]],
                    'limit' => 1
                ]
            );
            if (!empty($entities)) {
                $this->_isNew = FALSE;
                $entity = $entities[0];
                $this->_myentity = $entity;
//                CRM_Core_Error::debug_var('entity', $entity);

                $isApprover = intval($entity['contact_id_app']);
                if ($currentUserId == $isApprover) {
                    $this->_isApprover = TRUE;
                }
                $isSocial = intval($entity['contact_id_sub']);
                if ($currentUserId == $isSocial) {
                    $this->_isSocial = TRUE;
                }

                if ($entity['status_id'] == 1) {
                    $this->_isPendingApproval = TRUE;
                }

                if ($entity['status_id'] == 2) {
                    $this->_isApproved = TRUE;
                }

                if ($entity['status_id'] == 3) {
                    $this->_isRejected = TRUE;
                }

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

            $date = $this->add('datepicker', 'date',
                E::ts('Date: '), CRM_Core_SelectValues::date(NULL, 'Y-m-d H:i:s'), TRUE, ['time' => FALSE]);
            if ($this->_isApproved) {
                $date->freeze();
            }
            //3

            $noteAttrib = CRM_Core_DAO::getAttribute('CRM_Core_DAO_Note');
            $description = $this->add('textarea', 'description', ts('Description'), $noteAttrib['note'], TRUE);
            if ($this->_isApproved) {
                $description->freeze();
            }

            //4
            $amount = $this->add('text', 'amount', ts('Amount'), ['size' => 8, 'maxlength' => 8], TRUE);

            $this->addRule('amount', ts('Please enter a valid money value (e.g. %1).', [1 => CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency('9.99')]), 'money');
            if ($this->_isApproved) {
                $description->freeze();
            }

            //5
            // add attachments part - can be added only if editfields is true
            if (!$this->_isApproved) {
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
            $status = $this->add('select', 'status_id',
                E::ts('Status'),
                $statuses,
                TRUE, ['class' => 'huge crm-select2',
//                    'data-option-edit-path' => 'civicrm/admin/options/o8_fund_trxn_status'
                ]);
            if (!$this->_isAdmin) {
                $status->freeze();
            }
            //7 case
            $case = $this->addEntityRef('case_id', E::ts('Case'), [
                'entity' => 'case',
                'class' => 'huge',
                'placeholder' => ts('- Select Case -'),
//                'multiple' => TRUE,
            ], TRUE);
            if ($this->_isApproved) {
                $case->freeze();
            }

            //8
            $props = [];
            $contact_id_sub = $this->addEntityRef('contact_id_sub',
                E::ts('Contact (Social Worker)'), $props, TRUE);
            if ($this->_isApproved) {
                $contact_id_sub->freeze();
            }
            if($this->_isSocial){
                $contact_id_sub->freeze();
            }

            //9
            $contact_id_app = $this->addEntityRef('contact_id_app',
                E::ts('Contact (Approver)'), $props, TRUE);
            if ($this->_isApproved) {
                $contact_id_app->freeze();
            }


            //10
            $component_id = $this->addEntityRef('component_id', E::ts('Component'), [
                'entity' => 'fund_component',
                'api' => [
                    'search_fields' => ['code', 'name'],
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
            if ($this->_isApproved) {
                $component_id->freeze();
            }
            //11
            $account_id = $this->addEntityRef('account_id', E::ts('Account'), [
                'entity' => 'fund_account',
                'api' => [
                    'search_fields' => ['code', 'name'],
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
            if ($this->_isApproved) {
                $account_id->freeze();
            }
            if ($this->_isRejected) {
                $this->freeze();
                if ($this->_isAdmin) {
                    $status->unfreeze();
                }
            }
//            $this->add('text', 'amount', ts('Amount'));
//            $this->addRule('amount', ts('Please enter a valid amount.'), 'money');
            // todo will be changed by transaction api or by hook?
            $this->_changeitButtonName = $this->getButtonName('submit', 'changeit');
            $this->_acceptButtonName = $this->getButtonName('submit', 'accept');
            $this->_reviewButtonName = $this->getButtonName('submit', 'review');
            $this->_rejectButtonName = $this->getButtonName('submit', 'reject');
            $this->_withdrawButtonName = $this->getButtonName('submit', 'withdraw');
            if ($this->_isNew) {
                $this->addButtons([
                    [
                        'type' => 'upload',
                        'name' => E::ts('Submit'),
                        'isDefault' => TRUE,
                    ],
                ]);
            } else {
                $review = [
                    'type' => 'submit',
                    'subName' => 'review',
                    'name' => E::ts('Review Later'),
                    'icon' => 'fa-clock-o',
                ];
                $changeit = [
                    'type' => 'submit',
                    'subName' => 'changeit',
                    'name' => E::ts('Update'),
                    'isDefault' => TRUE,
                ];
                $accept = [
                    'type' => 'submit',
                    'subName' => 'accept',
                    'name' => E::ts('Accept'),
                    'subName' => 'accept'
                ];
                $reject = [
                    'type' => 'submit',
                    'subName' => 'reject',
                    'name' => E::ts('Reject'),
                    'icon' => 'fa-times',
                ];
                $withdraw = [
                    'type' => 'submit',
                    'subName' => 'withdraw',
                    'name' => E::ts('Withdraw'),
                    'icon' => 'fa-trash',
                ];
                $cancel = [
                    'type' => 'cancel',
                    'name' => E::ts('Cancel')];
                    $buttons[] = $cancel;
                if($this->_isPendingApproval){
                    if($this->_isSocial){
                        $buttons[] = $accept;
                        $buttons[] = $changeit;
                        $buttons[] = $reject;
                    }
                    if($this->_isAdmin){
                        $buttons[] = $changeit;
                    }else{
                        $status->freeze();
                    }

                }elseif($this->_isRejected){
                    if($this->_isAdmin){
                        $buttons[] = $changeit;
                    }
                }
                else{
                    if($this->_isAdmin){
                        $buttons[] = $changeit;
                    }else{
                        $status->freeze();
                    }
                    if($this->_isApprover){
                        $buttons[] = $reject;
                        $buttons[] = $accept;
                        $status->unfreeze();
                    }

                }
                $this->addButtons($buttons);
            }
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
            $post = $_POST;
            $changeit = $post[$this->_changeitButtonName];
            $accept = $post[$this->_acceptButtonName];
            $review = $post[$this->_reviewButtonName];
            $reject = $post[$this->_rejectButtonName];
            $withdraw = $post[$this->_withdrawButtonName];

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
                if ($reject) {
                    $values['status_id'] = 3; //status values to constants?
                }
                if ($withdraw) {
                    $values['status_id'] = 3; //status values to constants?
                }
                if ($accept) {
                    $values['status_id'] = 2; //status values to constants?
                }
//                CRM_Core_Error::debug_var('attach', $attach);
            } else {
                $values['modified_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['modified_date'] = date('YmdHis');
                $values['created_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['created_date'] = date('YmdHis');
//                if (!isset($values['status_id'])) {
//                    $values['status_id'] = 1;
//                }
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
