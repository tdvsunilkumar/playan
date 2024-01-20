@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
    {{__('Profile Account')}}
@endsection
@push('script-page')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function(){
            $('.list-group-item').filter(function(){
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Profile')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3">
            <div class="card sticky-top" style="top:30px">
                <div class="list-group list-group-flush" id="useradd-sidenav">
                    <a href="#personal_info" class="list-group-item list-group-item-action border-0">{{__('Personal Info')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                </div>
            </div>
        </div>
        <div class="col-xl-9">
            <div id="personal_info" class="card">
                <div class="card-header">
                    <h5>{{('Personal Info')}}</h5>
                </div>
                <div class="card-body">
                    {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'post', 'enctype' => "multipart/form-data"))}}
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group mb-0">
                                        <label class="col-form-label text-dark">{{__('Name')}}</label>
                                        <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="name" placeholder="{{ __('Enter Your Name') }}" value="{{ $userDetail->name }}" required autocomplete="name">
                                        @error('name')
                                        <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label text-dark">{{__('Email')}}</label>
                                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $userDetail->email }}" required autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <div class="choose-files">
                                            <label for="avatar">
                                                <div class=" bg-primary profile_update"> <i class="ti ti-upload px-1"></i>{{__('Upload Avatar')}}</div>
                                                <input type="file" class="form-control file" name="profile" id="avatar" data-filename="profile_update">
                                            </label>
                                        </div>
                                        <span class="text-xs text-muted">{{ __('Please upload a valid image file. Size of image should not be more than 2MB.')}}</span>
                                        @error('avatar')
                                        <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<div class="form-group">
												@if($eSignatureExist > 0)
												<input type="checkbox" id="active-check" name="is_active_e_sign" {{ $userDetail->is_active_e_sign == 1 ? 'checked' : ''}} />
												@else
												<input type="checkbox" id="active-check" name="is_active_e_sign" />
												@endif
												<label for="active-check">Activate E-Signature</label>
											</div>
										</div>
										<div class="col-lg-6 col-md-6">
											<div class="form-group">
												<input type="checkbox" id="is_activate_digitalsignature"  name="is_activate_digitalsignature" {{ $userDetail->is_activate_digitalsignature == 1 ? 'checked' : ''}} />
												<label for="active-check">Activate Digital Signature</label>
											</div>
										</div>
									</div>
                                </div>
                                
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-0">
                                    <label class="col-form-label text-dark">{{__('E-Signature')}}</label>
                                </div>
                                <div class="e-signatures fronts">                            
                                    <img src="{{ $eSignature }}"/>
                                    <button type="button" class="bg-primary btn-sm text-white mt-2" id="btnofLotModal" style="border-radius: 10px;padding: 8px 15px;font-size: 12px;font-weight: 500;border: transparent;">Change e-Signature</button>
                                </div>
                                <div class="choose-files" style="position: absolute; margin-top: -35px; margin-left: 145px;">
                                    <label>
                                        <div class="bg-primary profile_update"> <i class="ti ti-upload px-1"></i>{{__('Upload Signature')}}</div>
                                        <input type="file" class="form-control file" name="e_sign" accept="image/png">
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 text-end">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </form>
                </div>
				<div class="modal top fade" id="NumberofLotModal">
					<div class="modal-dialog modal-fullscreen">
						<div class="modal-content">
						   <div class="modal-header">
								<h4 class="modal-title">{{__('E-Signature')}}</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						   </div>
							<div class="container">
								{{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'post', 'enctype' => "multipart/form-data"))}}
								<div class="modal-body">
										@csrf
										<input class="form-control @error('name') is-invalid @enderror" name="name" type="hidden" id="name" placeholder="{{ __('Enter Your Name') }}" value="{{ $userDetail->name }}" required autocomplete="name">
										<input class="form-control @error('email') is-invalid @enderror" name="email" type="hidden" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $userDetail->email }}" required autocomplete="email">
										<input type="hidden" id="active-check" name="is_active_e_sign" value="{{ $userDetail->is_active_e_sign }}">
										<input type="hidden" id="active-check" name="is_activate_digitalsignature" value="{{ $userDetail->is_activate_digitalsignature }}">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group mb-0">
													<label class="col-form-label text-dark">{{__('E-Signature')}}</label>
												</div>
												<div class="e-signature front {{ ($eSignatureExist > 0) ? '' : 'hidden' }}">                            
													<img src="{{ $eSignature }}"/>
													<button type="button" class="change-btn btn bg-primary btn-sm text-white mt-2" id="btnofLotModal">Change e-Signature</button>
												</div>
												<div class="e-signature back {{ ($eSignatureExist > 0) ? 'hidden' : '' }}">
													<div id="sig" class="mt-2"></div>
													<button type="button" class="clear-btn btn bg-primary btn-sm text-white mt-2">Clear e-Signature</button>
													<textarea id="signature64" name="signature" style="display: none"></textarea>
												</div>
												<div class="choose-files" style="position: absolute; margin-top: -35px; margin-left: 145px;">
													<label>
														<div class="bg-primary profile_update"> <i class="ti ti-upload px-1"></i>{{__('Upload Signature')}}</div>
														<input type="file" class="form-control file" name="e_sign" accept="image/png">
													</label>
												</div>
											</div>
										</div>
									
								</div>    
							</div>
							<div class="modal-footer"> 
								<input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeOrderModal" data-bs-dismiss="modal">
							   <input type="submit" name="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
							</div>
							{{Form::close()}}
							
						</div>
					 </div> 
				</div>  
            </div>
            <div id="change_password" class="card">
                <div class="card-header">
                    <h5>{{('Change Password')}}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('update.password')}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 form-group">
                                <label for="old_password" class="col-form-label text-dark">{{ __('Old Password') }}</label>
                                <input class="form-control @error('old_password') is-invalid @enderror" name="old_password" type="password" id="old_password" required autocomplete="old_password" placeholder="{{ __('Enter Old Password') }}">
                                @error('old_password')
                                <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-sm-6 form-group">
                                <label for="password" class="col-form-label text-dark">{{ __('Password') }}</label>
                                <input class="form-control @error('password') is-invalid @enderror" name="password" type="New password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                                @error('password')
                                <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-6 form-group">
                                <label for="password_confirmation" class="col-form-label text-dark">{{ __('New Confirm Password') }}</label>
                                <input class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" type="password" required autocomplete="new-password" id="password_confirmation" placeholder="{{ __('Enter Your Password') }}">
                            </div>
                            <div class="col-lg-12 text-end">
                                <input type="submit" value="{{__('Change Password')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/e-signature/css/jquery.signature.css?v='.filemtime(getcwd().'/assets/vendors/e-signature/css/jquery.signature.css').'') }}"/>
