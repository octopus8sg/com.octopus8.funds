{crmScope extensionKey='com.octopus8.funds'}
    <div class="crm-content-block">
        <div class="action-link">
{*                    {debug}*}
            <a class="button add-fund" href="{crmURL p="civicrm/fund/form" q="reset=1&action=add" }">
                <i class="crm-i fa-plus-circle">&nbsp;</i>
                {ts}Add Fund{/ts}
            </a>
        </div>
        <div class="clear"></div>
        {include file="CRM/Funds/Form/FundFilter.tpl"}
        <div class="clear"></div>
        <div class="crm-results-block">
            <div class="crm-search-results">
                {include file="CRM/common/enableDisableApi.tpl"}
                {include file="CRM/common/jsortable.tpl"}
                <table class="selector-funds row-highlight pagerDisplay" id="Funds" name="Funds">
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
                            {ts}Start Date{/ts}
                        </th>
                        <th scope="col">
                            {ts}End Date{/ts}
                        </th>
                        <th scope="col">
                            {ts}Source Organisation (Contact){/ts}
                        </th>
                        <th scope="col">
                            {ts}Amount{/ts}
                        </th>
                        <th id="nosort">&nbsp;Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
{crmScript ext=com.octopus8.funds file=js/funds.js}
{/crmScope}