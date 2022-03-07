{crmScope extensionKey='com.octopus8.funds'}
    <h3 class="crm-content-block">
            {*                    {debug}*}
        <div class="clear"></div>
        {include file="CRM/Funds/Form/FundDashboardFilter.tpl"}
        <div class="clear"></div>
        <h3>Funding Summary</h3>
        <div class="crm-results-block">
            <div class="crm-search-results">
                {include file="CRM/common/enableDisableApi.tpl"}
                {include file="CRM/common/jsortable.tpl"}
                <table class="selector-funding row-highlight pagerDisplay" id="Funding" name="Funding">
                    <thead class="sticky">
                    <tr>
                        <th id="sortable"  scope="col">
                            {ts}FUND CODE{/ts}
                        </th>
                        <th scope="col">
                            {ts}FUND NAME{/ts}
                        </th>
                        <th scope="col">
                            {ts}START DATE{/ts}
                        </th>
                        <th scope="col">
                            {ts}END DATE{/ts}
                        </th>
                        <th scope="col">
                            {ts}FUND BUDGET{/ts}
                        </th>
                        <th scope="col">
                            {ts}FUND EXPENDITURE{/ts}
                        </th>
                        <th scope="col">
                            {ts}FUND RESIDUE{/ts}
                        </th>
                        <th scope="col">
                            {ts}FUND BALANCE{/ts}
                        </th>
                        <th id="nosort">
                            {ts}FUND PROJECTION{/ts}
                        </th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
        <h3>Contact Summary</h3>
        <div class="crm-results-block">
            <div class="crm-search-results">
                <table>
                <table class="selector-contact row-highlight pagerDisplay" id="DashboardContact" name="DashboardContact">
                    <thead class="sticky">
                    <tr>
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
                            {ts}Clients Target{/ts}
                        </th>
                        <th scope="col">
                            {ts}Clients Current{/ts}
                        </th>
                        <th id="nosort">Clients Balance</th>
                        <th scope="col">
                            {ts}Social Workers{/ts}
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
{crmScript ext=com.octopus8.funds file=js/summary.js}
{crmScript ext=com.octopus8.funds file=js/contact.js}
{/crmScope}