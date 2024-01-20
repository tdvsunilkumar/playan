!function($) {
    "use strict";

    var employeeForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _files = []; var _fileName = [];

    employeeForm.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], select, textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if($(this).hasClass("required")){
                    if($(this).is("[multiple]")){
                        if( !$(this).val() || $(this).find('option:selected').length <= 0 ){
                            $(this).addClass('is-invalid');
                            $(this).closest('.form-group').find('.select3-selection--multiple').addClass('is-invalid');
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
                        $(this).closest('.form-group').find('.bootstrap-select').addClass('is-invalid');
                        $(this).closest('.form-group').find('.select3-selection--single').addClass('is-invalid');
                    } 
                }
            }
        });

        return $required;
    },

    employeeForm.prototype.validateScript = function ($form, $required)
    {
        $required = 0;

        $.each($form.find("input[type='text'], textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if ($(this).val().includes('<script>')) {
                    $(this).addClass('is-invalid');
                    $required++;
                }
            }
        });

        return $required;
    },

    employeeForm.prototype.readURL = function(input) {
        if (input.files && input.files[0]) {
            var self = input.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            console.log(self);
        }
    }

    employeeForm.prototype.validateFile = function (_file)
    {   
        var _error = 0;
        var ext = _file.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["jpg" , "jpeg", "png", "bmp", "gif", "pdf"];

        if (arrayExtensions.lastIndexOf(ext) == -1) {
            _error = 1;
        }

        return _error;
    },

    employeeForm.prototype.prepareUpload = function(input)
    {       
        // var self = event.target;
        if (input.files[0] != '' && input.files[0] !== undefined) {
            var found = false;
            for (var i = 0; i < _fileName.length; i++) {
                if (_fileName[i] == input.name) {
                    found = true;
                    break; break;
                }
            }

            if (found == true) {
                _files[i] = input.files[0];
            } else {
                _fileName.push(input.name);
                _files.push(input.files[0]);
            }
        } else {
            $.each(_fileName, function (ix) {
                if (_fileName[ix] == input.name) {
                    _fileName.splice(ix, 1);
                    _files.splice(ix, 1);
                    console.log(self);
                    return false;
                }
            });
        }

        console.log(_fileName);
        console.log(_files);
    } 

    employeeForm.prototype.do_uploads = function(_id) {
        var data = new FormData();
        $.each(_files, function(key, value)
        {   
            data.append(key, value);
        }); 
        console.log(data);
        var datas = [];
        $.ajax({
            type: "POST",
            url: _baseUrl + 'human-resource/employees/upload/' + _id + '?category=employees',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            async: false,
            success: function (data) {
                console.log(data); 
                datas = data;
                // datas = $.parseJSON( data );
            }
        });

        return datas;
    },

    employeeForm.prototype.init = function()
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
        // $('input[type="file"]').on('change', $.employeeForm.prepareUpload(this));  

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
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="employeeForm"]');
            var _id     = _form.find('[name="id"]').val();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?address=' + _form.find("#barangay_id option:selected").text() : _form.attr('action') + '/store?address=' + _form.find("#barangay_id option:selected").text();
            var _error  = $.employeeForm.validate(_form, 0);
            var _error2 = $.employeeForm.validateScript(_form, 0);

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
            } else if (_error2 != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>You cannot submit a value that has script tag.",
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
                                        $.employee.load_contents(1);
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

        /*
        | ---------------------------------
        | # when departmental restriction is change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="is_dept_restricted"]', function (e) {
            var _self = $(this);
            var _depAccess = $('#departmental_access');
            if (_self.find('option:selected').text() == 'No') {
                _depAccess.addClass('required').prop('disabled', false).closest('.form-group').addClass('required');
            } else {
                _depAccess.removeClass('required').prop('disabled', true).closest('.form-group').removeClass('required').find('.select3-selection--multiple').removeClass('is-invalid');
            }
            $.employee.required_fields();
        });

        /*
        | ---------------------------------
        | # when input file on change
        | ---------------------------------
        */ 
        this.$body.on('change', '#employee-modal .custom-file-input', function (event) {
            var fileName = $(this).val();
            $.employeeForm.readURL(this);
            $.employeeForm.prepareUpload(this);
            $(this).next('.custom-file-label').removeClass('is-invalid').addClass("selected").html(fileName);
        }); 

        /*
        | ---------------------------------
        | # when input file on change
        | ---------------------------------
        */
        this.$body.on('click', '#employee-modal #hr-upload-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _modal = _self.closest('.modal');
            var _file  = _modal.find('input[name="attachment"]');
            var _fError  = $.employeeForm.validateFile(_file.val());
            var _validExtensions = ["jpg","pdf","jpeg","gif","png","bmp"];
            var _toast = $('#modalToast');
            var _empID = $.employee.fetchID();

            if (_file[0].files.length == 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill add some attachment first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else if (_fError > 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to use this file format!<br/>Only formats are allowed: " + _validExtensions.join(', ') + ".",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                _self.prop('disabled', true).html('wait.....');
                var d1 = $.employeeForm.do_uploads(_empID);
                $.when( d1 ).done(function ( response ) 
                {   
                    if (response.status == 'success') {
                        _self.prop('disabled', true).html('wait.....');
                        _modal.find('.store-btn').addClass('hidden');
                        setTimeout(function () {
                            _toast.find('.toast-body').html(response.text);
                            _toast.show();
                            $.employee.load_file_contents();
                        }, 500 + 300 * (Math.random() * 5));
                        setTimeout(function () {
                            _files = [];
                            _modal.find('input[type="file"]').val('');
                            _modal.find('.custom-file label').text('').removeClass('selected');
                            _self.prop('disabled', false).html('Upload Now');
                            _toast.hide();
                        }, 3000);
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            html: "Invalid Request!",
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
        | # when download button is click
        | ---------------------------------
        */
        this.$body.on('click', '#hrUploadTable .download-btn', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _row   = _self.closest('tr');
            var _file  = _row.attr('data-row-file');
            var _empID = $.employee.fetchID();
            var _url   = _baseUrl + 'human-resource/employees/download/' + _empID + '?category=employees&file=' + _file;
            window.open(_url, '_blank');
        }); 

        /*
        | ---------------------------------
        | # when delete button is click
        | ---------------------------------
        */
        // this.$body.on('click', '#hrUploadTable .remove-btn', function (e) {
        //     e.preventDefault();
        //     var _self  = $(this);
        //     var _row   = _self.closest('tr');
        //     var _id    = _row.attr('data-row-id');
        //     var _file  = _row.attr('data-row-file');
        //     var _empID = $.employee.fetchID();
        //     var _url   = _baseUrl + 'human-resource/employees/delete/' + _empID + '?category=employees&id=' + _id + '&file=' + _file;
   
        //     console.log(_url);
        //     Swal.fire({
        //         html: "Are you sure? <br/>the file ("+ _file +") will be deleted.",
        //         icon: "warning",
        //         showCancelButton: !0,
        //         buttonsStyling: !1,
        //         confirmButtonText: "Yes, delete it!",
        //         cancelButtonText: "No, return",
        //         customClass: { confirmButton: "btn btn-danger", 
        //         cancelButton: "btn btn-active-light" },
        //     }).then(function (t) {
        //         t.value
        //             ? 
        //             $.ajax({
        //                 type: 'DELETE',
        //                 url: _url,
        //                 success: function(response) {
        //                     console.log(response);
        //                     Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
        //                         function (e) {
        //                             e.isConfirmed && ((t.disabled = !1));
        //                             $.employee.load_file_contents();
        //                         }
        //                     );
        //                 },
        //                 complete: function() {
        //                     window.onkeydown = null;
        //                     window.onfocus = null;
        //                 }
        //             })
        //             : "cancel" === t.dismiss 
        //     });
        // }); 
    }

    //init employeeForm
    $.employeeForm = new employeeForm, $.employeeForm.Constructor = employeeForm

}(window.jQuery),

//initializing employeeForm
function($) {
    "use strict";
    $.employeeForm.init();
}(window.jQuery);