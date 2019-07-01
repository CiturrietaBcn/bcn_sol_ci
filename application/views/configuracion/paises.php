<div style="margin-left:20px">
    <table id="tPaises" class="gview_jqgrid"></table>
    <div id="psPager" class="myPager"></div>
    <div id="ptoolbar" class="gbox_jqgrid"></div>
</div>
<script>
    $(function () {
        var $grid = $("#tPaises");
        var colNames = ['Codigo', 'Nombre'];
        var colModel = [
            {name: 'ps_codigo', index: 'ps_codigo', editable: true},
            {name: 'ps_detalle', index: 'ps_detalle', editable: true}
        ];
        $grid.jqGrid({
            hidegrid: false,
            url: '<?= base_url() ?>index.php/Ajax/paises_query',
            datatype: 'xml',
            mtype: 'POST',
            colNames: colNames,
            colModel: colModel,
            pager: '#psPager',
            rowNum: 25,
            rowList: [25, 50, 100, 500],
            sortname: 'ps_codigo',
            sortorder: 'asc',
            viewrecords: false,
            gridview: true,
            caption: "Mantenimiento de paises",
            editurl:"<?= base_url() ?>index.php/Ajax/paises_edit",
            gridComplete: function () {
                var newWidth = $grid.closest(".ui-jqgrid").parent().width();
                $(this).jqGrid("setGridWidth", newWidth, true);
                $(this).setGridHeight($(window).height() - 350, true)
            }
        });
        $grid.jqGrid('navGrid', '#psPager', {del: false, add: true, edit: true, search: false, refresh: true});
    });
</script>