CRM.$(function ($) {

    $("a.sw-add-transaction").off("click").click(function (event) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el = CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function () {
            var hm_tab = $('.sw-selector-transactions');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });

    $("a.sw-view-transaction").off("click").click(function (event) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el = CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function () {
            var hm_tab = $('.sw-selector-transactions');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var transactions_sourceUrl = CRM.vars.source_url['transactions_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //transactions datatable

        var transactions_tab = $('.sw-selector-transactions');
        var transactions_table = transactions_tab.DataTable();
        var transactions_dtsettings = transactions_table.settings().init();
        transactions_dtsettings.bFilter = true;
        //turn on search

        transactions_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        transactions_dtsettings.sAjaxSource = transactions_sourceUrl;
        transactions_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        transactions_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.sw-view-transaction").css('background','red');
            $("a.sw-update-transaction").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.sw-selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.sw-delete-transaction").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.sw-selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.sw-view-transaction").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.sw-selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        transactions_dtsettings.fnServerData = function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "transaction_id",
                "value": $('#sw_transaction_id').val()
            });
            aoData.push({
                "name": "socialtab",
                "value": 1
            });
            aoData.push({
                "name": "case_id",
                "value": $('#sw_transaction_case_id').val()
            });
            aoData.push({
                "name": "contact_id_app",
                "value": $('#sw_contact_id_app').val()
            });
            aoData.push({
                "name": "contact_id_sub",
                "value": $('#sw_contact_id_sub').val()
            });
            aoData.push({
                "name": "created_by",
                "value": $('#sw_created_by').val()
            });
            aoData.push({
                "name": "account_id",
                "value": $('#sw_transaction_account_id').val()
            });
            aoData.push({
                "name": "fund_id",
                "value": $('#sw_transaction_fund_id').val()
            });
            aoData.push({
                "name": "sub_account_id",
                "value": $('#sw_transaction_sub_account_id').val()
            });
            aoData.push({
                "name": "dateselect_from",
                "value": $('#sw_transaction_dateselect_from').val()
            });
            aoData.push({
                "name": "dateselect_to",
                "value": $('#sw_transaction_dateselect_to').val()
            });
            aoData.push({
                "name": "status_id",
                "value": $('#sw_transaction_status_id').val()
            });
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        transactions_table.destroy();
        var new_transactions_table = transactions_tab.DataTable(transactions_dtsettings);
        //End Reset Table
        $('.sw-transaction-filter :input').change(function () {
            new_transactions_table.draw();
        });

    });


});