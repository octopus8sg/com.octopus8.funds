{crmScope extensionKey='com.octopus8.funds'}
    <div class="transaction-tab view-content">
        {*            <div class="action-link">*}
        {*                <a class="button" target="_blank" href="{crmURL p="civicrm/device/makedata" q="cid=$contactId"}">*}
        {*                    <i class="crm-i fa-plus-circle">&nbsp;</i>*}
        {*                    {ts}Add Sample Data {/ts}*}
        {*                </a>*}
        {*                <a class="button" target="_blank" href="{crmURL p="civicrm/device/makerules" q="cid=$contactId"}">*}
        {*                    <i class="crm-i fa-plus-circle">&nbsp;</i>*}
        {*                    {ts}Add Default Device Alert / Alarm Rules{/ts}*}
        {*                </a>*}
        {*            </div>*}
        <div id="secondaryTabContainer1" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            {include file="CRM/common/TabSelected.tpl" defaultTab="data" tabContainer="#secondaryTabContainer1"}

            <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
                <li id="tab_data"
                    class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab ui-tabs-active ui-state-active">
                    <a href="#own-subtab" title="{ts}Own Transactions{/ts}">
                        {ts}Submissions{/ts} <em>{$submissions}</em>
                    </a>
                </li>
                <li id="tab_devices"
                    class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab">
                    <a href="#fm-subtab" title="{ts}Financial Manager{/ts}">
                        {ts}Approvals{/ts} <em>{$approvals}</em>
                    </a>
                </li>
                {if $social_worker}
                <li id="tab_analytics"
                    class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab">
                    <a href="#sw-subtab" title="{ts}Social Worker{/ts}">
                        {ts}Social Worker{/ts} <em>{$social_worker}</em>
                    </a>
                </li>
                {/if}
                {if $organisation}
                <li id="tab_alarm_rules"
                    class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab">
                    <a href="#org-subtab" title="{ts}Organisation{/ts}">
                        {ts}Organisation{/ts} <em>{$organisation}</em>
                    </a>
                </li>
                {/if}
            </ul>

            <div id="own-subtab" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
                {include file="CRM/Funds/Page/ContactTab.tpl"}
            </div>
            {if $financial_manager}
            <div id="fm-subtab" class="devices-subtab ui-tabs-panel ui-widget-content ui-corner-bottom">
                {include file="CRM/Funds/Page/ApproverTab.tpl"}
            </div>
            {/if}
            {if $social_worker}
            <div id="sw-subtab" class="analytics-subtab ui-tabs-panel ui-widget-content ui-corner-bottom">
                {include file="CRM/Funds/Page/SocialTab.tpl"}
            </div>
            {/if}
            {if $organisation}
            <div id="org-subtab" class="alarm-rules-subtab ui-tabs-panel ui-widget-content ui-corner-bottom">
                {include file="CRM/Funds/Page/OrgTab.tpl"}
            </div>
            {/if}
            <div class="clear"></div>
        </div>
    </div>
{/crmScope}

{literal}
    <script type="text/javascript">
        CRM.$(function ($) {
            $('input.hasDatepicker')
                .crmDatepicker({
                    format: "yy-mm-dd",
                    altFormat: "yy-mm-dd",
                    dateFormat: "yy-mm-dd"
                });

        });
        // CRM.$(function($) {
        //   $("input[name='dateselect_to']").datepicker({
        //     format: "yy-mm-dd",
        //     altFormat: "yy-mm-dd",
        //     dateFormat: "yy-mm-dd"
        //   });
        //   $("input[name='dateselect_from']").datepicker({
        //     format: "yy-mm-dd",
        //     altFormat: "yy-mm-dd",
        //     dateFormat: "yy-mm-dd"
        //   });
        // });
    </script>
{/literal}
