<div class="modal form fade" id="e-signature-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bacRFQLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::model($userDetail, array('route' => array('update.signature'), 'method' => 'post', 'enctype' => "multipart/form-data")) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage User E-Signature</h5>
            </div>
            <div class="modal-body">
                <div class="e-signature">
                    <div id="sig" class="mt-2"></div>
                    <textarea id="signature64" name="signature" style="display: none"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-print clear-btn aling-middle d-flex justify-content-center">Clear e-Signature</button>
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn save-btn bg-info align-middle text-white d-flex justify-content-center">Save Changes</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>