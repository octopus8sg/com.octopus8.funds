CRM.$(function ($) {

    $("a.add-account-type").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-account-types');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var account_types_sourceUrl = CRM.vars.source_url['account_types_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //subaccounts datatable
        var account_types_tab = $('.selector-account-types');
        var account_types_table = account_types_tab.DataTable();
        var account_types_dtsettings = account_types_table.settings().init();
        account_types_dtsettings.bFilter = true;
        //turn on search

        account_types_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        account_types_dtsettings.sAjaxSource = account_types_sourceUrl;
        account_types_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        account_types_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-account_type").css('background','red');
            $("a.update-account-type").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-account-types');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.update-account-type").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-account-types');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        account_types_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "account_type_id",
                "value": $('#account_type_id').val() });
            aoData.push({ "name": "account_type_name",
                "value": $('#account_type_name').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        account_types_table.destroy();
        var new_account_types_table = account_types_tab.DataTable(account_types_dtsettings);
        //End Reset Table
        $('.account-type-filter :input').change(function(){
            new_account_types_table.draw();
        });

    });


});