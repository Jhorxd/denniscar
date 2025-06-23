<script type="text/javascript" src="<?php echo base_url(); ?>js/ventas/controlservicio.js?=<?= JS; ?>"></script>
<link href="<?= base_url(); ?>js/fancybox/dist/jquery.fancybox.css?=<?= CSS; ?>" rel="stylesheet">
<script src="<?= base_url(); ?>js/fancybox/dist/jquery.fancybox.js?=<?= JS; ?>"></script>

<script type="text/javascript">
    var base_url = '<?= base_url(); ?>';
</script>
<script type="text/javascript" src="<?= base_url(); ?>js/nicEdit/nicEdit.js?=<?= JS; ?>"></script>

<script type="text/javascript">
    bkLib.onDomLoaded(function () {
        new nicEditor({
            fullPanel: true
        }).panelInstance('mensaje');
    });
    bkLib.onDomLoaded(function () {
        new nicEditor({
            fullPanel: true
        }).panelInstance('mensajeWS');
    });
</script>

<div class="container-fluid">
    <div class="row header">
        <div class="col-md-12 col-lg-12">
            <div><?= $titulo_busqueda; ?></div>
        </div>
    </div>
    <form id="form_busqueda" method="post">
        <div class="row fuente8 py-1">
            <div class="col-md-1">
                <label for="searchPlaca">Placa / VIN</label>
                <input type="text" name="searchPlaca" id="searchPlaca" value="" placeholder="Ingrese la placa"
                    class="form-control h-1" autocomplete="off" />
            </div>
            <div class="col-md-1">
                <label for="searchSerie">Marca</label>
                <input type="text" name="searchMarca" id="searchMarca" value="" placeholder="Marca"
                    class="form-control h-1" autocomplete="off" />
            </div>
            <div class="col-md-1">
                <label for="searchOrden">Orden de pedido</label>
                <input type="text" name="searchOrden" id="searchOrden" value="" placeholder="Orden"
                    class="form-control h-1" autocomplete="off" />
            </div>
            <div class="col-md-2">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="" class="form-control h-1" />
            </div>
            <div class="col-md-2">
                <label for="fecha_fin">Fecha fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="" class="form-control h-1" />
            </div>
            <div class="col-md-1">
                <label for="searchSerie">Serie</label>
                <input type="text" name="searchSerie" id="searchSerie" value="" placeholder="FPP1" maxlength="4"
                    class="form-control h-1" autocomplete="off" />
            </div>
            <div class="col-md-1">
                <label for="searchNumero">Número</label>
                <input type="number" name="searchNumero" id="searchNumero" value="" placeholder="1"
                    class="form-control h-1" autocomplete="off" />
            </div>
            <div class="col-md-1">
                <label for="searchRuc">RUC</label>
                <input type="text" name="searchRuc" id="searchRuc" value="" placeholder="Ruc" class="form-control h-1"
                    autocomplete="off" />
            </div>
            <div class="col-md-1">
                <label for="searchRazon">Razon Social</label>
                <input type="text" name="searchRazon" id="searchRazon" value="" placeholder="Razon social"
                    class="form-control h-1" autocomplete="off" />
            </div>
            <div class="col-md-1">
                <label for="searchProducto">Producto</label>
                <input type="text" name="searchProducto" id="searchProducto" value="" placeholder="Producto"
                    class="form-control h-1" autocomplete="off" />
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="acciones">
                        <div id="botonBusqueda">
                            <!-- <ul class="lista_botones">
                                <li id="nuevo" onclick="addControl();">Nuevo Control</li>
                            </ul> -->
                            <ul class="lista_botones">
                                <li id="limpiar">Limpiar</li>
                            </ul>
                            <ul class="lista_botones">
                                <li id="buscar">Buscar</li>
                            </ul>
                            <ul id="downloadOcompra" class="lista_botones">
                                <li id="excel">Excel</li>
                            </ul>
                        </div>
                        <div id="lineaResultado">Registros encontrados</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="header text-align-center"><?= $titulo_tabla; ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <table class="fuente8 display" id="table-control">
                        <thead>
                            <tr class="cabeceraTabla">
                                <th style="width:8%" data-orderable="true">PLACA / VIN</th>
                                <th style="width:8%" data-orderable="true">MARCA</th>
                                <th style="width:8%" data-orderable="true">ORDEN PEDIDO</th>
                                <th style="width:12%" data-orderable="true">FECHA REGISTRO</th>
                                <th style="width:8%" data-orderable="true">SERIE</th>
                                <th style="width:6%" data-orderable="true">NÚMERO</th>
                                <th style="width:10%" data-orderable="true">RUC</th>
                                <th style="width:10%" data-orderable="true">RAZON SOCIAL</th>
                                <th style="width:13%" data-orderable="true">PRODUCTO</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
            <?= $oculto; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalEditar" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="align:center;">
        <div class="modal-content" style="width: 90%; margin: auto;">
            <div class="modal-header" align="center">
                <button type="button" id="close" class="close" style="float: right;" data-dismiss="modal"
                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="myModalLabel">CONTROL DE SERVICIO</h2>
            </div>
            <div class="modal-body" style="font-size: 10pt;">
                <div class="box-body">
                    <form role="form" id="frmControl" method="post" action="<?php echo $action; ?>" autocomplete="on">
                        <div align="left"><label>NUMERO:
                                <input type="text" class="comboMedio" id="codigo_control" name="codigo_control"
                                    style="border: 0; background: white; font-size: 12pt;" readonly></label>
                        </div>

                        <fieldset>
                            <section>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="placaSearch">PLACA / VIN DEL VEHICULO</label>
                                            <input type="hidden" id="placa" name="placa" value="">
                                            <input type="text" class="form-control h-1" id="placaSearch"
                                                name="placaSearch" placeholder="Ingrese la placa">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fecha_control">FECHA DE CONTROL</label>
                                            <input type="date" class="form-control h-1" id="fecha_control"
                                                name="fecha_control" placeholder="Ingrese la fecha">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="km_actual">KM ACTUALES</label>
                                            <input type="number" class="form-control h-1" id="km_actual"
                                                name="km_actual" placeholder="Ingrese km actual">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="prox_sugerido">PROX CAMBIO SUGERIDO</label>
                                            <input type="number" class="form-control h-1" id="prox_sugerido"
                                                name="prox_sugerido" placeholder="Proximo Cambio Sugerido">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="marca">MARCA DEL VEHICULO</label>
                                            <input type="text" class="form-control h-1" id="marca" name="marca"
                                                placeholder="Ingrese la marca">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="modelo">MODELO DEL VEHICULO</label>
                                            <input type="text" class="form-control h-1" id="modelo" name="modelo"
                                                placeholder="Ingrese el modelo">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="anorigen">AÑO</label>
                                            <input type="text" class="form-control h-1" id="anorigen" name="anorigen"
                                                placeholder="ingrese el año">
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                    </div>
                                </div>
                            </section>
                        </fieldset>

                        <hr>
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title header">
                                        <a data-toggle="collapseme" style="color:#000; width: 100%; display: block"
                                            data-parent="#accordion" id="collapseme_inicial" href="#collapseme1">ACEITE
                                            DE MOTOR</a>
                                    </h4>
                                </div>
                                <div id="collapseme1" class="panel-collapseme collapseme in">
                                    <div class="panel-body">
                                        <fieldset>
                                            <section>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="aceite_motor">Aceite de motor</label>
                                                        <input type="text" class="form-control h-1" id="aceite_motor"
                                                            name="aceite_motor" placeholder="Ingrese aceite motor">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="prox_cambio">Próximo cambio</label>
                                                        <input type="number" class="form-control h-1" id="prox_cambio"
                                                            name="prox_cambio" placeholder="Ingrese proximo cambio">
                                                    </div>
                                                </div>
                                            </section>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title header">
                                        <a data-toggle="collapseme" style="color:#000; width: 100%; display: block"
                                            data-parent="#accordion" href="#collapseme2">
                                            FILTROS</a>
                                    </h4>
                                </div>
                                <div id="collapseme2" class="panel-collapseme collapseme">
                                    <div class="panel-body">
                                        <fieldset>
                                            <section>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label for="filtro_aceite">Filtro de Aceite</label>
                                                        <input type="text" class="form-control h-1" id="filtro_aceite"
                                                            name="filtro_aceite" placeholder="Ingrese filtro aceite">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label for="filtro_aire">Filtro de Aire</label>
                                                        <input type="text" class="form-control h-1" id="filtro_aire"
                                                            name="filtro_aire" placeholder="Ingrese filtro aire">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label for="filtro_aa">Filtro de Aire Acondicionado</label>
                                                        <input type="text" class="form-control h-1" id="filtro_aa"
                                                            name="filtro_aa" placeholder="Ingrese filtro AA">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label for="filtro_petroleo">Filtro de Petróleo</label>
                                                        <input type="text" class="form-control h-1" id="filtro_petroleo"
                                                            name="filtro_petroleo"
                                                            placeholder="Ingrese filtro petroleo">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label for="filtro_sep_agua">Filtro separador de Agua</label>
                                                        <input type="text" class="form-control h-1" id="filtro_sep_agua"
                                                            name="filtro_sep_agua"
                                                            placeholder="Ingrese filtro separador">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label for="filtro_gasolina">Filtro de Gasolina</label>
                                                        <input type="text" class="form-control h-1" id="filtro_gasolina"
                                                            name="filtro_gasolina"
                                                            placeholder="Ingrese filtro de gasolina">
                                                    </div>
                                                </div>
                                            </section>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title header">
                                        <a data-toggle="collapseme" style="color:#000; width: 100%; display: block"
                                            data-parent="#accordion" href="#collapseme3">
                                            BUJIA / LIMPIEZA DE OBTURADOR</a>
                                    </h4>
                                </div>
                                <div id="collapseme3" class="panel-collapseme collapseme">
                                    <div class="panel-body">
                                        <fieldset>
                                            <section>
                                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="float: left;">
                                                    <div class="form-group">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Bujía</label>
                                                        <select name="bujia" id="bujia" class="form-control h-2">
                                                            <option value=''>::Seleccione::</option>
                                                            <option value='BOSH'>BOSH</option>
                                                            <option value='NGK'>NGK</option>
                                                            <option value='DENSO'>DENSO</option>
                                                            <option value='CHAMPION'>CHAMPION</option>
                                                            <option value='OTROS'>OTROS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Detalle</label>
                                                        <input type="text" class="form-control h-1" id="det_bujia"
                                                            name="det_bujia" placeholder="Ingrese detalle de bujia">
                                                    </div>
                                                </div>
                                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="float: left;">
                                                    <div class="form-group">
                                                    </div>
                                                </div>
                                            </section>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title header">
                                        <a data-toggle="collapseme" style="color:#000; width: 100%; display: block"
                                            data-parent="#accordion" href="#collapseme4">
                                            ACEITE DE CAJA / CORONA</a>
                                    </h4>
                                </div>
                                <div id="collapseme4" class="panel-collapseme collapseme">
                                    <div class="panel-body">
                                        <fieldset>
                                            <section>
                                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="float: left;">
                                                    <div class="form-group">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Aceite de caja</label>
                                                        <input type="text" class="form-control h-1" id="aceite_caja"
                                                            name="aceite_caja" placeholder="Ingrese aceite caja">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Próximo cambio</label>
                                                        <input type="number" class="form-control h-1"
                                                            id="prox_cambio_aceite_caja" name="prox_cambio_aceite_caja"
                                                            placeholder="Aceite caja">
                                                    </div>
                                                </div>
                                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="float: left;">
                                                    <div class="form-group">
                                                    </div>
                                                </div>
                                            </section>
                                        </fieldset>
                                        <fieldset>
                                            <section>
                                                <div class="row">
                                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                        style="float: left;">
                                                        <div class="form-group">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                        style="float: left;">
                                                        <div class="form-group">
                                                            <label>Aceite de corona</label>
                                                            <input type="text" class="form-control h-1"
                                                                id="aceite_corona" name="aceite_corona"
                                                                placeholder="Ingrese aceite corona">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                        style="float: left;">
                                                        <div class="form-group">
                                                            <label>Próximo cambio</label>
                                                            <input type="number" class="form-control h-1"
                                                                id="prox_cambio_aceite_corona"
                                                                name="prox_cambio_aceite_corona"
                                                                placeholder="Aceite corona">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                        style="float: left;">
                                                        <div class="form-group">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2" style="float: left;">
                                                        <div class="form-group">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4" style="float: left;">
                                                        <div class="form-group">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4" style="float: left;">
                                                        <div class="form-group">
                                                            <label for="engrase">Engrase</label>
                                                            <input type="checkbox" class="h-1" id="engrase"
                                                                name="engrase" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title header">
                                        <a data-toggle="collapseme" style="color:#000; width: 100%; display: block"
                                            data-parent="#accordion" href="#collapseme5">
                                            REFRIGERANTE</a>
                                    </h4>
                                </div>
                                <div id="collapseme5" class="panel-collapseme collapseme">
                                    <div class="panel-body">
                                        <fieldset>
                                            <section>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Refrigerante</label>
                                                        <input type="text" class="form-control h-1" id="refrigerante"
                                                            name="refrigerante" placeholder="refrigerante">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Próximo cambio</label>
                                                        <input type="number" class="form-control h-1"
                                                            id="prox_cambio_refrigerante"
                                                            name="prox_cambio_refrigerante"
                                                            placeholder="Proximo cambio refrigerante">
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Porcentaje</label>
                                                        <select name="porcentaje" id="porcentaje"
                                                            class="form-control h-2">
                                                            <option value=''>::Seleccione::</option>
                                                            <option value='17%'>17%</option>
                                                            <option value='22%'>22%</option>
                                                            <option value='33%'>33%</option>
                                                            <option value='50%'>50%</option>
                                                            <option value='96%'>96%</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </section>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title header">
                                        <a data-toggle="collapseme" style="color:#000; width: 100%; display: block"
                                            data-parent="#accordion" href="#collapseme6">
                                            LIQUIDO DE FRENO</a>
                                    </h4>
                                </div>
                                <div id="collapseme6" class="panel-collapseme collapseme">
                                    <div class="panel-body">
                                        <fieldset>
                                            <section>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="float: left;">
                                                    <div class="form-group">
                                                        <label>Líquido de freno</label>
                                                        <select name="liq_freno" id="liq_freno"
                                                            class="form-control h-2">
                                                            <option value=''>::Seleccione::</option>
                                                            <option value='DOT 3'>DOT 3</option>
                                                            <option value='DOT 4'>DOT 4</option>
                                                            <option value='DOT 5'>DOT 5</option>
                                                            <option value='OTROS'>OTROS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </section>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <fieldset>
                            <section>
                                <legend>
                                    OBSERVACIONES
                                </legend>
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <textarea type="text" class="form-control h-3" id="observaciones"
                                            name="observaciones" placeholder="Ingresar observaciones"></textarea>
                                    </div>
                                </div>
                            </section>
                        </fieldset>

                        <div class="modal-footer">
                            <button type="button" id="salir" class="btn btn-secondary"
                                data-dismiss="modal">Salir</button>
                            <button id="agregar_control_boton" type="button" class="btn btn-primary"
                                onclick="registrar_control()">Registrar Control</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-envmail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"
            style="width: 700px; height: auto; margin: auto; font-family: Trebuchet MS, sans-serif; font-size: 10pt;">
            <form method="post" id="form-mail">
                <div class="contenido" style="width: 100%; margin: auto; height: auto; overflow: auto;">
                    <div class="tempde_head">

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7" style="text-align: center;">
                                <h3>ENVIO DE DOCUMENTOS POR CORREO</h3>
                            </div>
                        </div>

                        <input type="hidden" id="idDocMail" name="idDocMail">
                    </div>

                    <div class="tempde_body">

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <label for="placa_mail">Placa:</label>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <label for="kilometraje_mail">Kilometros actuales:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control h-1" id="placa_mail" name="placa_mail" value=""
                                    placeholder="Placa del vehiculo" readonly>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control h-1" id="kilometraje_mail"
                                    name="kilometraje_mail" value="" placeholder="Kilometraje actual" readonly>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <label for="destinatario">Destinatarios:</label>
                                <span class="mail-contactos"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <input type="text" class="form-control h-1" id="destinatario" name="destinatario"
                                    value="" placeholder="Correo">
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <label for="asunto">Asunto:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <input type="text" class="form-control h-1" id="asunto" name="asunto" value=""
                                    placeholder="Asunto">
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <label for="mensaje">Mensaje:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <textarea id="mensaje" name="mensaje" style="width: 520px; height: 300px">
<p><b>SRES.</b> <span class="mail-cliente"></span></p>
<p><span class="mail-empresa-envio"></span>, ENVÍA UN DOCUMENTO ELECTRÓNICO.</p>
<p><b>CONTROL NÚMERO:</b> <span class="mail-serie-numero"></span></p>
<p><b>PLACA NÚMERO:</b> <span class="placa_mail_envio"></span></p>
<p><b>KILOMETROS ACTUALES:</b> <span class="kmactuales_envio"></span></p>
<p><b>FECHA DE CONTROL:</b> <span class="mail-fecha"></span></p>
<p><b>PROXIMO CAMBIO:</b> <span class="proxcambio_mail"></span></p>
</textarea>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="tempde_footer">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6"></div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <span class="icon-loading-md"></span>
                                <div style="float: right">
                                    <span class="btn btn-success btn-sendMail">Enviar</span>
                                    &nbsp;
                                    <span class="btn btn-danger btn-close-envmail">Cerrar</span>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

