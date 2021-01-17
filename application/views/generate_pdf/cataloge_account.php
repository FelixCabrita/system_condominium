<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?php echo NAME_SYSTEM ?> :: CATALOGO DE CUENTAS</title> 
</head>
<style>
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
  
  padding: 8px;
} 
.account{
    width:150px; 
}
#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #76c1c8;
  color: white;
}
</style>
<body> 
<img src="data:image/png;base64,<?php echo LOGO_PDF ?>">
<h3  style="padding:0px;margin:0px;"><?php echo $condominium->name ?></h3>
<p  style="padding:0px;margin:0px"> 

RCN: <?php echo $condominium->rcn ?> <br>

TELEFONO: <?php echo $condominium->mobile ?>   <br>

DIRECCION: <?php echo $condominium->address ?></p> 

</table>
<h2  style="color:red;text-align:center">CATALOGO DE CUENTAS</h2> 
<table id="customers"   cellspacing="1" cellpadding="1">   
    <tr>
        <th class="account">CUENTAS</th>
        <th>CONCEPTOS</th> 
    </tr>
     <?php echo $data;?>
</table>
</body>
</html>