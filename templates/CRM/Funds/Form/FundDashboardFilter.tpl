<div class="crm-content-block">

    <div class="crm-block crm-form-block crm-basic-criteria-form-block">
        <div class="crm-accordion-wrapper crm-expenses_search-accordion">
            <div class="crm-accordion-header crm-master-accordion-header">{ts}Filter Fund Dashboard{/ts}</div>
            <!-- /.crm-accordion-header -->
            <div class="crm-accordion-body">
                <table class="form-layout fund-filter">
                    <tbody>
                    <tr>
                        <td class="label">Fund Code</td>
                        <td>{$form.fund_id.html}</td>
                    </tr>
                    <tr>
                        <td class="label">Source Organisation</td>
                        <td>{$form.fund_contact_id.html}</td>
                    </tr>
                    <tr>
                        <td class="label">Fund Name</td>
                        <td>{$form.fund_name.html}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <!- /.crm-accordion-body -->
        </div><!-- /.crm-accordion-wrapper -->
    </div><!-- /.crm-form-block -->
</div>
{* {debug}*}