$codigo_pais = "+51"; // Código de país predeterminado

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_celular = $_POST["numero-celu"];
    $codigo_pais = $_POST["codigo-pais"];

    $numero_whatsapp = $codigo_pais . $numero_celular;

} else {
    $numero_whatsapp = ""; // Número predeterminado de WhatsApp
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Enviar por WhatsApp</title>
    <style>
        .body {
            align-items: center;
        }

        .modal-footer #W1 {
            background-color: #25d366;
        }

        .modal-footer #W1:hover {
            color: #ffff;
            background-color: #000000;
        }

        .modal-footer #W3 {
            background-color: #25d366;
        }

        .modal-footer #W3:hover {
            color: #ffff;
            background-color: #000000;
        }

        #subirnum {
            margin-top: 20px;
        }

        .card {
            position: relative;
            left: 100px;
            height: 20rem;
            width: 100rem;
        }
    </style>
</head>

<body>
    <div class="modal fade modal-envWS" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="width: 700px; height: auto; margin: auto; font-family: Trebuchet MS, sans-serif; font-size: 10pt;">
                <div class="modal-header" col-sm-7 col-md-7 col-lg-7>
                    <h2 class="modal-title" id="exampleModalLabel" style="text-align: center;">ENVIAR POR WHATSAPP</h2>
                </div>
                <div class="card-body">
                    <form id="formulario-whatsapp" method="POST" action="">
                        <br>
                        <div class="col-sm-1 col-md-1 col-lg-1"></div>
                        <div class="input-group mb-3 col-sm-7 col-md-7 col-lg-7s">
                            <input type="text" class="form-control " placeholder="Numero Celular" aria-label="Username"
                                name="numero-celu" id="numero-celu" aria-describedby="basic-addon1" max="9"
                                style="position: relative; left: 110px">
                            <div class="input-group-prepend" style="position: relative; right: 50px; bottom: 30px;">
                                <div class="col-sm-1 col-md-1 col-lg-1"></div>
                                <select class="custom-select btn btn-outline-secondary codigo-num"
                                    id="inputGroupSelect01" style="padding: 0;" name="codigo-pais">
                                    <option value="+51" <?php if ($codigo_pais == '+51')
                                        echo 'selected'; ?>>+51
                                        <img src="image/countrys/peru.png">
                                    </option>
                                    <option value="+52" <?php if ($codigo_pais == '+52')
                                        echo 'selected'; ?>>+52
                                    </option>
                                    <option value="+54" <?php if ($codigo_pais == '+54')
                                        echo 'selected'; ?>>+54
                                    </option>
                                    <option value="+55" <?php if ($codigo_pais == '+55')
                                        echo 'selected'; ?>>+55
                                    </option>
                                    <option value="+56" <?php if ($codigo_pais == '+56')
                                        echo 'selected'; ?>>+56
                                    </option>
                                    <option value="+593" <?php if ($codigo_pais == '+593')
                                        echo 'selected'; ?>>+593
                                    </option>
                                </select>
                            </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <a id="W1" type="butom" class="btn btn-light">WhatsApp Web <i class="fas fa-laptop"
                            style="margin-left: 5px;"> </i></a>
                    <span class="btn btn-danger btn-close-envWS">Cerrar</span>
                </div>
            </div>
        </div>

        <br>
        <center />
        <script>
            document.getElementById("W1").addEventListener("click", function (event) {
                event.preventDefault();

                var codigoPais = document.getElementById("inputGroupSelect01").value;
                var numeroCelular = document.getElementById("numero-celu").value;

                var nicEditorInstance = nicEditors.findEditor('mensaje');
                var mensajeConten = nicEditorInstance.getContent();

                var tempDiv = document.createElement("div");
                tempDiv.innerHTML = mensajeConten;
                var message = tempDiv.textContent || tempDiv.innerText;

                var enlaceWhatsapp = "https://wa.me/" + encodeURIComponent(codigoPais + numeroCelular) +
                    "?text=" + encodeURIComponent(message);

                window.open(enlaceWhatsapp, '_blank');
            });
        </script>
