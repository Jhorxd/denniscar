<?php
include("system/application/libraries/pchart/pData.php");
include("system/application/libraries/pchart/pChart.php");
include("system/application/libraries/cezpdf.php");
include("system/application/libraries/class.backgroundpdf.php");

class Controlservicio extends Controller
{

	private $url;
	private $compania;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('lib_props');
		$this->load->library('html');
		$this->load->helper('utf_helper');

		$this->load->model('maestros/empresa_model');
		$this->load->model('ventas/controlservicio_model');

		$this->compania = $this->session->userdata('compania');
		$this->url = base_url();
	}

	public function index()
	{
		$this->load->library('layout', 'layout');
		$this->layout->view('seguridad/inicio');
	}

	public function control_servicios($j = '0', $tipo_oper = 'C', $eval = '0')
	{
		$data['compania'] = $this->compania;
		$this->load->library('layout', 'layout');

		$data['registros'] = $this->controlservicio_model->total_controles($tipo_oper);
		$conf['base_url'] = site_url('ventas/controlservicio/control_servicios/');

		$data['fechai'] = form_input(array("name" => "fechai", "id" => "fechai", "class" => "cajaGeneral cajaSoloLectura", "readonly" => "readonly", "size" => 10, "maxlength" => "10", "value" => ""));
		$data['fechaf'] = form_input(array("name" => "fechaf", "id" => "fechaf", "class" => "cajaGeneral cajaSoloLectura", "readonly" => "readonly", "size" => 10, "maxlength" => "10", "value" => ""));
		$data['titulo_tabla'] = "CONTROL DE SERVICIOS";
		$data['titulo_busqueda'] = "BUSCAR CONTROL DE SERVICIO";
		$data['tipo_oper'] = $tipo_oper;
		$data['id_documento'] = ($tipo_oper == 'C') ? 3 : 18;
		$data['cboVendedor'] = $this->lib_props->listarVendedores();
		$data['oculto'] = form_hidden(array('base_url' => base_url(), 'tipo_oper' => $tipo_oper));
		;
		$this->layout->view('ventas/controlservicio_index', $data);
	}

	public function exportar_excel()
	{
		$tipo_oper = 'V';

		// Recibir filtros (puedes usar get o post, ajusta según cómo envíes los datos)
		$fecha_inicio = $this->input->get_post('fecha_inicio');
		$fecha_fin = $this->input->get_post('fecha_fin');
		$searchPlaca = $this->input->get_post('searchPlaca');
		$searchSerie = $this->input->get_post('searchSerie');
		$searchNumero = $this->input->get_post('searchNumero');
		$searchMarca = $this->input->get_post('searchMarca');
		$searchOrden = $this->input->get_post('searchOrden');
		$searchRuc = $this->input->get_post('searchRuc');
		$searchRazon = $this->input->get_post('searchRazon');
		$searchProducto = $this->input->get_post('searchProducto');

		// Para exportar, no necesitas paginación ni búsqueda global
		$start = 0;
		$length = 1000000; // un número alto para traer todo
		$search = ''; // sin búsqueda global
		$order_col = 'cd.CPDEC_FechaRegistro';
		$order_dir = 'DESC';

		// Obtener datos filtrados usando el mismo modelo que datatable_control
		$listado = $this->comprobantedetalle_model->obtener_combinado(
			$tipo_oper,
			$start,
			$length,
			$search,
			$order_col,
			$order_dir,
			$fecha_inicio,
			$fecha_fin,
			$searchPlaca,
			$searchSerie,
			$searchNumero,
			$searchMarca,
			$searchOrden,
			$searchRuc,
			$searchRazon,
			$searchProducto
		);

		// Cargar librería Excel (PHPExcel o PhpSpreadsheet)
		$this->load->library('Excel');
		$sheet = $this->excel->setActiveSheetIndex(0);
		$sheet->setTitle('Reporte');

		// Ajustar anchos de columna (modifica según necesidad)
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(13);
		$sheet->getColumnDimension('D')->setWidth(18);
		$sheet->getColumnDimension('E')->setWidth(6);
		$sheet->getColumnDimension('F')->setWidth(10);
		$sheet->getColumnDimension('G')->setWidth(20);
		$sheet->getColumnDimension('H')->setWidth(36);
		$sheet->getColumnDimension('I')->setWidth(40);

		// Cabeceras
		$sheet->setCellValue('A1', 'Placa/VIN');
		$sheet->setCellValue('B1', 'Marca');
		$sheet->setCellValue('C1', 'Orden Pedido');
		$sheet->setCellValue('D1', 'Fecha Registro');
		$sheet->setCellValue('E1', 'Serie');
		$sheet->setCellValue('F1', 'Número');
		$sheet->setCellValue('G1', 'RUC');
		$sheet->setCellValue('H1', 'Razón Social');
		$sheet->setCellValue('I1', 'Producto');


		// Aplicar negrita a las cabeceras (rango A1:I1)
		$sheet->getStyle('A1:I1')->getFont()->setBold(true);

		// Datos
		$row = 2;
		foreach ($listado as $item) {
			$sheet->setCellValue('A' . $row, $item->CPDEC_placavin);
			$sheet->setCellValue('B' . $row, $item->CPDEC_marca);
			$sheet->setCellValue('C' . $row, $item->CPDEC_ordenpedido);
			$sheet->setCellValue('D' . $row, $item->CPDEC_FechaRegistro);
			$sheet->setCellValue('E' . $row, $item->CPC_Serie);
			$sheet->setCellValue('F' . $row, $item->CPC_Numero);
			$sheet->setCellValue('G' . $row, $item->numdoc);
			$sheet->setCellValue('H' . $row, $item->nombre);
			$sheet->setCellValue('I' . $row, $item->CPDEC_Descripcion);
			$row++;
		}

		// Enviar headers para descarga
		$filename = "Control_servicio_" . date('Y-m-d_H-i-s') . ".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment;filename=\"$filename\"");
		header("Cache-Control: max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}



	public function datatable_control()
	{
		$tipo_oper = 'V'; // Puedes hacer que sea parámetro si quieres

		// Parámetros enviados por DataTables
		$start = $this->input->post('start');
		$length = $this->input->post('length');
		$draw = $this->input->post('draw');
		$search = $this->input->post('search')['value'];
		$order_col_index = $this->input->post('order')[0]['column'];
		$order_dir = $this->input->post('order')[0]['dir'];

		// NUEVOS filtros enviados desde el frontend
		$fecha_inicio = $this->input->post('fecha_inicio');
		$fecha_fin = $this->input->post('fecha_fin');
		$searchPlaca = $this->input->post('searchPlaca');
		$searchSerie = $this->input->post('searchSerie');
		$searchNumero = $this->input->post('searchNumero');
		$searchMarca = $this->input->post('searchMarca');
		$searchOrden = $this->input->post('searchOrden');
		$searchRuc = $this->input->post('searchRuc');
		$searchRazon = $this->input->post('searchRazon');
		$searchProducto = $this->input->post('searchProducto');

		// Definir columnas para ordenar (deben coincidir con las columnas enviadas al DataTable)
		$columnas = [
			0 => "cd.CPDEC_placavin",
			1 => "cd.CPDEC_marca",
			2 => "cd.CPDEC_ordenpedido",
			3 => "cd.CPDEC_FechaRegistro",
			4 => "c.CPC_Serie",
			5 => "c.CPC_Numero",
			6 => "cli.CLIC_CodigoUsuario",
			7 => "numdoc",   // Alias, asegúrate que se pueda ordenar
			8 => "nombre",   // Alias, asegúrate que se pueda ordenar
			9 => "cd.CPDEC_Descripcion"
		];

		$order_col = isset($columnas[$order_col_index]) ? $columnas[$order_col_index] : "cd.CPDEC_FechaRegistro";

		// Llamar al modelo pasando los filtros nuevos
		$listado = $this->comprobantedetalle_model->obtener_combinado(
			$tipo_oper,
			$start,
			$length,
			$search,
			$order_col,
			$order_dir,
			$fecha_inicio,
			$fecha_fin,
			$searchPlaca,
			$searchSerie,
			$searchNumero,
			$searchMarca,
			$searchOrden,
			$searchRuc,
			$searchRazon,
			$searchProducto
		);

		// Obtener total y filtrados con los filtros aplicados
		$total = $this->comprobantedetalle_model->contar_todos();
		$filtered = $this->comprobantedetalle_model->contar_filtrados(
			$search,
			$tipo_oper,
			$fecha_inicio,
			$fecha_fin,
			$searchPlaca,
			$searchSerie,
			$searchNumero,
			$searchMarca,
			$searchOrden,
			$searchRuc,
			$searchRazon,
			$searchProducto
		);

		// Preparar datos para DataTables
		$data = [];
		foreach ($listado as $row) {
			$data[] = [
				$row->CPDEC_placavin,
				$row->CPDEC_marca,
				$row->CPDEC_ordenpedido,
				$row->CPDEC_FechaRegistro,
				$row->CPC_Serie,
				$row->CPC_Numero,
				$row->numdoc,
				$row->nombre,
				$row->CPDEC_Descripcion
			];
		}

		// Respuesta JSON
		$json = [
			"draw" => intval($draw),
			"recordsTotal" => intval($total),
			"recordsFiltered" => intval($filtered),
			"data" => $data
		];

		echo json_encode($json);
	}




	public function dtControlNoAsociado()
	{

		$posDT = -1;
		$columnas = array(
			++$posDT => "CONT_Fecha",
			++$posDT => "CONT_Codigo",
			++$posDT => "CONT_Placa",
			++$posDT => "CONT_KmActual",
			++$posDT => "CONT_ProxCambio",
			++$posDT => ""
		);


		$filter = new stdClass();
		$filter->start = $this->input->post("start");
		$filter->length = $this->input->post("length");
		$filter->search = $this->input->post("search")["value"];

		$ordenar = $this->input->post("order")[0]["column"];
		if ($ordenar != "") {
			$filter->order = $columnas[$ordenar];
			$filter->dir = $this->input->post("order")[0]["dir"];
		}

		$filter->placa = $this->input->post('placa');
		$filter->fecha = $this->input->post('fecha');

		$listado_control = $this->controlservicio_model->getControlNoAsociado($filter);
		$lista = array();
		if (count($listado_control) > 0) {
			foreach ($listado_control as $indice => $valor) {
				$fecha = mysql_to_human($valor->CONT_Fecha);
				$codigo = $valor->CONT_Codigo;
				$numero = $this->lib_props->getNumberFormat($codigo, 6);
				$placa = $valor->VEH_Placa;
				$km_actual = number_format($valor->CONT_KmActual, 2);
				$prox_cambio = number_format($valor->CONT_ProxCambio, 2);
				$estado = $valor->CONT_FlagEstado;

				$pdfImprimir = "<a class='btn btn-info' href='" . base_url() . "index.php/ventas/controlservicio/control_ver_pdf_conmenbrete/$codigo/1' data-fancybox data-type='iframe'><img src='" . base_url() . "images/icono_imprimir.png' width='16' height='16' border='0' title='Imprimir'></a>";
				$btn_assoc = "<button type='button' class='btn btn-success' onclick='seleccionar_ctrl_servicio($valor->CONT_Codigo, \"$numero\")'>Seleccionar</button>";

				$posDT = -1;
				$lista[] = array(
					++$posDT => $fecha,
					++$posDT => $numero,
					++$posDT => $placa,
					++$posDT => $km_actual,
					++$posDT => $prox_cambio,
					++$posDT => $pdfImprimir,
					++$posDT => $btn_assoc
				);
			}
		}

		unset($filter->start);
		unset($filter->length);

		$filterAll = new stdClass();

		$json = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => count($this->controlservicio_model->getControlNoAsociado($filter)),
			"recordsFiltered" => intval(count($this->controlservicio_model->getControlNoAsociado($filter))),
			"data" => $lista
		);

		die(json_encode($json));
	}

	public function registrar_control()
	{
		$json_result = "error";
		$json_message = "";
		$codigo_control = $this->input->post('codigo_control');

		$placa = trim($this->input->post('placa'));
		$nplaca = trim($this->input->post('placaSearch'));
		$fecha_control = $this->input->post('fecha_control');
		$km_actual = $this->input->post('km_actual');
		#nuevo desarrollo
		$marca = $this->input->post('marca');
		$modelo = $this->input->post('modelo');
		$anorigen = $this->input->post('anorigen');

		$aceite_motor = $this->input->post('aceite_motor');
		$prox_cambio = $this->input->post('prox_cambio');
		$filtro_aceite = $this->input->post('filtro_aceite');
		$filtro_aire = $this->input->post('filtro_aire');
		$filtro_aa = $this->input->post('filtro_aa');
		$filtro_petroleo = $this->input->post('filtro_petroleo');
		$filtro_sep_agua = $this->input->post('filtro_sep_agua');
		$filtro_gasolina = $this->input->post('filtro_gasolina');
		$bujia = $this->input->post('bujia');
		$det_bujia = $this->input->post('det_bujia');
		$aceite_caja = $this->input->post('aceite_caja');
		$prox_cambio_aceite_caja = $this->input->post('prox_cambio_aceite_caja');
		$aceite_corona = $this->input->post('aceite_corona');
		$prox_cambio_aceite_corona = $this->input->post('prox_cambio_aceite_corona');
		$refrigerante = $this->input->post('refrigerante');
		$prox_cambio_refrigerante = $this->input->post('prox_cambio_refrigerante');
		$porcentaje = $this->input->post('porcentaje');
		$liq_freno = $this->input->post('liq_freno');
		$observaciones = $this->input->post('observaciones');
		$engrase = $this->input->post('engrase');
		$usuario = $this->session->userdata('user');
		$prox_sugerido = $this->input->post('prox_sugerido');

		if ($placa == "" && $nplaca != "") {
			$exists = $this->empresa_model->existsPlaca($nplaca);
			if (!$exists["match"]) {
				$vehiculo = new stdClass();
				$vehiculo->CLIP_Codigo = 0;
				$vehiculo->VEH_Placa = trim(strtoupper($nplaca));
				$vehiculo->VEH_FlagEstado = "1";
				$vehiculo->VEH_FechaRegistro = date("Y-m-d H:i:s");
				$placa = $this->empresa_model->insertar_vehiculo($vehiculo);
			} else {
				$placa = $exists["result"]->VEH_Codigo;
			}
		}

		if ($placa) {
			$filter = new stdClass();
			$filter->VEH_Codigo = $placa;
			$filter->CONT_Fecha = $fecha_control;
			$filter->CONT_KmActual = $km_actual;
			$filter->CONT_Modelo = $modelo;
			$filter->CONT_Marca = $marca;
			$filter->CONT_Anorigen = $anorigen;
			$filter->CONT_AceiteMotor = $aceite_motor;
			$filter->CONT_ProxCambio = $prox_cambio;
			$filter->CONT_FiltroAceite = $filtro_aceite;
			$filter->CONT_FiltroAire = $filtro_aire;
			$filter->CONT_FiltroAA = $filtro_aa;
			$filter->CONT_FiltroPetroleo = $filtro_petroleo;
			$filter->CONT_FiltroSeparadorAgua = $filtro_sep_agua;
			$filter->CONT_FiltroGasolina = $filtro_gasolina;
			$filter->CONT_TipoBujia = $bujia;
			$filter->CONT_DescripcionBujia = $det_bujia;
			$filter->CONT_AceiteCaja = $aceite_caja;
			$filter->CONT_ProxCambioAceiteCaja = $prox_cambio_aceite_caja;
			$filter->CONT_AceiteCorona = $aceite_corona;
			$filter->CONT_ProxCambioAceiteCorona = $prox_cambio_aceite_corona;
			$filter->CONT_Refrigerante = $refrigerante;
			$filter->CONT_ProxCambioRefrigerante = $prox_cambio_refrigerante;
			$filter->CONT_Porcentaje = $porcentaje;
			$filter->CONT_LiquidoFreno = $liq_freno;
			$filter->CONT_Observaciones = $observaciones;
			$filter->CONT_Engrase = $engrase;
			$filter->CONT_prox_sugerido = $prox_sugerido;

			$control = $this->controlservicio_model->insertar_control($filter);
			if ($control) {
				$json_result = "success";
				$json_message = "¡Se ha registrado el control de servicio!";
			} else {
				$json_result = "error";
				$json_message = "El servicio no fue registrado";
			}
		} else {
			$json_result = "error";
			$json_message = "Error al crear/seleccionar la placa";
		}

		$json = array("result" => $json_result, "message" => $json_message, "codigo" => $control);
		die(json_encode($json));
	}

	public function editar_control($id = '')
	{
		$id_control = $this->input->post("id");
		$result = array();
		if ($id_control != null && count(trim($id_control)) > 0) {

			$datosControl = $this->controlservicio_model->obtener_control($id_control);

			if ($datosControl != null && count($datosControl) > 0) {
				foreach ($datosControl as $indice => $valor) {
					$codigo_control = $valor->CONT_Codigo;
					$idPlaca = $valor->VEH_Codigo;
					$placa = $valor->VEH_Placa;
					$fecha_control = $valor->CONT_Fecha;
					$modelo = $valor->CONT_Modelo;
					$marca = $valor->CONT_Marca;
					$anorigen = $valor->CONT_Anorigen;
					$km_actual = $valor->CONT_KmActual;
					$aceite_motor = $valor->CONT_AceiteMotor;
					$prox_cambio = $valor->CONT_ProxCambio;
					$filtro_aceite = $valor->CONT_FiltroAceite;
					$filtro_aire = $valor->CONT_FiltroAire;
					$filtro_aa = $valor->CONT_FiltroAA;
					$filtro_petroleo = $valor->CONT_FiltroPetroleo;
					$filtro_sep_agua = $valor->CONT_FiltroSeparadorAgua;
					$filtro_gasolina = $valor->CONT_FiltroGasolina;
					$bujia = $valor->CONT_TipoBujia;
					$det_bujia = $valor->CONT_DescripcionBujia;
					$aceite_caja = $valor->CONT_AceiteCaja;
					$prox_cambio_aceite_caja = $valor->CONT_ProxCambioAceiteCaja;
					$aceite_corona = $valor->CONT_AceiteCorona;
					$prox_cambio_aceite_corona = $valor->CONT_ProxCambioAceiteCorona;
					$refrigerante = $valor->CONT_Refrigerante;
					$prox_cambio_refrigerante = $valor->CONT_ProxCambioRefrigerante;
					$porcentaje = $valor->CONT_Porcentaje;
					$liq_freno = $valor->CONT_LiquidoFreno;
					$observaciones = $valor->CONT_Observaciones;
					$engrase = $valor->CONT_Engrase;
					$prox_sugerido = $valor->CONT_prox_sugerido;

				}
			}
		}

		$result[] = array(
			"codigo_control" => $codigo_control,
			"idPlaca" => $idPlaca,
			"placa" => $placa,
			"fecha_control" => $fecha_control,
			"marca" => $marca,
			"modelo" => $modelo,
			"anorigen" => $anorigen,
			"km_actual" => $km_actual,
			"aceite_motor" => $aceite_motor,
			"prox_cambio" => $prox_cambio,
			"filtro_aceite" => $filtro_aceite,
			"filtro_aire" => $filtro_aire,
			"filtro_aa" => $filtro_aa,
			"filtro_petroleo" => $filtro_petroleo,
			"filtro_sep_agua" => $filtro_sep_agua,
			"filtro_gasolina" => $filtro_gasolina,
			"bujia" => $bujia,
			"det_bujia" => $det_bujia,
			"aceite_caja" => $aceite_caja,
			"prox_cambio_aceite_caja" => $prox_cambio_aceite_caja,
			"aceite_corona" => $aceite_corona,
			"prox_cambio_aceite_corona" => $prox_cambio_aceite_corona,
			"refrigerante" => $refrigerante,
			"prox_cambio_refrigerante" => $prox_cambio_refrigerante,
			"porcentaje" => $porcentaje,
			"liq_freno" => $liq_freno,
			"observaciones" => $observaciones,
			"engrase" => $engrase,
			"prox_sugerido" => $prox_sugerido
		);

		echo json_encode($result);
	}

	public function eliminar_control($id = '')
	{
		$id_control = $this->input->post('term');
		$this->controlservicio_model->eliminar_control($id_control);
	}

	public function control_ver_pdf_conmenbrete($codigo, $flagPdf = 1)
	{
		switch (FORMATO_IMPRESION) {
			case 1:
				$this->control_ver_pdf_a4($codigo, $flagPdf);
				break;
		}
	}

	public function control_ver_pdf_a4($codigo, $flagPdf = 1, $enviarcorreo = false)
	{
		$this->lib_props->control_pdf($codigo, $flagPdf, $enviarcorreo);
		return NULL;
	}

	public function modificar_control()
	{
		$codigo_control = $this->input->post('codigo_control');

		$codPlaca = $this->input->post('placa');
		$placa = $this->input->post('placaSearch');
		$marca = $this->input->post('marca');
		$modelo = $this->input->post('modelo');
		$anOrigen = $this->input->post('anorigen');
		$fecha_control = $this->input->post('fecha_control');
		$km_actual = $this->input->post('km_actual');
		$aceite_motor = $this->input->post('aceite_motor');
		$prox_cambio = $this->input->post('prox_cambio');
		$filtro_aceite = $this->input->post('filtro_aceite');
		$filtro_aire = $this->input->post('filtro_aire');
		$filtro_aa = $this->input->post('filtro_aa');
		$filtro_petroleo = $this->input->post('filtro_petroleo');
		$filtro_sep_agua = $this->input->post('filtro_sep_agua');
		$filtro_gasolina = $this->input->post('filtro_gasolina');
		$bujia = $this->input->post('bujia');
		$det_bujia = $this->input->post('det_bujia');
		$aceite_caja = $this->input->post('aceite_caja');
		$prox_cambio_aceite_caja = $this->input->post('prox_cambio_aceite_caja');
		$aceite_corona = $this->input->post('aceite_corona');
		$prox_cambio_aceite_corona = $this->input->post('prox_cambio_aceite_corona');
		$refrigerante = $this->input->post('refrigerante');
		$prox_cambio_refrigerante = $this->input->post('prox_cambio_refrigerante');
		$porcentaje = $this->input->post('porcentaje');
		$liq_freno = $this->input->post('liq_freno');
		$observaciones = $this->input->post('observaciones');
		$prox_sugerido = $this->input->post('prox_sugerido');
		$engrase = $this->input->post('engrase');
		$usuario = $this->session->userdata('user');

		$filter = new stdClass();
		$filter->VEH_Codigo = $codPlaca;
		$filter->CONT_Modelo = $modelo;
		$filter->CONT_Marca = $marca;
		$filter->CONT_Anorigen = $anOrigen;
		$filter->CONT_Fecha = $fecha_control;
		$filter->CONT_KmActual = $km_actual;
		$filter->CONT_AceiteMotor = $aceite_motor;
		$filter->CONT_ProxCambio = $prox_cambio;
		$filter->CONT_FiltroAceite = $filtro_aceite;
		$filter->CONT_FiltroAire = $filtro_aire;
		$filter->CONT_FiltroAA = $filtro_aa;
		$filter->CONT_FiltroPetroleo = $filtro_petroleo;
		$filter->CONT_FiltroSeparadorAgua = $filtro_sep_agua;
		$filter->CONT_FiltroGasolina = $filtro_gasolina;
		$filter->CONT_TipoBujia = $bujia;
		$filter->CONT_DescripcionBujia = $det_bujia;
		$filter->CONT_AceiteCaja = $aceite_caja;
		$filter->CONT_ProxCambioAceiteCaja = $prox_cambio_aceite_caja;
		$filter->CONT_AceiteCorona = $aceite_corona;
		$filter->CONT_ProxCambioAceiteCorona = $prox_cambio_aceite_corona;
		$filter->CONT_Refrigerante = $refrigerante;
		$filter->CONT_ProxCambioRefrigerante = $prox_cambio_refrigerante;
		$filter->CONT_Porcentaje = $porcentaje;
		$filter->CONT_LiquidoFreno = $liq_freno;
		$filter->CONT_Observaciones = $observaciones;
		$filter->CONT_prox_sugerido = $prox_sugerido;
		$filter->CONT_Engrase = $engrase;

		$this->controlservicio_model->modificar_control($codigo_control, $placa, $filter);

		$json = array("result" => "success", "message" => "Información actualizada", "codigo" => $codigo_control);
		die(json_encode($json));
	}

	public function getInfoSendMail()
	{
		$id_control = $this->input->post("id");
		$controlInfo = $this->controlservicio_model->obtener_control($id_control);

		$data = array();

		$datosControl = $this->controlservicio_model->obtener_control($id_control);

		if ($datosControl != null && count($datosControl) > 0) {
			foreach ($datosControl as $indice => $valor) {
				$codigo_control = $valor->CONT_Codigo;
				$placa = $valor->VEH_Placa;
				$fecha_control = $valor->CONT_Fecha;
				$km_actual = $valor->CONT_KmActual;
				$aceite_motor = $valor->CONT_AceiteMotor;
				$prox_cambio = $valor->CONT_ProxCambio;
				$filtro_aceite = $valor->CONT_FiltroAceite;
				$filtro_aire = $valor->CONT_FiltroAire;
				$filtro_aa = $valor->CONT_FiltroAA;
				$filtro_petroleo = $valor->CONT_FiltroPetroleo;
				$filtro_sep_agua = $valor->CONT_FiltroSeparadorAgua;
				$filtro_gasolina = $valor->CONT_FiltroGasolina;
				$bujia = $valor->CONT_TipoBujia;
				$det_bujia = $valor->CONT_DescripcionBujia;
				$aceite_caja = $valor->CONT_AceiteCaja;
				$prox_cambio_aceite_caja = $valor->CONT_ProxCambioAceiteCaja;
				$aceite_corona = $valor->CONT_AceiteCorona;
				$prox_cambio_aceite_corona = $valor->CONT_ProxCambioAceiteCorona;
				$refrigerante = $valor->CONT_Refrigerante;
				$prox_cambio_refrigerante = $valor->CONT_ProxCambioRefrigerante;
				$porcentaje = $valor->CONT_Porcentaje;
				$liq_freno = $valor->CONT_LiquidoFreno;
				$observaciones = $valor->CONT_Observaciones;
			}
		}

		if ($val->empresa != NULL) {
			$contactos = $this->empresa_model->listar_contactosEmpresa($val->empresa);

			$lista = array();

			if ($val->email != NULL && $val->email != "")
				$lista[] = array("contacto" => $val->razon_social, "correo" => $val->email);

			if (count($contactos) > 0) {
				foreach ($contactos as $value) {
					$nombres_persona = $value->PERSC_Nombre . " " . $value->PERSC_ApellidoPaterno . " " . $value->PERSC_ApellidoMaterno;
					$emailcontacto = $value->ECONC_Email;
					$lista[] = array("contacto" => $nombres_persona, "correo" => $emailcontacto);
				}
			}
		}

		$data[] = array(
			"codigo" => $codigo_control,
			"placa" => $placa,
			"serie" => "Nro.",
			"numero" => $codigo_control,
			"fecha" => mysql_to_human($fecha_control),
			"prox_cambio" => $prox_cambio,
			"km_actual" => $km_actual,
			"empresa_envio" => $_SESSION["nombre_empresa"]
		);
		$json = (count($data) > 0) ? array("match" => true, "info" => $data) : array("match" => false, "info" => NULL);

		echo json_encode($json);
	}
	public function getInfoSendWS()
	{
		$id_control = $this->input->post("id");
		$controlInfo = $this->controlservicio_model->obtener_control($id_control);

		$data = array();

		$datosControl = $this->controlservicio_model->obtener_control($id_control);

		if ($datosControl != null && count($datosControl) > 0) {
			foreach ($datosControl as $indice => $valor) {
				$codigo_control = $valor->CONT_Codigo;
				$placa = $valor->VEH_Placa;
				$fecha_control = $valor->CONT_Fecha;
				$km_actual = $valor->CONT_KmActual;
				$aceite_motor = $valor->CONT_AceiteMotor;
				$prox_cambio = $valor->CONT_ProxCambio;
				$filtro_aceite = $valor->CONT_FiltroAceite;
				$filtro_aire = $valor->CONT_FiltroAire;
				$filtro_aa = $valor->CONT_FiltroAA;
				$filtro_petroleo = $valor->CONT_FiltroPetroleo;
				$filtro_sep_agua = $valor->CONT_FiltroSeparadorAgua;
				$filtro_gasolina = $valor->CONT_FiltroGasolina;
				$bujia = $valor->CONT_TipoBujia;
				$det_bujia = $valor->CONT_DescripcionBujia;
				$aceite_caja = $valor->CONT_AceiteCaja;
				$prox_cambio_aceite_caja = $valor->CONT_ProxCambioAceiteCaja;
				$aceite_corona = $valor->CONT_AceiteCorona;
				$prox_cambio_aceite_corona = $valor->CONT_ProxCambioAceiteCorona;
				$refrigerante = $valor->CONT_Refrigerante;
				$prox_cambio_refrigerante = $valor->CONT_ProxCambioRefrigerante;
				$porcentaje = $valor->CONT_Porcentaje;
				$liq_freno = $valor->CONT_LiquidoFreno;
				$observaciones = $valor->CONT_Observaciones;
			}
		}

		if ($val->empresa != NULL) {
			$contactos = $this->empresa_model->listar_contactosEmpresa($val->empresa);

			$lista = array();

			if ($val->email != NULL && $val->email != "")
				$lista[] = array("contacto" => $val->razon_social, "correo" => $val->email);

			if (count($contactos) > 0) {
				foreach ($contactos as $value) {
					$nombres_persona = $value->PERSC_Nombre . " " . $value->PERSC_ApellidoPaterno . " " . $value->PERSC_ApellidoMaterno;
					$emailcontacto = $value->ECONC_Email;
					$lista[] = array("contacto" => $nombres_persona, "correo" => $emailcontacto);
				}
			}
		}

		$data[] = array(
			"codigo" => $codigo_control,
			"placa" => $placa,
			"serie" => "Nro.",
			"numero" => $codigo_control,
			"fecha" => mysql_to_human($fecha_control),
			"prox_cambio" => $prox_cambio,
			"km_actual" => $km_actual,
			"empresa_envio" => $_SESSION["nombre_empresa"]
		);
		$json = (count($data) > 0) ? array("match" => true, "info" => $data) : array("match" => false, "info" => NULL);
		echo json_encode($json);
	}

	public function sendDocMail()
	{
		$titulo = $this->input->post("asunto");
		$destinatario = $this->input->post("destinatario");
		$mensaje = $this->input->post("mensaje");

		$adjunto = true;

		$tipo = 999;
		$codigo = $this->input->post("codigo");

		$send = $this->lib_props->sendDocMail($titulo, $destinatario, $mensaje, $adjunto, $tipo, $codigo);
		$json = ($send == true) ? array("result" => "success") : array("result" => "error");

		echo json_encode($json);
	}
}