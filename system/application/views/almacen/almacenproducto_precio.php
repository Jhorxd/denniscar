 <script src="<?php echo base_url(); ?>js/jquery.columns.min.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/almacen/almacenproducto.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/funciones.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/fancybox/jquery.fancybox-1.3.4.pack.js?=<?=JS;?>"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js?=<?=JS;?>"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.js?=<?=JS;?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/fancybox/jquery.fancybox-1.3.4.css?=<?=CSS;?>" media="screen" />
<script type="text/javascript">
    $(document).ready(function() {
        $("a#linkSerie").fancybox({
                'width'	     : 750,
                'height'         : 540,
                'autoScale'	     : false,
                'transitionIn'   : 'none',
                'transitionOut'  : 'none',
                'showCloseButton': false,
                'modal'          : false,
                'type'	     : 'iframe'
        });

        $("#dialogSeries").dialog({
    		resizable: false,
    	    height: "auto",
    	    width: 600,
            autoOpen: false,
            show: {
              effect: "blind",
              duration: 1000
            },
            hide: {
              effect: "explode",
              duration: 1000
            }
          });
        
        

        $('.paginacion').live('click', function(){
            var urls = "<?php echo base_url() ?>index.php/almacen/producto/buscar_productos/";
            $('#cuerpoPagina').html('<div><img src="images/loading.gif?=<?=IMG;?>" width="70px" height="70px"/></div>');
            var page = $(this).attr('data');        
            var dataString = 'page='+page;
            $.ajax({
                type: "GET",
                url: urls,
                data: dataString,
                success: function(data) {
                    $('#cuerpoPagina').fadeIn(1000).html(data);
                }
            });
        });
    });
</script>

<div id=dialogSeries title="Series Ingresadas">
  <div id="mostrarDetallesSeries">
	<div id="detallesSeries"></div>	
  </div>
</div>
<div id="pagina">
    <div id="zonaContenido">
    <div align="center">
        <div id="tituloForm" class="header"><?php echo $titulo_tabla;;?></div>
        <div id="cuerpoPagina">
    <form id="form_busqueda" method="post">
        <div class="row fuente8 py-1">
            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                <label for="txtCodigo">C¨®digo: </label>
                <input id="txtCodigo" name="txtCodigo" type="text" class="form-control w-porc-90 h-1" placeholder="Codigo" maxlength="30" value="<?=$codigo;?>">
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                <label for="txtNombre">Nombre: </label>
                <input id="txtNombre" name="txtNombre" type="text" class="form-control w-porc-90 h-1" maxlength="100" placeholder="Nombre producto" value="<?php echo $nombre; ?>">
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                <label for="txtFamilia">Familia: </label>
                <select name="txtFamilia" id="txtFamilia" class="form-control w-porc-90 h-2">
                    <option value=""> TODOS </option><?php
                    if ($familias != NULL){
                        foreach ($familias as $i => $v){ ?>
                            <option value="<?=$v->FAMI_Codigo;?>"><?=$v->FAMI_Descripcion;?></option> <?php
                        }
                    } ?>
                </select>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                <label for="txtMarca">Marca: </label>
                <input id="txtMarca" type="text" class="form-control w-porc-90 h-1" name="txtMarca" maxlength="100" placeholder="Marca producto" value="<?=$marca;?>">
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                <label for="txtModelo">Modelo: </label>
                <select name="txtModelo" id="txtModelo" class="form-control w-porc-90 h-2">
                    <option value=""> TODOS </option><?php
                    if ($modelos != NULL){
                        foreach ($modelos as $indice => $val){
                            if ($val->PROD_Modelo != ''){ ?>
                                <option value="<?=$val->PROD_Modelo;?>"><?=$val->PROD_Modelo;?></option> <?php
                            }
                        }
                    } ?>
                </select>
            </div>
    </form>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="acciones">
                        <div id="botonBusqueda">
                            <ul id="limpiarP" class="lista_botones">
                                <li id="limpiar">Limpiar</li>
                            </ul>
                            <ul id="buscar" class="lista_botones">
                                <li id="buscar">Buscar</li>
                            </ul>
                        </div>
                        <div id="lineaResultado">Registros encontrados</div>
                    </div>
                </div>
            </div>
            <input id="codigoInterno" name="codigoInterno" type="hidden" class="cajaGrande" maxlength="100" placeholder="Codigo original" value="<?=$codigoInterno;?>">
        </div>
         
            <div id="frmResultado">
                <div id="cargando_datos" class="loading-table">
                    <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                </div>
                <?php echo $form_open2;?>
                <input type="hidden" name="compania" id="compania"/>
                <input type="hidden" name="almacen" id="almacen" />
                <input type="hidden" name="producto" id="producto" />
                <input type="hidden" name="codproducto" id="codproducto" />
                <input type="hidden" name="nombre_producto" id="nombre_producto" />
                <a href="javascript:;" id="linkSerie"></a>
                <table class="display fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" id="table-stock">
                    <thead>
                        <tr class="cabeceraTabla">
                            <th width="10%" data-orderable="true">CODIGO</th>
                            <th width="30%" data-orderable="true">DESCRIPCION</th>
                            <th width="10%" data-orderable="true">MODELO</th>
                            <th width="10%" data-orderable="true">FAMILIA</th>
                            <th width="10%" data-orderable="true">MARCA</th>
                            <th width="05%" data-orderable="false">STOCK</th>
                            <th width="02%" data-orderable="false">UND</th>
                            <th width="08%" data-orderable="false">P.COSTO S/</th>
                            <th width="08%" data-orderable="false">P.COSTO $</th>
                            <th width="10%" data-orderable="false">P.VENTA</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <?php echo $form_close2;?>
            </div>
             <div style="margin-top: 15px;"><?php echo $paginacion;?></div>
          
            <input type="hidden" id="iniciopagina" name="iniciopagina">
            <input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
            <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
        </div>
    </div>
