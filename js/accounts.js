CRM.$(function ($) {

    $("a.add-account").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-accounts');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var accounts_sourceUrl = CRM.vars.source_url['accounts_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //accounts datatable
        var accounts_tab = $('.selector-accounts');
        var accounts_table = accounts_tab.DataTable();
        var accounts_dtsettings = accounts_table.settings().init();
        accounts_dtsettings.bFilter = true;
        //turn on search

        accounts_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        accounts_dtsettings.sAjaxSource = accounts_sourceUrl;
        accounts_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        accounts_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-account").css('background','red');
            $("a.update-account").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-accounts');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-account").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-accounts');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        accounts_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "account_id",
                "value": $('#account_id').val() });
            aoData.push({ "name": "account_name",
                "value": $('#account_name').val() });
            aoData.push({ "name": "account_fund_id",
                "value": $('#account_fund_id').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        accounts_table.destroy();
        var new_accounts_table = accounts_tab.DataTable(accounts_dtsettings);
        //End Reset Table
        $('.account-filter :input').change(function(){
            new_accounts_table.draw();
        });

    });


});