<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?php echo NAME_SYSTEM ?> :: ENTRADA DE DIARIO # <?php echo str_pad($header->id,4,"0", STR_PAD_LEFT) ?></title> 
</head>
<style>
  body{
    font-size:13px;
  }
#customers { 
  border-collapse: collapse;
  width: 100%;
  font-size:13px;
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
RCN: <?php echo $condominium->rcn ?> <br> 
TELEFONO: <?php echo $condominium->mobile ?><br> 
DIRECCION: <?php echo $condominium->address ?></p>  
</table>
<h2  style="color:red;text-align:center">Movimiento de cuenta</h2> 

<p><b>Modulo: </b> <?php echo $header->module?><b><br>Tipo de documento: </b> <?php echo $header->documentType?><br>
<b>Concepto: </b> <?php echo $header->description?><br>
<b>Nº Asiento: </b> <?php echo $header->id?><spam style="margin-left:150px">&nbsp;</spam><b>Fecha: </b> <?php echo date_format (new DateTime($header->created_at), 'Y-m-d g:i A') ?></p>
<table id="customers"   cellspacing="1" cellpadding="1">   
  <tr class="header">  
      <th class="account">CUENTA </th>  
      <th >NOTA</th>  
      <th  class="amount">DEBITO</th>
      <th  class="amount">CREDITO</th> 
  </tr> 
  <?php
  $debit=0; $credit=0;
  if(!empty($detail))
  {
      foreach($detail as $record)
      { 
        $debit = $debit+ $record->debit; 
        $credit = $credit+ $record->credit;
  ?>
  <tr>    
      <td><?php echo $record->name_ac ?></td>
      <td><?php echo  empty($record->description)?"NINGUNA":$record->description ?></td> 
      <td class="amount"><?php echo  number_format($record->debit, 2, ',', '') ?></td>
      <td class="amount"><?php echo  number_format($record->credit, 2, ',', '') ?></td>
      
  </tr>
  <?php } } ?>
  <tr class="total">  
      <td >&nbsp;</td> 
      <td >&nbsp;</td> 
      <td class="amount"><?php echo  number_format($debit, 2, ',', '') ?></td>  
      <td class="amount"><?php echo  number_format($credit, 2, ',', '') ?></td> 
  </tr> 
</table>
</body>
</html>