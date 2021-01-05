<?php
$generate_credit = $info->generate_credit;
$generate_debit = $info->generate_debit; 
$debit_card = $info->debit_card; 
$debit_cash = $info->debit_cash; 
$debit_check = $info->debit_check; 
$debit_transfer = $info->debit_transfer;  
$credit_input = $info->credit_input; 
$this->load->helper('form');  
?>

<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Establecer parametros <br> </h4>
            <?php
                $error = $this->session->flashdata('error');   
                if($error) { ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                        <?php echo $error; ?>          
                    </div> 
                <?php }
                $success = $this->session->flashdata('success');
                if($success) { ?> 
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                        <?php echo $success; ?>              
                    </div>  
            <?php } ?>
          
            <form class="form-inlne"  action="<?php echo base_url() ?>cambiando_parametros" method="post" id="edit" role="form"> 
                <label for="generate_credit"><b>Generacion de factura</b></label><br> 
                <div class="row"> 
                    <div class="form-group col-sm-6">
                        <label for="generate_debit">DEBITO</label>
                        <select name="generate_debit" id="generate_debit" class="form-control">  
                            <?php  if(!empty($getDataAccountDetail)) { foreach($getDataAccountDetail as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo $generate_debit==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>     
                   
                    <div class="form-group col-sm-6">
                        <label for="generate_credit">CREDITO</label>
                        <select name="generate_credit" id="generate_credit" class="form-control">  
                            <?php  if(!empty($getDataAccountDetail)) { foreach($getDataAccountDetail as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo $generate_credit==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>    
                     
                </div>   
                <hr>
                <label for="generate_credit"><b>Al momento de generar un proceso de caja</b></label><br> 
                <label for="generate_credit"><b>::DEBITO PARA TIPOS DE PAGO</b></label><br> 
                <div class="row"> 
                    <div class="form-group col-sm-3">
                        <label for="debit_card">TARJETA</label>
                        <select name="debit_card" id="debit_card" class="form-control">  
                            <?php  if(!empty($getDataAccountDetail)) { foreach($getDataAccountDetail as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo $debit_card==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>     
                   
                    <div class="form-group col-sm-3">
                        <label for="debit_check">CHEQUE</label>
                        <select name="debit_check" id="debit_check" class="form-control">  
                            <?php  if(!empty($getDataAccountDetail)) { foreach($getDataAccountDetail as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo $debit_check==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>  
                    <div class="form-group col-sm-3">
                        <label for="debit_transfer">TRANSFERENCIA O DEPOSITO</label>
                        <select name="debit_transfer" id="debit_transfer" class="form-control">  
                            <?php  if(!empty($getDataAccountDetail)) { foreach($getDataAccountDetail as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo $debit_transfer==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>  
                    <div class="form-group col-sm-3">
                        <label for="debit_cash">EFECTIVO</label>
                        <select name="debit_cash" id="debit_cash" class="form-control">  
                            <?php  if(!empty($getDataAccountDetail)) { foreach($getDataAccountDetail as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo $debit_cash==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>    
                </div>   

                <label for="generate_credit"><b>::CREDITO</b></label><br> 
                <div class="row"> 
                    <div class="form-group col-sm-12"> 
                        <select name="credit_input" id="credit_input" class="form-control">  
                            <?php  if(!empty($getDataAccountDetail)) { foreach($getDataAccountDetail as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo $credit_input==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>     
                </div>     
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div>
                
            </form>  
        </div>
    </div>
</div>  