<?php
class Controlservicio_model extends Model{
	public $somevar;

	public function __construct()
	{
		parent::__construct();
		$this->somevar['compania'] = $this->session->userdata('compania');
		$this->somevar['usuario'] = $this->session->userdata('user');
	}

	public function total_controles($tipo_oper = 'C')
	{
		$where = array("COMPP_Codigo" => $this->somevar['compania']);
		$query = $this->db->select('COUNT(CONT_Codigo) as total')
		->order_by('CONT_Fecha', 'desc')
		->where_not_in('CONT_FlagEstado', '0')
		->where($where)
		->get('cji_controlservicio');
		return $query->row()->total;
	}

	public function insertar_control($filter='')
	{
		$compania = $this->somevar['compania'];
		$usuario  = $this->somevar['usuario'];

		$filter->USUA_Codigo  = $usuario;
		$filter->COMPP_Codigo = $compania;
		$this->db->insert("cji_controlservicio", (array)$filter);
		$control = $this->db->insert_id();

		return $control;
	}

	public function registrar_asociado($filter)
	{
		$this->db->insert("cji_comprobanteservicio", (array)$filter);
		$control = $this->db->insert_id();
		return $control;
	}

	public function clean_asociados($comprobante){
		#$sql = "DELETE FROM cji_comprobanteservicio WHERE CPP_Codigo = '$comprobante' AND CS_FlagEstado LIKE '1'";
		$sql = "UPDATE cji_comprobanteservicio SET CS_FlagEstado = '0' WHERE CPP_Codigo = '$comprobante' AND CS_FlagEstado LIKE '1'";
		$result = $this->db->query($sql);
	}

	public function obtener_control($id_control='')
	{
		$sql = "SELECT cs.*, v.VEH_Placa
							FROM cji_controlservicio cs
							INNER JOIN cji_vehiculos v ON v.VEH_Codigo = cs.VEH_Codigo
							WHERE cs.CONT_Codigo = '" . $id_control . "'";
		$query  = $this->db->query($sql);

		if ($query->num_rows()>0) {
			return $query->result();
		}
	}

	public function eliminar_control($id_control='')
	{
		$data = array("CONT_FlagEstado" => '0');
		$where = array("CONT_Codigo" => $id_control);
		$this->db->where($where);
		$this->db->update('cji_controlservicio', $data);
	}


	public function modificar_control($id_control, $placa,$filter = null)
	{
		$where = array("CONT_Codigo" => $id_control);
		$this->db->where($where);
		$this->db->update('cji_controlservicio', (array)$filter);
		$this->db->set(['VEH_Placa' => $placa]);
		$this->db->where('VEH_Codigo', $filter->VEH_Codigo);
		$this->db->update('cji_vehiculos');
	}


