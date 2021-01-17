<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?php echo NAME_SYSTEM ?> :: BALANCE DE COMPROBACION</title> 
</head>
<style>
  body{
    font-size:13px;
  }
#customers { 
  border-collapse: collapse;
  width: 100%;
  font-size:10px;
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
    width:80px; 
}
.amount{
    width:90px; 
    text-align: right;
}
.note{
    width:280px; 
}
</style>
<body> 
<img   src="data:image/png;base64,<?php echo LOGO_PDF ?>">
    <h3  style="padding:0px;margin:0px;"><?php echo $condominium->name ?></h3>
<p  style="padding:0px;margin:0px">  
RCN: <?php echo $condominium->rcn ?> <br> 
TELEFONO: <?php echo $condominium->mobile ?><br> 
DIRECCION: <?php echo $condominium->address ?><br> FECHA: <?php echo date("Y-m-d g:i A") ?></p>  
<h2  style="color:red;text-align:center;margin-bottom:10px">BALANCE DE COMPROBACION</h2>  
<h5  style="color:blue;text-align:center;margin-top:10px">Periodo del 
                <spam style="color:black"><?php echo $date_start ?></spam> al 
                <spam style="color:black"><?php echo $date_ending ?></spam></h5> 
<table id="customers"  cellspacing="1" cellpadding="1">   
  <tr class="header">  
      <th class="account">CUENTA </th>  
      <th  class="note">CONCEPTO</th>  
      <th  class="amount">AUXILIAR</th>
      <th  class="amount">DEBITO</th>
      <th  class="amount">CREDITO</th> 
  </tr> 
  <?php echo $data;?>
</table>
</body>
</html>