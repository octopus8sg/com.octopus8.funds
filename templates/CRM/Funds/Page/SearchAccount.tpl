{crmScope extensionKey='com.octopus8.funds'}
    <div class="crm-content-block">
        <div class="action-link">
            {*                    {debug}*}
            <a class="button add-account" href="{crmURL p="civicrm/fund/account" q="reset=1&action=add" }">
                <i class="crm-i fa-plus-circle">&nbsp;</i>
                {ts}Add Account{/ts}
            </a>
        </div>
        <div class="clear"></div>
        {include file="CRM/Funds/Form/AccountFilter.tpl"}
        <div class="clear"></div>
        <div class="crm-results-block">
            <div class="crm-search-results">
                {include file="CRM/common/enableDisableApi.tpl"}
                {include file="CRM/common/jsortable.tpl"}
                <table class="selector-accounts row-highlight pagerDisplay" id="Accounts" name="Accounts">
                    <thead class="sticky">
                    <tr>
                        <th id="sortable" scope="col">
                            {ts}ID{/ts}
                        </th>
                        <th scope="col">
                            {ts}Code{/ts}
                        </th>
                        <th scope="col">
                            {ts}Name{/ts}
                        </th>
                        <th scope="col">
                            {ts}Type{/ts}
                        </th>
                        <th scope="col">
                            {ts}Description{/ts}
                        </th>
                        <th id="nosort">&nbsp;Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    {crmScript ext=com.octopus8.funds file=js/accounts.js}
{/crmScope}