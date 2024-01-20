!function($) {
    "use strict";

    var menu_groupForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    menu_groupForm.prototype.validate = function($form, $required)
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

    menu_groupForm.prototype.reload_divisions_employees = function(_division, _employee, _designation, _department = 0)
    {   
        _employee.find('option').remove(); _division.find('option').remove(); _designation.val('');
        console.log(_baseUrl + 'general-services/departmental-requisitions/reload-divisions-employees/' + _department);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/issuance/reload-divisions-employees/' + _department,
            success: function(response) {
                console.log(response.employees);
                _employee.append('<option value="">select a requestor</option>');  
                $.each(response.employees, function(i, item) {
                    _employee.append('<option value="' + item.id + '">' + item.fullname + '</option>');  
                }); 
                console.log(response.divisions);
                _division.append('<option value="">select a division</option>');  
                $.each(response.divisions, function(i, item) {
                    _division.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
                }); 
            },
            async: false
        });
    },

    menu_groupForm.prototype.reload_designation = function(_designation, _employee = 0)
    {   
        _designation.find('option').remove(); 
        console.log(_baseUrl + 'general-services/departmental-requisitions/reload-designation/' + _employee);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/issuance/reload-designation/' + _employee,
            success: function(response) {
                console.log(response.data);
                _designation.append('<option value="">select a designation</option>');  
                _designation.append('<option value="' + response.data.id + '">' + response.data.description + '</option>');  
                _designation.val(response.data.id);
            },
            async: false
        });
    },

    menu_groupForm.prototype.init = function()
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
        | # when department on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="department_id"]', function (event) {
            var _self = $(this);
            $.menu_groupForm.reload_divisions_employees($('select[name="division_id"]'), $('select[name="employee_id"]'), $('select[name="designation_id"]'), _self.val());
        });

        /*
        | ---------------------------------
        | # when requestor on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="employee_id"]', function (event) {
            var _self = $(this);
            $.menu_groupForm.reload_designation($('select[name="designation_id"]'), _self.val());
        });

        /*
        | ---------------------------------
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="name"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _form = _self.closest('form');
            var _text = _self.val();
            _form.find('input[name="code"]').val(_text.replace(' ', '-').toLowerCase());
            _form.find('input[name="slug"]').val(_text.replace(' ', '-').toLowerCase());
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
            var _form   = $('form[name="issuanceApvForm"]');
            var _id     = $.menu_group.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = 0;

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
                                _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        $.menu_group.load_contents();
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                            _form.find('input[name="code"]').addClass('is-invalid').next().text('This is an existing code.');
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

    //init menu_groupForm
    $.menu_groupForm = new menu_groupForm, $.menu_groupForm.Constructor = menu_groupForm

}(window.jQuery),

//initializing menu_groupForm
function($) {
    "use strict";
    $.menu_groupForm.init();
}(window.jQuery);
