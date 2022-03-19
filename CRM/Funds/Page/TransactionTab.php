<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_TransactionTab extends CRM_Core_Page
{

    public function run()
    {

// This part differs for different search pages
        CRM_Utils_System::setTitle(E::ts('Financial Managers Transactions '));
        $urlQry['snippet'] = 4;
        $pageName = 'TransactionTab';
        $ajaxSourceName = 'transactions_source_url';
        $urlQry['snippet'] = 4;
        $contactId = CRM_Utils_Request::retrieve('cid', 'Positive');
        $contactType = CRM_Contact_BAO_Contact::getContactType($contactId);
        $isApprover = $isSocial = $isOrganization = FALSE;
        if ($contactType == 'Organization') {
            $isOrganization = TRUE;
        }

        $urlQry['cid'] = $contactId;
        $urlQry['pagename'] = $pageName;
        $ajaxSourceUrl = CRM_Utils_System::url('civicrm/fund/transaction_ajax', $urlQry, FALSE, NULL, FALSE);
// End this part differs for different search pages

        $sourceUrl[$ajaxSourceName] = $ajaxSourceUrl;
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
        $controller_data->assign('pagename', $pageName);
        $controller_data->run();

//        $financial_manager_group_id = _find_financial_manager_group_id();
        $social_worker_group_id = _find_social_worker_group_id();
//    CRM_Core_Error::debug_var('contactId', $contactId);

        $myEntities = civicrm_api3('FundTransaction', 'getcount', [
            'created_by' => $contactId,
        ]);
        $this->assign('submissions', $myEntities);

//        $isApprover = boolval(CRM_Contact_BAO_GroupContact::isContactInGroup($contactId, $financial_manager_group_id));
        $isSocial = boolval(CRM_Contact_BAO_GroupContact::isContactInGroup($contactId, $social_worker_group_id));
        $contact = \Civi\Api4\Contact::get(0)
            ->addWhere('id', '=', $contactId)
            ->execute()->single();
        $contactType = $contact['contact_type'];
            $myEntities = civicrm_api3('FundTransaction', 'getcount', [
                'contact_id_app' => $contactId,
            ]);
            $this->assign('approvals', $myEntities);
        if ($isSocial) {
            $myEntities = civicrm_api3('FundTransaction', 'getcount', [
                'contact_id_sub' => $contactId,
            ]);
            $this->assign('social_worker', $myEntities);
        }
        if ($isOrganization) {
            $myEntities = civicrm_api3('FundTransaction', 'getcount', [
                'fund.contact_id' => $contactId,
            ]);

            $this->assign('organisation', $myEntities);

        }
        parent::run();
    }

}
