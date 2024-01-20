!function($) {
    "use strict";

    var faqForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _rows = ''; var files = []; var filesName = []; 

    faqForm.prototype.validate = function($form, $required)
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

    faqForm.prototype.fetchRows = function()
    {   
        _rows = '<div class="element">';
        _rows += '<hr/>';
        _rows += '<input name="id[]" class="hidden" type="text"/>';
        _rows += '<h5 class="numbering"></h5>';
        _rows += '<div class="row">';
        _rows += '<div class="col-sm-12">';
        _rows += '<div class="form-group m-form__group required">';
        _rows += '<label for="header" class="required fs-6 fw-bold">Header<span class="ms-1 text-danger">*</span></label>';
        _rows += '<textarea id="header" class="form-control form-control-solid required" rows="2" name="header[]"></textarea>';
        _rows += '<span class="m-form__help text-danger"></span>';
        _rows += '</div>';
        _rows += '</div>';
        _rows += '<div class="col-sm-12">';
        _rows += '<div class="form-group m-form__group mb-0">';
        _rows += '<label for="content" class="required fs-6 fw-bold">Content</label>';
        _rows += '<textarea id="content" class="form-control form-control-solid" rows="3" name="content[]" cols="50"></textarea>';
        _rows += '<span class="m-form__help text-danger"></span>';
        _rows += '</div>';
        _rows += '</div>';
        _rows += '<div class="col-sm-12">';
        _rows += '<div class="form-group m-form__group">';
        _rows += '<label for="exampleInputEmail1">';
        _rows += 'File Attachment';
        _rows += '</label>';
        _rows += '<div></div>';
        _rows += '<div class="custom-file">';
        _rows += '<input type="text" name="file[]" class="hidden"/>';
        _rows += '<input type="file" class="custom-file-input" id="customFile" name="attachment[]" accept="image/*">';
        _rows += '<label class="custom-file-label" for="customFile">';
        _rows += 'Choose file';
        _rows += '</label>';
        _rows += '</div>';
        _rows += '</div>';
        _rows += '</div>';
        _rows += '</div>';
        _rows += '</div>';
    },

    faqForm.prototype.uploads = function($id) {
        console.log('file length:' + files.length);
        var data = new FormData();
        $.each(files, function(key, value)
        {   
            data.append(key, value);
        }); 
        
        console.log(data);
        $.ajax({
            type: "POST",
            url: _baseUrl + 'components/faqs/uploads',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            async: false,
            success: function (data) {
                console.log(data);                       
            }
        });

        return true;
    },

    faqForm.prototype.prepareUpload = function(event)
    {       
        var self = event.target;
        console.log(event.target.files[0].name);
        if (event.target.files[0] != '' && event.target.files[0] !== undefined) {
            var found = false;
            for (var i = 0; i < filesName.length; i++) {
                if (filesName[i] == event.target.files[0].name) {
                    found = true;
                    break; break;
                }
            }

            if (found == true) {
                files[i] = event.target.files[0];
                console.log('true');
            } else {
                filesName.push(event.target.files[0].name);
                files.push(event.target.files[0]);
                console.log('false');
            }
        } else {
            $.each(filesName, function (ix) {
                if (filesName[ix] == event.target.files[0].name) {
                    filesName.splice(ix, 1);
                    files.splice(ix, 1);
                    console.log(self);
                    return false;
                }
            });
        }

        console.log(filesName);
        console.log(files);
    } 

    
    faqForm.prototype.readURL = function(input) {
        if (input.files && input.files[0]) {
            var self = input.files[0];
            var closeFile = $(input).closest('.avatar-upload').find('.close-file');
            console.log(closeFile);
            var reader = new FileReader();
            reader.onload = function(e) {
                $(input).closest('.avatar-upload').find('.avatar_preview').css('background-image', 'url('+e.target.result +')');
                $(input).closest('.avatar-upload').find('.avatar_preview').hide();
                $(input).closest('.avatar-upload').find('.avatar_preview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
    
            console.log(self);
            if (self.length != 0) {
                closeFile.removeClass('invisible');
            } else {
                closeFile.addClass('invisible');
            }
        }
    }

    faqForm.prototype.updateOrder = function(data)
    {   
        console.log(data);
        $.ajax({
            type: 'POST',
            url: _baseUrl + 'components/faqs/update-order',
            data:{ orders: data },
            success: function(response) {
                console.log(response);
                $.faq.resetCounter();
            },
            async: false
        })
    },

    faqForm.prototype.init = function()
    {   
        $.faqForm.fetchRows();

        $("#faq-modal .layers").sortable({
            delay: 150,
            stop: function() {
                var _id = $.faq.fetchID();
                if (_id > 0) {
                    var selectedData = new Array();
                    $('#faq-modal .layers .element').each(function() {
                        selectedData.push($(this).attr("data-row-id"));
                    });
                    $.faqForm.updateOrder(selectedData);
                }
            }
        });

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
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="name"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _form = _self.closest('form');
            var _text = _self.val().replace('&', 'and');
            _form.find('input[name="code"]').val(_text.replace(/\s+/g, '-').toLowerCase());
            // _form.find('input[name="slug"]').val(_text.replace(/\s+/g, '-').toLowerCase());
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
            var _form   = $('form[name="faqForm"]');
            var _id     = $.faq.fetchID();
            var _method = 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.faqForm.validate(_form, 0);
            var _files  = $.faqForm.uploads();

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
			var shouldSubmitForm = false;
			if (!shouldSubmitForm) {
            var form = $('.formDtls');
            Swal.fire({
                title: "Are you sure?",
                html: '<span>Some Details may not be editable after saving</span>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
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
											$.faq.load_contents();
											_modal.modal('hide');
										}
									);
								}, 500 + 300 * (Math.random() * 5));
							} else {
								_self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
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
				
			
				} else {
                    console.log("Form submission canceled");
                }
					});
					e.preventDefault();
				}
            }
			
			
        });

        /*
        | ---------------------------------
        | # when input file on change
        | ---------------------------------
        */
        this.$body.on('change', '#faq-modal .custom-file-input', function (event) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').removeClass('is-invalid').addClass("selected").html(fileName);
            $(this).prev().val(fileName.replace(/^.*\\/, ""));
            $.faqForm.prepareUpload(event);
        }); 

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _layers = _modal.find('.layers');
            _layers.append(_rows);
            $.faq.resetCounter();
        });

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.remove-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _layers = _modal.find('.layers');
            var _elements = _modal.find('.element');
            if (_elements.length > 1) {
                _modal.find('.element:last-child').remove();
            }
        });
    }

    //init faqForm
    $.faqForm = new faqForm, $.faqForm.Constructor = faqForm

}(window.jQuery),

//initializing faqForm
function($) {
    "use strict";
    $.faqForm.init();
}(window.jQuery);
