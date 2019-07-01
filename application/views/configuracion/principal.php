<style>
    .gview_jqgrid, div.myPager {width:100% !important;}
    .ui-jqgrid-hdiv, .ui-jqgrid-htable {width:100% !important;}
    .ui-jqgrid-bdiv, .gview_jqgrid {width:100% !important;}
    .ui-jqgrid .ui-jqgrid-hbox {padding-right:0 !important;}
    .ui-jqgrid tr.jqgrow td { white-space: pre-wrap !important;}
    div.gbox_jqgrid { width: 100% !important; margin-bottom: 10px !important;}
</style>
<div class="container-fluid">
    <div class="row flex-nowrap flex-sm-wrap tex">
        <div class="col-md-6 col-xs-12" id="usuarios" style="height: 100%"></div>
        <div class="col-md-6 col-xs-12" id="paises" style="height: 100%"></div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#usuarios").load("<?= base_url() ?>index.php/inicio/conf_usuarios");
        $("#paises").load("<?= base_url() ?>index.php/inicio/conf_paises");
    });
    $(window).bind('resize', function () {
        var newWidth = $("#tUsuarios").closest(".ui-jqgrid").parent().width();
        $("#tUsuarios").jqGrid("setGridWidth", newWidth, true);
        $("#tUsuarios").setGridHeight($(window).height() - 350, true);
        var newWidth = $("#tPaises").closest(".ui-jqgrid").parent().width();
        $("#tPaises").jqGrid("setGridWidth", newWidth, true);
        $("#tPaises").setGridHeight($(window).height() - 350, true);
    }).trigger('resize');
</script>