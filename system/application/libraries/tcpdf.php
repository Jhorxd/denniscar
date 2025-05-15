<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once APPPATH."/third_party/TCPDF/tcpdf.php";
 
class pdf extends TCPDF {
    public function __construct(){
        parent::__construct(); 
    }

    public function Header( $flagPdf = 1 ){
        $this->Image("images/cabeceras/au.jpg", 10, 15, 40, 30, '', '', '', true, 300, '', false, false, 0);
    }
}

class pdfGeneral extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $RazonSocial;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        if ($flagPdf == 1){
            $this->SetAutoPageBreak(false, 0);
            //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 40);
        }
        
        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;

    }

    public function printHeaderData(){
        
        $posY = 10;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7
        $datos_cabecera="";
        $logo_yape = base_url() . 'images/yape.png';
        $logo_plin = base_url() . 'images/plin.png';
        if ($this->empresa==1) {
            $datosempre='';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>BCP 194 382515 50028 CCI 002 194 1382 5155 002899 <br>SCOTIABANK 749 0060 485 CCI 009 749 2074 90060 48534 <br>Cel. 998265946 / 981308570 / 934475536 Correo: dakarwashperu@gmail.com<br>VENTA DE ACEITES, GRASAS Y ACCESORIOS AL POR MAYOR Y MENOR</td>
            <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>
            ';
        }else if($this->empresa==873){
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">DE: '.$this->RazonSocial.'</td><td></td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>BCP 194 157494 16099 CCI 002 194 11574941 6099 94 <br>SCOTIABANK 749 00911 00 CCI 74920 47 9009 1100 32<br>ventas.motosur2022@gmail.com Cel: 951471137 / 981308570</td>
                             <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>';
        }else{
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">'.$this->RazonSocial.'</td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel: 998 266 946 / 981 308 570 / 916439633</td>';
        }
        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 7, 123, 26, '', '', '', false, 300);

        $this->SetY(33);

        $comprobanteHTML = '<table style="width:5cm; font-size:8pt;" border="0">
        ' . $datosempre. '
        <tr>
        ' . $datos_cabecera. '
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }
}

class pdfCotizacion extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('maestros/persona_model');
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        $this->SetAutoPageBreak(false, 0);
        //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
        $this->SetAutoPageBreak(true, 5);

        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 10;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';
         
        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7
        $datos_cabecera="";
        $logo_yape = base_url() . 'images/yape.png';
        $logo_plin = base_url() . 'images/plin.png';
        if ($this->empresa==1) {
            $datosempre='';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel. : 940 059 637 </td>
            <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>
            ';
        }else if($this->empresa==873){
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">DE: '.$this->RazonSocial.'</td><td></td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>BCP 194 157494 16099 CCI 002 194 11574941 6099 94 <br>SCOTIABANK 749 00911 00 CCI 74920 47 9009 1100 32<br>ventas.motosur2022@gmail.com Cel: 951471137 / 981308570</td>
                             <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>';
        }else{
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">'.$this->RazonSocial.'</td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel: 998 266 946 / 981 308 570 / 916439633</td>';
        }
        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 7, 123, 26, '', '', '', false, 300);

        $this->SetY(33);

        $comprobanteHTML = '<table style="width:5cm; font-size:8pt;" border="0">
        ' . $datosempre. '
        <tr>
        ' . $datos_cabecera. '
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }

    public function Footer( $personal = NULL ){
                $this->SetFont('freesans', '', 7);
        $this->SetY(-9);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr>
                    <td style="text-align:center;">Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'</td>
                </tr>
            </table>';

        $this->writeHTML($pieHTML,false,'');
        $this->SetY(55);

        /* $personal = $this->ci->persona_model->obtener_datosPersona($personal);

        $this->SetFont('freesans', '', 8);
        $this->SetY(-30);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr bgcolor="#F6F6F6">
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Elaboró</td>
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Teléfonos</td>
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Móvil</td>
                    <td style="font-weight:bold; text-align:center;">Email</td>
                </tr>
                <tr>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Nombre.' '.$personal[0]->PERSC_ApellidoPaterno.' '.$personal[0]->PERSC_ApellidoMaterno.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Telefono.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Movil.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Email.'</td>
                </tr>
            </table>';

        $this->writeHTML($pieHTML,false,'');
        */
        $this->SetY(55);
    }
}

