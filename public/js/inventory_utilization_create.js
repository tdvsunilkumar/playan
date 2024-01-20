var text_loader = "Loading...";
    var submit_status = ""; 

    $(document).ready(function () {
        $("#rec_type").select3({dropdownAutoWidth : false,dropdownParent: $("#rec-type-group")});
        $("#supplier").select3({dropdownAutoWidth : false,dropdownParent: $("#supplier-group")});
        $("#date_range").select3({dropdownAutoWidth : false,dropdownParent: $("#date-range-group")});
        $("#year").select3({dropdownAutoWidth : false,dropdownParent: $("#year-group")});

        $('#rec_type').change(function (e) {
            $rec_type = $(this).val();
            if($rec_type == 1){
                $('#supplier').attr('disabled', true);
            }else{
                $('#supplier').attr('disabled', false);
            }
        });
        
    });

    formSubmit = (status_key) =>{
        if(submit_status == ''){
        submit_status = status_key;
        $('#is_posted').val(status_key);
        }
    }

    !function($) {
        "use strict";
    
        var acctg_ledger = function() {
            this.$body = $("body");
        };
    
        var $required = 0;
    
        
        acctg_ledger.prototype.init = function()
        {   
            $.acctg_ledger.preload_select3();
    
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
                $.acctg_ledger.reload_account_code(_self.val(), _account);
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
                $.acctg_ledger.reload_category_name(_self.val(), _name);
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
                var _form   = $('form[name="medicalUtilizationForm"]');
                var _action = _form.attr('action') + '/export/' + _form.find('select[name="export_as"]').val() + '?' + _form.serialize();
                var _error  = $.acctg_ledger.validate(_form, 0);
    
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
    
        //init acctg_ledger
        $.acctg_ledger = new acctg_ledger, $.acctg_ledger.Constructor = acctg_ledger
    
    }(window.jQuery),
    
    //initializing acctg_ledger
    function($) {
        "use strict";
        $.acctg_ledger.required_fields();
        $.acctg_ledger.init();
    }(window.jQuery);