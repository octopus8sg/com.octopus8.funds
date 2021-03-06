{crmScope extensionKey='com.octopus8.funds'}
    <div class="crm-content-block">
        <div class="clear"></div>
        {include file="CRM/Funds/Form/TransactionORGFilter.tpl"}
        <div class="clear"></div>
        <div class="clear"></div>
        <div class="crm-results-block">
            <div class="crm-search-results">
                {include file="CRM/common/enableDisableApi.tpl"}
                {include file="CRM/common/jsortable.tpl"}
                <table class="org-selector-transactions row-highlight pagerDisplay" id="Transactions"
                       name="Transactions">
                    <thead class="sticky">
                    <tr>
                        <th id="sortable" scope="col">
                            {ts}ID{/ts}
                        </th>
                        <th scope="col">
                            Date
                        </th>
                        <th scope="col">
                            {ts}Description{/ts}
                        </th>
                        <th scope="col">
                            {ts}Amount{/ts}
                        </th>
                        <th scope="col">
                            {ts}Account{/ts}
                        </th>
                        <th scope="col">
                            {ts}SubAccount{/ts}
                        </th>
                        <th scope="col">
                            {ts}Contact (Social Worker){/ts}
                        </th>
                        <th scope="col">
                            {ts}Contact (Approver){/ts}
                        </th>
                        <th scope="col">
                            {ts}Contact (Created By){/ts}
                        </th>
                        <th scope="col">
                            {ts}Case{/ts}
                        </th>
                        <th scope="col">
                            {ts}Fund{/ts}
                        </th>
                        <th scope="col">
                            {ts}Status{/ts}
                        </th>
                        <th id="nosort">&nbsp;Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
{crmScript ext=com.octopus8.funds file=js/orgtransaction.js}
{/crmScope}