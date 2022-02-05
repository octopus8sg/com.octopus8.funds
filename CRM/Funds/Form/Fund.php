<?php

use CRM_Funds_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Funds_Form_Fund extends CRM_Core_Form
{

    protected $_id;

    protected $_myentity;

    public function getDefaultEntity()
    {
        return 'Fund';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_fund';
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
        CRM_Utils_System::setTitle('Add Fund');
        if ($this->_id) {
            CRM_Utils_System::setTitle('Edit Fund');
            $entities = civicrm_api4('Fund', 'get',
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
            $session->replaceUserContext(CRM_Utils_System::url('civicrm/fund/form',
                ['id' => $this->getEntityId(), 'action' => 'update']));
        }
    }


    public function buildQuickForm()
    {
        $this->assign('id', $this->getEntityId());
        $this->add('hidden', 'id');
        if ($this->_action != CRM_Core_Action::DELETE) {
            $props = ['api' => ['params' => ['contact_type' => 'Organization']]];
            $this->addEntityRef('contact_id',
                E::ts('Source Organisation (Contact)'), $props, TRUE);
            $this->add('text', 'code', E::ts('Code'), ['class' => 'huge'], TRUE);
            $this->addRule('code',
                ts('Fund Code should consist of numbers and letters'),
                'alphanumeric', null, 'client');
            $this->add('text', 'name', E::ts('Name'), ['class' => 'huge'], TRUE);
//            $this->addDatePickerRange('fund_dateselect',
//                'Select Date',
//                FALSE,
//                TRUE,
//                "Start Date: ",
//                "End Date: ",
//                [],
//                '_to',
//                '_from'
//            );
            $this->add('datepicker', 'start_date',
                E::ts('Start Date: '), CRM_Core_SelectValues::date(NULL, 'Y-m-d H:i:s'), TRUE, ['time' => FALSE]);
            $this->add('datepicker', 'end_date',
                E::ts('End Date: '), CRM_Core_SelectValues::date(NULL, 'Y-m-d H:i:s'), TRUE, ['time' => FALSE]);
//            $params = ['type' => 'any'];
//            $allCases = CRM_Case_BAO_Case::getCases(TRUE, $params);
//            $this->addElement('select',
//                'target_cases', ts('Target Cases'), $allCases,
//                ['multiple' => TRUE, 'class' => 'crm-select2 huge']);
//
            $this->addEntityRef('target_cases', E::ts('Target Cases'), [
                'entity' => 'case',
                'class' => 'huge',
                'placeholder' => ts('- Select Case -'),
                'multiple' => TRUE,
            ], TRUE);

            $this->add('text', 'amount', ts('Minimum Amount'), ['size' => 8, 'maxlength' => 8], TRUE);
            $this->addRule('amount', ts('Please enter a valid money value (e.g. %1).', [1 => CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency('9.99')]), 'money');

            $this->add('text', 'amount', ts('Amount'));
            $this->addRule('amount', ts('Please enter a valid amount.'), 'money');

//            $noteAttrib = CRM_Core_DAO::getAttribute('CRM_Core_DAO_Note');
            $this->add('textarea', 'description', ts('Description'));

            // add attachments part
            CRM_Core_BAO_File::buildAttachment($this,
                'civicrm_o8_fund',
                $this->_id
            );
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
            $params['entity_table'] = 'civicrm_o8_fund';
            $params['entity_id'] = $this->getEntityId();
            $values['entity_table'] = 'civicrm_o8_fund';
            $values['entity_id'] = $this->getEntityId();
            $params['name'] = $values['name'];
            $params['code'] = $values['code'];
            $params['target_cases'] = $values['target_cases'];
            $params['amount'] = $values['amount'];
            $params['start_date'] = $values['start_date'];
            $params['end_date'] = $values['end_date'];
            $params['contact_id'] = $values['contact_id'];
            // add attachments as needed
            if ($action == 'update') {
                $attach = CRM_Core_BAO_File::formatAttachment($values, $values, 'civicrm_o8_fund', $this->getEntityId());
                $values['modified_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['modified_date'] = date('YmdHis');
//                CRM_Core_Error::debug_var('attach', $attach);
            } else {
                $values['modified_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['modified_date'] = date('YmdHis');
                $values['created_by'] = CRM_Core_Session::getLoggedInContactID();
                $values['created_date'] = date('YmdHis');
                $attach = CRM_Core_BAO_File::formatAttachment($values, $values, 'civicrm_o8_fund');
//                CRM_Core_Error::debug_var('attach', $attach);
            }
            CRM_Funds_BAO_Fund::create($values);
//            $result = civicrm_api4('Fund', $action, ['values' => $params]);
            CRM_Core_Session::setStatus(ts('Your Fund has been saved.'), ts('Saved'), 'success');
        }
        parent::postProcess();
    }

}
