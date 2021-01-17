<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?php echo NAME_SYSTEM.' :: '.$condominium->name ?></title> 
</head>
<style> 
*{
  margin:0px;
  padding:0px;
}
img{ 
  width: 100%; 
  margin-bottom:300px;
} 
div{
  border: 1px Solid #000; 
  width:100%;
  height: 76px; 
  background-color:#000;
  color:#fff;
}
</style>
<body style=" text-align:center"> 
<img src="data:image/png;base64,<?php echo TAG_IMG ?>"  >
<div>
<h3  style="padding-top:5px;margin:0px;"><?php echo $condominium->name ?></h3>
<h4  style="padding:0px;margin:5px;"><?php echo $code ?></h4> 
 </div>
</body>
</html>