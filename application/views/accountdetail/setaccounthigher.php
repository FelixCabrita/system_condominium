<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0); ">
            <div class="col-sm-12">
                <h4>Cuentas detalle de la cuenta mayor {<?php echo $accounthigherInfo->id. ' - ' .$accounthigherInfo->name?>}</h4>
            </div> 
        </div>
        <hr>  
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
        <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_cuenta_detalle" method="post" role="form"> 
            <div class="row"> 
                <div class="form-group col-sm-3">
                    <label for="apartment">Apartamento</label> 
                    <select name="apartment" id="apartment" class="form-control">  
                        <option value ="" >Ninguno</option>
                        <?php  if(!empty($apartmentRecords)) { foreach($apartmentRecords as $record) {  ?>
                            <option value ="<?php echo $record->id ?>" data-edifice="<?php echo  $record->code_edifice ?>" data-apartment="<?php echo  $record->apartment ?>"><?php echo $record->code  ?></option>
                        <?php }} ?> 
                    </select>
                </div>  
                <div class="form-group col-sm-4">
                    <label for="code">Codigo</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="code"  name="code" value="<?php echo set_value('code'); ?>"   maxlength="10" > 
                    <input type="hidden" value="<?php echo $accounthigherInfo->id; ?>" name="accounthigher" id="accounthigher" />    
                    <input type="hidden" value="<?php echo 1; ?>" name="view" id="view" />    
                    <input type="hidden" value="<?php echo $accounthigherInfo->code; ?>" name="code_accounthigher" id="code_accounthigher" />    
                    <?php echo validation_error('code');?> 
                </div>   
                <div class="form-group col-sm-3">
                    <label for="name">Nombre de la cuenta</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="200" > 
                    <?php echo validation_error('name');?> 
                </div>    
                <div class="form-group col-sm-2 text-center"> 
                    <button type="submit" class="btn btn-sm btn-info"  style="height: 100%;max-height:75;   min-height: 100%;    display: flex;align-items: center;" title="AGREGAR CUENTA"><i class="fa fa-check"></i>&nbsp; AGREGAR CUENTA</button>
                </div>   
            </div>   
        </form>
            <hr>    
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">CODIGO</th>
                        <th class="text-center">DESCRIPCION</th>  
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ESTADO</th> 
                        
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($accountdetailRecords))
                    {
                        foreach($accountdetailRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->name ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status==STATUS_ACTIVE){
                            echo '<span class="badge badge-success">ACTIVO</span>';
                        }else if($record->status==STATUS_INACTIVE){
                            echo '<span class="badge badge-danger">INACTIVO</span>';
                        } ?></td>
                        
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">CODIGO</th>
                        <th class="text-center">DESCRIPCION</th>  
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ESTADO</th>  
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
    
</div>    
<script src="<?php echo base_url() ?>assets/scripts/bootstrap4-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/bootstrap-notify-init.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables.init.js"></script>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addAccountDetail.js" type="text/javascript"></script>  

