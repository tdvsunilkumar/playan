!function($) {
    "use strict";

    var requisitionForm = function() {
        this.$body = $("body");
    };

    var $required = 0;
    var arrayMeasurePax=[];
    var arrayRequirments=[];
    requisitionForm.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], input[type='number'],input[type='file'],radio, select, textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if($(this).hasClass("required")){
                    if($(this).is("[multiple]")){
                        if( !$(this).val() || $(this).find('option:selected').length <= 0 ){
                            $(this).addClass('is-invalid');
                            $required++;
                        }
                    } else if($(this).val()=="" || $(this).val()=="0"){
                        if(!$(this).is("select")) {
                            $(this).addClass('is-invalid');
                            $required++;
                        } else {
                            $(this).addClass('is-invalid');
                            $required++;                                          
                        }
                        $(this).closest('.form-group').find('.select3-selection--single').addClass('is-invalid');
                    } 
                }
            }
        });

        return $required;
    },
    requisitionForm.prototype.reload_client_det = function(client_id)
    {   
        // if (_purchaseType > 0) {
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-online-application/reload_client_det/' + client_id,
                success: function(response) {
                    console.log(response.data);
                    $('#c_mobile_no').val(response.data.p_mobile_no);
                    $('#c_tel_no').val(response.data.p_telephone_no);
                    $('#c_email_address').val(response.data.p_email_address);
                    if(response.data.gender == 0)
                    {
                        $('#c_gender').val('Female');
                    }
                    else{
                        $('#c_gender').val('Male');
                    }
                },
                async: false
            });

            return true;
        // }
    },
    requisitionForm.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # select, input, and textarea on change or keyup remove error
        | ---------------------------------
        */
        this.$body.on('keyup', 'input, textarea', function (e) {
            e.preventDefault();
            var _self = $(this);
            _self.removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
        });
        this.$body.on('change', 'select, input', function (e) {
            e.preventDefault();
            var _self = $(this);
            _self.removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
            _self.closest(".form-group").find(".is-invalid").removeClass("is-invalid");
        });

        /*
        | ---------------------------------
        | # when add mesure or pax click
        | ---------------------------------
        */
        this.$body.on('click', '#filter_box1', function (e){
            e.preventDefault();
            var _busnId = $.requisition.fetchID();
            var _self = $('#this_is_filter1');
            if (_self.css('display') === 'none') {
                _self.css('display', 'block');
              } else {
                _self.css('display', 'none');
              }
              $('#buspx_no_units').val('');
              $('#buspx_capacity').val('');
              $('#id').val('');
              $('select[name="busn_psic_id"]').val('');
              $('select[name="buspx_charge_id"]').find('option').remove();
              $('select[name="buspx_charge_id"]').val('');
            $.requisitionForm.reload_busn_plan($('select[name="busn_psic_id"]'), _busnId);
        }); 

        /*
        | ---------------------------------
        | # when business plan change in measure or pax 
        | ---------------------------------
        */
        this.$body.on('click', '#bidding-details-tab', function (e){
            e.preventDefault();
            var _self = $(this);
            var busn_id = $.requisition.fetchID();
            var url = _baseUrl +'business-online-application/print-summary/' + busn_id;
            $('#pdf-iframe').attr('src', url);
        });
        this.$body.on('click', '#pr-details-tab', function (e){
            e.preventDefault();
            $("#tab").val(3);
            $.requisition.load_contents();
            $.requisition.load_measure_pax();
            $.requisition.perfect_scrollbar();
            $.requisition.load_requirment_doc();
            // $(".save-btn").addClass('hide');
        });
        this.$body.on('click', '#bidding-details-tab', function (e){
            e.preventDefault();
            $("#tab").val(4);
            // $(".save-btn").addClass('hide');
        });
        this.$body.on('click', '#alob-details-tab', function (e){
            e.preventDefault();
            $("#tab").val(2);
            var rp_code=$("#rp_code").val();
            var floor_val = $('#floor_val_id');
            // if(rp_code != ""){
            //     $.requisitionForm.reload_rpt_info(rp_code);
            //     $.requisitionForm.loadFloorVal(rp_code,floor_val);
            // }
            var ofc_brgy=$("#busn_office_main_barangay_id").val();
            if (ofc_brgy !== null && ofc_brgy !== "") {
                $.ajax({
                    type: "GET",
                    url: _baseUrl + 'business-online-application/checkMuncByBrgy/' + ofc_brgy,
                    success: function(response) {
            
                        if(response.data == 0)
                        {
                            $(".same_as_address").addClass('hidden');
                        }
                        else{
                            $(".same_as_address").removeClass('hidden');
                        }
                    },
                    async: false
                });                
            }
            else{
                $(".same_as_address").addClass('hidden');
            }
            // $(".save-btn").removeClass('hide');
        });
        this.$body.on('click', '#request-details-tab', function (e){
            e.preventDefault();
            $("#tab").val(1);
            // $(".save-btn").removeClass('hide');
        });
       
         /*
        | ---------------------------------
        | # when business application approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.approve-btn', function (e) {
            var _id = $.requisition.fetchID();
            var _url = _baseUrl + 'business-online-application/approve/' + _id;
            var _self = $(this);
            var _modal = _self.closest('.modal');
        
            // Disable the button
            _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right disabled');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: 'Accept the following details?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then(function (t) {
                if (t.value) {
                    $.ajax({
                        type: 'GET',
                        url: _url,
                        success: function (response) {
                            console.log(response);
                            _modal.modal('hide');
                            // $.requisition.load_data();
                        },
                        complete: function () {
                            // Re-enable the button after AJAX response is processed
                            _self.html('Accept').prop('disabled', false).removeClass('disabled');
                            window.onkeydown = null;
                            window.onfocus = null;
                            _modal.modal('hide');
                        }
                    });
                } else {
                    // Re-enable the button if the user cancels the Swal modal
                    _self.html('Accept').prop('disabled', false).removeClass('disabled');
                }
            });
        });

         /*
        | ---------------------------------
        | # when business application decline button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.decline-btn', function (e) {
            var _id     = $.requisition.fetchID();
            var _url    = _baseUrl + 'business-online-application/decline/' + _id;
            var _self   = $(this);
            var _modal  = _self.closest('.modal');

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: 'You want to Decline.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then(function (t) {
                    t.value
                        ? 
                        $.ajax({
                            type: 'GET',
                            url: _url,
                            success: function(response) {
                                console.log(response);
                               
                                _modal.modal('hide');
                                // $.requisition.load_data();
                            },
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        })
                        : "cancel" === t.dismiss 
                });
            
            
        });

        
    }

    //init requisitionForm
    $.requisitionForm = new requisitionForm, $.requisitionForm.Constructor = requisitionForm

}(window.jQuery),

//initializing requisitionForm
function($) {
    "use strict";
    $.requisitionForm.init();
    // $.requisition.preload_select3();
}(window.jQuery);
