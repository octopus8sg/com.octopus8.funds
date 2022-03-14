<?php

use CRM_Funds_ExtensionUtil as E;

class CRM_Funds_Page_OrgTab extends CRM_Core_Page
{

    public function run()
    {
        CRM_Utils_System::setTitle(E::ts('Search Transactions'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $pagename = 'OrgTab';
        $urlQry['pagename'] = $pagename;
        $transactions_source_url = CRM_Utils_System::url('civicrm/fund/transaction_ajax', $urlQry, FALSE, NULL, FALSE);
//        $transactions_source_url = "";
        $sourceUrl['transactions_source_url'] = $transactions_source_url;
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
        $controller_data->assign('pagename', $pagename);
        $controller_data->run();
        parent::run();
    }

}
