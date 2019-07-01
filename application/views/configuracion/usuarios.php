<div style="margin-left:20px">
    <table id="tUsuarios" class="gview_jqgrid"></table>
    <div id="UsPager" class="myPager"></div>
    <div id="ptoolbar" class="gbox_jqgrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        var $grid = $("#tUsuarios");
        var colNames = ['Usuario', 'RUT', 'Perfil', 'Nombre', 'Email', 'Cargo', 'Estado', 'Bloqueado', 'Intentos Fallidos', 'Contraseña'];
        //usu_codigo, usu_nombre, usu_idlegal_id, usu_email, usu_cargo, usu_activo, usu_intentos, usu_bloqueo
        var colModel = [
            {name: 'usu_codigo', index: 'usu_codigo', width: 60, editable: true},
            {name: 'usu_idlegal_id', index: 'usu_idlegal_id', width: 60},
            {name: 'per_nombre_perfil', index: 'per_nombre_perfil', width: 60, editable: true},
            {name: 'usu_nombre', index: 'usu_nombre', width: 80, editable: true},
            {name: 'usu_email', index: 'usu_email', width: 110, editable: true, email: true},
            {name: 'usu_cargo', index: 'usu_cargo', width: 70, editable: true},
            {name: 'usu_activo', index: 'usu_activo', width: 70, editable: true, edittype: "select", formatter: 'select', stype: 'select',
                editoptions: {
                    value: '1:Activo;0:Desactivo'
                },
                searchoptions: {
                    clearSearch: false,
                    value: '1:Activo;0:Desactivo'
                }
            },
            {name: 'usu_bloqueo', index: 'usu_bloqueo', width: 70, editable: true, edittype: "select", formatter: 'select', stype: 'select',
                editoptions: {
                    value: '1:Bloqueado;0:Desbloqueado'
                },
                searchoptions: {
                    clearSearch: false,
                    value: '1:Bloqueado;0:Desbloqueado'
                }
            },
            {name: 'usu_intentos', index: 'usu_intentos', width: 70},
            {name: 'usu_contraseña', index: 'usu_contraseña', width: 70, editable: true, hidden: true, edittype: "password", formatter: 'password'
                , stype: 'password', editrules: {edithidden: true}}
        ];
        $grid.jqGrid({
            hidegrid: false,
            url: '<?= base_url() ?>index.php/Ajax/usuarios_query',
            datatype: 'xml',
            mtype: 'POST',
            colNames: colNames,
            colModel: colModel,
            pager: '#UsPager',
            rowNum: 25,
            rowList: [25, 50, 100, 500],
            sortname: 'usu_codigo',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: "Mantención de usuarios",
            gridComplete: function () {
                var newWidth = $grid.closest(".ui-jqgrid").parent().width();
                $(this).jqGrid("setGridWidth", newWidth, true);
                $(this).setGridHeight($(window).height() - 350, true);
            }
        });
        $grid.jqGrid('navGrid', '#UsPager', {del: false, add: true, edit: true, search: false, refresh: true,
            editfunc: function (rowId) {
                $("#modal").load("<?= base_url() ?>index.php/Ajax/modalUsuario/" + rowId);
                setTimeout(function () {
                    $("#tituloModalUs").html("Editar Usuario");
                    $("#modalUsuario").modal('show');
                }, 400);
            },
            addfunc: function () {
                $("#modal").load("<?= base_url() ?>index.php/Ajax/modalUsuario");
                setTimeout(function () {
                    $("#tituloModalUs").html("Nuevo Usuario");
                    $("#modalUsuario").modal('show');
                }, 400);
            }
        });
        var newWidth = $grid.closest(".ui-jqgrid").parent().width();
        $grid.jqGrid("setGridWidth", newWidth, true);
        $grid.jqGrid('navButtonAdd', '#UsPager', {
            caption: "Exportar",
            buttonicon: "ui-icon-disk",
            onClickButton: function () {
                $grid.jqGrid('excelExport', {"url": "documentos_emitidos_query.php", mtype: 'POST'});
            }
        });
    });
</script>
<div id="modal"></div>