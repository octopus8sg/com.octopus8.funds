<div class="crm-content-block">

    <div class="crm-block crm-form-block crm-basic-criteria-form-block">
        <div class="crm-accordion-wrapper crm-expenses_search-accordion">
            <div class="crm-accordion-header crm-master-accordion-header">{ts}Filter Transaction Data{/ts}</div>
            <!-- /.crm-accordion-header -->
            <div class="crm-accordion-body">
                <table class="form-layout transaction-filter">
                    <tbody>
                    <tr>
                        <td class="label">{$form.contact_id_sub.label}</td>
                        <td>{$form.contact_id_sub.html}</td>
                        <td class="label">{$form.contact_id_app.label}</td>
                        <td>{$form.contact_id_app.html}</td>
                    </tr>
                    <tr>
                        <td class="label">{$form.transaction_status_id.label}</td>
                        <td>{$form.transaction_status_id.html}</td>
                        <td class="label">{$form.transaction_case_id.label}</td>
                        <td>{$form.transaction_case_id.html}</td>
                    </tr>
                    <tr>
                        <td class="label">{$form.transaction_account_id.label}</td>
                        <td>{$form.transaction_account_id.html}</td>
                        <td class="label">{$form.transaction_component_id.label}</td>
                        <td>{$form.transaction_component_id.html}</td>
                    </tr>
                    <tr>
                        <td class="label">{$form.transaction_dateselect_from.label}</td>
                        <td>{$form.transaction_dateselect_from.html}</td>
                        <td class="label">{$form.transaction_dateselect_to.label}</td>
                        <td>{$form.transaction_dateselect_to.html}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <!- /.crm-accordion-body -->
        </div><!-- /.crm-accordion-wrapper -->
    </div><!-- /.crm-form-block -->
</div>
{* {debug}*}
