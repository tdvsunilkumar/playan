@extends('layouts.admin')

@section('page-title')
    {{__('FAQ')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('FAQ') }}</li>
@endsection

@section('content')
    <div class="row">
        <h4 class="help">How can we help you?</h4>
        <div class="col-md-9">
            <div class="form-group has-search">
                <span class="fa fa-search form-control-feedback"></span>
                <input type="text" class="keywords form-control form-control-lg" placeholder="Search for any topic or keywords">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group m-form__group required">
                {{
                    Form::select('group_id', $groups, $value = '', ['id' => 'group_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a group menu'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <h4 class="mb-0">Popular Topics</h4>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="layers">
                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('faq.view')
@endsection

@push('styles')
<style>
    .layers {
        height: 100%;
        position: relative;
        width: 100%;
        min-height: 540px;
    }
    .layers.active {
        background: rgba(255,255,255,0.7);
    }
    .layers .spinner-border {
        position: absolute;
        top: calc(50% - 2.5rem);
        left: calc(50% - 1rem);
    }
    h4.help {
        color: #1f3996;
    }
    .has-search .form-control {
        padding-left: 2.375rem;
    }
    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
    }
    .card {
        min-height: 218px;
        max-height: 218px;
    }
    .card h5 {
        margin: 0;
        margin-top: 1.5rem;
        text-align: center;
    }
    .card i {
        color: #1f3996;
        font-size: 4rem;
    }
    .card p {
        margin: 0;
        color: #9d9d9d;
        text-align: center;
    }
    .modal img {
        max-height: 600px !important;
        max-width: 100%;
    }
    .modal span {
        color: #fff;
        font-size: 3rem;
    }
    @media (min-width: 768px) {
        .carousel-inner {
            min-height: 698px;
        }
    }
    .carousel-control-prev, .carousel-control-next {
        width: 1%;
    }
    .carousel-control-prev {
        margin-left: -10%;
    }
    .carousel-control-next {
        margin-right: -10%;
    }
    .carousel-control-prev i,
    .carousel-control-next i {
        font-size: 5rem;
        text-shadow: 2px 2px #000;
    }
    .modal.form button i {
        font-size: 2rem !important;
    }
    .carousel-indicators [data-bs-target] {
        background-color: #000;
    }
    .carousel-indicators {
        bottom: -13%;
    }
    #group_id {
        background: #fff;
    }
    .select3-container--default .select3-selection--single {
        background: #fff;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('js/datatables/faq.js?v='.filemtime(getcwd().'/js/datatables/faq.js').'') }}"></script>
@endpush