</body>

<script type="text/javascript">
    $(document).ready(function () {

        $("#placaSearch").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?= base_url(); ?>index.php/maestros/empresa/getPlacas/",
                    type: "POST",
                    data: {
                        placa: $("#placaSearch").val()
                    },
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                $("#placaSearch").val(ui.item.value);
                $("#placa").val(ui.item.id);
            },
            minLength: 2
        });

        $('#table-control').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url(); ?>index.php/ventas/controlservicio/datatable_control",
                type: "POST",
                data: {
                    dataString: ""
                },
                beforeSend: function () {
                    $(".loading-table").show();
                },
                error: function () { },
                complete: function () {
                    $(".loading-table").hide();
                }
            },
            language: spanish,
            order: [
                [3, "desc"]
            ]
        });

        $("#buscar").click(function () {
            // Obtener valores de los nuevos campos
            let fechai = $("#fecha_inicio").val();
            let fechaf = $("#fecha_fin").val();
            let placa = $("#searchPlaca").val();
            let serie = $("#searchSerie").val();
            let numero = $("#searchNumero").val();
            let marca = $("#searchMarca").val();
            let orden = $("#searchOrden").val();
            let ruc = $("#searchRuc").val();
            let razon = $("#searchRazon").val();
            let producto = $("#searchProducto").val();

            // Inicializar o reinicializar DataTable con los nuevos parámetros
            $('#table-control').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?= base_url(); ?>index.php/ventas/controlservicio/datatable_control",
                    type: "POST",
                    data: {
                        fecha_inicio: fechai,
                        fecha_fin: fechaf,
                        searchPlaca: placa,
                        searchSerie: serie,
                        searchNumero: numero,
                        searchMarca: marca,
                        searchOrden: orden,
                        searchRuc: ruc,
                        searchRazon: razon,
                        searchProducto: producto
                    },
                    beforeSend: function () {
                        $(".loading-table").show();
                    },
                    error: function () {
                        // Puedes manejar errores aquí
                    },
                    complete: function () {
                        $(".loading-table").hide();
                    }
                },
                language: spanish,
                order: [
                    [1, "desc"]
                ]
            });
        });

        $("#excel").click(function () {
            let params = {
                fecha_inicio: $("#fecha_inicio").val(),
                fecha_fin: $("#fecha_fin").val(),
                searchPlaca: $("#searchPlaca").val(),
                searchSerie: $("#searchSerie").val(),
                searchNumero: $("#searchNumero").val(),
                searchMarca: $("#searchMarca").val(),
                searchOrden: $("#searchOrden").val(),
                searchRuc: $("#searchRuc").val(),
                searchRazon: $("#searchRazon").val(),
                searchProducto: $("#searchProducto").val()
            };

            let form = $('<form>', {
                action: "<?= base_url(); ?>index.php/ventas/controlservicio/exportar_excel",
                method: 'POST',
                target: '_blank'
            });

            $.each(params, function (key, val) {
                form.append($('<input>', { type: 'hidden', name: key, value: val }));
            });

            $('body').append(form);
            form.submit();
            form.remove();
        });




        $("#limpiar").click(function () {
            // Limpiar valores de los inputs según los nuevos IDs
            $("#fecha_inicio").val("");
            $("#fecha_fin").val("");
            $("#searchPlaca").val("");
            $("#searchSerie").val("");
            $("#searchNumero").val("");
            $("#searchMarca").val("");
            $("#searchOrden").val("");
            $("#searchRuc").val("");
            $("#searchRazon").val("");
            $("#searchProducto").val("");

            // Variables vacías para enviar al backend
            let fecha_inicio = "";
            let fecha_fin = "";
            let searchPlaca = "";
            let searchSerie = "";
            let searchNumero = "";
            let searchMarca = "";
            let searchOrden = "";
            let searchRuc = "";
            let searchRazon = "";
            let searchProducto = "";

            // Reiniciar DataTable sin filtros
            $('#table-control').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?= base_url(); ?>index.php/ventas/controlservicio/datatable_control/",
                    type: "POST",
                    data: {
                        fecha_inicio: fecha_inicio,
                        fecha_fin: fecha_fin,
                        searchPlaca: searchPlaca,
                        searchSerie: searchSerie,
                        searchNumero: searchNumero,
                        searchMarca: searchMarca,
                        searchOrden: searchOrden,
                        searchRuc: searchRuc,
                        searchRazon: searchRazon,
                        searchProducto: searchProducto
                    },
                    beforeSend: function () {
                        $(".loading-table").show();
                    },
                    error: function () {
                        // Manejo de error si quieres
                    },
                    complete: function () {
                        $(".loading-table").hide();
                    }
                },
                language: spanish,
                order: [
                    [3, "desc"]
                ]
            });
        });


        $(".btn-close-envmail").click(function () {
            $(".modal-envmail").modal("hide");
        });

        $(".btn-close-envWS").click(function () {
            $(".modal-envWS").modal("hide");
        });

        $(".btn-sendMail").click(function () {

            documento = '<?= (isset($id_documento)) ? $id_documento : ""; ?>';
            codigo = $("#idDocMail").val();

            destinatario = $("#destinatario").val();
            asunto = $("#asunto").val();
            mensaje = $(".nicEdit-main").html();

            if (codigo == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Sin documento. Intentelo nuevamente.",
                    showConfirmButton: true,
                    timer: 2000
                });
                return null;
            }

            if (destinatario == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Debe ingresar un destinatario.",
                    showConfirmButton: true,
                    timer: 2000
                });
                $("#destinatario").focus();
                return null;
            } else {
                correosI = destinatario.split(",");
                /* DEVELOPING
                expr = /^[a-zA-Z0-9@_.\-]{1,3}$/
                
                for (var i = 0; i < correosI.length - 1; i++) {
                    if ( expr.test(correosI[i]) == false ){
                        alert("Verifique que todos los correos indicados sean validos.");
                    }
                  }*/
            }

            if (asunto == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Indique el asunto del correo.",
                    showConfirmButton: true,
                    timer: 2000
                });
                $("#asunto").focus();
                return null;
            }

            var url = "<?= base_url(); ?>index.php/compras/ocompra/sendDocMail";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    codigo: codigo,
                    destinatario: destinatario,
                    asunto: asunto,
                    mensaje: mensaje,
                    documento: documento
                },
                dataType: "json",
                error: function (data) { },
                beforeSend: function () {
                    $(".tempde_footer .icon-loading-md").show();
                    $(".btn-sendMail").hide();
                },
                success: function (data) {
                    if (data.result == "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Correo electronico enviado.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $(".modal-envmail").modal("hide");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Correo no enviado, intentelo nuevamente.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                complete: function () {
                    $(".tempde_footer .icon-loading-md").hide();
                    $(".btn-sendMail").show();
                }
            });
        });
    });

    function open_mail(id) {
        $(".modal-envmail").modal("toggle");

        url = "<?= base_url(); ?>index.php/ventas/controlservicio/getInfoSendMail";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: id
            },
            dataType: "json",
            error: function () {

            },
            beforeSend: function () {
                $("#id_control").val("");
                $("#placa").val("");
                $("#kilometraje").val("");
                $("#proximo").val("");
                $("#destinatario").val("");

                $(".mail-empresa-envio").html("");
                $(".mail-serie-numero").html("");
                $(".mail-fecha").html("");


                $(".mail-contactos").html("");
            },
            success: function (data) {
                var info = data.info[0];

                if (data.match == true) {
                    $("#idDocMail").val(info.codigo);
                    $("#placa_mail").val(info.placa);

                    $("#kilometraje_mail").val(info.km_actual);
                    $("#prox_sugerido_mail").val(info.prox_sugerido);



                    $(".proxcambio_mail").html(info.prox_cambio);
                    $(".placa_mail_envio").html(info.placa);
                    $(".kmactuales_envio").html(info.km_actual);
                    $(".prox_sugerido_envio").html(info.prox_sugerido);
                    $(".mail-empresa-envio").html(info.empresa_envio);
                    $(".mail-serie-numero").html(info.serie + " - " + info.numero);
                    $(".mail-fecha").html(info.fecha);


                    $.each(info.contactos, function (i, item) {
                        var inputsContactos =
                            "&nbsp;<input type='checkbox' class='ncontacto' onclick='ingresar_correo(\"" +
                            item.correo + "\")' value='" + item.correo + "'>" + item.contacto;
                        $(".mail-contactos").append(inputsContactos);
                    });
                }
            }
        });
    }

    function open_WS(id) {
        $(".modal-envWS").modal("toggle");

        url = "<?= base_url(); ?>index.php/ventas/controlservicio/getInfoSendWS";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: id
            },
            dataType: "json",
            error: function () {

            },
            beforeSend: function () {
                $("#id_control").val("");
                $("#placa").val("");
                $("#kilometraje").val("");
                $("#proximo").val("");
                $("#destinatario").val("");

                $(".mail-empresa-envio").html("");
                $(".mail-serie-numero").html("");
                $(".mail-fecha").html("");


                $(".mail-contactos").html("");
            },
            success: function (data) {
                var info = data.info[0];

                if (data.match == true) {
                    $("#idDocMail").val(info.codigo);
                    $("#placa_mail").val(info.placa);

                    $("#kilometraje_mail").val(info.km_actual);
                    $("#prox_sugerido_mail").val(info.prox_sugerido);



                    $(".proxcambio_mail").html(info.prox_cambio);
                    $(".placa_mail_envio").html(info.placa);
                    $(".kmactuales_envio").html(info.km_actual);
                    $(".prox_sugerido_envio").html(info.prox_sugerido);
                    $(".mail-empresa-envio").html(info.empresa_envio);
                    $(".mail-serie-numero").html(info.serie + " - " + info.numero);
                    $(".mail-fecha").html(info.fecha);


                    $.each(info.contactos, function (i, item) {
                        var inputsContactos =
                            "&nbsp;<input type='checkbox' class='ncontacto' onclick='ingresar_correo(\"" +
                            item.correo + "\")' value='" + item.correo + "'>" + item.contacto;
                        $(".mail-contactos").append(inputsContactos);
                    });
                }
            }
        });
    }


    function ingresar_correo(correo) {

        destinatarios = $("#destinatario").val();

        correosI = destinatarios.split(",");
        cantidad = correosI.length;

        add = true;

        for (i = 0; i < cantidad; i++) {
            if (correosI[i] == correo)
                add = false;
        }

        if (add == true) {
            if (destinatarios != "")
                $("#destinatario").val(destinatarios + "," + correo);
            else
                $("#destinatario").val(correo);
        } else {
            nvoCorreos = "";
            for (i = 0; i < cantidad; i++) {
                if (correosI[i] != correo) {
                    if (i > 0 && nvoCorreos != "") {
                        nvoCorreos += ",";
                    }

                    nvoCorreos += correosI[i];
                }
            }

            $("#destinatario").val(nvoCorreos);
        }
    }
</script>