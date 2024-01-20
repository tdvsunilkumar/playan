!function($) {
    "use strict";

    var treasury_collection = function() {
        this.$body = $("body");
    };

    var $required = 0;

    treasury_collection.prototype.required_fields = function() {
        
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

    treasury_collection.prototype.validate = function($form, $required)
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

    treasury_collection.prototype.preload_select3 = function()
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
                    dropdownParent: $('#report-ledger-card'),
                });
            });
        }
    },

    treasury_collection.prototype.reload_account_code = function(_type, _acctCode)
    {   
        _acctCode.find('option').remove(); 
        console.log(_baseUrl + 'reports/accounting/ledgers/reload?type=' + _type);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'reports/accounting/ledgers/reload?type=' + _type,
            success: function(response) {
                _acctCode.append('<option value="">select an account code</option>');  
                $.each(response.data, function(i, item) {
                    _acctCode.append('<option value="' + item.id + '">' + item.code + ' - ' + item.description + '</option>');  
                }); 
            },
            async: false
        });
    },

    treasury_collection.prototype.reload_category_name = function(_category, _name)
    {   
        _name.find('option').remove(); 
        console.log(_baseUrl + 'reports/accounting/ledgers/reload-category-name?category=' + _category);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'reports/accounting/ledgers/reload-category-name?category=' + _category,
            success: function(response) {
                _name.append('<option value="">select a name</option>');  
                $.each(response.data, function(i, item) {
                    _name.append('<option value="' + item.id + '">' + item.fullname + '</option>');  
                }); 
            },
            async: false
        });
    },
    
    treasury_collection.prototype.init = function()
    {   
        $.treasury_collection.preload_select3();

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
        | # reload account code
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="ledger_type"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _account = $('#code');
            $.treasury_collection.reload_account_code(_self.val(), _account);
        });

         /*
        | ---------------------------------
        | # reload category name
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="category"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _name = $('#name');
            $.treasury_collection.reload_category_name(_self.val(), _name);
        });

        /*
        | ---------------------------------
        | # print action
        | ---------------------------------
        */
        this.$body.on('click', '[data-actions="print"]', function (e) {
            e.preventDefault();
            var w = window.innerWidth;
            var h = window.innerHeight;
            var divContents = document.getElementById("report-ledger-card").innerHTML; 
            var a = window.open('', '', 'height='+h+', width='+w+''); 
            a.document.write('<html>'); 
            a.document.write('<head>'); 
            a.document.write('<style>'); 
            a.document.write('@media print{.col-md-3,.col-md-9,.col-sm-12,.col-sm-3,.col-sm-9{position:relative;float:left}table td,table th{border-left:1px solid #333;padding:.5rem;font-size:.85rem;border-bottom:1px solid #333}.border-bottom,table td,table th{border-bottom:1px solid #333}body *{font-family:arial,sans-serif}.row>*{flex-shrink:0;width:100%;max-width:100%;padding-right:calc(var(--bs-gutter-x) * .5);padding-left:calc(var(--bs-gutter-x) * .5);margin-top:var(--bs-gutter-y)}.col-sm-12{width:100%}.col-md-9{width:60%}.col-md-3{text-align:right;width:40%}.col-sm-9{width:75%}.col-sm-3{width:25%}.offset-sm-6{margin-left:50%}.fw-bold{font-weight:700!important}.fs-1{font-size:1.75rem!important}.fs-4{font-size:1.25rem!important}.fs-5{font-size:1rem!important}.fs-6{font-size:.9375rem!important}.text-center{text-align:center!important}.pb-2{padding-bottom:.5rem!important}.m-0{margin:0!important}.mb-1{margin-bottom:.25rem!important}.mb-0{margin-bottom:0!important}.mt-3{margin-top:1rem!important}.mt-4{margin-top:1.5rem!important}.mt-5{margin-top:3rem!important}img{position:absolute;left:0;top:25px;width:120px}#report-ledger-card p span.fw-bold{float:left;width:140px}table{border-top:1px solid #333;border-right:1px solid #333;width:100%;min-width:100%;margin-top:1rem;margin-bottom:1rem}table th{background:#eaeaea;color:#333;text-align:center;text-transform:uppercase}table td{text-align:left}table td:nth-child(4){min-width:200px!important;max-width:200px!important;word-wrap:break-word;white-space:normal}}'); 
            a.document.write('</style>'); 
            a.document.write('</head>'); 
            a.document.write('<body onload="close();">'); 
            a.document.write(divContents); 
            a.document.write('</body></html>'); 
            a.document.close(); 
            a.print(); 
            a.onfocus=function(){ a.close();}
        })

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _form   = $('form[name="reportsCollectionForm"]');
            var _action = _form.attr('action') + '/export/' + _form.find('select[name="export_as"]').val() + '?' + _form.serialize();
            var _error  = $.treasury_collection.validate(_form, 0);

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
                window.open(_action, '_blank');
            }
        });
    }

    //init treasury_collection
    $.treasury_collection = new treasury_collection, $.treasury_collection.Constructor = treasury_collection

}(window.jQuery),

//initializing treasury_collection
function($) {
    "use strict";
    $.treasury_collection.required_fields();
    $.treasury_collection.init();
}(window.jQuery);
