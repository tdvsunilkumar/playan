!function($) {
    "use strict";

    var dashboard = function() {
        this.$body = $("body");
    };

    var $required = 0;

    dashboard.prototype.validate = function($form, $required)
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

    dashboard.prototype.preload_select3 = function()
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

    dashboard.prototype.preload_dashboard = function(_class = 'dashboard')
    {   
        console.log(_class);
        $('.widgets').css('visibility', 'hidden');
        $('.widgets.' + _class).css('visibility', 'visible');
    }

    dashboard.prototype.init = function()
    {   
        $.dashboard.preload_select3();
        $.dashboard.preload_dashboard();
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
        | # when dashboard menu on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="dashboard_menu"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            $.dashboard.preload_dashboard(_self.val());
        });
    }

    //init dashboard
    $.dashboard = new dashboard, $.dashboard.Constructor = dashboard

}(window.jQuery),

//initializing dashboard
function($) {
    "use strict";
    $.dashboard.init();
}(window.jQuery);
