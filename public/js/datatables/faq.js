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

    faq.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    faq.prototype.paginate = function($rows)
    {   
        $('.pager').remove();
        $rows.each(function() {
            var currentPage = 0;
            var numPerPage = 8;
            var $rows = $(this);
            $rows.bind('repaginate', function() {
                $rows.find('.col-xl-3').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
            });
            $rows.trigger('repaginate');
            var numRows = $rows.find('.col-xl-3').length;
            // alert(numRows);
            var numPages = Math.ceil(numRows / numPerPage);
            var $pager = $('<div class="pager d-flex pagination justify-content-center"></div>');
            for (var page = 0; page < Math.min(numPages, 100); page++) {
                $('<span class="page-number page-item"></span>').text(page + 1).bind('click', {
                    newPage: page
                }, function(event) {
                    currentPage = event.data['newPage'];
                    $rows.trigger('repaginate');
                    $(this).addClass('active').siblings().removeClass('active');
                }).appendTo($pager).addClass('clickable');
            }
            $pager.insertAfter($rows).find('span.page-number:first').addClass('active');
        });
    },

    faq.prototype.preload_content = function()
    {
        var _layer = $('.content .layers'); _layer.addClass('active');
        var _rows = _layer.find('.row');
        _rows.append('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
        $.ajax({
            type: "GET",
            url: _baseUrl + 'faq/lists?keywords= ' + $('.keywords').val() + '&group=' + $('#group_id').val(),
            success: function(response) {  
                _rows.empty();
                var _lists = '';  
                if (response.data.length > 0) {
                    $.each(response.data, function(i, item) 
                    {   
                        _lists += '<div class="col-xl-3">';
                        _lists += '<div class="card">';
                        _lists += '<a href="javascript:;" data-row-id="' + item.id + '" class="toggle-faq">';
                        _lists += '<div class="p-4">';
                        _lists += '<div class="text-center">';
                        _lists += '<i class="' + item.icon + '"></i>';
                        _lists += '</div>';
                        _lists += '<h5 title="' + item.title + '" class="w-100">' + item.title +'</h5>';
                        _lists += '<p>' + item.description + '</p>';
                        _lists += '</div>';
                        _lists += '</a>';
                        _lists += '</div>';
                        _lists += '</div>';
                    });
                }
                setTimeout(function() { 
                    _rows.append(_lists);
                    _layer.removeClass('active');
                    $.faq.paginate(_rows);
                }, 100);
            }
        });
    },

    faq.prototype.load_content = function(_inner, _indicator, _id)
    {       
        $.ajax({
            type: "GET",
            url: _baseUrl + 'faq/view/' + _id,
            success: function(response) {                    
                _inner.empty(); _indicator.empty(); var _content = ''; var _content2 = ''; 
                $.each(response.details, function(i, item) 
                {   
                    if (i == 0) {
                        _content2 += '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="' + i + '" class="active" aria-label="Slide ' + i + '" aria-current="true"></button>';
                        _content += '<div class="carousel-item active">';
                    } else {
                        _content2 += '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="' + i + '" aria-label="Slide ' + i + '" class=""></button>';
                        _content += '<div class="carousel-item">';
                    }
                    _content += '<div class="carousel-header">'+ item.header + '</div>';
                    if (item.content.length > 0) {
                        _content += '<div class="carousel-content">'+ item.content + '</div>';
                    }
                    if (item.file.length > 0) {
                        _content += '<div class="center">';
                        _content += '<img src="' + _baseUrl + 'uploads/FAQ/' + item.file + '"/>';
                        _content += '</div>';
                    }
                    _content += '</div>';
                });
                _indicator.append(_content2);
                _inner.append(_content);
            }
        });
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

    faq.prototype.init = function()
    {   
        $.faq.preload_content();
        $.faq.preload_select3();

        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.faq.hideTooltip();
        });

        var carousel = $('.carousel');        
        $('#faq-modal').on('hidden.bs.modal', function (e) {
            carousel.carousel();
        });
        $('#faq-modal').on('shown.bs.modal', function (e) {
            carousel.carousel({
                interval: false
            });
        });

        $('.keywords').on('keyup', function (e) {
            if(e.which == 13) {
                $.faq.preload_content();
            } else if ($(this).val() == '') {
                $.faq.preload_content();
            }
        });

        $('#group_id').on('change', function (e) {
            $.faq.preload_content();
        });

        /*
        | ---------------------------------
        | # when FAQ is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.toggle-faq', function (e){
            var _self = $(this);
            var _modal = $('#faq-modal');
            var _id = _self.attr('data-row-id');
            var _text = _self.find('h5').attr('title');
            var _inner = _modal.find('.carousel-inner');
            var _indicator = _modal.find('.carousel-indicators');
            var d1 = $.faq.load_content(_inner, _indicator, _id);
            $('.layers').addClass('active').find('.row').append('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
            $.when( d1 ).done(function (v1) { 
                setTimeout(function () {
                    $('.layers').removeClass('active').find('.spinner-border').remove();
                    _modal.find('h5').text(_text + '?');
                    _modal.modal('show');
                }, 500 + 300 * (Math.random() * 5));
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