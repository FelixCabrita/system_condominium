<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?php echo NAME_SYSTEM ?> :: FACTURA #<?php echo str_pad($bill->unique_code,4,"0", STR_PAD_LEFT) ?> </title> 
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
DIRECCION: <?php echo strtoupper($condominium->address) ?><br>
USUARIO CAJA: <?php echo strtoupper($user->name) ?></p>  

<h2  style="color:red;text-align:center">FACTURA #<?php echo str_pad($bill->bill_group,4,"0", STR_PAD_LEFT) ?></h2>  
<hr>
<table  style="margin:auto;width:480px">   
  <tr>  
      <th style=" text-align:left"><b>INFORMACION DEL CLIENTE </b></th>  
      <th  >&nbsp;</th>  
      <th  >&nbsp;</th>
      <th style=" text-align:left;padding-left:20px"><b>INFORMACION DEL PAGO</b></th> 
  </tr> 
  <tr>  
      <th style="font-size:11px;text-align:left"><b>DOCUMENTO: </b> <?php echo  strtoupper($client->document) ?></th>  
      <th  ></th>  
      <th  >&nbsp;</th>
      <th style="font-size:11px;text-align:left;padding-left:20px"><b>TIPO: </b> <?php echo  checkpaymenttype($bill->payment_type); ?></th> 
  </tr> 
  <tr>  
      <th style="font-size:11px;text-align:left"><b>NOMBRE: </b> <?php echo strtoupper($client->name) ?></th>  
      <th  ></th>  
      <th  >&nbsp;</th>
      <th style="font-size:11px;text-align:left;padding-left:20px"> <?php if($bill->payment_type=='1'){ ?><b>CODIGO: </b> <?php echo strtoupper($bill->transfer_code); }else if($bill->payment_type=='2'){ ?><b>NUMERO: </b> <?php echo strtoupper($bill->check_number); } ?></th> 
  </tr> 
  <tr>  
      <th style="font-size:11px;text-align:left"><b>TIPO: </b> <?php echo checkRole($client->type) ?></th>  
      <th  ></th>  
      <th  >&nbsp;</th>
      <th style="font-size:11px;text-align:left;padding-left:20px"> <?php if($bill->payment_type=='1'){ ?><b>FECHA: </b> <?php echo strtoupper($bill->transfer_date); }else if($bill->payment_type=='2'){ ?><b>BANCO: </b> <?php echo strtoupper($bill->check_transmitter); } ?></th> 
  </tr> 
</table>  
<h3 style="color:red;text-align:center">DETALLE DE FACTURA</h3>  
<table id="customers"   cellspacing="1" cellpadding="1">   
  <tr class="header">  
      <th style ="width:300px;text-align: center;">DETALLE </th>  
      <th style ="width:100px;text-align: center;">APTO.</th>  
      <th style ="width:100px;text-align: center;">EMISION</th>
      <th style ="width:100px;text-align: center;">MONTO</th> 
  </tr> 
 
  <?php
  $total = 0;
  if(!empty($bills))
  {
      foreach($bills as $record)
      { 
        $total = $total+ $record->amount; 
       
  ?>
  <tr>     
      <td style ="width:300px;text-align: center;"><?php echo  strtoupper($record->description) ?> </th>  
      <td style ="width:100px;text-align: center;"><?php echo  strtoupper($record->code) ?></th>  
      <td style ="width:100px;text-align: center;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></th>
      <td style ="width:100px;text-align: center;"><?php echo  number_format($record->amount, 2, ',', '') ?></th>
      
      
  </tr>
  <?php } } ?>
  <tr class="total">  
      <td >&nbsp;</td> 
      <td >&nbsp;</td> 
      <td >&nbsp;</td>  
      <td style ="width:100px;text-align: center;"><?php echo  number_format($total, 2, ',', '') ?></td> 
  </tr> 
</table> 
</body>
</html>