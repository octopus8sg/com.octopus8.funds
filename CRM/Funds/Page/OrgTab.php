<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_OrgTab extends CRM_Core_Page
{

    public function run()
    {
// This part differs for different search pages
        CRM_Utils_System::setTitle(E::ts('Organisation Funds Transactions'));
        $urlQry['snippet'] = 4;
        $pageName = 'OrgTab';
        $ajaxSourceName = 'transactions_source_url';
        $urlQry['snippet'] = 4;
        $contactId = CRM_Utils_Request::retrieve('cid', 'Positive');
        $this->assign('contactId', $contactId);
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
        parent::run();
    }

}
