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
    requisitionForm.prototype.validate_min = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], input[type='number'],input[type='file'],radio, select, textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if($(this).hasClass("min")){
                    if($(this).val()=="" || $(this).val() < "0"){
                            $(this).addClass('is-invalid');
                            $required++;                                          
                    } 
                }
                
            }
        });

        return $required;
    },

    requisitionForm.prototype.reload_busn_plan = function(busn_psic_id,_busnId)
    {   
        // if (_purchaseType > 0) {
            busn_psic_id.find('option').remove();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-permit/application/reload-busn-plan/' + _busnId,
                success: function(response) {
                    console.log(response.data);
                    busn_psic_id.append('<option value="">select a Business Plan</option>');  
                    $.each(response.data, function(i, item) {
                        busn_psic_id.append('<option value="' + item.ID + '">' + item.subclass_code + "-" + item.subclass_description + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },
    requisitionForm.prototype.reload_subclass = function(sub_class)
    {   
        // if (_purchaseType > 0) {
            sub_class.find('option').remove();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-permit/application/reload-sub-class',
                success: function(response) {
                    console.log(response.data);
                    sub_class.append('<option value="">select a Business Plan</option>');  
                    $.each(response.data, function(i, item) {
                        sub_class.append('<option value="' + item.id + '">' + item.subclass_code + "-" + item.subclass_description + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },
    

    requisitionForm.prototype.reload_measure_pax = function(buspx_charge_id,_busnPlanId)
    {   
        // if (_purchaseType > 0) {
            buspx_charge_id.find('option').remove();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-permit/application/reload-measure-pax/' + _busnPlanId,
                success: function(response) {
                    console.log(response.data);
                    arrayMeasurePax=response.data;
                    buspx_charge_id.append('<option value="">select a measure or pax</option>');  
                    $.each(response.data, function(i, item) {
                        console.log(item)
                        buspx_charge_id.append('<option value="' + item.charge_id + '">' + item.charge_desc + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    requisitionForm.prototype.reload_client_det = function(client_id)
    {   
        // if (_purchaseType > 0) {
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-permit/application/reload_client_det/' + client_id,
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

    requisitionForm.prototype.reload_rpt_info = function(rp_code)
    {   
        // if (_purchaseType > 0) {
            var year = $('#busn_tax_year').val();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-permit/application/reload_rpt_info/' + rp_code + '/' + year,
                success: function(response) {
                    console.log(response);
                    var ownerFirstName = response.data.rpo_first_name || "";
                    var ownerMiddleName = response.data.rpo_middle_name || "";
                    var ownerLastName = response.data.rpo_custom_last_name || "";
                    var sufix = response.data.sufix || "";
                    var ownerFullName = "";
                    if (ownerFirstName) {
                     ownerFullName = ownerFirstName;
                    }
                
                    if (ownerMiddleName) {
                        ownerFullName += ' ' + ownerMiddleName;
                    }

                    if (ownerLastName) {
                        ownerFullName += ' ' + ownerLastName;
                    }
                
                    if (sufix) {
                        ownerFullName += ', ' + sufix;
                    }

                    $('#busn_bldg_property_index_no').val(response.data.rp_pin_declaration_no);
                    $('#busn_bldg_tax_declaration_no').val(response.data.rp_tax_declaration_no);
                    $('#rp_property_code').val(response.data.rp_property_code);
                    $('#buld_tax_status').val(response.data.pay_status);
                    $('#buld_owner').val(response.data.building_own_name);
                    $('#busn_office_building_name').val(response.data.building_name);
                },
                async: false
            });

            return true;
        // }
    },

    requisitionForm.prototype.loadFloorVal = function(rp_code,floor_val)
    {   
        // if (_purchaseType > 0) {
            floor_val.find('option').remove();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-permit/application/load_floor_val/' + rp_code,
                success: function(response) {
                    console.log(response.data);
                  

                    $.each(response.data, function(i, item) {
                        console.log(item)
                        var a = [4, 5];
                       
                        floor_val.append('<option value="' + item.id + '">' + item.rpbfv_floor_no + '</option>');  
                        // floor_val.val(a).trigger('change.select3');
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    requisitionForm.prototype.reload_requirment = function(req_code,_busnPlanId)
    {   
        // if (_purchaseType > 0) {
            req_code.find('option').remove();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-permit/application/reload-requirments/' + _busnPlanId,
                success: function(response) {
                    console.log(response.data);
                    arrayRequirments=response.data;
                    req_code.append('<option value="">select requirments</option>');  
                    $.each(response.data, function(i, item) {
                        console.log(item)
                        req_code.append('<option value="' + item.req_rel_id + '">' + item.req_description + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    requisitionForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'business-permit/application/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'business-permit/application/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        if(_status == 0)
        {
            return _status = 'draft';
        }
        return _status;
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
        | # keypress numeric only
        | ---------------------------------
        */
        this.$body.on('keypress', '.numeric-only', function (event) {
            var charCode = (event.which) ? event.which : event.keyCode    
    
            if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
                return false;             
            }
        });

         /*
        | ---------------------------------
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('keypress', '.numeric-double', function (event) {
            var $this = $(this);
            if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
                ((event.which < 48 || event.which > 57) &&
                    (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }
    
            var text = $(this).val();
            if ((event.which == 46) && (text.indexOf('.') == -1)) {
                setTimeout(function () {
                    if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                        $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                    }
                }, 1);
            }
    
            if ((text.indexOf('.') != -1) &&
                (text.substring(text.indexOf('.')).length > 2) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 2)) {
                event.preventDefault();
            }
        });
        /*
        | ---------------------------------
        | # when add business plan click
        | ---------------------------------
        */
        this.$body.on('click', '#add_bsn_plan', function (e){
            e.preventDefault();
            var _busnId = $.requisition.fetchID();
            var _self = $('#bsn_plan_div');
            if (_self.css('display') === 'none') {
                _self.css('display', 'block');
              } else {
                _self.css('display', 'none');
              }
              $('select[name="subclass_id"]').val('');
              $('#plan_id').val('');
              $('#busp_no_units').val('');
              $('#busp_capital_investment').val('');
              $('#busp_essential').val('');
              $('#busp_non_essential').val('');
              select3Ajax("subclass_id","div_psic_subclass","getPsicSubclass");
            //   $.requisitionForm.reload_subclass($('select[name="subclass_id"]'));
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
        | # when add mesure or pax click
        | ---------------------------------
        */
        this.$body.on('click', '.edit-btn-measure', function (e){
            e.preventDefault();
            var _busnId = $.requisition.fetchID();
            console.log('hee');
            $.requisitionForm.reload_busn_plan($('select[name="busn_psic_id"]'), _busnId);
        });

         /*
        | ---------------------------------
        | # when add mesure or pax click
        | ---------------------------------
        */
        this.$body.on('click', '#add-doc', function (e){
            e.preventDefault();
            var _busnId =  $.requisition.fetchID();
            console.log('hee');
            $('select[name="busn_psic_id"]').val('');
            $.requisitionForm.reload_busn_plan($('select[name="busn_psic_id"]'), _busnId);
            $.requisitionForm.reload_requirment($('select[name="req_rel_id"]'), $('select[name="busn_psic_id"]').val());
            $('select[name="req_rel_id"] option[value="optionValue"]').remove();
            $('#attachment').val('');
            $('select[name="req_rel_id"]').val('');
        });

        /*
        | ---------------------------------
        | # when business plan change in measure or pax 
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="busn_psic_id"]', function (event) {
            var _self = $(this);
            $.requisitionForm.reload_measure_pax($('select[name="buspx_charge_id"]'), _self.val());
        });
        /*
        | ---------------------------------
        | # when taxpayer name change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="client_id"]', function (event) {
            var _self = $(this);
            $.requisitionForm.reload_client_det(_self.val());
        });
         /*
        | ---------------------------------
        | # when Tax Declaration No change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="rp_code"]', function (event) {
            var _self = $(this);
            var floor_val = $('#floor_val_id');
            $.requisitionForm.reload_rpt_info(_self.val());
            $.requisition.getgeolocations(_self.val());
            $.requisitionForm.loadFloorVal(_self.val(),floor_val);
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
            var url = _baseUrl +'business-permit/application/print-summary/' + busn_id;
            $('#pdf-iframe').attr('src', url);
        });

        /*
        | ---------------------------------
        | #  when business plan change in doc
        | ---------------------------------
        */
        this.$body.on('change', '#busn_psic_id_req', function (event) {
            var _self = $(this);
            $.requisitionForm.reload_requirment($('select[name="req_rel_id"]'), _self.val());
        });


        


        /*
        | ---------------------------------
        | # when business operation submit btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.save-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="appDetailsForm"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _lineId = $.requisition.fetchLineID();
            var _form1 = $('form[name="requisitionForm"]');
            var _form2 = $('form[name="busnOptForm"]');
            var isCheckValue = $('#busn_office_is_same_as_main').prop('checked');
            if (isCheckValue) {
                var busn_office_is_same_as_main=1;
            } else {
                var busn_office_is_same_as_main=0;
            }

            var enYesChecked = $('#yes_radio_en').prop('checked');
            var enNoChecked = $('#no_radio_en').prop('checked');
            if (enYesChecked) {
               var en_radio=1;
            } else if (enNoChecked) {
                var en_radio=0;
            } 

            var yesChecked = $('#yes_radio').prop('checked');
            var noChecked = $('#no_radio').prop('checked');
            if (yesChecked) {
               var radio=1;
            } else if (noChecked) {
                var radio=0;
            } 


            
            var _method = (_lineId > 0) ? 'PUT' : (_id > 0) ? 'PUT' : 'POST';
            var _action = (_lineId > 0) ? _form.attr('action') + '/update-line/' + _lineId + '?busn_status=0' : (_id > 0) ? _form.attr('action') + '/update/' + _id + '?is_check=' + busn_office_is_same_as_main + '&radio=' + radio + '&en_radio=' + en_radio + '&busn_status=0'  : _form.attr('action') + '/store?is_check=' + busn_office_is_same_as_main + '&radio=' + radio + '&en_radio=' + en_radio + '&busn_status=0';

            var _tab= $("#tab").val();
            // Serialize the form data
            var formData = _form.serialize();
            var formData1 = _form1.serialize();
            var formData2 = _form2.serialize();
            if (_tab == 1) {
                var _error1  = $.requisitionForm.validate(_form1, 0);
                var _error2 = 0;
                 // Combine the form data
                var combinedData = formData + '&' + formData1 ;
              } else {
                var _error2  = $.requisitionForm.validate(_form2, 0);
                var _error1 = 0;
                var combinedData = formData + '&' + formData2 ;
              }
            var tinInput = $('#busn_tin_no').val();
            var pattern = new RegExp($('#busn_tin_no').attr('pattern'));
            if (_error1 != 0 || _error2 != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else if (_tab == 1 && (!pattern.test(tinInput) || tinInput.length !== 15)) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Invalid TIN format. Please enter in the format 000-000-000-000.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null; 
            } else {
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                var d1 = $.requisitionForm.fetch_status(_id);
               
                $.when( d1 ).done(function ( v1 ) 
                {   
                    if (v1 == 'draft') {
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: combinedData,
                            success: function(response) {
                                $.requisition.updateID(response.data.id);
                                console.log($.requisition.fetchID());
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    $("#pr-details-tab").removeClass('disabled')
                                );
                                $.requisition.load_contents();
                                $.requisition.load_measure_pax();
                                $.requisition.load_requirment_doc();
                                $.requisition.updateRemortBplo(response.data.id);
                                $("#bidding-details-tab").removeClass('disabled');
                                $(".submit-btn").removeClass('hide');
                                _self.html('Save Draft').prop('disabled', false);
                            }, 
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        });
                    } else {
                        _self.html('Add Item').prop('disabled', false);
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The request is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
                });
            }
        });

        this.$body.on('click', '#pr-details-tab', function (e){
            e.preventDefault();
            $("#tab").val(3);
            $(".save-btn").addClass('hide');
        });
        this.$body.on('click', '#bidding-details-tab', function (e){
            e.preventDefault();
            $("#tab").val(4);
            $(".save-btn").addClass('hide');
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
                    url: _baseUrl + 'business-permit/application/checkMuncByBrgy/' + ofc_brgy,
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
            $(".save-btn").removeClass('hide');
        });
        
        this.$body.on('click', '#request-details-tab', function (e){
            e.preventDefault();
            $("#tab").val(1);
            $(".save-btn").removeClass('hide');
        });
        this.$body.on('click', '#btnOrderofPayment', function (e){
            e.preventDefault();
             $("#tab").val(2);
             $("#orderofpaymentModal").modal('show');
        });
        this.$body.on('click', '#closeOrderModalnew', function (e){
            e.preventDefault();
             $("#orderofpaymentModal").modal('hide');
             $("#tab").val(2);
             // alert('here');
        });
        this.$body.on('input', '#busn_bldg_area', function (e){
            e.preventDefault();
            console.log('input');
            var inputValue = $(this).val();
            var decimalRegex = /^\d+(\.\d{0,3})?$/;
    
            if (!decimalRegex.test(inputValue)) {
                // Remove any characters after the third decimal place
                var formattedValue = inputValue.match(/^\d+(\.\d{0,3})?/);
                if (formattedValue) {
                    $(this).val(formattedValue[0]);
                } else {
                    $(this).val('');
                }
            }
        });

        this.$body.on('input', '#busn_bldg_total_floor_area', function (e){
            e.preventDefault();
            console.log('input');
            var inputValue = $(this).val();
            var decimalRegex = /^\d+(\.\d{0,3})?$/;
    
            if (!decimalRegex.test(inputValue)) {
                // Remove any characters after the third decimal place
                var formattedValue = inputValue.match(/^\d+(\.\d{0,3})?/);
                if (formattedValue) {
                    $(this).val(formattedValue[0]);
                } else {
                    $(this).val('');
                }
            }
        });

        /*
        | ---------------------------------
        | # when business operation submit btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="appDetailsForm"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _lineId = $.requisition.fetchLineID();
            var isCheckValue = $('#busn_office_is_same_as_main').prop('checked');
            if (isCheckValue) {
                var busn_office_is_same_as_main=1;
            } else {
                var busn_office_is_same_as_main=0;
            }

            var enYesChecked = $('#yes_radio_en').prop('checked');
            var enNoChecked = $('#no_radio_en').prop('checked');
            if (enYesChecked) {
               var en_radio=1;
            } else if (enNoChecked) {
                var en_radio=0;
            } 

            var yesChecked = $('#yes_radio').prop('checked');
            var noChecked = $('#no_radio').prop('checked');
            if (yesChecked) {
               var radio=1;
            } else if (noChecked) {
                var radio=0;
            } 
            var _method = (_lineId > 0) ? 'PUT' : (_id > 0) ? 'PUT' : 'POST';
            var _action = (_lineId > 0) ? _form.attr('action') + '/update-line/' + _lineId + '?busn_status=1' : (_id > 0) ? _form.attr('action') + '/update/' + _id + '?is_check=' + busn_office_is_same_as_main + '&radio=' + radio + '&en_radio=' + en_radio + '&busn_status=1'  : _form.attr('action') + '/store?is_check=' + busn_office_is_same_as_main + '&radio=' + radio + '&en_radio=' + en_radio + '&busn_status=1';
            var _form1 = $('form[name="requisitionForm"]');
            var _form2 = $('form[name="busnOptForm"]');
            var _error1  = $.requisitionForm.validate(_form1, 0);
            var _error2  = $.requisitionForm.validate(_form2, 0);
                        // Serialize the form data
            var formData = _form.serialize();
            var formData1 = _form1.serialize();
            var formData2 = _form2.serialize();
            var tinInput = $('#busn_tin_no').val();
            var pattern = new RegExp($('#busn_tin_no').attr('pattern'));
            // Combine the form data
            var combinedData = formData + '&' + formData1 + '&' + formData2 ;

            if (_error1 != 0 || _error2 != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else if (!pattern.test(tinInput) || tinInput.length !== 15) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Invalid TIN format. Please enter in the format 000-000-000-000.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null; 
            }  else {
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                var d1 = $.requisitionForm.fetch_status(_id);
                console.log(d1)
                $.when( d1 ).done(function ( v1 ) 
                {   
                    if (v1 == 'draft') {
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: combinedData,
                            success: function(response) {
                                console.log(response);
                                $.requisition.updateID(response.data.id);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    $("#pr-details-tab").removeClass('disabled')
                                );
                                $.requisition.load_contents();
                                $.requisition.load_measure_pax();
                                $.requisition.load_requirment_doc();
                                $.requisition.updateRemortBplo(response.data.id);
                                $("#bidding-details-tab").removeClass('disabled');
                                $(".submit-btn").removeClass('hide');
                                _self.html('Submit').prop('disabled', false);
                            }, 
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        });
                    } else {
                        _self.html('Add Item').prop('disabled', false);
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The request is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
                });
            }
        });

        

        


         /*
        | ---------------------------------
        | # when add business plan submit btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn-add-busn-plan', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="addBusinessPlan"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _method = 'POST';
            var _action = _form.attr('action') + '/add-business-plan?busn_id=' + _id;
            var _error  = $.requisitionForm.validate(_form, 0);
            var _error_min  = $.requisitionForm.validate_min(_form, 0);

            

            if (_error != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            }else if(_error_min != 0){
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please enter a value greater than or equal to 0.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                var d1 = $.requisitionForm.fetch_status(_id);
                console.log(d1)
                $.when( d1 ).done(function ( v1 ) 
                {   
                    if (v1 == 'draft') {
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response.data);
                                $.requisition.load_contents();
                                $.requisition.load_requirment_doc();
                                $.requisitionForm.reload_busn_plan($('select[name="busn_psic_id"]'), _id);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    $.requisition.load_measure_pax()
                                );
                                $("#bsn_plan_div").slideToggle(300);
                                $("#add_bsn_plan").toggleClass('active');
                                $('select[name="subclass_id"]').val('8');
                                $('#plan_id').val('');
                                $('#busp_no_units').val('');
                                $('#busp_capital_investment').val('');
                                $('#busp_essential').val('');
                                $('#busp_non_essential').val('');
                                $.requisition.updateRemortBploBusnPlan(response.data);
                                _self.html('Add').prop('disabled', false);
                            }, 
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        });
                    } else {
                        _self.html('Add').prop('disabled', false);
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The request is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
                });
            }
        });

         /*
        | ---------------------------------
        | # when add measure pax submit btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn-add-measure', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="addMeasurePax"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _method = 'POST';
            var buspx_charge_id=$("#buspx_charge_id");

            // let index = arrayMeasurePax.findIndex(x => x.charge_id === buspx_charge_id.val());
            // if(index>=0){
            // var tfoc_id = arrayMeasurePax[index].tfoc_id;
            // }
         
            var _error  = $.requisitionForm.validate(_form, 0);
            if (_error != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                var tfoc_id = arrayMeasurePax[buspx_charge_id.val()]['tfoc_id'];
                var _action = _form.attr('action') + '/add-measure-pax?busn_id=' + _id + '&tfoc_id=' + tfoc_id;
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                var d1 = $.requisitionForm.fetch_status(_id);
                console.log(d1)
                $.when( d1 ).done(function ( v1 ) 
                {   
                    if (v1 == 'draft') {
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                $.requisition.load_contents();
                                $.requisition.load_requirment_doc();
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    $.requisition.load_measure_pax()
                                );
                                $("#this_is_filter1").slideToggle(300);
                                $("#filter_box1").toggleClass('active');
                                _self.html('Add').prop('disabled', false);
                                $('#buspx_no_units').val('');
                                $('#buspx_capacity').val('');
                                $('#id').val('');
                                $('select[name="busn_psic_id"]').val('');
                                $('select[name="buspx_charge_id"] option[value="optionValue"]').remove();
                                $.requisition.updateRemortBploMeasurePax(response.data.busn_id,response.data.busn_psic_id,response.data.buspx_charge_id);
                            }, 
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        });
                    } else {
                        _self.html('Add Item').prop('disabled', false);
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The request is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
                });
            }
        });


         /*
        | ---------------------------------
        | # when add doc btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn-add-doc', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="addReqDoc"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _method = 'POST';
            var req_rel_id=$("#req_rel_id");
            var req_code = arrayRequirments[req_rel_id.val()]['req_code'];
            var br_code = arrayRequirments[req_rel_id.val()]['br_code'];
            var _action = _form.attr('action') + '/add-requirment-doc?busn_id=' + _id + '&req_code=' + req_code + '&br_code=' + br_code;
            var _error  = $.requisitionForm.validate(_form, 0);
        
            if (_error != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;
            } else {
                console.log(req_code);
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                var d1 = $.requisitionForm.fetch_status(_id);
        
                $.when(d1).done(function (v1) {   
                    if (v1 == 'draft') {
                        var formData = new FormData(_form[0]);
                        formData.append('busn_id', _id);
                        formData.append('req_code', req_code);
                        formData.append('br_code', br_code);
                        
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: formData,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                console.log(response);
                                $.requisition.load_contents();
                                $.requisition.load_requirment_doc();
                                Swal.fire({
                                    title: response.title,
                                    text: response.text,
                                    icon: response.type,
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" }
                                }).then($.requisition.load_measure_pax());
                                $("#doc-form").slideToggle(300);
                                $("#add-doc").toggleClass('active');
                                $.requisition.updateRemortBploReqDoc(response.data.busn_id,response.data.busn_psic_id,response.data.req_code);
                                _self.html('Add').prop('disabled', false);
                            }, 
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        });
                    } else {
                        _self.html('Add Item').prop('disabled', false);
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The request is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
                });
            }
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
