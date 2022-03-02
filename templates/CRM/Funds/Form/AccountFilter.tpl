<div class="crm-content-block">

    <div class="crm-block crm-form-block crm-basic-criteria-form-block">
        <div class="crm-accordion-wrapper crm-expenses_search-accordion">
            <div class="crm-accordion-header crm-master-accordion-header">{ts}Filter Account Data{/ts}</div>
            <!-- /.crm-accordion-header -->
            <div class="crm-accordion-body">
                <table class="form-layout account-filter">
                    <tbody>
                    <tr>
                        <td class="label">{$form.account_id.label}</td>
                        <td>{$form.account_id.html}</td>
                    </tr>
                    <tr>
                        <td class="label">{$form.account_name.label}</td>
                        <td>{$form.account_name.html}</td>
                    </tr>
                    <tr>
                        <td class="label">{$form.account_account_type_id.label}</td>
                        <td>{$form.account_account_type_id.html}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <!- /.crm-accordion-body -->
        </div><!-- /.crm-accordion-wrapper -->
    </div><!-- /.crm-form-block -->
</div>
{* {debug}*}
