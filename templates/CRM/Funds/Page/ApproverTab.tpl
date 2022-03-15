{crmScope extensionKey='com.octopus8.funds'}
    <div class="crm-content-block">
        <div class="clear"></div>
        {include file="CRM/Funds/Form/TransactionFMFilter.tpl"}
        <div class="clear"></div>
        <div class="action-link">
            {*                    {debug}*}
            <a class="button add-transaction" href="{crmURL p="civicrm/fund/transaction" q="reset=1&action=add" }">
                <i class="crm-i fa-plus">&nbsp;</i>
                {ts}Add Transaction{/ts}
            </a>
            <a class="button add-transaction" href="{crmURL p="civicrm/fund/transaction" q="reset=1&action=add" }">
                <i class="crm-i fa-plus-circle">&nbsp;</i>
                {ts}Import Transaction{/ts}
            </a>
            <a class="button select-all-transactions" href="#" }">
            <i class="crm-i fa-check">&nbsp;</i>{ts}Select All{/ts}
            </a>
            <a class="button approve-transactions" href="#" }">
            <i class="crm-i fa-circle">&nbsp;</i>{ts}Approve Selected{/ts}
            </a>
            </a>
            <a class="button reject-transactions" href="#" }">
            <i class="crm-i fa-times">&nbsp;</i>{ts}Reject Selected{/ts}
            </a>
        </div>
        <div class="clear"></div>
        <div class="crm-results-block">
            <div class="crm-search-results">
                {include file="CRM/common/enableDisableApi.tpl"}
                {include file="CRM/common/jsortable.tpl"}
                <table class="selector-fm-transactions row-highlight pagerDisplay" id="Transactions" name="Transactions">
                    <thead class="sticky">
                    <tr>
                        <th id="sortable" scope="col">
                            {ts}ID{/ts}
                        </th>
                        <th scope="col">
                            {ts}Date{/ts}
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
{crmScript ext=com.octopus8.funds file=js/transaction.js}
{/crmScope}