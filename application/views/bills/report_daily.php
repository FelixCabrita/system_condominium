<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);">
                <div class="col-sm-6">
                    <h4>Entrada de diario</h4>
                </div> 
                <div class="col-sm-3">
                    <h5> <b># <?php echo str_pad($max,4,"0", STR_PAD_LEFT) ?> </b></h5>
                </div> 
                <div class="col-sm-3">
                    <h5>Fecha: <b><?php echo date("Y-m-d") ?></b></h5>
                </div> 
            </div>
            <hr>
            <div class="row" style = "margin:0px;padding:0px"> 
                <div class=" col-sm-6"> 
                    <div class="row" style = "margin:0px"> 
                        <div class=" col-sm-3"> 
                            <label for="module"  style="font-size:12px;height: 100%;max-height:75;   min-height: 100%;    display: flex;align-items: center;" >MODULO</label>
                        </div>  
                        <div class=" col-sm-9"> 
                            <select name="module" id="module" class="form-control">  
                                <?php  if(!empty($moduleRecords)) { foreach($moduleRecords as $record) {  ?>
                                    <option value ="<?php echo $record->id ?>" <?php echo 6==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                                <?php }} ?> 
                            </select>
                        </div>  
                    </div>  
                </div>   
                <div class=" col-sm-6"> 
                    <div class="row" style = "margin:0px"> 
                        <div class=" col-sm-3"> 
                            <label for="documentType"  style="font-size:12px;height: 100%;max-height:75;   min-height: 100%;    display: flex;align-items: center;" >TIPO DOCUMENTO</label>
                        </div>  
                        <div class=" col-sm-9"> 
                            <select name="documentType" id="documentType" class="form-control">  
                                <?php  if(!empty($documentTypeRecords)) { foreach($documentTypeRecords as $record) {  ?>
                                    <option value ="<?php echo $record->id ?>" data-module ="<?php echo $record->module?>" <?php echo 4==$record->id?"selected":"" ?> ><?php echo $record->code." - ".$record->name ?></option>
                                <?php }} ?> 
                            </select>
                        </div>  
                    </div>  
                </div>   
            </div>   
            <hr>
            <div class="row" style = "margin:0px"> 
                <div class="form-group col-sm-4">
                    <label for="code">Cuenta</label>
                    <select name="account" id="account" class="form-control">  
                        <?php  if(!empty($accountDetailRecords)) { foreach($accountDetailRecords as $record) {  ?>
                            <option value ="<?php echo $record->id ?>" <?php echo 6==$record->id?"selected":"" ?> data-name="<?php echo $record->code." - ".$record->name ?>" ><?php echo $record->code." - ".$record->name ?></option>
                        <?php }} ?> 
                    </select>
                 
                </div>   
                <div class="form-group col-sm-2">
                    <label for="debit">Debito</label>
                    <input type="number"  class="form-control mb-2 mr-sm-5"  value="0" min="0" id="debit"  name="debit"  > 
                    
                </div>  
                <div class="form-group col-sm-2">
                    <label for="credit">Credito</label>
                    <input type="number"  class="form-control mb-2 mr-sm-5" id="credit"  value="0" min="0"  name="credit" > 
                    
                </div>    
                <div class="form-group col-sm-3">
                    <label for="note">Apunte</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="note"   name="note" > 
                    
                </div>    
                <div class="form-group col-sm-1 text-center"> 
                    <button type="submit" class="btn btn-sm btn-info add"  style="height: 100%;max-height:75;  min-height: 100%;    display: flex;align-items: center;" title="AGREGAR CUENTA"> <i class="fa fa-plus" style="font-size:20px"></i></button>
                </div>   
            </div>   
            <div class="table-responsive">
                <table id="reportdaily" class="display" style="min-width: 845px">
                    <thead>
                        <tr> 
                            <th class="text-center" style ="display:none"># </th> 
                            <th class="text-center">CUENTA </th> 
                            <th class="text-center">DEBITO</th>
                            <th class="text-center">CREDITO</th> 
                            <th class="text-center">NOTA</th> 
                            <th class="text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody> 
                        
                    </tbody>
                    <tfoot>
                        <tr> 
                            <th class="text-center" style ="display:none"># </th> 
                            <th class="text-center">CUENTA </th> 
                            <th class="text-center">DEBITO</th>
                            <th class="text-center">CREDITO</th> 
                            <th class="text-center">NOTA</th> 
                            <th class="text-center">ACCIONES</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div class="row" style = "margin:0px"> 
                 <div class="form-group col-sm-1">
                    <label for="debit"   style="height: 100%;max-height:75;   min-height: 100%;    display: flex;align-items: center;" >RESULTADO</label> 
                </div> 
                <div class="form-group col-sm-4">
                    <label for="debit">Debito</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5"  readonly id="debit_total">  
                </div>  
                <div class="form-group col-sm-4">
                    <label for="credit">Credito</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="credit_total"  readonly >  
                </div> 
                <div class="form-group col-sm-2">
                    <label for="balance">Saldo</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="balance"  readonly >  
                </div>     
                <div class="form-group col-sm-1 text-center"> 
                    <button type="submit" class="btn btn-success pay text-center"  style="height: 100%;max-height:75;  min-height: 100%;    display: flex;align-items: center;" title="AGREGAR ASCIENTO"><i class="fa fa-save" style="font-size:20px"></i></button>
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
<script src="<?php echo base_url() ?>assets/scripts/system/repordaily.js"></script>  
 