<div class="tab-pane fade" id="bidding-details" role="tabpanel" aria-labelledby="bidding-details-tab">    {{ Form::open(array('url' => 'business-online-application', 'class'=>'formDtls', 'name' => 'requisitionForm')) }}
    @csrf
    <h4 class="text-header">Business Information and Registration</h4>
   
    <iframe id="pdf-iframe" src="" width="100%" height="1200px"></iframe>        
    
   
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>