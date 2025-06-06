<?php

  function getMes($mes)
  {
    $mes = str_pad((int) $mes,2,"0",STR_PAD_LEFT);
    switch ($mes) 
    {
        case "01": return "ENE";
        case "02": return "FEB";
        case "03": return "MAR";
        case "04": return "ABR";
        case "05": return "MAY";
        case "06": return "JUN";
        case "07": return "JUL";
        case "08": return "AGO";
        case "09": return "SET";
        case "10": return "OCT";
        case "11": return "NOV";
        default: return "DIC";
    }
  }
  
  function getMonths($start, $end) {
      $startParsed = date_parse_from_format('Y-m-d', $start);
      $startMonth = $startParsed['month'];
      $startYear = $startParsed['year'];

      $endParsed = date_parse_from_format('Y-m-d', $end);
      $endMonth = $endParsed['month'];
      $endYear = $endParsed['year'];

      return ($endYear - $startYear) * 12 + ($endMonth - $startMonth) + 1;
  }
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
  $(document).ready(function(){
      base_url = $("#base_url").val();
    $(".fuente8 tbody tr:odd").addClass("itemParTabla");
    $(".fuente8 tbody tr:even").addClass("itemImparTabla");
    
    $(".fecha").datepicker({ dateFormat: "yy-mm-dd" });
    
    $("#reporte").click(function(){
    
      if($('#fecha_inicio').val() == "" || $('#fecha_fin').val() == "")
      {

        alert("Ingrese ambas fechas");
      }else{
        var startDate = new Date($('#fecha_inicio').val());
        var endDate = new Date($('#fecha_fin').val());

        if (startDate > endDate){
          alert("Rango de Fechas inválido");
        }else
        {
          $("#generar_reporte").submit();
        }
      }
    });
    
    $('#verReporte').click(function(){
      if($('#fecha_inicio').val() == "" || $('#fecha_fin').val() == ""){
        alert("Ingrese ambas fechas");
      }
      else{
        var startDate = $('#fecha_inicio').val();
        var endDate = $('#fecha_fin').val();

        if (startDate > endDate){
          alert("Rango de Fechas inválido");
        }
        else{
          fechaI = startDate.split('-');
          fechaF = endDate.split('-');
          fechaIF = startDate+"/"+endDate;

          location.href = base_url+ "index.php/reportes/ventas/ExeVenta/"+fechaIF;
        }
      }
    });
  });
 
    function factura(oper,tipo,codigo){
  var op;
  if(oper==0){
    op="C";
  }else{
    op="V";
  }
    switch(tipo){
      case 1:
        var url = base_url+"index.php/ventas/comprobante/comprobante_ver_pdf/"+codigo+"/TICKET";
        break;
      case 2:
        var url = base_url+"index.php/ventas/comprobante/comprobante_ver_pdf/"+codigo+"/TICKET";
        break;
    }
    
        window.open(url,'',"width=800,height=600,menubars=no,resizable=no;");
    }
    function boleta(oper,codigo){
    if(oper==0){
      var url = base_url+"index.php/ventas/comprobante/comprobante_ver_pdf/"+codigo+"/TICKET";
    }
    if(oper==1){
      var url = base_url+"index.php/ventas/comprobante/comprobante_ver_pdf/"+codigo+"/TICKET";
    }
       
        window.open(url,'',"width=800,height=600,menubars=no,resizable=no;");
    }
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="<?=base_url();?>/bootstrap/css/bootstrap.css" />
<style>
input[type="search"]{
    background-color: #fff;
    text-transform: uppercase;
    color: #000;
    width: 240px;
    border-color: #a3b3bb;
    border-style: solid;
    border-width: 1px;
    font-size: 9pt;
    font-weight: bold;
    padding: 0.5em;
};
</style>
<div id="pagina">
    <div id="zonaContenido">
    <div align="center">
    <div id="tituloForm" class="header">REPORTE DE VENTAS </div>
    <div id="frmBusqueda">
      <form method="post" action="" id="generar_reporte">
        Desde: <input type="text" id="fecha_inicio" name="fecha_inicio" readonly class="fecha cajaMedia" value="<?php echo ((isset($_POST['reporte'])) ? $_POST['fecha_inicio'] : ''); ?>"> Hasta: <input type="text" id="fecha_fin" name="fecha_fin" class="fecha cajaMedia" readonly value="<?php echo ((isset($_POST['reporte'])) ? $_POST['fecha_fin'] : ''); ?>"> <input type="hidden" name="reporte" value=""><!--<input type="button" id="reporte" value="Generar" class="btn btn-success"> -->
          <a href="javascript:;" id="verReporte"><img src="<?php echo base_url();?>images/xls.png" style="width:40px; border:none;" class="imgBoton" align="absmiddle"/></a>
      </form>
      <br><br>

      <?php if(isset($_POST['reporte'])): ?>
      <table class="fuente8 display" cellspacing="0" cellpadding="3" border="0" id="resumen">
      <thead>
        <tr class="cabeceraTablaResultado"><th colspan="7">Reporte de ventas desde <?php echo mysql_to_human($fecha_inicio); ?> hasta el <?php echo mysql_to_human($fecha_fin); ?></th></tr>
        <tr class="cabeceraTabla">
            <th style="text-align: center">Fecha de Comprobante</th>
            <th style="text-align: center">Fecha de Ultimo Pago</th>
            <th>Comprobante</th>
            <th>Valor de Venta S/.</th>
            <th>Valor de Venta US$.</th>
            <th>Monto del Pago S/.</th>
            <th>Forma de Pago</th>
        </tr>
      </thead>
      <tbody>
      <?php 
    $total = 0;
    $total2 = 0;

    ?>
      <?php $total_filas = count($resumen); ?>
      <?php foreach($resumen as $fila): ?>
      <?php 
    

    if( $fila['MONED_Codigo']==2 ){
    //$total += $fila['VENTAS']*$fila['CPC_TDC']; 
      if ($fila['FORPAP_Codigo'] != 1)
        $totalCreditos2 += $fila['pagos'];
      else
        $total2 += $fila['VENTAS'];
    }else{
      if ($fila['FORPAP_Codigo'] != 1)
        $totalCreditos1 += $fila['pagos'];
      else
        $total += $fila['VENTAS'];
    //$total2 += $fila['VENTAS']/$fila['CPC_TDC']; 
    }
    
    
    if($fila['CPC_TipoOperacion']=="C"){
      $operacion=0;
    }else{
      $operacion=1;
    };

    if($fila['TIPO']=='N'){
      $fila['TIPO']=1;
    }
    
    if($fila['TIPO']=='F'){
      $fila['TIPO']=2;
    }
    
    echo "<tr>
            <td style='text-align:center'>{$fila['FECHA']}</td>
            <td style='text-align:center'>{$fila['FECHAPAGO']}</td>
            <td>
            ";
            if($fila['TIPO']=="B")
              echo "<a href='javascript:;' onclick='boleta({$operacion},{$fila['CODIGO']})'target='_parent'> <img src='".base_url()."images/pdf.png' width='12px'/> </a>";
            else
              echo "<a href='javascript:;' onclick='factura({$operacion},".$fila['TIPO'].",{$fila['CODIGO']})' target='_parent'> <img src='".base_url()."images/pdf.png' width='12px'/> </a>";
    echo "
            {$fila['SERIE']}-{$fila['NUMERO']}
            </td>";
    
      if( $fila['MONED_Codigo']==2 ){
        echo "<td></td>"; 
        echo "<td align='right'>".number_format($fila['VENTAS'],2,'.','')."</td>";
      }else{
        echo "<td align='right'>".number_format($fila['VENTAS'],2,'.','')."</td>";
        echo "<td align='right'></td>";
      }

      echo "<td align='right'>".number_format($fila['CPAGC_Monto'],2,'.','')."</td>";
      
      if ($fila['FORPAP_Codigo'] != 1)
        echo "<td align='center' style='background:yellow'>CREDITO</td>";
      else
        echo "<td align='center' style='background:rgba(210,255,82,.5);'>EFECTIVO</td>";
                   

           "</tr>";
        ?>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="cabeceraTabla">
            <td align="right" colspan="5">INGRESO EN EFECTIVO</td>
            <td align="right"><?php echo number_format($total,2,'.',''); ?></td>
            <td align="right"><?php echo number_format($total2,2,'.',''); ?></td>
        </tr>
        <tr class="cabeceraTabla">
            <td align="right" colspan="5">INGRESO EN CREDITOS</td>
            <td align="right"><?php echo number_format($totalCreditos1,2,'.',''); ?></td>
            <td align="right"><?php echo number_format($totalCreditos2,2,'.',''); ?></td>
        </tr>
        <tr class="cabeceraTabla">
            <td align="right" colspan="5">INGRESO TOTAL</td>
            <td align="right"><?php echo number_format($totalCreditos1+$total,2,'.',''); ?></td>
            <td align="right"><?php echo number_format($totalCreditos2+$total2,2,'.',''); ?></td>
        </tr>
      </tfoot>
      </table>
      
      <?php endif; ?>
      <?php echo $oculto?>
    </div>
    </div>
    </div>
</div>
