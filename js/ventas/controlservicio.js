var base_url;
var tipo_oper;
var contiene_igv;
jQuery(document).ready(function(){
	base_url   = $("#base_url").val();
	tipo_oper    = $("#tipo_oper").val();
	contiene_igv = $("#contiene_igv").val();

	$("#salir").click(function(event) {
		limpiar_modal_control();
	});

	$("#close").click(function(event) {
		limpiar_modal_control();
	});

	$("#limpiarOcompra").click(function(){
		$("#salir").val(1);
		url = base_url+"index.php/compras/ocompra/ocompras/0/"+tipo_oper;
		location.href = url;
	});

	$("#volverSeguimiento").click(function(){
		$("#salir").val(1);
		url = base_url+"index.php/compras/ocompra/seguimiento_ocompras/0/"+tipo_oper+"/1";
		location.href = url;
	});

	$("#cancelarOcompra").click(function(){
		$("#salir").val(1);
		url = base_url+"index.php/compras/ocompra/ocompras/0/"+tipo_oper;
		location.href = url;
	});

	$("#aceptarOcompra").click(function(){
		url = base_url+"index.php/compras/ocompra/ocompras/0/"+tipo_oper+"/1";
		location.href = url;
	});

	$("#cancelarOcompra2").click(function(){
		url = base_url+"index.php/compras/ocompra/ocompras/0/"+tipo_oper;
		location.href = url;
	});

	$("#form_busqueda").keypress(function(e) {
    if(e.which == 13){
    	return false;
    }
  });

	$("#nuevoOcompa").click(function(){
		$("#ModalEditar").toggle();
	});

	$("#checkTodos").change(function(){
		$('input').each( function() {                   
			if($("#checkTodos").attr('checked') == true){
				this.checked = true;
			} else {
				this.checked = false;
			}
		});               
	});

});

function enviar_cotizacion(codigo){
	url = base_url+"index.php/compras/ocompra/ventana_cotizacion_correos/"+codigo;
	window.open(url,'',"width=800,height=600,menubars=no,resizable=no;")
}


function ver_ocompra(ocompra){
	location.href = base_url+"index.php/compras/ocompra/ver_ocompra/"+ocompra;
}

