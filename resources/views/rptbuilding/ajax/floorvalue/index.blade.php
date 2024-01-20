{{ Form::hidden('id',(isset($propertyStatus->id))?$propertyStatus->id:'', array('id' => 'id')) }}
{{ Form::hidden('property_id',(isset($propertyId))?$propertyId:'', array('id' => 'property_id')) }}
<style>
   .modal-xll {
	max-width: 1350px !important;
    }

    .accordion-button {
        margin-bottom: 12px;
    }

    .form-group {
        margin-bottom: unset;
    }

    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }

    .form-control,
    .custom-select {
        padding-left: 5px;
        font-size: 12px;
    }

    .textright {
        text-align: right;
    }

    .pt10 {
        padding-top: 10px;
    }

    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }

    .row {
        padding-top: 10px;
    }

    .choices__inner {
        min-height: 35px;
        padding: 5px;
        padding-left: 5px;
   }
   .field-requirement-details-status label{margin-top: 7px;}
   #flush-collapsetwo{
   /*        padding-bottom: 80px;*/
   }
/*.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 70%;
  transform: translate(-50%, -50%);
}*/
</style>
<div class="modal-header">
   <h4 class="modal-title">Building Floor Value Computations & Description</h4>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>
<div class="container"></div>
<div class="modal-body">
   <div class="row pt10" >
      <!----  Approval Data Info ------------>
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingfive">
               </h6>
               <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                  <div class="basicinfodiv">
                     <!--------------- Land Apraisal Listing Start Here------------------>
                     <div class="row" style="padding-top: 10px;">
                        <div class="col-sm-6">
                           <!-- <a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalForm" class="btn btn-primary btnPopupOpen" type="add">Add</a> -->
                           <a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalForm" class="btn btn-primary" type="add">Add Floor Value</a>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xl-12">
                           <div class="card">
                              <div class="card-body table-border-style">
                                 <div class="table-responsive" id="floorValueDescription">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal-footer">
   <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
   
</div>
{{Form::close()}}