	public function getControl($filter = NULL){
		$compania = $this->somevar['compania'];

		$limit = ( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";
		$order = ( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : "";

		$where = "";
		$union = '';

		if($filter->fechai != "" && $filter->fechaf == "")
			$where .= " AND ctrl.CONT_Fecha BETWEEN '$filter->fechai 00:00:00' AND '".date("Y-m-d")." 23:59:59'";
		else if ($filter->fechai != "" && $filter->fechaf != "")
			$where .= " AND ctrl.CONT_Fecha BETWEEN '$filter->fechai 00:00:00' AND '$filter->fechaf 23:59:59'";

		if ($filter->placa != '')
			$where .= ' AND v.VEH_Placa LIKE "%'.$filter->placa.'%"';

		if ($filter->serie != '')
			$where .= " AND c.CPC_Serie LIKE '%$filter->serie%'";
		
		if ($filter->numero != '')
			$where .= " AND c.CPC_Numero LIKE '%$filter->numero%'";

		if ($filter->marca != '')
			$where .= ' AND ctrl.CONT_Marca LIKE "%'.$filter->marca.'%"';
		
		if ($filter->modelo != '')
			$where .= ' AND ctrl.CONT_Modelo LIKE "%'.$filter->modelo.'%"';

		$sql = "SELECT ctrl.*, v.VEH_Placa, c.CPP_Codigo, c.CPC_Serie, c.CPC_Numero, c.CPC_FlagEstado as flagComprobante FROM cji_controlservicio ctrl
							INNER JOIN cji_vehiculos v ON v.VEH_Codigo = ctrl.VEH_Codigo
							LEFT JOIN cji_comprobanteservicio cs ON cs.CONT_Codigo = ctrl.CONT_Codigo AND cs.CS_FlagEstado LIKE '1'
							LEFT JOIN cji_comprobante c ON c.CPP_Codigo = cs.CPP_Codigo
							WHERE ctrl.COMPP_Codigo = '$compania'
							$where
							GROUP BY ctrl.CONT_Codigo
							$order $limit";
		$query = $this->db->query($sql);

		if ($query->num_rows > 0)
			return $query->result();
		else
			return NULL;
	}

	public function getControlNoAsociado($filter = NULL){
		$compania = $this->somevar['compania'];

		$limit = ( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";
		$order = ( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : "";

		if($filter->fecha != "")
			$where .= " AND ctrl.CONT_Fecha BETWEEN '$filter->fecha 00:00:00' AND '$filter->fecha 23:59:59'";

		if ($filter->placa != '')
			$where .= ' AND v.VEH_Placa LIKE "%'.$filter->placa.'%"';

		$sql = "SELECT *
			FROM cji_controlservicio ctrl
			LEFT JOIN cji_vehiculos v ON v.VEH_Codigo = ctrl.VEH_Codigo
			WHERE ctrl.COMPP_Codigo = '$compania'
				AND ctrl.CONT_FlagEstado LIKE '1'
				AND NOT EXISTS(SELECT CONT_Codigo FROM cji_comprobanteservicio cs WHERE cs.CONT_Codigo = ctrl.CONT_Codigo AND cs.CS_FlagEstado LIKE '1')
				$where $order $limit";
		$query = $this->db->query($sql);

		if ($query->num_rows > 0) {
			return $query->result();
		}
		else
			return NULL;
	}

	public function getServAssoc($comprobante){
		$sql = "SELECT c.*, v.VEH_Placa FROM cji_comprobanteservicio cs
							INNER JOIN cji_controlservicio c ON c.CONT_Codigo = cs.CONT_Codigo
							INNER JOIN cji_vehiculos v ON v.VEH_Codigo = c.VEH_Codigo
							WHERE CPP_Codigo = '$comprobante' AND CS_FlagEstado LIKE '1'";
		$query = $this->db->query($sql);

		if ($query->num_rows > 0)
			return $query->result();
		else
			return NULL;
	}

	public function getOcompraMail($ocompra){
		$sql = "SELECT o.*, m.MONED_Simbolo,
									(SELECT e.EMPRP_Codigo 
									FROM cji_cliente c
									LEFT JOIN cji_persona p ON p.PERSP_Codigo = c.PERSP_Codigo
									LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = c.EMPRP_Codigo
									WHERE c.CLIP_Codigo = o.CLIP_Codigo
									) as empresa,

									(SELECT e.EMPRC_Email 
									FROM cji_cliente c
									LEFT JOIN cji_persona p ON p.PERSP_Codigo = c.PERSP_Codigo
									LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = c.EMPRP_Codigo
									WHERE c.CLIP_Codigo = o.CLIP_Codigo
									) as email,

									(SELECT CONCAT_WS(' ', p.PERSC_Nombre, p.PERSC_ApellidoPaterno, e.EMPRC_RazonSocial)
										FROM cji_cliente c
										LEFT JOIN cji_persona p ON p.PERSP_Codigo = c.PERSP_Codigo
										LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = c.EMPRP_Codigo
										WHERE c.CLIP_Codigo = o.CLIP_Codigo
									) as razon_social,

									(SELECT CONCAT_WS(' ', p.PERSC_NumeroDocIdentidad, e.EMPRC_Ruc)
									FROM cji_cliente c
									LEFT JOIN cji_persona p ON p.PERSP_Codigo = c.PERSP_Codigo
									LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = c.EMPRP_Codigo
									WHERE c.CLIP_Codigo = o.CLIP_Codigo
									) as ruc

								FROM cji_ordencompra o
								LEFT JOIN cji_moneda m ON m.MONED_Codigo = o.MONED_Codigo
								WHERE o.OCOMP_Codigo = $ocompra
								";

		$query = $this->db->query($sql);
		if ($query->num_rows > 0) {
			foreach ($query->result() as $fila) {
				$data[] = $fila;
			}
			return $data;
		}
		return array();
	}

}