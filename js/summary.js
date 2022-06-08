CRM.$(function ($) {

    $("a.add-fund").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-funding');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var funds_sourceUrl = CRM.vars.source_url['funding_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //funds datatable
        var funds_tab = $('.selector-funding');
        var funds_table = funds_tab.DataTable();
        var funds_dtsettings = funds_table.settings().init();
        funds_dtsettings.bFilter = true;
        //turn on search

        funds_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        funds_dtsettings.sAjaxSource = funds_sourceUrl;
        funds_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        funds_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-fund").css('background','red');
            $("a.view-fund").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-funding');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            // $("a.update-fund").css('background','blue');
            $("a.update-fund").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-funding');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-fund").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-funding');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        funds_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "fund_id",
                "value": $('#fund_id').val() });
            aoData.push({ "name": "fund_name",
                "value": $('#fund_name').val() });
            aoData.push({ "name": "contact_id",
                "value": $('#fund_contact_id').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        funds_table.destroy();
        var new_funds_table = funds_tab.DataTable(funds_dtsettings);
        //End Reset Table
        $('.fund-filter :input').change(function(){
            new_funds_table.draw();
        });

    });


});