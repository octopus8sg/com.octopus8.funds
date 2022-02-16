<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
    [
        'name' => 'CRM_Funds_Form_Report_AccountsSummary',
        'entity' => 'ReportTemplate',
        'params' => [
            'version' => 3,
            'label' => 'Accounts Summary',
            'description' => 'AccountsSummary (com.octopus8.funds)',
            'class_name' => 'CRM_Funds_Form_Report_AccountsSummary',
            'report_url' => 'com.octopus8.funds/accountssummary',
            'component' => '',
            'grouping' => 'Funding',
        ],
    ],
];
