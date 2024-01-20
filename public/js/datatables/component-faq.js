!function($) {
    "use strict";

    var faq = function() {
        this.$body = $("body");
    };

    var _faqID = 0; var _table; var _page = 0; var _rows = '';

    faq.prototype.required_fields = function() {
        
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], select, textarea");
                if ($input.val() == '') {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    faq.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#faqTable', {
            ajax: { 
                url : _baseUrl + 'components/faqs/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.faq.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.faq.hideTooltip();
                }
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-status', data.status);
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' ); 
            }, 
            columns: [
                { data: 'id' },
                { data: 'group' },
                { data: 'title' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
            ]
        } );

        return true;
    },

    faq.prototype.fetchID = function()
    {
        return _faqID;
    }

    faq.prototype.updateID = function(_id)
    {
        return _faqID = _id;
    }

    faq.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    faq.prototype.preload_select3 = function()
    {
        if ( $('.select3') ) {
            $.each($('.select3'), function(){
                var _self = $(this);
                var _selfID = $(this).attr('id');
                var _parentID = 'parent_' + _selfID;
                _self.closest('.form-group').attr('id', _parentID);

                _self.select3({
                    allowClear: true,
                    dropdownAutoWidth : false,
                    dropdownParent: $('#' + _parentID),
                });
            });
        }
    },

    faq.prototype.updateOrder = function(data)
    {   
        console.log(data);
        $.ajax({
            type: 'POST',
            url: _baseUrl + 'components/faqs/update-order',
            data:{ orders: data },
            success: function(response) {
                console.log(response);
                _table.ajax.reload();
            },
            async: false
        })
    },

    faq.prototype.perfect_scrollbar = function()
    {
        if ($(".table-responsive")) {
            $.each($('.table-responsive'), function(_i = 0){
                _i++;
                $(this).attr('id', '_table' + _i);
                var _divID = '#' + $(this).attr('id');
                var px = new PerfectScrollbar(_divID, {
                    wheelSpeed: 0.5,
                    swipeEasing: 0,
                    wheelPropagation: 1,
                    minScrollbarLength: 40,
                });
            });
        }

    },

    faq.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    faq.prototype.fetchRows = function()
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

    faq.prototype.resetCounter = function()
    {   
        var _counter = 1;
        var _modal = $('#faq-modal');
        $.each(_modal.find('.element'), function(){
            var _self = $(this);
            _self.find('.numbering').html('#' + _counter);
            _counter++;
        });
        return true;
    }

    faq.prototype.init = function()
    {   
        $.faq.fetchRows();
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.faq.preload_select3();
        $.faq.load_contents();
        $.faq.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.faq.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when re order table
        | ---------------------------------
        */
        $("#faqTable tbody").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('#faqTable tbody tr').each(function() {
                    selectedData.push($(this).attr("data-row-id"));
                });
                $.faq.updateOrder(selectedData);
            }
        });

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-2 input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.faq.load_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#faq-modal', function (e) {
            var modal = $(this);
            modal.find('.modal-header h5').html('Manage FAQ');
            modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('select.select3').val('').trigger('change.select3'); 
            modal.find('.custom-file label').text('').removeClass('selected');
            modal.find('.layers').empty();
            modal.find('.layers').append(_rows);
            $.faq.preload_select3();
            _faqID = 0;
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#faq-modal');
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#faqTable .edit-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _modal  = $('#faq-modal');
            var _url    = _baseUrl + 'components/faqs/edit/' + _id;                    
            var _layers = _modal.find('.layers');
            console.log(_url);
            _faqID = _id;
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    _modal.find('.layers').empty(); 
                    var _counter = 1;
                    $.each(response.details, function (k, v) {
                        _layers.append(_rows);
                        var _elements = _layers.find('.element:last-child');
                        setTimeout(() => {
                            $.each(v, function (x, y) {
                                _elements.find('.numbering').html('#' + _counter);
                                if (x == 'id') { 
                                    _elements.attr('data-row-id', y);
                                } 
                                _elements.find('input[name="'+ x +'[]"]').val(y);
                                _elements.find('textarea[name="'+ x +'[]"]').val(y);
                                if (x == 'file' && y != '') {
                                    _elements.find('.custom-file label').text('C:\\fakepath\\' + y).addClass('selected');
                                }
                            });
                            _counter++;
                        }, '100');
                    });
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit FAQ (<span class="variables">' + _id + '</span>)');
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#faqTable .remove-btn, #faqTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/faqs/remove/' + _id : _baseUrl + 'components/faqs/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the group menu with code ("+ _code +") will be removed." : "Are you sure? <br/>the group menu with code ("+ _code +") will be restored.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (_status == 'Active') ? "Yes, remove it!" : "Yes, restore it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: (_status == 'Active') ? "btn btn-danger" : "btn btn-info", cancelButton: "btn btn-active-light" },
            }).then(function (t) {
                t.value
                    ? 
                    $.ajax({
                        type: 'PUT',
                        url: _url,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                            .then(
                                function (e) {
                                    e.isConfirmed && ((t.disabled = !1));
                                    $.faq.load_contents();
                                }
                            );
                        },
                        complete: function() {
                            window.onkeydown = null;
                            window.onfocus = null;
                        }
                    })
                    : "cancel" === t.dismiss 
            });
            
        }); 

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#faqTable .order-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _url    = (_self.hasClass('order-up')) ? _baseUrl + 'components/faqs/order/up/' + _id : _baseUrl + 'components/faqs/order/down/' + _id;
            console.log(_url);
            $.ajax({
                type: 'PUT',
                url: _url,
                success: function(response) {
                    console.log(response);
                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                    .then(
                        function (e) {
                            e.isConfirmed;
                            $.faq.load_contents();
                        }
                    );
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 
    }

    //init faq
    $.faq = new faq, $.faq.Constructor = faq

}(window.jQuery),

//initializing faq
function($) {
    "use strict";
    $.faq.required_fields();
    $.faq.init();
}(window.jQuery);