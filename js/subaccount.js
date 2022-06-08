CRM.$(function ($) {

    $("a.add-sub_account").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-sub_accounts');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var sub_accounts_sourceUrl = CRM.vars.source_url['sub_accounts_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //subaccounts datatable
        var sub_accounts_tab = $('.selector-sub_accounts');
        var sub_accounts_table = sub_accounts_tab.DataTable();
        var sub_accounts_dtsettings = sub_accounts_table.settings().init();
        sub_accounts_dtsettings.bFilter = true;
        //turn on search

        sub_accounts_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        sub_accounts_dtsettings.sAjaxSource = sub_accounts_sourceUrl;
        sub_accounts_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        sub_accounts_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-sub_account").css('background','red');
            $("a.update-sub_account").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-sub_accounts');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-sub_account").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-sub_accounts');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        sub_accounts_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "sub_account_id",
                "value": $('#sub_account_id').val() });
            aoData.push({ "name": "sub_account_name",
                "value": $('#sub_account_name').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        sub_accounts_table.destroy();
        var new_sub_accounts_table = sub_accounts_tab.DataTable(sub_accounts_dtsettings);
        //End Reset Table
        $('.subaccount-filter :input').change(function(){
            new_sub_accounts_table.draw();
        });

    });


});