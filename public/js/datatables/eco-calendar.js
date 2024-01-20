!function($) {
    "use strict";

    var ecoCalendar = function() {
        this.$body = $("body");
    };

    var _contents = [];

    ecoCalendar.prototype.load_contents = function(_page = 0) 
    {   
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'economic-and-investment/calendar/lists',
            success: function(response) {
                console.log(response.data);
                _contents = response.data;
            },
            async: false
        });
    },

    ecoCalendar.prototype.initPopover = function(el) {
        var skin = el.data('skin') ? 'm-popover--skin-' + el.data('skin') : '';
        var triggerValue = el.data('trigger') ? el.data('trigger') : 'hover';

        setTimeout((function() { 
            el.popover({
                html: true,
                // title:'ciao',
                trigger: triggerValue,
                placement: 'top',
                content: el.data('content'),
                template: '\
                <div class="m-popover ' + skin + ' popover" role="tooltip">\
                    <div class="arrow"></div>\
                    <h3 class="popover-header"></h3>\
                    <div class="popover-body"></div>\
                </div>'
            });
        }), 100);
    },

    ecoCalendar.prototype.init = function()
    {   
        var todayDate = moment().startOf('day');
        var YM = todayDate.format('YYYY-MM');
        var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
        var TODAY = todayDate.format('YYYY-MM-DD');
        var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

        $('#m_calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            navLinks: true,
            events: _contents,

            eventRender: function(event, element) {
                if (element.hasClass('fc-day-grid-event')) {
                    element.data('content', event.description);
                    element.data('placement', 'top');
                    $.ecoCalendar.initPopover(element); 
                } else if (element.hasClass('fc-time-grid-event')) {
                    element.find('.fc-title').append('<div class="fc-description">' + event.description + '</div>'); 
                } else if (element.find('.fc-list-item-title').lenght !== 0) {
                    element.find('.fc-list-item-title').append('<div class="fc-description">' + event.description + '</div>'); 
                }
            }
        });
    }

    //init accountPayable
    $.ecoCalendar = new ecoCalendar, $.ecoCalendar.Constructor = ecoCalendar

}(window.jQuery),

//initializing accountPayable
function($) {
    "use strict";
    $.ecoCalendar.load_contents();
    $.ecoCalendar.init();
}(window.jQuery);