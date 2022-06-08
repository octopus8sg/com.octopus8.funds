CRM.$(function ($) {

    $("a.add-category").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-categorys');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var categorys_sourceUrl = CRM.vars.source_url['categorys_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //categorys datatable
        var categorys_tab = $('.selector-categorys');
        var categorys_table = categorys_tab.DataTable();
        var categorys_dtsettings = categorys_table.settings().init();
        categorys_dtsettings.bFilter = true;
        //turn on search

        categorys_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        categorys_dtsettings.sAjaxSource = categorys_sourceUrl;
        categorys_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        categorys_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-category").css('background','red');
            $("a.update-category").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-categorys');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-category").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-categorys');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        categorys_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "category_id",
                "value": $('#category_id').val() });
            aoData.push({ "name": "category_name",
                "value": $('#category_name').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        categorys_table.destroy();
        var new_categorys_table = categorys_tab.DataTable(categorys_dtsettings);
        //End Reset Table
        $('.category-filter :input').change(function(){
            new_categorys_table.draw();
        });

    });


});