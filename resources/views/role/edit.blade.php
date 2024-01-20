{{Form::model($role,array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Role Name')))}}
                @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-bplodetails-tab" data-bs-toggle="pill" href="#bplodetails" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Business Permit')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-cto-tab" data-bs-toggle="pill" href="#cto" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Treasurer')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-planning-tab" data-bs-toggle="pill" href="#planning" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Planning & Development')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-healthsanitary-tab" data-bs-toggle="pill" href="#healthsanitary" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Health & Safety')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-project-tab" data-bs-toggle="pill" href="#project" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Fire Protection')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-assessment-tab" data-bs-toggle="pill" href="#assessment" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Environmental')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-point-tab" data-bs-toggle="pill" href="#point" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('payment system')}}</a>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link" id="pills-hrm-tab" data-bs-toggle="pill" href="#hrm" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('System Setup')}}</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" id="pills-administrative-tab" data-bs-toggle="pill" href="#administrative" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Administrative')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-fees-tab" data-bs-toggle="pill" href="#fees" role="tab" aria-controls="pills-contact" aria-selected="false">{{__('Fees Master')}}</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="bplodetails" role="tabpanel" aria-labelledby="pills-home-tab">
                    @php
                         $modules=['hrm dashboard','project dashboard','account dashboard','user','role','allaplicant','bplo applications'];
                       if(\Auth::user()->type == 'company'){
                          
                       }
                    @endphp
                    
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign General Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input" name="bplodetails_checkall"  id="bplodetails_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input ischeck bplodetails_checkall"  data-id="{{str_replace(' ', '', str_replace('&', '', $module))}}" ></td>
                                            <td><label class="ischeck bplodetails_checkall" data-id="{{str_replace(' ', '', str_replace('&', '', $module))}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '',  str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck bplodetails_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck staff_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="cto" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @php
                         $modules=['cto applications','penalty rates'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign CTO related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input custom_align_middle" name="cto_heckall"  id="cto_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input ischeck cto_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck cto_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input cto_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input cto_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input cto_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input cto_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck cto_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="planning" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['planning applications'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign planning related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="planning_checkall"  id="planning_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck planning_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck planning_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">

                                                 
                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input planning_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input planning_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input planning_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input planning_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                 <div class="tab-pane fade" id="healthsanitary" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['health certificate','app sanitary'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign healthsanitary related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="healthsanitary_checkall"  id="healthsanitary_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck healthsanitary_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck healthsanitary_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">

                                                 
                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input healthsanitary_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input healthsanitary_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input healthsanitary_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input healthsanitary_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="project" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['fsic applications','bplo permit and licence','inspection order','inspection report'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Project related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="project_checkall"  id="project_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck project_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck project_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">

                                                 
                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input project_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="assessment" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['environmental application'];
                    @endphp


                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Assessment related Permission to Roles')}}
                                </h6>

                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="assessment_checkall"  id="assessment_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck assessment_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck assessment_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">

                                                 
                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input assessment_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input assessment_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input assessment_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input assessment_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="point" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['check type master','system setup','setup pop receipts','collectors','income accounts'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Point related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input custom_align_middle" name="point_checkall"  id="point_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input ischeck point_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck point_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                   
                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input point_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input point_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input point_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input point_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                 <div class="tab-pane fade" id="hrmpermission" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=[];
                    @endphp

                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign HRM related Permission to Roles')}}
                                </h6>

                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="hrm_checkall"  id="hrm_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input align-middle ischeck hrm_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck hrm_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck fees_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck fees_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck fees_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck fees_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="fees" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['bussiness permit fee','bussiness sanitary fee','bussiness garbage fee','bussiness engineering fees','bussiness environmental fee'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Fees Master related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input custom_align_middle" name="fees_checkall"  id="fees_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input ischeck fees_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck fees_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                  @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input fees_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input fees_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input fees_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input fees_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="administrative" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @php
                        $modules=['psic sections','psic division','psic group','psic class','psic subclass','tax class','tax category','tax type','requirements','bplo requirements','bussiness classifications','bussiness activity','app types','bussiness types','barangay','bfp occupancy type','region','province','municipality','country','bplo system parameters','bplo tax rate effectivity','bplo fixed taxes & fees','bplo graduated new tax', 'tax declaration'];
                    @endphp
                    <div class="col-md-12">
                        <div class="form-group">
                            @if(!empty($permissions))
                                <h6 class="my-3">{{__('Assign Administrative related Permission to Roles')}}</h6>
                                <table class="table table-striped mb-0" id="dataTable-1">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input custom_align_middle" name="administrative_checkall"  id="administrative_checkall" >
                                        </th>
                                        <th>{{__('Module')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($modules as $module)
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input ischeck administrative_checkall"  data-id="{{str_replace(' ', '', $module)}}" ></td>
                                            <td><label class="ischeck administrative_checkall" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label></td>
                                            <td>
                                                <div class="row ">
                                                    @if(in_array('view '.$module,(array) $permissions))
                                                        @if($key = array_search('view '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'View',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('add '.$module,(array) $permissions))
                                                        @if($key = array_search('add '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Add',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('move '.$module,(array) $permissions))
                                                        @if($key = array_search('move '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('manage '.$module,(array) $permissions))
                                                        @if($key = array_search('manage '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input administrative_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('create '.$module,(array) $permissions))
                                                        @if($key = array_search('create '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input administrative_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('edit '.$module,(array) $permissions))
                                                        @if($key = array_search('edit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input administrative_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete '.$module,(array) $permissions))
                                                        @if($key = array_search('delete '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,$role->permission, ['class'=>'form-check-input administrative_checkall isscheck_'.str_replace(' ', '', str_replace('&', '', $module)),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('show '.$module,(array) $permissions))
                                                        @if($key = array_search('show '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif


                                                    @if(in_array('send '.$module,(array) $permissions))
                                                        @if($key = array_search('send '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('create payment '.$module,(array) $permissions))
                                                        @if($key = array_search('create payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete payment '.$module,(array) $permissions))
                                                        @if($key = array_search('delete payment '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income '.$module,(array) $permissions))
                                                        @if($key = array_search('income '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('expense '.$module,(array) $permissions))
                                                        @if($key = array_search('expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('income vs expense '.$module,(array) $permissions))
                                                        @if($key = array_search('income vs expense '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('loss & profit '.$module,(array) $permissions))
                                                        @if($key = array_search('loss & profit '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('tax '.$module,(array) $permissions))
                                                        @if($key = array_search('tax '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if(in_array('invoice '.$module,(array) $permissions))
                                                        @if($key = array_search('invoice '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('bill '.$module,(array) $permissions))
                                                        @if($key = array_search('bill '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('duplicate '.$module,(array) $permissions))
                                                        @if($key = array_search('duplicate '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('balance sheet '.$module,(array) $permissions))
                                                        @if($key = array_search('balance sheet '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Balance Sheet',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('ledger '.$module,(array) $permissions))
                                                        @if($key = array_search('ledger '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Ledger',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(in_array('trial balance '.$module,(array) $permissions))
                                                        @if($key = array_search('trial balance '.$module,$permissions))
                                                            <div class="col-md-3 custom-control custom-checkbox">
                                                                {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck administrative_checkall isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                                {{Form::label('permission'.$key,'Trial Balance',['class'=>'custom-control-label'])}}<br>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>

{{Form::close()}}

<script>
    $(document).ready(function () {
        $("#bplodetails_checkall").click(function(){
            $('.bplodetails_checkall').not(this).prop('checked', this.checked);
        });
        $("#cto_checkall").click(function(){
            $('.cto_checkall').not(this).prop('checked', this.checked);
        });
        $("#planning_checkall").click(function(){
            $('.planning_checkall').not(this).prop('checked', this.checked);
        }); 
         $("#healthsanitary_checkall").click(function(){
            $('.healthsanitary_checkall').not(this).prop('checked', this.checked);
        });
        $("#project_checkall").click(function(){
            $('.project_checkall').not(this).prop('checked', this.checked);
        });
        $("#assessment_checkall").click(function(){
            $('.assessment_checkall').not(this).prop('checked', this.checked);
        });
        $("#hrm_checkall").click(function(){
            $('.hrm_checkall').not(this).prop('checked', this.checked);
        });
        $("#point_checkall").click(function(){
            $('.point_checkall').not(this).prop('checked', this.checked);
        });
         $("#administrative_checkall").click(function(){
            $('.administrative_checkall').not(this).prop('checked', this.checked);
        });
        $("#fees_checkall").click(function(){
            $('.fees_checkall').not(this).prop('checked', this.checked);
        });
        $(".ischeck").click(function(){
            var ischeck = $(this).data('id');
            $('.isscheck_'+ ischeck).prop('checked', this.checked);
        });
    });
</script>
