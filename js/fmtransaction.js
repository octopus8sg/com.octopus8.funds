// alert("aaa");
CRM.$(function ($) {

    $("a.fm-add-transaction").off("click").click(function (event) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el = CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function () {
            var hm_tab = $('.fm-selector-transactions');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });

    $("a.fm-view-transaction").off("click").click(function (event) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el = CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function () {
            var hm_tab = $('.fm-selector-transactions');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var transactions_sourceUrl = CRM.vars.source_url['transactions_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //transactions datatable

        $('.fm-selector-transactions').on('click', 'tr', function () {
            let aaa = $(this).find('td:eq(10)').text();
            // alert(aaa);
            if (aaa === "Pending Approval") {
                $(this).toggleClass('row_selected columnheader');
            }
        });

        var transactions_tab = $('.fm-selector-transactions');
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
            // $("a.fm-view-transaction").css('background','red');
            $("a.fm-update-transaction").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.fm-selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.fm-delete-transaction").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.fm-selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.fm-view-transaction").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.fm-selector-transactions');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        transactions_dtsettings.fnServerData = function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "transaction_id",
                "value": $('#fm_transaction_id').val()
            });
            aoData.push({
                "name": "approvertab",
                "value": 1
            });
            aoData.push({
                "name": "case_id",
                "value": $('#fm_transaction_case_id').val()
            });
            aoData.push({
                "name": "contact_id_app",
                "value": $('#fm_contact_id_app').val()
            });
            aoData.push({
                "name": "contact_id_sub",
                "value": $('#fm_contact_id_sub').val()
            });
            aoData.push({
                "name": "created_by",
                "value": $('#fm_created_by').val()
            });
            aoData.push({
                "name": "account_id",
                "value": $('#fm_transaction_account_id').val()
            });
            aoData.push({
                "name": "fund_id",
                "value": $('#fm_transaction_fund_id').val()
            });
            aoData.push({
                "name": "sub_account_id",
                "value": $('#fm_transaction_sub_account_id').val()
            });
            aoData.push({
                "name": "dateselect_from",
                "value": $('#fm_transaction_dateselect_from').val()
            });
            aoData.push({
                "name": "dateselect_to",
                "value": $('#fm_transaction_dateselect_to').val()
            });
            aoData.push({
                "name": "status_id",
                "value": $('#fm_transaction_status_id').val()
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
        $("a.fm-approve-transactions").off("click").click(function (event) {
            event.preventDefault();
            var roo = new_transactions_table.$('.row_selected');
            var i = 0;
            if (roo.length !== 0) {
                roo.each(function () {
                    CRM.api3('FundTransaction', 'create', {
                        "id": $(this).find("td:eq(0)").text(),
                        "status_id": 2
                    }).then(function (results) {
                        // alert(results.toString());
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
        $("a.fm-reject-transactions").off("click").click(function (event) {
            event.preventDefault();
            var roo = new_transactions_table.$('.row_selected');
            var i = 0;
            if (roo.length !== 0) {
                roo.each(function () {
                    CRM.api3('FundTransaction', 'create', {
                        "id": $(this).find("td:eq(0)").text(),
                        "status_id": 3
                    }).then(function (results) {
                        // alert(results.toString());
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
        $("a.fm-select-all-transactions").off("click").click(function (event) {
            event.preventDefault();
            $('.fm-selector-transactions tr').each(function () {
                let aaa = $(this).find('td:eq(10)').text();
                // alert(aaa);
                if (aaa === "Pending Approval") {
                    $(this).toggleClass('row_selected columnheader');
                }
            });

        });
        //End Reset Table
        $('.fm-transaction-filter :input').change(function () {
            new_transactions_table.draw();
        });

    });


});