</div>			
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#nombre_prod').keyup(function(e){
            var key=e.keyCode || e.which;
            if (key==13){
               
                $("#buscar").click();
            } 
        });
        $('#table-stock').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : "<?=base_url();?>index.php/almacen/almacenproducto/datatable_almacen_producto_precios",
                    type: "POST",
                    data: { dataString: "" },
                    beforeSend: function(){
                    },
                    error: function(){
                    }
            },
            language: spanish
        });

        $("#buscar").click(function(){
            codigo = $('#txtCodigo').val();
            producto = $('#txtNombre').val();
            familia = $('#txtFamilia').val();
            marca = $('#txtMarca').val();
            modelo = $('#txtModelo').val();
            prodactivo = $('#prodactivo').val();
            
            $('#table-stock').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax:{
                        url : "<?=base_url();?>index.php/almacen/almacenproducto/datatable_almacen_producto_precios",
                        type: "POST",
                        data: { txtCodigo: codigo, txtNombre: producto, txtFamilia: familia, txtMarca: marca, txtModelo: modelo,prodactivo:prodactivo },
                        error: function(){
                        }
                },
                language: spanish
            });
        });

        $("#limpiarP").click(function(){
            $("#txtCodigo").val("");
            $("#txtNombre").val("");
            $("#txtFamilia").val("");
            $("#txtMarca").val("");
            $("#txtModelo").val("");
            $("#prodactivo").val("1");

            codigo = "";
            producto = "";
            familia = "";
            marca = "";
            modelo = "";
            

            $('#table-stock').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax:{
                        url : "<?=base_url();?>index.php/almacen/almacenproducto/datatable_almacen_producto_precios",
                        type: "POST",
                         data: { txtCodigo: codigo, txtNombre: producto, txtFamilia: familia, txtMarca: marca, txtModelo: modelo,prodactivo:prodactivo },
                        error: function(){
                        }
                },
                language: spanish
            });
        });
    });
</script>