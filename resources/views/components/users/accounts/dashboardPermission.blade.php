<div class="modal form fade" id="user-account-modal-dash" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="userAccountModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'components/users/accounts', 'class'=>'formDtls needs-validation', 'name' => 'userAccountDashForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white pt-2 pb-2" id="userAccountModalDash">
                        Manage User Account
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div id="result-layer-dash" class="hidden">
                        <div id="resultDash">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn-dash btn-primary">Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>