class pdfComprobante extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        if ($flagPdf == 1){
            $this->SetAutoPageBreak(false, 0);
            //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 40);
        }
        
        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 10;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7
        $datos_cabecera="";
        $logo_yape = base_url() . 'images/yape.png';
        $logo_plin = base_url() . 'images/plin.png';
        $this->Image($logo_yape, 120, 17, 10, 12, '', '', '', false, 300);
        $this->Image($logo_plin, 120, 37, 10, 10, '', '', '', false, 300);

        if ($this->empresa==1) {
            $datosempre='';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel. : 940 059 637 </td>';
        }else if($this->empresa==873){
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">DE: '.$this->RazonSocial.'</td><td></td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>BCP 194 157494 16099 CCI 002 194 11574941 6099 94 <br>SCOTIABANK 749 00911 00 CCI 74920 47 9009 1100 32<br>ventas.motosur2022@gmail.com Cel: 951471137 / 981308570</td>';
        }else{
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">'.$this->RazonSocial.'</td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel: 998 265 946 / 981 308 570 / 916439633</td>';
        }
        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 9, 100, 26, '', '', '', false, 300);

        $this->SetY(35);

        $comprobanteHTML = '<table style="width:5cm; font-size:8pt;" border="0">
        ' . $datosempre. '
        <tr>
        ' . $datos_cabecera. '
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }
}

class pdfGarantiaComprobante extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();

        $this->ci->load->model('maestros/persona_model');
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/garantia".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        $this->SetAutoPageBreak(false, 0);
        //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
        $this->SetAutoPageBreak(true, 5);
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 10;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7
        $datos_cabecera="";
        $logo_yape = base_url() . 'images/yape.png';
        $logo_plin = base_url() . 'images/plin.png';
        if ($this->empresa==1) {
            $datosempre='';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>BCP 194 382515 50028 CCI 002 194 1382 5155 002899 <br>SCOTIABANK 749 0060 485 CCI 009 749 2074 90060 48534 <br>Cel. 998265946 / 981308570 / 934475536 Correo: dakarwashperu@gmail.com<br>VENTA DE ACEITES, GRASAS Y ACCESORIOS AL POR MAYOR Y MENOR</td>
            <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>
            ';
        }else if($this->empresa==873){
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">DE: '.$this->RazonSocial.'</td><td></td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>BCP 194 157494 16099 CCI 002 194 11574941 6099 94 <br>SCOTIABANK 749 00911 00 CCI 74920 47 9009 1100 32<br>ventas.motosur2022@gmail.com Cel: 951471137 / 981308570</td>
                             <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>';
        }else{
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">'.$this->RazonSocial.'</td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel: 998 266 946 / 981 308 570 / 916439633</td>';
        }
        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 7, 123, 26, '', '', '', false, 300);

        $this->SetY(33);

        $comprobanteHTML = '<table style="width:5cm; font-size:8pt;" border="0">
        ' . $datosempre. '
        <tr>
        ' . $datos_cabecera. '
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }

    public function Footer(){
        $this->SetFont('freesans', '', 7);
        $this->SetY(-9);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr>
                    <td style="text-align:center;">Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'</td>
                </tr>
            </table>';

        #$this->writeHTML($pieHTML,false,'');
        $this->SetY(55);
    }
}

class pdfGuiaRemision extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct(); 
        $this->ci =& get_instance();
        $this->ci->load->library('lib_props');
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        if ($flagPdf == 1){
            $this->SetAutoPageBreak(false, 0);
            //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 40);
        }
        
        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 10;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:10pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7
        $datos_cabecera="";
        $logo_yape = base_url() . 'images/yape.png';
        $logo_plin = base_url() . 'images/plin.png';
        if ($this->empresa==1) {
            $datosempre='';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel. : 940 059 637 </td>
            <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>
            ';
        }else if($this->empresa==873){
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">DE: '.$this->RazonSocial.'</td><td></td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>BCP 194 157494 16099 CCI 002 194 11574941 6099 94 <br>SCOTIABANK 749 00911 00 CCI 74920 47 9009 1100 32<br>ventas.motosur2022@gmail.com Cel: 951471137 / 981308570</td>
                             <td><img src="'.$logo_yape.'" height="20px"><br><img src="'.$logo_plin.'" height="20px"></td>';
        }else{
            $datosempre='<tr><td style="font-weight:bold;width:11cm;">'.$this->RazonSocial.'</td></tr>';
            $datos_cabecera='<td style="width:11cm; font-size:8pt;">' . $this->direccion . ' ' . $this->ubigeo. '<br>Cel: 998 266 946 / 981 308 570 / 916439633</td>';
        }
        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 7, 123, 26, '', '', '', false, 300);

        $this->SetY(33);

        $comprobanteHTML = '<table style="width:5cm; font-size:8pt;" border="0">
        ' . $datosempre. '
        <tr>
        ' . $datos_cabecera. '
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }
}

?>