CRM.$(function ($) {

    $("a.add-transaction").click(function (event) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el = CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function () {
            var hm_tab = $('.selector-transactions');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var transactions_sourceUrl = CRM.vars.source_url['transactions_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //transactions datatable

        $('.selector-transactions').on('click', 'tr', function () {
            $(this).toggleClass('row_selected columnheader');
        });

        var transactions_tab = $('.selector-transactions');
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
            // $("a.view-transaction").css('background','red');
            $("a.update-transaction").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-transaction").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        transactions_dtsettings.fnServerData = function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "transaction_id",
                "value": $('#transaction_id').val()
            });
            aoData.push({
                "name": "case_id",
                "value": $('#transaction_case_id').val()
            });
            aoData.push({
                "name": "contact_id_app",
                "value": $('#contact_id_app').val()
            });
            aoData.push({
                "name": "contact_id_sub",
                "value": $('#contact_id_sub').val()
            });
            aoData.push({
                "name": "account_id",
                "value": $('#transaction_account_id').val()
            });
            aoData.push({
                "name": "sub_account_id",
                "value": $('#transaction_sub_account_id').val()
            });
            aoData.push({
                "name": "dateselect_from",
                "value": $('#transaction_dateselect_from').val()
            });
            aoData.push({
                "name": "dateselect_to",
                "value": $('#transaction_dateselect_to').val()
            });
            aoData.push({
                "name": "status_id",
                "value": $('#transaction_status_id').val()
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
        $('a.approve-transactions').click(function (event) {
            event.preventDefault();
            var roo = new_transactions_table.$('.row_selected');
            var i = 0;
            if (roo.length !== 0) {
                roo.each(function () {
                    CRM.api4('FundTransaction', 'update', {
                        values: {"status_id": 2},
                        where: [["id", "=", $(this).find("td:eq(0)").text()]]
                    }).then(function (results) {
                        i++;
                        new_transactions_table.draw();
                    }, function (failure) {
                        alert('There is an error! Check console, please');
                        console.log(failure);
                    });

                });
                if (i > 0) {
                    new_transactions_table.draw();
                }
                // new_transactions_table.fnDeleteRow( ooo[0] );
            } else {
                alert('No rows selected!')
            }

        });
        $('a.reject-transactions').click(function (event) {
            event.preventDefault();
            var roo = new_transactions_table.$('.row_selected');
            var i = 0;
            if (roo.length !== 0) {
                roo.each(function () {
                    CRM.api4('FundTransaction', 'update', {
                        values: {"status_id": 3},
                        where: [["id", "=", $(this).find("td:eq(0)").text()]]
                    }).then(function (results) {
                        i++;
                        new_transactions_table.draw();
                    }, function (failure) {
                        alert('There is an error! Check console, please');
                        console.log(failure);
                    });

                });
                if (i > 0) {
                    new_transactions_table.draw();
                }
                // new_transactions_table.fnDeleteRow( ooo[0] );
            } else {
                alert('No rows selected!')
            }

        });
        $('a.select-all-transactions').click(function (event) {
            event.preventDefault();
            $('.selector-transactions tr').toggleClass('row_selected columnheader');

        });
        //End Reset Table
        $('.transaction-filter :input').change(function () {
            new_transactions_table.draw();
        });

    });


});