<?php
class Comprobantedetalle_model extends Model
{
    var $somevar;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
        $this->somevar['empresa'] = $this->session->userdata('empresa');
        $this->somevar['compania'] = $this->session->userdata('compania');
        $this->somevar['user'] = $this->session->userdata('user');
        $this->somevar['hoy'] = mdate("%Y-%m-%d %h:%i:%s", time());
    }

    public function listar($comprobante)
    {
        $where = array("CPP_Codigo" => $comprobante, "CPDEC_FlagEstado" => "1");
        $query = $this->db->order_by('CPDEP_Codigo')->where($where)->get('cji_comprobantedetalle');
        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }
            return $data;
        }
    }

    public function obtener($id)
    {
        $sql = "SELECT cd.*
                    FROM cji_comprobantedetalle cd
                        WHERE cd.CPDEP_Codigo = $id
                ";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }
            return $data;
        } else
            return NULL;
    }

    public function obtener_combinado(
        $tipo_oper = 'V',
        $start = 0,
        $length = 10,
        $search = '',
        $order_col = 'cd.CPDEC_FechaRegistro',
        $order_dir = 'DESC',
        $fecha_inicio = null,
        $fecha_fin = null,
        $searchPlaca = null,
        $searchSerie = null,
        $searchNumero = null,
        $searchMarca = null,
        $searchOrden = null,
        $searchRuc = null,
        $searchRazon = null,
        $searchProducto = null
    ) {
        // Limpiar espacios en blanco de los filtros
        $search = !empty($search) ? trim($search) : $search;
        $searchPlaca = !empty($searchPlaca) ? trim($searchPlaca) : $searchPlaca;
        $searchSerie = !empty($searchSerie) ? trim($searchSerie) : $searchSerie;
        $searchMarca = !empty($searchMarca) ? trim($searchMarca) : $searchMarca;
        $searchOrden = !empty($searchOrden) ? trim($searchOrden) : $searchOrden;
        $searchRuc = !empty($searchRuc) ? trim($searchRuc) : $searchRuc;
        $searchRazon = !empty($searchRazon) ? trim($searchRazon) : $searchRazon;
        $searchProducto = !empty($searchProducto) ? trim($searchProducto) : $searchProducto;

        $numdoc_case = "CASE " . ($tipo_oper != 'C' ? "cli.CLIC_TipoPersona" : "cli.PROVC_TipoPersona") . "
                    WHEN '1' THEN e.EMPRC_Ruc
                    ELSE pe.PERSC_NumeroDocIdentidad END AS numdoc";

        $nombre_case = "CASE " . ($tipo_oper != 'C' ? "cli.CLIC_TipoPersona" : "cli.PROVC_TipoPersona") . "
                    WHEN '1' THEN e.EMPRC_RazonSocial
                    ELSE CONCAT(pe.PERSC_Nombre, ' ', pe.PERSC_ApellidoPaterno, ' ', pe.PERSC_ApellidoMaterno) END AS nombre";

        // Selección con COALESCE y NULLIF para mostrar 'No asignado' si está vacío o NULL
        $this->db->select("
        COALESCE(NULLIF(cd.CPDEC_placavin, ''), 'No asignado') AS CPDEC_placavin,
        COALESCE(NULLIF(cd.CPDEC_marca, ''), 'No asignado') AS CPDEC_marca,
        COALESCE(NULLIF(cd.CPDEC_ordenpedido, ''), 'No asignado') AS CPDEC_ordenpedido,
        cd.CPDEC_FechaRegistro,
        c.CPC_Serie,
        c.CPC_Numero,
        cli.CLIC_CodigoUsuario
    ", FALSE);

        $this->db->select($numdoc_case, FALSE);
        $this->db->select($nombre_case, FALSE);
        $this->db->select('cd.CPDEC_Descripcion', FALSE);

        $this->db->from('cji_comprobantedetalle cd');
        $this->db->join('cji_comprobante c', 'cd.CPP_Codigo = c.CPP_Codigo');
        $this->db->join('cji_cliente cli', 'cli.CLIP_Codigo = c.CLIP_Codigo');
        $this->db->join('cji_empresa e', 'e.EMPRP_Codigo = cli.EMPRP_Codigo', 'left');
        $this->db->join('cji_persona pe', 'pe.PERSP_Codigo = cli.PERSP_Codigo', 'left');

        // Filtros por rango de fechas
        if (!empty($fecha_inicio)) {
            $this->db->where('cd.CPDEC_FechaRegistro >=', $fecha_inicio);
        }
        if (!empty($fecha_fin)) {
            $this->db->where('cd.CPDEC_FechaRegistro <=', $fecha_fin);
        }

        // Filtros específicos por campos
        if (!empty($searchPlaca)) {
            $this->db->like('cd.CPDEC_placavin', $searchPlaca);
        }
        if (!empty($searchSerie)) {
            $this->db->like('c.CPC_Serie', $searchSerie);
        }
        if (!empty($searchNumero)) {
            $this->db->where('c.CPC_Numero', $searchNumero);
        }
        if (!empty($searchMarca)) {
            $this->db->like('cd.CPDEC_marca', $searchMarca);
        }
        if (!empty($searchOrden)) {
            $this->db->like('cd.CPDEC_ordenpedido', $searchOrden);
        }
        if (!empty($searchRuc)) {
            $this->db->like('e.EMPRC_Ruc', $searchRuc);
        }
        if (!empty($searchRazon)) {
            $this->db->like('e.EMPRC_RazonSocial', $searchRazon);
        }
        if (!empty($searchProducto)) {
            $this->db->like('cd.CPDEC_Descripcion', $searchProducto);
        }

        // Búsqueda general (DataTables search)
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('cd.CPDEC_placavin', $search);
            $this->db->or_like('cd.CPDEC_marca', $search);
            $this->db->or_like('cd.CPDEC_ordenpedido', $search);
            $this->db->or_like('cd.CPDEC_Descripcion', $search);
            $this->db->or_like('c.CPC_Serie', $search);
            $this->db->or_like('c.CPC_Numero', $search);
            $this->db->or_like('cli.CLIC_CodigoUsuario', $search);
            $this->db->or_like('e.EMPRC_RazonSocial', $search);
            $this->db->or_like('pe.PERSC_Nombre', $search);
            $this->db->group_end();
        }

        $this->db->order_by($order_col, $order_dir);
        $this->db->limit($length, $start);

        $query = $this->db->get();

        return $query->result();
    }




    public function contar_todos()
    {
        return $this->db->count_all('cji_comprobantedetalle');
    }

    public function contar_filtrados(
        $search = '',
        $tipo_oper = 'V',
        $fecha_inicio = null,
        $fecha_fin = null,
        $searchPlaca = null,
        $searchSerie = null,
        $searchNumero = null,
        $searchMarca = null,
        $searchOrden = null,
        $searchRuc = null,
        $searchRazon = null,
        $searchProducto = null
    ) {
        $this->db->from('cji_comprobantedetalle cd');
        $this->db->join('cji_comprobante c', 'cd.CPP_Codigo = c.CPP_Codigo');
        $this->db->join('cji_cliente cli', 'cli.CLIP_Codigo = c.CLIP_Codigo');
        $this->db->join('cji_empresa e', 'e.EMPRP_Codigo = cli.EMPRP_Codigo', 'left');
        $this->db->join('cji_persona pe', 'pe.PERSP_Codigo = cli.PERSP_Codigo', 'left');

        // Filtros por rango de fechas
        if (!empty($fecha_inicio)) {
            $this->db->where('cd.CPDEC_FechaRegistro >=', $fecha_inicio);
        }
        if (!empty($fecha_fin)) {
            $this->db->where('cd.CPDEC_FechaRegistro <=', $fecha_fin);
        }

        // Filtros específicos por campos
        if (!empty($searchPlaca)) {
            $this->db->like('cd.CPDEC_placavin', $searchPlaca);
        }
        if (!empty($searchSerie)) {
            $this->db->like('c.CPC_Serie', $searchSerie);
        }
        if (!empty($searchNumero)) {
            $this->db->where('c.CPC_Numero', $searchNumero);
        }
        if (!empty($searchMarca)) {
            $this->db->like('cd.CPDEC_marca', $searchMarca);
        }
        if (!empty($searchOrden)) {
            $this->db->like('cd.CPDEC_ordenpedido', $searchOrden);
        }
        if (!empty($searchRuc)) {
            $this->db->like('e.EMPRC_Ruc', $searchRuc);
        }
        if (!empty($searchRazon)) {
            $this->db->like('e.EMPRC_RazonSocial', $searchRazon);
        }
        if (!empty($searchProducto)) {
            $this->db->like('cd.CPDEC_Descripcion', $searchProducto);
        }

        // Búsqueda general (DataTables search)
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('cd.CPDEC_placavin', $search);
            $this->db->or_like('cd.CPDEC_marca', $search);
            $this->db->or_like('cd.CPDEC_ordenpedido', $search);
            $this->db->or_like('cd.CPDEC_Descripcion', $search);
            $this->db->or_like('c.CPC_Serie', $search);
            $this->db->or_like('c.CPC_Numero', $search);
            $this->db->or_like('cli.CLIC_CodigoUsuario', $search);
            $this->db->or_like('e.EMPRC_RazonSocial', $search);
            $this->db->or_like('pe.PERSC_Nombre', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }


    public function detalles($comprobante)
    {
        $sql = "SELECT cd.*, pr.PROD_CodigoInterno, pr.PROD_CodigoUsuario, pr.PROD_CodigoOriginal, pr.PROD_FlagBienServicio, pr.PROD_Nombre, um.UNDMED_Simbolo, um.UNDMED_Descripcion, m.MARCC_CodigoUsuario, m.MARCC_Descripcion,
                    l.LOTC_Numero, l.LOTC_FechaVencimiento

                    FROM cji_comprobantedetalle cd
                    INNER JOIN cji_comprobante c ON c.CPP_Codigo = cd.CPP_Codigo
                    INNER JOIN cji_producto pr ON cd.PROD_Codigo = pr.PROD_Codigo
                    LEFT JOIN cji_marca m ON m.MARCP_Codigo = pr.MARCP_Codigo
                    LEFT JOIN cji_unidadmedida um ON um.UNDMED_Codigo = cd.UNDMED_Codigo
                    LEFT JOIN cji_lote l ON l.LOTP_Codigo = cd.LOTP_Codigo
                        WHERE cd.CPP_Codigo = $comprobante AND cd.CPDEC_FlagEstado = 1
                ";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }
            return $data;
        } else
            return NULL;
    }

    public function insertar($filter = null)
    {
        $this->db->insert('cji_comprobantedetalle', (array) $filter);
    }
    public function modificar($comprobante_detalle, $filter = null)
    {
        $where = array("CPDEP_Codigo" => $comprobante_detalle);
        $this->db->where($where);
        $this->db->update('cji_comprobantedetalle', (array) $filter);
    }
    public function eliminar($comprobante_detalle)
    {
        $data = array("CPDEC_FlagEstado" => '0');
        $where = array("CPDEP_Codigo" => $comprobante_detalle);
        $this->db->where($where);
        $this->db->update('cji_comprobantedetalle', $data);
    }

    #GANANCIA
    public function reporte_ganancia($filter = null)
    {

        $limit = (isset($filter->start) && isset($filter->length)) ? " LIMIT $filter->start, $filter->length " : "";
        $order = "";#"ORDER BY c.CPC_Fecha desc";#( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : "";
        $where = "";
        $empresa = $this->somevar['empresa'];
        if ($filter->producto != '')
            $where .= " AND cd.PROD_Codigo = $filter->producto ";
        if ($filter->moneda != '')
            $where .= " AND c.MONED_Codigo = $filter->moneda ";

        $whereCompanias = "";

        if (trim($filter->compania) == 'compania_actual') {
            $whereCompanias = $this->somevar['compania'];
            $where .= " AND c.COMPP_Codigo = $whereCompanias";
        }

        $sql = "SELECT cd.*, m.MONED_Simbolo, c.CPC_Fecha,c.CPC_Numero, c.COMPP_Codigo, ee.EESTABC_Descripcion, p.PROD_Nombre,p.PROD_UltimoCosto, c.MONED_Codigo
                    FROM cji_comprobantedetalle cd
                    LEFT JOIN cji_comprobante c ON c.CPP_Codigo = cd.CPP_Codigo
                    
                    LEFT JOIN cji_producto p ON p.PROD_Codigo = cd.PROD_Codigo
                    LEFT JOIN cji_moneda m ON m.MONED_Codigo = c.MONED_Codigo
                    LEFT JOIN  cji_compania co ON co.COMPP_Codigo = c.COMPP_Codigo
                    LEFT JOIN cji_emprestablecimiento ee ON ee.EESTABP_Codigo = co.EESTABP_Codigo
                        WHERE co.EMPRP_Codigo = $empresa AND c.CPC_TipoOperacion = 'V' AND c.CPC_FlagEstado = 1 AND cd.CPDEC_FlagEstado = 1 AND c.CPC_Fecha BETWEEN '$filter->fechai 00:00:00' AND '$filter->fechaf 23:59:59' $where ORDER BY c.CPC_Numero desc $limit 
                ";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0)
            return $query->result();
        else
            return array();

    }

    public function reporte_ganancia_general($filter)
    {

        $limit = "";#( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";
        $order = "";#"ORDER BY c.CPC_Fecha desc";#( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : "";
        $where = "";

        if ($filter->producto != '')
            $where .= " AND cd.PROD_Codigo = $filter->producto ";
        if ($filter->moneda != '')
            $where .= " AND c.MONED_Codigo = $filter->moneda ";

        $whereCompanias = "";

        if ($filter->companias > 0 && $filter->todos == "") {
            if (is_array($filter->companias)) {
                $size = count($filter->companias);
                for ($i = 0; $i < $size; $i++) {
                    $whereCompanias .= ($whereCompanias != "") ? "," . $filter->companias[$i] : $filter->companias[$i];
                }
            } else {
                $whereCompanias = $filter->companias;
            }

            $where .= " AND c.COMPP_Codigo IN($whereCompanias)";
        }

        $sql = "SELECT cd.*, m.MONED_Simbolo, c.CPC_Fecha,c.CPC_Numero, c.COMPP_Codigo, ee.EESTABC_Descripcion, p.PROD_Nombre, p.PROD_UltimoCosto, apl.ALMALOTC_Costo, l.LOTC_Numero, l.LOTC_FechaVencimiento, c.MONED_Codigo, fp.FORPAC_Descripcion,c.FORPAP_Codigo
                    FROM cji_comprobantedetalle cd
                    INNER JOIN cji_comprobante c ON c.CPP_Codigo = cd.CPP_Codigo
                    LEFT JOIN cji_almaprolote apl ON apl.LOTP_Codigo = cd.LOTP_Codigo
                    LEFT JOIN cji_lote l ON l.LOTP_Codigo = cd.LOTP_Codigo
                    LEFT JOIN cji_producto p ON p.PROD_Codigo = cd.PROD_Codigo
                    LEFT JOIN cji_moneda m ON m.MONED_Codigo = c.MONED_Codigo
                    LEFT JOIN  cji_compania co ON co.COMPP_Codigo = c.COMPP_Codigo
                    LEFT JOIN cji_emprestablecimiento ee ON ee.EESTABP_Codigo = co.EESTABP_Codigo
                    LEFT JOIN cji_formapago fp ON fp.FORPAP_Codigo = c.FORPAP_Codigo
                        WHERE c.CPC_TipoOperacion = 'V' AND c.CPC_FlagEstado = 1 AND cd.CPDEC_FlagEstado = 1 AND c.CPC_Fecha BETWEEN '$filter->fechai 00:00:00' AND '$filter->fechaf 23:59:59' $where ORDER BY c.CPC_Numero desc 
                ";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0)
            return $query->result();
        else
            return array();

    }

    public function ganancia_por_compania($filter, $compania)
    {

        $limit = "";#( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";
        $order = "";#"ORDER BY c.CPC_Fecha desc";#( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : "";
        $where = "";

        if ($filter->producto != '')
            $where .= " AND cd.PROD_Codigo = $filter->producto ";
        if ($filter->moneda != '')
            $where .= " AND c.MONED_Codigo = $filter->moneda ";

        $whereCompanias = "";


        $where .= " AND c.COMPP_Codigo = $compania";
        $sql = "SELECT SUM(cd.CPDEC_Total) as total_ventas, SUM(p.PROD_UltimoCosto*cd.CPDEC_Cantidad) as costo_total 
                FROM cji_comprobantedetalle cd 
                LEFT JOIN cji_comprobante c ON c.CPP_Codigo = cd.CPP_Codigo 
                LEFT JOIN cji_producto p ON p.PROD_Codigo = cd.PROD_Codigo 
                LEFT JOIN cji_moneda m ON m.MONED_Codigo = c.MONED_Codigo 
                LEFT JOIN cji_compania co ON co.COMPP_Codigo = c.COMPP_Codigo 
                LEFT JOIN cji_emprestablecimiento ee ON ee.EESTABP_Codigo = co.EESTABP_Codigo 
                WHERE c.CPC_TipoOperacion = 'V' AND c.CPC_FlagEstado = 1 AND cd.CPDEC_FlagEstado = 1 AND c.CPC_Fecha 
                BETWEEN '$filter->fechai 00:00:00' AND '$filter->fechaf 23:59:59' 
                $where 


                ";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0)
            return $query->result();
        else
            return array();

    }

    public function reporte_ganancia_old($producto, $f_ini, $f_fin, $companias = '', $moneda = '')
    {

        $where = "";
        $empresa = $this->somevar['empresa'];

        if ($producto != '') {
            $where .= " AND cd.PROD_Codigo = $producto ";
        }

        if (trim($companias) == 'compania_actual') {
            $whereCompanias = $this->somevar['compania'];
            $where .= " AND c.COMPP_Codigo IN($whereCompanias)";
        }
        if (trim($moneda) != '') {

            $where .= " AND c.MONED_Codigo = $moneda ";
        }



        $sql = "SELECT cd.*, m.MONED_Simbolo, c.CPC_Fecha, c.COMPP_Codigo, ee.EESTABC_Descripcion, p.PROD_Nombre, p.PROD_UltimoCosto, apl.ALMALOTC_Costo, l.LOTC_Numero, l.LOTC_FechaVencimiento
                    FROM cji_comprobantedetalle cd
                    INNER JOIN cji_comprobante c ON c.CPP_Codigo = cd.CPP_Codigo
                    LEFT JOIN cji_almaprolote apl ON apl.LOTP_Codigo = cd.LOTP_Codigo
                    LEFT JOIN cji_lote l ON l.LOTP_Codigo = cd.LOTP_Codigo
                    LEFT JOIN cji_producto p ON p.PROD_Codigo = cd.PROD_Codigo
                    LEFT JOIN cji_moneda m ON m.MONED_Codigo = c.MONED_Codigo
                    LEFT JOIN  cji_compania co ON co.COMPP_Codigo = c.COMPP_Codigo
                    LEFT JOIN cji_emprestablecimiento ee ON ee.EESTABP_Codigo = co.EESTABP_Codigo
                        WHERE co.EMPRP_Codigo = $empresa AND c.CPC_TipoOperacion = 'V' AND c.CPC_FlagEstado = 1 AND cd.CPDEC_FlagEstado = 1 AND c.CPC_Fecha BETWEEN '$f_ini 00:00:00' AND '$f_fin 23:59:59' $where
                ";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0)
            return $query->result();
        else
            return array();

    }
    #FIN GANANCIA  
    public function promedio_ventas_articulos($producto, $f_ini, $f_fin)
    {
        $where = "";

        if ($producto != '')
            $where .= " AND cd.PROD_Codigo = $producto ";

        $compania = $this->somevar['compania'];
        $sql = "SELECT cd.*, m.MONED_Simbolo, c.CPC_Fecha, c.COMPP_Codigo, p.PROD_Nombre, mr.MARCC_Descripcion, mr.MARCC_CodigoUsuario,
                    (SELECT MIN(ccd.CPDEC_Pu_ConIgv) FROM cji_comprobantedetalle ccd INNER JOIN cji_comprobante cc ON cc.CPP_Codigo = ccd.CPP_Codigo WHERE ccd.CPDEC_FlagEstado = 1 AND cc.CPC_FlagEstado = 1 AND cc.CPC_TipoOperacion = 'V' AND cc.CPC_TipoDocumento != 'N' AND cc.COMPP_Codigo = c.COMPP_Codigo AND ccd.PROD_Codigo = cd.PROD_Codigo ) as pventa_minimo,
                    (SELECT MAX(ccd.CPDEC_Pu_ConIgv) FROM cji_comprobantedetalle ccd INNER JOIN cji_comprobante cc ON cc.CPP_Codigo = ccd.CPP_Codigo WHERE ccd.CPDEC_FlagEstado = 1 AND cc.CPC_FlagEstado = 1 AND cc.CPC_TipoOperacion = 'V' AND cc.CPC_TipoDocumento != 'N' AND cc.COMPP_Codigo = c.COMPP_Codigo AND ccd.PROD_Codigo = cd.PROD_Codigo ) as pventa_maximo,
                    (SELECT SUM(ccd.CPDEC_Pu_ConIgv) FROM cji_comprobantedetalle ccd INNER JOIN cji_comprobante cc ON cc.CPP_Codigo = ccd.CPP_Codigo WHERE ccd.CPDEC_FlagEstado = 1 AND cc.CPC_FlagEstado = 1 AND cc.CPC_TipoOperacion = 'V' AND cc.CPC_TipoDocumento != 'N' AND cc.COMPP_Codigo = c.COMPP_Codigo AND ccd.PROD_Codigo = cd.PROD_Codigo ) as total,
                    (SELECT COUNT(ccd.CPDEC_Pu_ConIgv) FROM cji_comprobantedetalle ccd INNER JOIN cji_comprobante cc ON cc.CPP_Codigo = ccd.CPP_Codigo WHERE ccd.CPDEC_FlagEstado = 1 AND cc.CPC_FlagEstado = 1 AND cc.CPC_TipoOperacion = 'V' AND cc.CPC_TipoDocumento != 'N' AND cc.COMPP_Codigo = c.COMPP_Codigo AND ccd.PROD_Codigo = cd.PROD_Codigo ) as cantidad_operaciones
                    FROM cji_comprobantedetalle cd
                    INNER JOIN cji_comprobante c ON c.CPP_Codigo = cd.CPP_Codigo
                    LEFT JOIN cji_producto p ON p.PROD_Codigo = cd.PROD_Codigo
                    LEFT JOIN cji_marca mr ON mr.MARCP_Codigo = p.MARCP_Codigo
                    LEFT JOIN cji_moneda m ON m.MONED_Codigo = c.MONED_Codigo
                        WHERE c.CPC_TipoOperacion = 'V' AND c.CPC_TipoDocumento != 'N' AND c.CPC_FlagEstado = 1 AND cd.CPDEC_FlagEstado = 1 AND c.CPC_Fecha BETWEEN '$f_ini 00:00:00' AND '$f_fin 23:59:59' AND c.COMPP_Codigo = $compania $where
                        GROUP BY cd.PROD_Codigo
                ";

        $query = $this->db->query($sql);

        if ($query->num_rows > 0)
            return $query->result();
        else
            return array();

    }

    public function listar_estado_by_id_orden_tipo($id_orden, $tipo)
    {
        $query = $this->db->from("cji_comprobantedetalle");

        $query->select_sum("cji_comprobantedetalle.CPDEC_Subtotal - cji_comprobantedetalle.CPDEC_Descuento", "CPDEC_Subtotal_calculado");

        $query->join("cji_comprobante", "cji_comprobante.CPP_Codigo = cji_comprobantedetalle.CPP_Codigo");
        $query->join("cji_ocompradetalle", "cji_ocompradetalle.OCOMDEP_Codigo = cji_comprobantedetalle.OCOMP_Codigo_VC");
        $query->join("cji_moneda", "cji_moneda.MONED_Codigo = cji_comprobante.MONED_Codigo");

        if ($tipo == 'ov') {
            $query->join("cji_cliente", "cji_cliente.CLIP_Codigo = cji_comprobante.CLIP_Codigo");
        } else {
            $query->join("cji_proveedor", "cji_proveedor.PROVP_Codigo = cji_comprobante.PROVP_Codigo");
        }

        $query->join("cji_persona", "cji_persona.PERSP_Codigo = " . ($tipo == 'ov' ? 'cji_cliente' : 'cji_proveedor') . ".PERSP_Codigo", "LEFT");
        $query->join("cji_empresa", "cji_empresa.EMPRP_Codigo = " . ($tipo == 'ov' ? 'cji_cliente' : 'cji_proveedor') . ".EMPRP_Codigo", "LEFT");

        $query->where("cji_comprobantedetalle.CPDEC_FlagEstado", 1);
        $query->where("cji_comprobante.CPC_FlagEstado", 1);

        $query->where("cji_ocompradetalle.OCOMP_Codigo", $id_orden);

        $query->group_by("cji_comprobantedetalle.CPP_Codigo");

        return $query->select(implode(",", array("cji_comprobantedetalle.*", "cji_comprobante.*", "cji_" . ($tipo == 'ov' ? "cliente" : "proveedor") . ".*", "cji_persona.*", "cji_empresa.*", "cji_moneda.*")))->get()->result();
    }

    public function listar_ventas_by_id_orden_producto($id_orden, $id_producto)
    {
        $query = $this->db->from("cji_comprobantedetalle");

        $query->join("cji_comprobante", "cji_comprobante.CPP_Codigo = cji_comprobantedetalle.CPP_Codigo");
        $query->join("cji_ocompradetalle", "cji_ocompradetalle.OCOMDEP_Codigo = cji_comprobantedetalle.OCOMP_Codigo_VC");
        $query->join("cji_moneda", "cji_moneda.MONED_Codigo = cji_comprobante.MONED_Codigo");

        $query->where("cji_comprobantedetalle.CPDEC_FlagEstado", 1);
        $query->where("cji_comprobante.CPC_FlagEstado", 1);
        $query->where("cji_ocompradetalle.OCOMDEC_FlagEstado", 1);

        $query->where("cji_comprobantedetalle.PROD_Codigo", $id_producto);
        $query->where("cji_ocompradetalle.OCOMP_Codigo", $id_orden);

        return $query->select(array("cji_comprobantedetalle.*", "cji_comprobante.*", "cji_moneda.*"))->get()->result();
    }

}
?>