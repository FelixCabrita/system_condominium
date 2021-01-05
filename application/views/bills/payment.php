<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);">
                <div class="col-sm-9">
                    <h4>Pago de factura</h4>
                </div> 
            </div>
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);">
                <div class="col-sm-4" style = "margin-bottom:1rem">
                    <div class="input-group"> 
                        <input type="text"  class="form-control " placeholder = "Buscar al cliente por documento de identidad" id="document"  name="document"  maxlength="100" > 
                        <div class="input-group-prepend">
                            <a id ="search" class="btn btn-secondary"><i class="fa fa-search"></i></a>
                        </div>
                        <input type="hidden"   name="client" id="client" />    
                    </div> 
                </div> 
                
                <div class="col-sm-4 dataclient">
                    <div class="input-group"> 
                        <input type="text"  class="form-control "   id="name" readonly > 
                    </div> 
                </div> 
                <div class="col-sm-4 dataclient">
                    <div class="input-group"> 
                        <input type="text"  class="form-control "  id="type"  readonly >  
                    </div> 
                </div> 
            </div> 
            <hr>
            <div class="row"> 
                <div class="form-group col-xl-8">

                    <div class="row"> 
                        <div class="form-group col-sm-4">
                            <label for="service">Servicio</label>
                            <select name="service" id="service" class="form-control">  
                                <?php  if(!empty($serviceRecords)) { foreach($serviceRecords as $record) {  ?>
                                    <option value ="<?php echo $record->id ?>" data-name = "<?php echo $record->name?>"  data-amount = "<?php echo $record->amount?>" ><?php echo $record->code." - ".$record->name ?></option>
                                <?php }} ?> 
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="apartment">Apartamento</label>
                            <select name="apartment" id="apartment" class="form-control">  
                                 
                            </select>
                        </div>    
                        <div class="form-group col-sm-3">
                            <label for="payment_mode">Modo de pago</label>
                            <select name="payment_mode" id="payment_mode" class="form-control">  
                                <option value ="0" >PAGO UNICO</option>
                                <option value ="1" >ABONO</option>
                            </select>
                        </div>  
                        <div class="form-group col-sm-2">
                            <button type="submit" class="btn btn-sm btn-info addservice" style="bottom: 0;top: 40%;width: 100%;" title="AGREGAR CUENTA"><i class="fa fa-check"></i>&nbsp; AGREGAR</button>
                        </div>   
                    </div>
                    <div class="table-responsive">
                        <table id="payment" class="display compact stripe" style="min-width: 100%">
                            <thead>
                                <tr>  
                                    <th class="text-center" style ="display:none">#</th>
                                    <th class="text-center">DETALLE</th>
                                    <th class="text-center">MONTO</th>
                                    <th class="text-center">APTO.</th>  
                                    <th class="text-center">EMISION.</th>  
                                    <th class="text-center" style ="display:none">ABONO</th>  
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody> 
                            
                            </tbody>
                            <tfoot>
                                <tr> 
                                    <th class="text-center" style ="display:none">#</th>
                                    <th class="text-center">DETALLE</th>
                                    <th class="text-center">MONTO</th>
                                    <th class="text-center">APTO.</th>  
                                    <th class="text-center">EMISIO.</th>  
                                    <th class="text-center" style ="display:none">ABONO</th>  
                                    <th class="text-center">ACCIONES</th>
                                
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="row"> 
                        <div class=" col-6">
                            <spam style = "font-size:16px"for="code">Monto a pagar</spam> 
                        </div>   
                        <div class=" col-6"> 
                            <label for="code"><b><spam  style = "font-size:16px;color:red" id="total"  name="total" readonly>0.0</spam></b></label>  
                        </div>   
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Monto con que se paga</label>
                        <input type="number"  class="form-control mb-2 mr-sm-5" id="amount"  name="amount"  min = '0' value="0" maxlength="200" >  
                    </div> 
                    <div class="row"> 
                        <div class=" col-5">
                            <spam style = "font-size:16px"for="code">Pago en:</spam> 
                        </div>   
                        <div class=" col-7"> 
                            <label for="code"><b><spam id="payment_type_text"  style = "font-size:16px;color:red">EFECTIVO</spam></b></label>
                            <input type="text" style="display:none"   id="payment_type_id"  name="payment_type_id" value="0">  
                        </div>   
                    </div>
                    <div class="row payment_check" style="display:none">
                        <div class="col-12" style="margin:0px;padding:0px"><p>Informacion del cheque</p></div>
                        <div class="col-12">
                            <label for="check_number">Nro Cheque</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5" id="check_number"  name="check_number"  maxlength="200" >  
                    
                        </div>
                        <div class="col-12">
                            <label for="check_transmitter">Banco Emisor</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5" id="check_transmitter"  name="check_transmitter"  maxlength="200" >  
                        </div>
                    </div>

                    <div class="row payment_transfer"  style="display:none">
                        <div class="col-12" style="margin:0px;padding:0px"><p>Informacion del transferencia</p></div>
                        <div class="col-12">
                            <label for="transfer_code">Codigo </label>
                            <input type="text"  class="form-control mb-2 mr-sm-5" id="transfer_code"  name="transfer_code"  maxlength="200" >  
                        </div>
                        <div class="col-12">
                            <label for="transfer_date">Fecha</label>
                            <input type="date"  class="form-control mb-2 mr-sm-5" id="transfer_date"  name="transfer_date"  maxlength="200" >  
                        </div>
                    </div>

                    <div class="row"> 
                        <div class=" col-6">
                            <div class="form-group text-center"> 
                                <button  class="btn btn-sm btn-info pay"   title="AGREGAR CUENTA"> PROCESAR PAGO</button>
                            </div> 
                         </div>   
                        <div class=" col-6"> 
                            <div class="form-group text-center"> 
                                <a  class="btn btn-sm btn-info" title="AGREGAR CUENTA" href="#"  data-toggle="modal" data-target="#payment_type" > TIPOS DE PAGO</a>
                            </div> 
                        </div>   
                    </div>
                  
                  
                </div>   
            </div>   
            
        </div>
    </div>
    
</div> 

<style>
.dataTables_info, .dataTables_paginate {
    display:none;
}
</style>


<script src="<?php echo base_url() ?>assets/scripts/bootstrap4-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/bootstrap-notify-init.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables.init.js"></script> 

<script src="<?php echo base_url() ?>assets/scripts/system/payment.js"></script>   


