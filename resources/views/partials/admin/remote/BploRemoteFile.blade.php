@if(session()->has('remort_serv_session_det_req'))
    @php
    $remortSession = Session::get('remort_serv_session_det_req', []);
    $remortReqTable = $remortSession['remort_req_table'] ?? null;
    $remortReqAction = $remortSession['remort_req_action'] ?? null;
    $remortReqId = $remortSession['remort_req_id'] ?? [];
    $remortReqErServId= $remortSession['remort_req_er_serv_id'] ?? null;
    @endphp

    {{ Form::hidden('method_req', $remortReqTable, ['id' => 'method_req']) }}
    {{ Form::hidden('action_req', $remortReqAction, ['id' => 'action_req']) }}
    {{ Form::hidden('method_req_id', json_encode($remortReqId), ['id' => 'method_req_id']) }}
    {{ Form::hidden('method_req_er_serv_id', $remortReqErServId, ['id' => 'method_req_er_serv_id']) }}
@endif
@if(session()->has('remort_serv_add_array_data'))
    @php
    $remortSession = Session::get('remort_serv_add_array_data', []);
    $remortArrayTable = $remortSession['remort_array_table'] ?? null;
    $remortArrayAction = $remortSession['remort_array_action'] ?? null;
    $remortArrayIds = $remortSession['remort_array_id'] ?? [];
    @endphp

    {{ Form::hidden('method_array', $remortArrayTable, ['id' => 'method_array']) }}
    {{ Form::hidden('action_array', $remortArrayAction, ['id' => 'action_array']) }}
    {{ Form::hidden('method_array_ids', json_encode($remortArrayIds), ['id' => 'method_array_ids']) }}
@endif
@php  Session::forget('remort_serv_session_det'); Session::forget('remort_serv_session_det_req'); Session::forget('remort_serv_add_array_data');@endphp

@if(session()->has('REMOTE_UPDATED_BUSINESS_TABLE'))
    {{ Form::hidden('REMOTE_UPDATED_BUSINESS_TABLE', Session::get('REMOTE_UPDATED_BUSINESS_TABLE'), ['id' => 'REMOTE_UPDATED_BUSINESS_TABLE']) }}
    @php  Session::forget('REMOTE_UPDATED_BUSINESS_TABLE'); @endphp

@endif