<style>
	.modal-content {
		width: 95%;
		margin-left: 30px;
	}
    .kbw-signature {
        width: 1472px;
        height: 672px;
        border: 1px solid #ced4da;
        margin-top: 0px !important;
        border-radius: 6px !important;
        display: block;
        clear: both;
    }
    .e-signature.front img {
        width: 100%;
        height: 700px;
        border: 1px solid #ced4da;
        border-radius: 6px !important;
        display: block;
        clear: both;
    }
	.e-signatures.fronts img {
        width: 275px;
        height: 144px;
        border: 1px solid #ced4da;
        border-radius: 6px !important;
        display: block;
        clear: both;
    }
    #sig canvas {
        width: 100% !important;
        height: auto;
        border-radius: 6px !important;
    }
    .change-btn,
    .clear-btn {
        border-radius: 10px !important;
        padding: 8px 15px;
        font-size: 12px;
        font-weight: 500;
        border: transparent;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/e-signature/js/jquery.signature.min.js?v='.filemtime(getcwd().'/assets/vendors/e-signature/js/jquery.signature.min.js').'') }}"></script>
<script>
    var sig = $('#sig').signature({
        syncField: '#signature64',
        syncFormat: 'PNG',
        background: 'transparent',
        thickness: 10
    });
    $('.clear-btn').click(function(e) {
        e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });
    $('.change-btn').click(function(e) {
        e.preventDefault();
        $('.e-signature.back').removeClass('hidden');
        $('.e-signature.front').addClass('hidden');
    });
	$('#is_activate_digitalsignature').on('change', function(){
	   this.value = this.checked ? 1 : 0;
	}).change();
	
	$("#btnofLotModal").click(function(){
		 $("#NumberofLotModal").modal('show');
	});
	$(".closeOrderModal").click(function(){
		 $("#NumberofLotModal").modal('hide');
 	});
</script>
@endpush
