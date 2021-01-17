<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body">  
            
            <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');   
                if($error) { ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                        <?php echo $error; ?>          
                    </div> 
                <?php }
                $success = $this->session->flashdata('success');
                if($success)  { ?> 
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                        <?php echo $success; ?>              
                    </div>  
            <?php } ?>
            <form class="form-inlne" id="exit_cashier"  action="<?php echo base_url() ?>nuevo_cierre_caja" method="post" role="form">
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);">
                <div class="col-sm-8">
                    <h4>Cierre de caja  </h4>
                </div>   
                <div class="col-sm-4">
                    <div class="input-group"> 
                        <div class="input-group-prepend">
                            <a class="btn btn-secondary" style="color:#ffff">Fecha</i></a>
                        </div>
                        <input type="date"  class="form-control "  id="date"  name="date" value=<?php echo date('Y-m-d', strtotime(' -1 days')); ?>> 
                        <?php echo validation_error('date');?> 
                    </div>  
                </div> 
            </div>
                <div class="row">
                    <div class="form-group col-sm-4 text-center">
                        <label class='text-center' for="cash_input">ARQUEO</label> 
                        <div class="form-group text-left">
                            <label for="cash_input">Efectivo</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="cash_input" min="0" value="0"  name="cash_input"> 
                            <?php echo validation_error('cash_input');?> 
                        </div>   
                        <div class="form-group text-left">
                            <label  for="transfer_input">Transferencia</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="transfer_input"  min="0" value="0"    name="transfer_input"> 
                            <?php echo validation_error('transfer_input');?> 
                        </div>   
                        <div class="form-group text-left">
                            <label for="check_input">Cheque</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="check_input"  min="0" value="0"    name="check_input"> 
                            <?php echo validation_error('check_input');?> 
                        </div>   
                        <div class="form-group text-left">
                            <label for="card_input">Tarjeta</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="card_input"   min="0" value="0"   name="card_input"> 
                            <?php echo validation_error('card_input');?> 
                        </div> 
                        <hr>  
                        <label class='01_total' style="color:red">0.0</label> 
                    </div>   
                    <div class="form-group col-sm-4 text-center">
                        <label class='text-center' for="cash_exit">SISTEMA</label> 
                        <div class="form-group text-left">
                            <label for="cash_exit">Efectivo</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="cash_exit"  readonly  value="0"   name="cash_exit"> 
                            <?php echo validation_error('cash_exit');?> 
                        </div>   
                        <div class="form-group text-left">
                            <label  for="transfer_exit">Transferencia</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="transfer_exit" readonly  value="0"    name="transfer_exit"> 
                            <?php echo validation_error('transfer_exit');?> 
                        </div>   
                        <div class="form-group text-left">
                            <label for="check_exit">Cheque</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="check_exit" readonly  value="0"    name="check_exit"> 
                            <?php echo validation_error('check_exit');?> 
                        </div>   
                        <div class="form-group text-left">
                            <label for="card_exit">Tarjeta</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="card_exit"  readonly  value="0"   name="card_exit"> 
                            <?php echo validation_error('card_exit');?> 
                        </div>  
                        <hr>  
                        <label class='02_total' style="color:red">0.0</label>  
                    </div>     

                    <div class="form-group col-sm-4  text-center">
                        <label for="name">DIFERENCIA</label> 
                        <div class="form-group text-left">
                            <label for="cash_result">Efectivo</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="cash_result"  readonly  value="0"  name="cash_result"> 
                         
                        </div>   
                        <div class="form-group text-left">
                            <label  for="transfer_result">Transferencia</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="transfer_result" readonly  value="0"   name="transfer_result"> 
                          
                        </div>   
                        <div class="form-group text-left">
                            <label for="check_result">Cheque</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="check_result" readonly  value="0"   name="check_result"> 
                           
                        </div>   
                        <div class="form-group text-left">
                            <label for="card_result">Tarjeta</label>
                            <input type="number"  class="form-control mb-2 mr-sm-5" id="card_result" readonly  value="0"   name="card_result">  
                        </div>   
                        <hr>
                        <input type="text" style="display: none" class="form-control mb-2 mr-sm-5" id="observation" readonly   name="observation">  
                        <p class='03_total' style="color:red">0.0</p>  
                    </div>   
                </div> 
                
               
            </form> <div class="form-group text-left">
                    <button type="submit" class="btn btn-primary btn-form send">Guardar proceso</button>
                    <button type="reset" class="btn btn-danger btn-form clear">Limpiar</button>
                </div> 
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/exit_cashier.js" type="text/javascript"></script>  