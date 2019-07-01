<style>
    /*    .gview_jqgrid, div.myPager {width:100% !important;height:100% !important;}
            .ui-jqgrid-hdiv, .ui-jqgrid-htable {width:100% !important;height:100% !important;}
            .ui-jqgrid-bdiv, #jqgrid {width:100% !important;height:100% !important;}
            .ui-jqgrid .ui-jqgrid-hbox {padding-right:0 !important;}
            .ui-jqgrid tr.jqgrow td { white-space: pre-wrap !important;}
        div.gbox_jqgrid { width: 100% !important; margin-bottom: 10px !important;}*/
</style>
<script type="text/javascript">
    $(function () {
        var $grid = $("#solicitudes");
        var colNames = ['Rut Empresa', 'Razon Social', 'Pais', 'Ambiente', 'Estado', 'Creado por', 'Fecha Creación', 'Nombre Ambiente'];
        var colModel = [
            {name: 'emp_rut', index: 'emp_rut', customlabel: "Rut Empresa:Empresa", width: 70},
            {name: 'emp_razonsocial', index: 'emp_razonsocial', customlabel: "Razon Social:Empresa", width: 100},
            {name: 'emp_pais', index: 'emp_pais', customlabel: "Razon Social:Empresa", editable: true, edittype: "select",
                formatter: 'select', stype: 'select', width: 70,
                editoptions: {
                    value: ':(Seleccione);<?= $paises ?>'
                },
                searchoptions: {
                    clearSearch: false,
                    value: ':Todos;<?= $paises ?>'
                }
            },
            {name: 'sol_ambiente', index: 'sol_ambiente', customlabel: "Ambiente:Ambiente", editable: true, edittype: "select",
                formatter: 'select', stype: 'select', width: 70,
                editoptions: {
                    value: ':(Seleccione);<?= $ambientes ?>'
                },
                searchoptions: {
                    clearSearch: false,
                    value: ':Todos;<?= $ambientes ?>'
                }
            },
            {name: 'sol_estado', index: 'sol_estado', customlabel: "Estado:Solicitud", editable: true, edittype: "select",
                formatter: 'select', stype: 'select', width: 70,
                editoptions: {
                    value: ':(Seleccione);<?= $estados ?>'
                },
                searchoptions: {
                    clearSearch: false,
                    value: ':Todos;<?= $estados ?>'
                }
            },
            {name: 'sol_creacion_usuario', index: 'sol_creacion_usuario', customlabel: "Creado por:Solicitud", width: 70},
            {name: 'sol_creacion_fecha', index: 'sol_creacion_fecha', customlabel: "Fecha Creacion:Solicitud",
                editable: true, hidden: false, width: 70,
                searchoptions: {
                    clearSearch: false
                    , sopt: 'eq'
                    , dataInit: function (elem) {
                        $(elem).daterangepicker({
                            onChange: datePick
                        })
                    }
                },
                editoptions: {size: 10
                    , maxlength: 10
                    , dataInit: function (elem) {
                        $(elem).daterangepicker()
                    }
                }
            },
            {name: 'sol_amb_nombre', index: 'sol_amb_nombre', customlabel: "Nombre Ambiente:Solicitud", width: 70}
        ];
        $grid.jqGrid({
            height: 300,
            hidegrid: false,
            url: '<?= base_url() ?>index.php/Ajax/solicitudes_query',
            datatype: 'xml',
            mtype: 'POST',
            colNames: colNames,
            colModel: colModel,
            pager: '#pager',
            rowNum: 25,
            rowList: [25, 50, 100, 500],
            sortname: 'sol_creacion_fecha',
            sortorder: 'desc',
            viewrecords: true,
            gridview: false,
            ondblClickRow: function (rowId) {
                location.href = "<?= base_url() ?>index.php/inicio/nueva_solicitud/" + rowId;
            },
            gridComplete: function () {
                $(this).setGridWidth($(window).width() - 50, true)
                $(this).setGridHeight($(window).height() - 350, true);
            }
        });
        $grid.jqGrid('setGroupHeaders', {
            useColSpanStyle: false,
            groupHeaders: [
                {startColumnName: 'emp_rut', numberOfColumns: 3, titleText: '<em>Empresa</em>'},
                {startColumnName: 'sol_ambiente', numberOfColumns: 1, titleText: '<em>Ambiente</em>'},
                {startColumnName: 'sol_estado', numberOfColumns: 4, titleText: '<em>Solicitud</em>'}
            ]
        })
        $grid.jqGrid('navGrid', '#pager', {del: false, add: true, edit: false, search: false, refresh: true,
            addfunc: function () {
                location.href = "<?= base_url() ?>index.php/inicio/nueva_solicitud";
            }
        });
        $grid.jqGrid('filterToolbar', {stringResult: false, searchOnEnter: false, autosearch: true, enableClear: false});
        $grid.setGridWidth($(window).width() - 50, true);
        $grid.setGridHeight($(window).height() - 350, true);
        $grid.jqGrid('navButtonAdd', '#pager', {
            caption: "Exportar",
            buttonicon: "ui-icon-disk",
            onClickButton: function () {
                $grid.jqGrid('excelExport', {"url": "documentos_emitidos_query.php", mtype: 'POST'});
            }
        });
    });
    $(window).bind('resize', function () {
        $("#solicitudes").setGridWidth($(window).width() - 50, true)
        $("#solicitudes").setGridHeight($(window).height() - 350, true);
    }).trigger('resize');
    datePick = function () {
        var grid = $("#emitidos");
        grid[0].triggerToolbar();
        grid.trigger("reloadGrid", [{page: 1}]);
    }

</script>
<div style="margin-left:20px">
    <table id="solicitudes"></table>
    <div id="pager"></div>
    <div id="ptoolbar"></div>
</div>
