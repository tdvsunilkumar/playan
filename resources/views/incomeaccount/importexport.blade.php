@extends('layouts.admin')
@section('page-title')
    {{__('Import Income Accounts')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Import Income Accounts')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row hide" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="container">
                        <div class="row">
                            <div class="col--sm12">
                               <!--  <form action="{{ url('/importincomeaccount') }}" method="post" enctype="multipart/form-data"> -->
                                    {{ Form::open(array('route' => array('incomeaccount.importExcel'),'method'=>'post', 'enctype' => "multipart/form-data")) }}
                                    <div class="mb-3">
                                        <label for="excel" class="form-label"></label>
                                        <input type="file" class="form-control" name="file" id="file" data-filename="upload_file" required>
                                    </div>
                                    <input type="submit" name="submit" value="{{('Import User Data')}}" class="btn  btn-primary">
                                    <a class="btn btn-warning" href="">Export User Data</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

