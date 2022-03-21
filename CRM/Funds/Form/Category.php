<?php

use CRM_Funds_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Funds_Form_Category extends CRM_Core_Form
{
    protected $_id;

    protected $_myentity;

    public function getDefaultEntity()
    {
        return 'FundCategory';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_fund_category';
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
        CRM_Utils_System::setTitle('Add Category');
        if ($this->_id) {
            CRM_Utils_System::setTitle('Edit Category');
            $entities = civicrm_api4('FundCategory', 'get',
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
            $session->replaceUserContext(CRM_Utils_System::url('civicrm/fund/category',
                ['id' => $this->getEntityId(), 'action' => 'update']));
        }else{
            $session = CRM_Core_Session::singleton();
            $session->replaceUserContext(CRM_Utils_System::url('civicrm/fund/categorysearch'));
        }
    }


    public function buildQuickForm()
    {
        $this->assign('id', $this->getEntityId());
        $this->add('hidden', 'id');
        if ($this->_action != CRM_Core_Action::DELETE) {
            $this->add('text', 'code', E::ts('Code'), ['class' => 'huge'], TRUE);
            $this->addRule('code', ts('Code already exists in Database.'),
                'objectExists', [
                    'CRM_Funds_DAO_FundCategory',
                    $this->_id,
                    'code',
                    CRM_Core_Config::domainID(),
                ]);

            $this->addRule('code',
                ts('Code should consist of numbers and letters'),
                'alphanumeric', null, 'client');
            $this->add('text', 'name', E::ts('Name'), ['class' => 'huge'], TRUE);

            $noteAttrib = CRM_Core_DAO::getAttribute('CRM_Core_DAO_Note');
            $this->add('textarea', 'description', ts('Description'), $noteAttrib['note']);

            if ($this->_action == CRM_Core_Action::VIEW || $this->_action == CRM_Core_Action::PREVIEW || (!$this->_isAdmin && !$this->_isApprover && !$this->_isSocial)) {
                CRM_Utils_System::setTitle('View Category');
                $this->freeze();
                $cancel = [
                    'type' => 'cancel',
                    'name' => E::ts('Close')];
                $buttons[] = $cancel;
                $this->addButtons($buttons);
            } else {
                $this->addButtons([
                    [
                        'type' => 'upload',
                        'name' => E::ts('Submit'),
                        'isDefault' => TRUE,
                    ],
                ]);
            }
        } else {
            CRM_Utils_System::setTitle('Delete Category');
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
            civicrm_api4('FundCategory', 'delete', ['where' => [['id', '=', $this->_id]]]);
            CRM_Core_Session::setStatus(E::ts('Removed Category'), E::ts('Category'), 'success');
        } else {

            $values = $this->controller->exportValues();
//            CRM_Core_Error::debug_var('values', $values);

            $action = 'create';
            if ($this->getEntityId()) {
                $params['id'] = $this->getEntityId();
                $action = 'update';
            }
            $params['name'] = $values['name'];
            $params['code'] = $values['code'];
            $params['description'] = $values['description'];
            // add attachments as needed
            if ($action == 'update') {
                $params['modified_by'] = CRM_Core_Session::getLoggedInContactID();
                $params['modified_at'] = date('YmdHis');
//                CRM_Core_Error::debug_var('attach', $attach);
            } else {
                $params['modified_by'] = CRM_Core_Session::getLoggedInContactID();
                $params['modified_at'] = date('YmdHis');
                $params['created_by'] = CRM_Core_Session::getLoggedInContactID();
                $params['created_at'] = date('YmdHis');
//                CRM_Core_Error::debug_var('attach', $attach);
            }
//            CRM_Funds_BAO_Fund::create($values);
            $result = civicrm_api4('FundCategory', $action, ['values' => $params]);
            CRM_Core_Session::setStatus(ts('Your Category has been saved.'), ts('Saved'), 'success');
        }
        parent::postProcess();
    }

}
