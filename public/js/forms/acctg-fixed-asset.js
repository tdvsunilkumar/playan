!function($) {
    "use strict";

    var fixed_assetForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    fixed_assetForm.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], select, textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if($(this).hasClass("required")){
                    if($(this).is("[multiple]")){
                        if( !$(this).val() || $(this).find('option:selected').length <= 0 ){
                            $(this).addClass('is-invalid');
                            $required++;
                        }
                    } else if($(this).val()==""){
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

    fixed_assetForm.prototype.init = function()
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

        this.$body.on('change', 'select[name="gl_account_id"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            $.fixed_asset.reload_items_via_gl(_self.val());
        });

        /*
        | ---------------------------------
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('click', 'input[name="is_depreciative"]', function (e) {
            var _self = $(this);            
            if(_self.is(':checked')) {
                $('.depreciation').prop('disabled', false);
                $('.depreciation').closest('.form-group').addClass('required');
            } else {
                $('.depreciation').prop('disabled', true);
                $('.depreciation').closest('.form-group').removeClass('required');
            }
            $.fixed_asset.required_fields();
        });

        /*
        | ---------------------------------
        | # when salvage value onChange
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="salvage_value"]', function (e) {
            var _salvage = $(this);
            var _monthly = $('input[name="monthly_depreciation"]');
            var _unit = $('input[name="unit_cost"]');
            var _lifeSpan = $('select[name="estimated_life_span"]');
            var _total = parseFloat(_unit.val()) -  parseFloat( parseFloat(_unit.val()) * parseFloat(parseFloat(_salvage.val()) / 100) );
            if (_salvage.val() > 0 && _lifeSpan.val() > 0) {   
                _monthly.val( parseFloat(_total)  / parseFloat(_lifeSpan.val()) );
            } else {
                _monthly.val('');
            }
        });

        /*
        | ---------------------------------
        | # when lifespan on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="estimated_life_span"]', function (e) {
            var _monthly = $('input[name="monthly_depreciation"]');
            var _unit = $('input[name="unit_cost"]');
            var _lifeSpan = $(this);
            var _salvage = $('select[name="salvage_value"]');
            var _total = parseFloat(_unit.val()) -  parseFloat( parseFloat(_unit.val()) * parseFloat(parseFloat(_salvage.val()) / 100) );
            if (_salvage.val() > 0 && _lifeSpan.val() > 0) {            
                _monthly.val( parseFloat(_total)  / parseFloat(_lifeSpan.val()) );
            } else {
                _monthly.val('');
            }
        });

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="fixedAssetForm"]');
            var _id     = $.fixed_asset.fetchID();
            var _depreciate = (_form.find('input[name="is_depreciative"]:checked').length > 0) ? 1 : 0;
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?is_depreciative=' + _depreciate + '&monthly_depreciation=' + _form.find('input[name="monthly_depreciation"]').val() : _form.attr('action') + '/store?is_depreciative=' + _depreciate + '&monthly_depreciation=' + _form.find('input[name="monthly_depreciation"]').val();
            var _error  = $.fixed_assetForm.validate(_form, 0);

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
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _method,
                    url: _action,
                    data: _form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        $.fixed_asset.load_contents();
                                        if (_modal.find('input[name="fixed_asset_no"]').val() == '') {
                                            _modal.find('input[name="fixed_asset_no"]').val(response.fixed_asset_no);
                                        }
                                        if (!(_id > 0)) {
                                            _modal.modal('hide');
                                        }
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                            _form.find('input[name="' + response.column + '"]').addClass('is-invalid').next().text(response.label);
                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                function (e) {
                                }
                            );
                        }
                    }, 
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            }
        });
    }

    //init fixed_assetForm
    $.fixed_assetForm = new fixed_assetForm, $.fixed_assetForm.Constructor = fixed_assetForm

}(window.jQuery),

//initializing fixed_assetForm
function($) {
    "use strict";
    $.fixed_assetForm.init();
}(window.jQuery);
