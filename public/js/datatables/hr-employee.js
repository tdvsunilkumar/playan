$(document).ready(function(){   
    // var isopen=$("#isopen").val();
    // if(isopen==1){
    //     $("#addEmployee").trigger("click");
    // }
    
});
!function($) {
    "use strict";
    var employee = function() {
        this.$body = $("body");
    };

    var _empID = 1, _table = '', _fileTable = '', _page = 0, _pageFile = 0;

    employee.prototype.required_fields = function() {
        $('label span.ms-1.text-danger').remove();
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], select, textarea");
                if ($input.val() == '' || $input.val() == null) {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                if ($input.hasClass('selectpicker')) {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    employee.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#employeeTable', {
            ajax: { 
                url : _baseUrl + 'human-resource/employees/lists',
                type: "GET", 
                data: {
                    "_token": _token,
                    "department": $('#filter_department').val(),
                    "status": $('#filter_status').val(),
                },
                complete: function() {
                    $.employee.shorten();
                    // $('[data-bs-toggle="tooltip"]').tooltip({
                    //     trigger : 'hover'
                    // });  
                }
            },
            language: {                
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.identification_no);
                $(row).attr('data-row-status', data.status);
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );        
            }, 
            columns: [
                { data: 'identification_no' },
                { data: 'title' },
                { data: 'fullname' },
                { data: 'address' },
                { data: 'mobile_no' },
                { data: 'designation' },
                { data: 'department' },
                // { data: 'division' },
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
                {  orderable: false, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-start' },
                {  orderable: false, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-start sliced' },
                // {  orderable: false, targets: 7, className: 'text-start sliced' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' }
            ]
        } );

        return true;
    },

    // employee.prototype.load_file_contents = function(_pageFile = 0) 
    // {   
    //     _fileTable = new DataTable('#hrUploadTable', {
    //         ajax: { 
    //             url : _baseUrl + 'human-resource/employees/upload-lists/' + _empID,
    //             type: "GET", 
    //             data: {
    //                 "_token": _token
    //             },
    //             complete: function() {
    //                 $.employee.shorten();
    //             }
    //         },
    //         language: {
    //             "processing": "<div class='spinner-border table' role='status'></div>",
    //         },
    //         bDestroy: true,
    //         lengthMenu: [
    //             [5, 10, 25, 50, -1],
    //             [5, 10, 25, 50, 'All'],
    //         ],
    //         order: [[0, "desc"]],
    //         serverSide: true,
    //         processing: true,
    //         pageLength: 5,
    //         createdRow: function( row, data, dataIndex ) {
    //             $(row).attr('data-row-id', data.id);
    //             $(row).attr('data-row-file', data.file);
    //         },
    //         initComplete: function(){
    //             this.api().page(_pageFile).draw( 'page' );        
    //         }, 
    //         columns: [
    //             { data: 'filename' },
    //             { data: 'type' },
    //             { data: 'size' },
    //             { data: 'actions' }
    //         ],
    //         rowReorder: {
    //             dataSrc: 'order'
    //         },
    //         columnDefs: [
    //             {  orderable: true, targets: 0, className: 'text-start sliced' },
    //             {  orderable: true, targets: 1, className: 'text-start sliced' },
    //             {  orderable: true, targets: 2, className: 'text-start' },
    //             {  orderable: false, targets: 3, className: 'text-center' }
    //         ]
    //     } );

    //     return true;
    // },

    employee.prototype.reload_division = function($department)
    {   
        var $division = $('#acctg_department_division_id'); $division.find('option').remove(); 

        console.log(_baseUrl + 'human-resource/employees/reload-division-via-department/' + $department);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'human-resource/employees/reload-division-via-department/' + $department,
            success: function(response) {
                console.log(response.data);
                $division.append('<option value="">select a division</option>');  
                $.each(response.data, function(i, item) {
                    $division.append('<option value="' + item.id + '"> ' + item.code + ' - ' + item.name + '</option>');  
                }); 
            },
            async: false
        });
        
        // $('.m_selectpicker').selectpicker('refresh');
    },

    employee.prototype.preload_select3 = function()
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

    employee.prototype.fetchID = function()
    {
        return _empID;
    }

    employee.prototype.updateID = function(_id)
    {
        return _empID = _id;
    }
    
    employee.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    employee.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    employee.prototype.perfect_scrollbar = function()
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

    employee.prototype.preload_selectpicker = function()
    {
        if ( $('.selectpicker') ) {

            $('*:not(.bootstrap-select) > .selectpicker').selectpicker('refresh');
            //$('*:not(.bootstrap-select) > .selectpicker').selectpicker('refresh');
            var remove = $('.bootstrap-select');
            $(remove).replaceWith($(remove).contents('.selectpicker'));
            $('.selectpicker').selectpicker('render');
            // $('.selectpicker').selectpicker();
        }
    },

    employee.prototype.select4Ajax = function (id, parentId, Url, length = 0) 
    {
        $("#"+id).select3({
            allowClear: true,
            dropdownAutoWidth : false,
            dropdownParent: $("#"+parentId),
            minimumInputLength: length,
            ajax: {
                url: _baseUrl + Url,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (params,val) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function (data,params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 20) < data.data_cnt
                        }
                    };
                },
                cache: true
            }
        }).val($("#"+id).val()).trigger('change');
    }, 

    employee.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.employee.preload_select3();
        $.employee.preload_selectpicker();
        $.employee.load_contents();
        $.employee.perfect_scrollbar();
        $.employee.select4Ajax('barangay_id', 'parent_barangay_id', 'getBarngayList');
        // $('[data-bs-toggle="tooltip"]').on('click', function () {
        //     $(this).tooltip('hide')
        // });
        var _modalx = new bootstrap.Modal($('#employee-modal'), {
            backdrop: 'static',
            keyboard: false
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#employee-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Employee');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            var _select = _modal.find('select[name="barangay_id"]');
            _select.find('option').remove();
            _select.append('<option value="">select a barangay</option>');
            _modal.find('.upload-row').addClass('hidden');
            $.employee.load_contents(_table.page());
            _empID = 0;
        });
        this.$body.on('shown.bs.modal', '#employee-modal', function (e) {
            var _modal = $(this);
            $.employee.hideTooltip();
            $('.add-btn').prop('disabled', false);
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _self = $(this);
            var _modals = $('#employee-modal');
            _self.prop('disabled', true);
            _modals.find('select[name="is_dept_restricted"]').val('Yes').trigger('change.select3'); 
            _modalx.show();
        });
       
        /*
        | ---------------------------------
        | # when department field onchange
        | ---------------------------------
        */
        this.$body.on('change', '#acctg_department_id', function (e){
            e.preventDefault();
            var _self = $(this);
            if (_self.val() > 0) {
                $.employee.reload_division(_self.val());
            }
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#employeeTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#employee-modal');
            var _url    = _baseUrl + 'human-resource/employees/edit/' + _id;
            _empID = _id;
            console.log(_url);
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.employee.reload_division(response.data.acctg_department_id);
                    $.when( d1 ).done(function ( v1 ) 
                    {  
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            if(k=='barangay_id'){
                                $("#"+k).html(v)
                                $.employee.select4Ajax("p_barangay_id_no","accordionFlushExample","getBarngayList");
                            }
                        });
                        if (response.data.is_dept_restricted > 0) {
                            _modal.find('select[name="is_dept_restricted"]').val('Yes').trigger('change.select3');   
                            _modal.find('select[name="departmental_access[]"]').val('').trigger('change.select3').prop('disabled', true);             
                        }else{
                            _modal.find('select[name="is_dept_restricted"]').val('No').trigger('change.select3');
                            _modal.find('select[name="departmental_access[]"]').val(response.access).trigger('change.select3').prop('disabled', false);
                        }
                        $.employee.load_file_contents();
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.upload-row').removeClass('hidden');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Employee (<span class="variables">' + _code + '</span>)');
                        _modal.modal('show');
                    });
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
        this.$body.on('click', '#employeeTable .remove-btn, #employeeTable .restore-btn', function (e) {
            e.preventDefault();
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'human-resource/employees/remove/' + _id : _baseUrl + 'human-resource/employees/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the employee with code ("+ _code +") will be removed." : "Are you sure? <br/>the employee with code ("+ _code +") will be restored.",
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
                                    $.employee.load_contents(_table.page());
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
        | # when filter changes
        | ---------------------------------
        */
        this.$body.on('change', '#filter_department, #filter_status', function (e) {
            $.employee.load_contents(_table.page());
        });
    }

    //init employee
    $.employee = new employee, $.employee.Constructor = employee

}(window.jQuery),

//initializing employee
function($) {
    "use strict";
    $.employee.required_fields();
    $.employee.init();
}(window.jQuery);
