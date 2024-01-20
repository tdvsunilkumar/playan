<div class="modal form fade" id="employee-modal" tabindex="-1" role="dialog" aria-labelledby="employeeModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'human-resource/employees', 'class'=>'formDtls', 'name' => 'employeeForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-4 pb-4">
                    <h5 class="modal-title full-width c-white" id="employeeModal">
                        Manage Employee
                        <span class="variables"></span>
                    </h5>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save"></i> Save Changes</button>
                </div>
            {{ Form::close() }}

            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body text-white">
                    Hello, world! This is a toast message.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
