@extends('layouts.admin')
@section('page-title')
    {{__('Miscellaneous Cashiering')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Miscellaneous Cashiering')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
       <!--  <a href="#" data-size="lg" data-url="{{ url('/psicsection/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create PSIC Section')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a> -->
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
<?php if(!empty($data)){ print_r($data); exit;}?>
@section('content')
<style type="text/css">
    tr:nth-child(even) {
    background-color: #ffffff;
}
</style>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Major Type')}}</th>
                                <th>{{__('Division')}}</th>
                                <th>{{__('Group')}}</th>
                                <th>{{__('Class')}}</th>
                                <th>{{__('Sub-Class')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=0; @endphp
                            @foreach ($PsicSections as $section)
                                <?php $i=$i+1; ?>
                                <tr>
                                    <td style="border:1px solid #000;padding:0px;margin: 0px;">
                                        @php
                                            $content = $section->section_description;
                                        @endphp
                                        @if(strlen($content) > 30)
                                            <div class="content" style=" height: 100px; overflow-y: auto;">
                                                <span class="short" >{{ substr($content, 0, 30) }}</span>
                                                <span class="full" style="display:none;">{!! nl2br($content) !!}</span>
                                                <a href="#" class="more-btn">More</a>
                                                <a href="#" class="less-btn" style="display:none;">Less</a>
                                            </div>
                                        @else
                                            {!! nl2br($content) !!}
                                        @endif
                                    </td>
                                <td colspan="4" style="padding:0px;margin: 0px;">
                                        <table class="table datatable">
                                            @foreach ($getdivisions[$section->id] as $division)
                                            <tr>
                                                <td style="border:1px solid #000;">
                                                    @php
                                                        $content = $division->division_description;
                                                    @endphp
                                                    @if(strlen($content) > 30)
                                                        <div class="content">
                                                            <span class="short">{{ substr($content, 0, 30) }}</span>
                                                            <span class="full" style="display:none;">{{ $content }}</span>
                                                            <a href="#" class="more-btn">More</a>
                                                            <a href="#" class="less-btn" style="display:none;">Less</a>
                                                        </div>
                                                    @else
                                                        {{ $content }}
                                                    @endif
                                                </td>
                                                 <td colspan="3" style="padding:0px;margin: 0px;">
                                                    <table style="width: 100%;" class="table datatable">
                                                     @foreach ($getgroup[$division->id] as $groups)
                                                    <tr><td style="border:1px solid #000;">

                                                      @php
                                                        $content = $groups->group_description;
                                                    @endphp
                                                    @if(strlen($content) > 30)
                                                        <div class="content">
                                                            <span class="short">{{ substr($content, 0, 30) }}</span>
                                                            <span class="full" style="display:none;">{{ $content }}</span>
                                                            <a href="#" class="more-btn">More</a>
                                                            <a href="#" class="less-btn" style="display:none;">Less</a>
                                                        </div>
                                                    @else
                                                        {{ $content }}
                                                    @endif

                                                    </td>
                                                    <td colspan="2" style="padding:0px;margin: 0px;">
                                                        <table style="width: 100%;" class="table datatable" >
                                                         @foreach ($getclass[$groups->id] as $class)
                                                                <tr><td style="border:1px solid #000;">

                                                                      @php
                                                                        $content = $class->class_description;
                                                                    @endphp
                                                                    @if(strlen($content) > 30)
                                                                        <div class="content">
                                                                            <span class="short">{{ substr($content, 0, 30) }}</span>
                                                                            <span class="full" style="display:none;">{{ $content }}</span>
                                                                            <a href="#" class="more-btn">More</a>
                                                                            <a href="#" class="less-btn" style="display:none;">Less</a>
                                                                        </div>
                                                                    @else
                                                                        {{ $content }}
                                                                    @endif

                                                                        </td>
                                                                        <td  style="padding:0px;margin: 0px;">
                                                                            <table style="width: 100%;" class="table datatable">
                                                                             @foreach ($getSubClasses[$class->id] as $subclass)
                                                                                    <tr><td style="border:1px solid #000;">

                                                                                          @php
                                                                                            $content = $subclass->subclass_description;
                                                                                        @endphp
                                                                                        @if(strlen($content) > 30)
                                                                                            <div class="content">
                                                                                                <span class="short">{{ substr($content, 0, 30) }}</span>
                                                                                                <span class="full" style="display:none;">{{ $content }}</span>
                                                                                                <a href="#" class="more-btn">More</a>
                                                                                                <a href="#" class="less-btn" style="display:none;">Less</a>
                                                                                            </div>
                                                                                        @else
                                                                                            {{ $content }}
                                                                                        @endif

                                                                                            </td>
                                                                                        </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                    @endforeach
                                                </table>
                                                </td>

                                                </tr>
                                                    @endforeach
                                                </table>
                                                </td> 
                                               
                                                
                                            </tr>
                                        @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>
    // Add click event listener to the "Less" button
        document.querySelectorAll('.less-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var contentWrapper = this.parentElement;
                contentWrapper.querySelector('.short').style.display = 'block';
                contentWrapper.querySelector('.full').style.display = 'none';
                contentWrapper.querySelector('.more-btn').style.display = 'block';
                contentWrapper.querySelector('.less-btn').style.display = 'none';

            });
        });
        // Add click event listener to the "More" button
        document.querySelectorAll('.more-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var contentWrapper = this.parentElement;
                contentWrapper.querySelector('.short').style.display = 'none';
                contentWrapper.querySelector('.full').style.display = 'block';
                contentWrapper.querySelector('.more-btn').style.display = 'none';
                contentWrapper.querySelector('.less-btn').style.display = 'block';
            });
        });

        
    </script>



@endsection
