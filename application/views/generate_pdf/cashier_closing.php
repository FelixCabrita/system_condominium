<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?php echo NAME_SYSTEM ?> :: CIERRE DE CAJA </title> 
</head>
<style>
  body{
    font-size:12px;
  }
#customers { 
  border-collapse: collapse;
  width: 100%;
  font-size:11px;
  margin-top:10px
}

#firm {  
  width: 100%;
  font-size:11px; border-spacing: 100px;
  margin-top:10px
}
#firm td { 
  border-top: 2px solid #000; 
 
}
img{
  position: absolute; 
  left:620px;
  width: 80px;
  top: -10px
}

hr{
  padding-top: 5px;
  padding-bottom: 5px;
  text-align: left; 
  background-color: #1AABAB;
  color: white;
 }

 th{
  font-weight: normal; 
 }
 
#customers td, #customers th {
  
  padding: 6px;
} 

#customers td, #customers th {
  
  padding: 6px;
} 


#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #76c1c8;
  color:  #000;
}
 
#customers .total td { 
  padding: 6px;
  color:  #000;
  background-color: #76c1c8;
} 
.account{
    width:200px; 
}
.amount{
    width:150px; 
    text-align: right;
}
</style>
<body> 
<img   src="data:image/png;base64,<?php echo LOGO_PDF ?>">
<h3  style="padding:0px;margin:0px;"><?php echo $condominium->name ?></h3>
<p  style="padding:0px;margin:0px">  
RCN: <?php echo strtoupper($condominium->rcn) ?> <br> 
TELEFONO: <?php echo strtoupper($condominium->mobile) ?><br> 
DIRECCION: <?php echo strtoupper($condominium->address) ?><br> </p>

<h2  style="color:red;text-align:center">CIERRE DE CAJA</h2>  
<hr>
<p  style="padding:0px;margin:0px">  
CAJERO: <?php echo strtoupper($user->name) ?> <br> 
FECHA DE CIERRE: <?php echo strtoupper($detail->created_at) ?>  <br> 
NOTA: <?php echo strtoupper($detail->observation) ?> 
<?php echo $detail->status==0?" <br> ESTADO DE CIERRE: ANULADO<br>OBSERVACION: ".$detail->observation_status:"" ?> 
</p>
<h3 style="color:red;text-align:center">DETALLE DE FACTURA</h3>  
<table id="customers"   cellspacing="1" cellpadding="1">      
  <tr class="header">  
      <th style ="text-align: center;">TIPO DE PAGO </th>  
      <th style ="text-align: center;">MONTO CAJA</th> 
      <th style ="text-align: center;">MONTO SISTEMA</th>   
      <th style ="text-align: center;">DIFERENCIA</th>     
  </tr> 
 
  <?php
  $total = 0;
  $total_exit = 0;
  if(!empty($detail)) {  
    $cash = $detail->cash_input;   
    $card = $detail->card_input;  
    $transfer = $detail->transfer_input;
    $check = $detail->check_input;
    $total = $cash+$card+$transfer+$check;

    $cash_exit = $detail->cash_exit;  
    $card_exit = $detail->card_exit;  
    $transfer_exit = $detail->transfer_exit;
    $check_exit = $detail->check_exit;
    $total_exit = $cash_exit+$card_exit+$transfer_exit+$check_exit;
  ?>
  <tr>     
      <td style ="width:300px;text-align: center;"><?php echo ("EFECTIVO") ?> </th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format($cash, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format($cash_exit, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format(($cash-$cash_exit), 2, ',', '') ?></th>  
  </tr>
  <tr>     
      <td style ="width:300px;text-align: center;"><?php echo ("TARJETA") ?> </th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format($card, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format($card_exit, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format(($card-$card_exit), 2, ',', '') ?></th>  
  </tr>
  <tr>     
      <td style ="width:300px;text-align: center;"><?php echo ("TRANSFERENCIA") ?> </th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format($transfer, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format($transfer_exit, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format(($transfer-$transfer_exit), 2, ',', '') ?></th>  
  </tr>
  <tr>     
      <td style ="width:300px;text-align: center;"><?php echo ("CHEQUE") ?> </th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format($check, 2, ',', '') ?></th>   
      <td style ="width:100px;text-align: center;"><?php echo number_format($check_exit, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format(($check-$check_exit), 2, ',', '') ?></th>  
  </tr>
  <?php } ?>
  <tr class="total">  
      <td >&nbsp;</td>
      <td style ="width:100px;text-align: center;"><?php echo  number_format($total, 2, ',', '') ?></td> 
      <td style ="width:100px;text-align: center;"><?php echo number_format($total_exit, 2, ',', '') ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo number_format(($total-$total_exit), 2, ',', '') ?></th>  
  </tr> 
</table> 
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<table id='firm' >   

  <tr>   
      <td style ="width:100px;text-align: center;">FIRMA CAJERO</th>  
      <td style ="width:100px;text-align: center;">FIRMA SUPERVISOR</th>  
  </tr>

</table> 
</body>
</html>