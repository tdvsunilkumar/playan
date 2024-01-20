@extends('layouts.admin')
@section('page-title')
    {{__('My Calendar')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('My Calendar')}}</li>
@endsection


@section('content')
   <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-2">
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="row">
                        <div class="col-xl-3">
                        {{ Form::label('filter_months', __('Month'),['class'=>'form-label']) }}
                        {{ 
                            Form::selectMonth('filter_months', 
                            
                            Carbon\Carbon::today()->month, 
                            $attributes = array(
                            'id' => 'filter_months',
                            'class' => 'form-control filter select3',
                            )) 
                        }}
                        </div>
                        <div class="col-xl-3">
                        {{ Form::label('filter_year', __('Year'),['class'=>'form-label']) }}
                        {{ 
                            Form::selectYear('filter_year', 
                            2020, Carbon\Carbon::today()->addYears('5')->year,
                            Carbon\Carbon::today()->year, 
                            $attributes = array(
                            'id' => 'filter_year',
                            'class' => 'form-control filter select3',
                            )) 
                        }}
                        </div>
                    </div>
                    
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="col-xl-3">
            </div>
        </div>
    </div>
   </div>
    
    <!-- <script src="{{ asset('js/HR/salarygrade.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        // $('a').on('click', function(event) {
        //     event.preventDefault()
        // });
        $('.select3').select3({
				dropdownAutoWidth : false,
			});
        var calendar = new FullCalendar.Calendar(calendarEl, {
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: DIR+'events',
            defaultView: 'month',
            editable: false,
            eventColor: 'transparent',
            eventClick: function(info) {
                info.preventDefault()
            }
            // eventRender: function (info) {
            //     var eventDiv = info.el;
            //     var eventColor = info.event.extendedProps.bg_color;
            //     var eventTitle = info.event.title;

            //     eventDiv.style.backgroundColor = eventColor + ' !important';
            //     eventDiv.innerHTML = '<span class="event-title">' + eventTitle + '</span>';
            // },
            // eventDrop: function (event) {
            //     var start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
            //     var end = moment(event.end).format('YYYY-MM-DD HH:mm:ss');

            //     $.ajax({
            //         url: '/event/update',
            //         data: {
            //             title: event.title,
            //             start: start,
            //             end: end,
            //             id: event.id,
            //             _token: $('meta[name="csrf-token"]').attr('content')
            //         },
            //         type: 'POST',
            //         success: function (response) {
            //             alert('Event updated successfully.');
            //         }
            //     });
            // }
        });

        calendar.render();
        
        var months = $('#filter_months');
        var year = $('#filter_year');
        $('.filter').on('change', function() {
            date = year.val()+ '-' + ('00' + months.val()).slice(-2) + '-01'
            console.log(date);
            calendar.gotoDate(date);
        });
        
    });
    </script>
@endsection


