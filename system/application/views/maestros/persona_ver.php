<?php
$nombre_persona = $this->session->userdata('nombre_persona');
$persona        = $this->session->userdata('persona');
$usuario        = $this->session->userdata('usuario');
$url            = base_url()."index.php";
if(empty($persona)) header("location:$url");
?>
<html>
	<head>
	<title><?php echo TITULO;?></title>
	<link href="<?php echo URL_CSS;?>estilos.css?=<?=CSS;?>" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo URL_BASE;?>js/jquery.js?=<?=JS;?>"></script>
        <script type="text/javascript" src="<?php echo URL_BASE;?>js/mantenimiento.js?=<?=JS;?>"></script>
	<script type="text/javascript" src="<?php echo URL_BASE;?>js/jquery.metadata.js?=<?=JS;?>"></script>
	<script type="text/javascript" src="<?php echo URL_BASE;?>js/jquery.validate.js?=<?=JS;?>"></script>		
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $titulo;?></div>
				<div id="frmBusqueda">
					
                    <div id="datosPersona">
                        <table class="fuente8" width="98%" cellspacing=0 cellpadding="6" border="0">
                                <tr>
                                  <td>Nombres</td>
                                  <td>
                                     <?php echo $datos[0]->PERSC_Nombre;?>
                                  </td>
                                  <td>Lugar de Nacimiento</td>
                                  <td><?php echo $nacimiento;?></td>
                                </tr>
                                <tr>
                                    <td>Apellidos Paterno</td>
                                    <td><?php echo $datos[0]->PERSC_ApellidoPaterno;?></td>
                                  <td>Sexo</td>
                                  <td><?php echo $sexo;?></td>
                                </tr>
                                <tr>
                                    <td>Apellidos Materno</td>
                                    <td><?php echo $datos[0]->PERSC_ApellidoMaterno;?></td>
                                  <td>Estado Civil</td>
                                  <td><?php echo $estado_civil;?></td>

                                </tr>
                                <tr>
                                    <td>Tipo de Documento</td>
                                    <td><?php echo $tipo_doc;?></td>
                                  <td>Nacionalidad</td>
                                  <td><?php echo $nacionalidad;?></td>
                                </tr>
                                <tr>
                                    <td>Número de Documento</td>
                                    <td><?php echo $datos[0]->PERSC_NumeroDocIdentidad;?></td>
                                   <td>R.U.C.</td>
                                      <td><?php echo $datos[0]->PERSC_Ruc;?></td>
                                </tr>
                        </table>
                        </div>
					
					<div id="divDireccion">
                        <table class="fuente8" width="98%" cellspacing="0" cellpadding="6" border="0">
                          <tr height="10px">
                            <td colspan="4"><hr></td>
                          </tr>
                            <tr>
							  <td>Departamento</td>
                              <td colspan="3">
							  	<div id="divUbigeo">
									<?php echo $dpto;?>
                                    &nbsp;&nbsp;
                                    Provincia&nbsp;&nbsp;
                                    <?php echo $prov;?>
									&nbsp;&nbsp;
                                    Distrito&nbsp;&nbsp;
                                    <?php echo $dist;?>
								</div>
                              </td>
                          </tr>
                            <tr>
                              <td width="16%">Direcci&oacute;n fiscal</td>
                              <td colspan="3"><?php echo $direccion;?></td>
                           </tr>
                          <tr height="10px">
                            <td colspan="4"><hr></td>
                          </tr>
                          <tr>
                            <td colspan="4">
                                <table width="100%" class="fuente8" cellspacing=0 cellpadding=3 border="0">
                                    <tr>
                                        <td width="16%">Tel&eacute;fono </td>
                                        <td><?php echo $telefono;?></td>
                                        <td>M&oacute;vil</td>
                                        <td><?php echo $movil;?></td>
                                        <td>Fax</td>
                                        <td><?php echo $fax;?></td>
                                    </tr>
                                    <tr>
                                        <td>Correo electr&oacute;nico  </td>
                                        <td><?php echo $email;?></td>
                                        <td>Direcci&oacute;n web </td>
                                        <td colspan="3"><?php echo $web;?></td>
                                    </tr>
                                </table>
                            </td>
                          </tr>
                        </table>
                  </div>					   
			  </div>
				<div id="botonBusqueda">
					<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
					<a href="#" onclick="atras_persona();"><img src="<?php echo base_url();?>images/botonaceptar.jpg?=<?=IMG;?>" width="85" height="22" border="1" onMouseOver="style.cursor=cursor"></a>
			  </div>
		  </div>
		  </div>
		</div>
	</body>
</html>
