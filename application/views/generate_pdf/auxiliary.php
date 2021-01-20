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
  font-size:12px;
}
img{
  position: absolute; 
  left:620px;
  width: 80px;
  top: -10px
}

 hr{
    padding-top: 5px; 
  text-align: left;
  background-color: #1AABAB;
  color: white;
 }

#customers td, #customers th {
  text-align: center;
  padding: 6px;
} 

#customers td, #customers th {
  text-align: left;
  padding: 6px;
} 


#customers th {
  padding-top: 6px;
  padding-bottom: 6px;
  text-align: left;
  font-size:9px;
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
    text-align: right !important;
}
.note{
    width:280px; 
}

.amount_line{
    width:90px; 
    text-align: right !important;
    border: 1px ; 
    border-style: solid 0 double 0 ;
}
</style>
<body> 
<img   src="data:image/png;base64,<?php echo LOGO_PDF ?>">
    <h3  style="padding:0px;margin:0px;"><?php echo $condominium->name ?></h3>
<p  style="padding:0px;margin:0px">  
RCN: <?php echo $condominium->rcn ?> <br> 
TELEFONO: <?php echo $condominium->mobile ?><br> 
DIRECCION: <?php echo $condominium->address ?><br> FECHA: <?php echo date("Y-m-d g:i A") ?></p>  
<?php echo $data;?>
</body>
</html>