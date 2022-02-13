CRM.$(function ($) {

    $("a.add-component").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-components');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var components_sourceUrl = CRM.vars.source_url['components_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //components datatable
        var components_tab = $('.selector-components');
        var components_table = components_tab.DataTable();
        var components_dtsettings = components_table.settings().init();
        components_dtsettings.bFilter = true;
        //turn on search

        components_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        components_dtsettings.sAjaxSource = components_sourceUrl;
        components_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        components_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-component").css('background','red');
            $("a.update-component").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-components');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-component").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                // alert(href);
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-components');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        components_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "component_id",
                "value": $('#component_id').val() });
            aoData.push({ "name": "component_name",
                "value": $('#component_name').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        components_table.destroy();
        var new_components_table = components_tab.DataTable(components_dtsettings);
        //End Reset Table
        $('.component-filter :input').change(function(){
            new_components_table.draw();
        });

    });


});