function ocompra_ver_pdf(ocompra){
    //url = base_url+"index.php/compras/ocompra/ocompra_ver_pdf/"+ocompra;
    url = base_url+"index.php/compras/ocompra/ocompra_ver_pdf_conmenbrete/"+ocompra+"/0";
    window.open(url,'',"width=800,height=600,menubars=no,resizable=no;")
  }

  function ocompra_ver_pdf_conmenbrete(ocompra){
  	url = base_url+"index.php/compras/ocompra/ocompra_ver_pdf_conmenbrete/"+ocompra+"/1";
  	window.open(url,'',"width=800,height=600,menubars=no,resizable=no;")
  }

  function ocompra_download_excel(ocompra){
  	url = base_url + "index.php/compras/ocompra/ocompra_descarga_excel/"+ocompra;
  	location.href = url;
  }

  function registrar_control() {


  	var codigo_control = $('#codigo_control').val();
  	var tipo_id = $("#tipo_id").val();
  	/*validacion*/

  	let placa = $("#placaSearch").val().toUpperCase().trim(); // trim() elimina espacios

	let patron = /^(?:[A-Z0-9\-]{6,7}|[A-Z0-9]{17})$/;

	if (!patron.test(placa)) {
		Swal.fire({
			icon: "error",
			title: "Verifique los datos ingresados.",
			html: "<b class='color-red'>Debe ingresar una placa (6-7 caracteres) o un VIN válido (17 caracteres).</b>",
			showConfirmButton: true,
			timer: 4000
		});
		$("#placaSearch").focus();
		return false;
	}

  	if($('#fecha_control').val()===""){
  		Swal.fire('Debe Ingresar la fecha de control!')
  		Swal.fire(
  			'Debe Ingresar la fecha de control!',
  			'Por favor ingresa la fecha del control de servicio',
  			'error'
  			)
  		$('#direccion').focus();
  		return false;
  	}

  	/*fin validacion*/
  	if (codigo_control === ''){
  		dataString = $('#frmControl').serialize();
  		url = base_url + "index.php/ventas/controlservicio/registrar_control";
  	}else{
  		dataString = $('#frmControl').serialize();
  		url = base_url + "index.php/ventas/controlservicio/modificar_control";
  	}

  	$.ajax({
  		type: "POST",
  		url: url,
  		data: dataString,
  		dataType: 'json',
  		async: false,
  		beforeSend: function (data) {
  		},
  		error: function (data) {
  			Swal.fire('NO SE PUDO COMPLETAR LA OPERACIÓN - REVISE LOS CAMPOS INGRESADOS. ')
  		},
  		success: function (data) {
				Swal.fire({
					icon: data.result,
					title: data.message,
					showConfirmButton: true,
					timer: 4000
				});
				if (data.result == "success"){
  				limpiar_modal_control();
  				$('#table-control').DataTable().ajax.reload();
				}
  		}
  	});
  } 

  function addControl(id){
  	limpiar_modal_control();
  	$("#myModalLabel").html("Nuevo Control de Servicios");
  	$("#agregar_distribuidor_boton").html("Registrar Control");
  	$("#ModalEditar").modal("toggle");
  }

  function editar_control(id){
  	limpiar_modal_control();
  	$("#myModalLabel").html("Editar Control de Servicios");
  	$("#agregar_distribuidor_boton").html("Editar Datos");
  	var ruta = base_url+"index.php/ventas/controlservicio/editar_control/"+id;

  	$.ajax({
  		type:"POST",
  		url: ruta,
  		data:{ id: id },
  		dataType:'json',
  		success:function(data){
  			if (!$.isEmptyObject(data)) {
  				$("#codigo_control").val(data[0].codigo_control);
  				$("#placa").val(data[0].idPlaca);
  				$("#placaSearch").val(data[0].placa);
				$("#marca").val(data[0].marca);
				$("#modelo").val(data[0].modelo);
				$("#anorigen").val(data[0].anorigen);
  				$("#fecha_control").val(data[0].fecha_control);
  				$("#km_actual").val(data[0].km_actual);
  				$("#aceite_motor").val(data[0].aceite_motor);
  				$("#prox_cambio").val(data[0].prox_cambio);
  				$("#filtro_aceite").val(data[0].filtro_aceite);
  				$("#filtro_aire").val(data[0].filtro_aire);
  				$("#filtro_aa").val(data[0].filtro_aa);
  				$("#filtro_petroleo").val(data[0].filtro_petroleo);
  				$("#filtro_sep_agua").val(data[0].filtro_sep_agua);
  				$("#filtro_gasolina").val(data[0].filtro_gasolina);
  				$("#bujia").val(data[0].bujia);
  				$("#det_bujia").val(data[0].det_bujia);
  				$("#aceite_caja").val(data[0].aceite_caja);
  				$("#prox_cambio_aceite_caja").val(data[0].prox_cambio_aceite_caja);
  				$("#aceite_corona").val(data[0].aceite_corona);
  				$("#prox_cambio_aceite_corona").val(data[0].prox_cambio_aceite_corona);
  				$("#refrigerante").val(data[0].refrigerante);
  				$("#prox_cambio_refrigerante").val(data[0].prox_cambio_refrigerante);
  				$("#porcentaje").val(data[0].porcentaje);
  				$("#liq_freno").val(data[0].liq_freno);
  				$("#observaciones").val(data[0].observaciones);
          $("#prox_sugerido").val(data[0].prox_sugerido);
  				
  				if (data[0].engrase == '1' && !$("#engrase").is(":checked")){
						$("#engrase").attr("checked", true);
  				}
  			}
  		},
  		complete: function(){
  			$("#ModalEditar").modal("toggle");
  		}
  	});
  }

  function ver_control(id) {
  	limpiar_modal_control();  
  	$("#myModalLabel").html("Ver Registros de Control");
  	var ruta = base_url+"index.php/ventas/controlservicio/editar_control/"+id;

  	$.ajax({
  		type:"POST",
  		url: ruta,
  		data:{ id: id},
  		dataType:'json',
  		success:function(data){
			console.log(data);
  			if (!$.isEmptyObject(data)) {
				
  				$("#agregar_control_boton").hide();
  				$("#codigo_control").val(data[0].codigo_control);
  				$("#placa").val(data[0].idPlaca);
				$("#marca").val(data[0].marca);
				$("#modelo").val(data[0].modelo);
				$("#anorigen").val(data[0].marca);
				$("#anorigen").val(data[0].anorigen);
  				$("#placaSearch").val(data[0].placa);
  				$("#fecha_control").val(data[0].fecha_control);
  				$("#km_actual").val(data[0].km_actual);
  				$("#aceite_motor").val(data[0].aceite_motor);
  				$("#prox_cambio").val(data[0].prox_cambio);
  				$("#filtro_aceite").val(data[0].filtro_aceite);
  				$("#filtro_aire").val(data[0].filtro_aire);
  				$("#filtro_aa").val(data[0].filtro_aa);
  				$("#filtro_petroleo").val(data[0].filtro_petroleo);
  				$("#filtro_sep_agua").val(data[0].filtro_sep_agua);
  				$("#filtro_gasolina").val(data[0].filtro_gasolina);
  				$("#bujia").val(data[0].bujia);
  				$("#det_bujia").val(data[0].det_bujia);
  				$("#aceite_caja").val(data[0].aceite_caja);
  				$("#prox_cambio_aceite_caja").val(data[0].prox_cambio_aceite_caja);
  				$("#aceite_corona").val(data[0].aceite_corona);
  				$("#prox_cambio_aceite_corona").val(data[0].prox_cambio_aceite_corona);
  				$("#refrigerante").val(data[0].refrigerante);
  				$("#prox_cambio_refrigerante").val(data[0].prox_cambio_refrigerante);
  				$("#porcentaje").val(data[0].porcentaje);
  				$("#liq_freno").val(data[0].liq_freno);
  				$("#observaciones").val(data[0].observaciones);
          $("#prox_sugerido").val(data[0].prox_sugerido);

  				if (data[0].engrase == '1' && !$("#engrase").is(":checked")){
						$("#engrase").attr("checked", true);
  				}

  				$("#codigo_persona").attr({"disabled": "true"});
  				$("#codigo_control").attr({"disabled": "true"});
  				$("#placa").attr({"disabled": "true"});
  				$("#placaSearch").attr({"disabled": "true"});
				$("#marca").attr({"disabled": "true"});
				$("#modelo").attr({"disabled": "true"});
				$("#anorigen").attr({"disabled": "true"});
  				$("#fecha_control").attr({"disabled": "true"});
  				$("#km_actual").attr({"disabled": "true"});
  				$("#aceite_motor").attr({"disabled": "true"});
  				$("#prox_cambio").attr({"disabled": "true"});
  				$("#filtro_aceite").attr({"disabled": "true"});
  				$("#filtro_aire").attr({"disabled": "true"});
  				$("#filtro_aa").attr({"disabled": "true"});
  				$("#filtro_petroleo").attr({"disabled": "true"});
  				$("#filtro_sep_agua").attr({"disabled": "true"});
  				$("#filtro_gasolina").attr({"disabled": "true"});
  				$("#bujia").attr({"disabled": "true"});
  				$("#det_bujia").attr({"disabled": "true"});
  				$("#aceite_caja").attr({"disabled": "true"});
  				$("#prox_cambio_aceite_caja").attr({"disabled": "true"});
  				$("#aceite_corona").attr({"disabled": "true"});
  				$("#prox_cambio_aceite_corona").attr({"disabled": "true"});
  				$("#refrigerante").attr({"disabled": "true"});
  				$("#prox_cambio_refrigerante").attr({"disabled": "true"});
  				$("#porcentaje").attr({"disabled": "true"});
  				$("#liq_freno").attr({"disabled": "true"});
  				$("#observaciones").attr({"disabled": "true"});
          $("#prox_sugerido").attr({"disabled": "true"});
  				$("#engrase").attr({"disabled": "true"});
  			}
  		},
  		complete: function(){
  			$("#ModalEditar").modal("toggle");
  		}
  	});
  }

  function limpiar_modal_control(){
  	$("#collapse_inicial").click();
  	$("#codigo_control").val("");
  	$("#placa").val("");
	$("#marca").val("");
	$("#modelo").val("");
	$("#anorigen").val("");
  	$("#placaSearch").val("");
  	$("#fecha_control").val("");
  	$("#km_actual").val("");
  	$("#aceite_motor").val("");
  	$("#prox_cambio").val("");
  	$("#filtro_aceite").val("");
  	$("#filtro_aire").val("");
  	$("#filtro_aa").val("");
  	$("#filtro_petroleo").val("");
  	$("#filtro_sep_agua").val("");
  	$("#filtro_gasolina").val("");
  	$("#bujia").val("");
  	$("#det_bujia").val("");
  	$("#aceite_caja").val("");
  	$("#prox_cambio_aceite_caja").val("");
  	$("#aceite_corona").val("");
  	$("#prox_cambio_aceite_corona").val("");
  	$("#refrigerante").val("");
  	$("#prox_cambio_refrigerante").val("");
  	$("#porcentaje").val("");
  	$("#liq_freno").val("");
  	$("#observaciones").val("");
    $("#prox_sugerido").val("");
  	$("#engrase").attr("checked", false);

  	$("#agregar_control_boton").show();

  	$("#codigo_persona").removeAttr("disabled");

  	$("#codigo_control").removeAttr("disabled");
  	$("#placa").removeAttr("disabled");
  	$("#placaSearch").removeAttr("disabled");
	  $("#marca").removeAttr("disabled");
	  $("#modelo").removeAttr("disabled");
	  $("#anorigen").removeAttr("disabled");
  	$("#fecha_control").removeAttr("disabled");
  	$("#km_actual").removeAttr("disabled");
  	$("#aceite_motor").removeAttr("disabled");
  	$("#prox_cambio").removeAttr("disabled");
  	$("#filtro_aceite").removeAttr("disabled");
  	$("#filtro_aire").removeAttr("disabled");
  	$("#filtro_aa").removeAttr("disabled");
  	$("#filtro_petroleo").removeAttr("disabled");
  	$("#filtro_sep_agua").removeAttr("disabled");
  	$("#filtro_gasolina").removeAttr("disabled");
  	$("#bujia").removeAttr("disabled");
  	$("#det_bujia").removeAttr("disabled");
  	$("#aceite_caja").removeAttr("disabled");
  	$("#prox_cambio_aceite_caja").removeAttr("disabled");
  	$("#aceite_corona").removeAttr("disabled");
  	$("#prox_cambio_aceite_corona").removeAttr("disabled");
  	$("#refrigerante").removeAttr("disabled");
  	$("#prox_cambio_refrigerante").removeAttr("disabled");
  	$("#porcentaje").removeAttr("disabled");
  	$("#liq_freno").removeAttr("disabled");
  	$("#observaciones").removeAttr("disabled");
    $("#prox_sugerido").removeAttr("disabled");
  	$("#engrase").removeAttr("disabled");
  }


  function eliminar_control(codigo){
  	let url =  base_url+"index.php/ventas/controlservicio/eliminar_control/";
  	Swal.fire({
  		icon: "question",
  		title: "¿Desea anular este servicio?",
  		showConfirmButton: true,
  		showCancelButton: true,
  		confirmButtonText: "Aceptar",
  		cancelButtonText: "Cancelar"
  	}).then(result => {
  		if (result.value){
  			$.ajax({
  				url: url,
  				type: "POST",
  				data: {
  					term: codigo
  				},
  				dataType: "json",
  				success: function (data) {
  					Swal.fire({
                icon: "success",
                title: "Control anulado.",
                showConfirmButton: true,
                timer: 2000
            });
  					$('#table-control').DataTable().ajax.reload();
  				}
  			});
  		}
  	});
  }