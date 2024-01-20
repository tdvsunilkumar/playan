var pageLength='25';
var DIR=$("#DIR").val();
$(document).ready(function(){
    $("#filter_box").click(function(){
        $("#this_is_filter").slideToggle(300);
        $("#filter_box").toggleClass('active');
    });
    $("#btn_clear").on('click', function(e){
        window.location.href=location.pathname;
    });

    if($('.datatable').length) {
        $('.datatable').DataTable();
    }
    if($('.numeric').length) {
        $('.numeric').numeric();
    }
});

// $(document).ready(function(){
//     $("#filter_box1").click(function(){
//         $("#this_is_filter1").slideToggle(300);
//         $("#filter_box1").toggleClass('active');
//     });
//     $("#btn_clear").on('click', function(e){
//         window.location.href=location.pathname;
//     });

//     if($('.datatable').length) {
//         $('.datatable').DataTable();
//     }
//     if($('.numeric').length) {
//         $('.numeric').numeric();
//     }
// });

$(document).ready(function(){
    $("#add-doc").click(function(){
        $("#doc-form").slideToggle(300);
        $("#add-doc").toggleClass('active');
    });
    $("#btn_clear").on('click', function(e){
        window.location.href=location.pathname;
    });

    if($('.datatable').length) {
        $('.datatable').DataTable();
    }
    if($('.numeric').length) {
        $('.numeric').numeric();
    }
});

function get_page_number(total_cnt,selectedNumb=0){
    setTimeout(function(){ 
        $(".showLess").shorten({
            "showChars" : 30,
            "moreText"	: "More",
            "lessText"	: "Less",
        });
    }, 500);
    var val=total_cnt;
    if(val == "NULL")
    {
        return "";
    }
    var total_cnt=parseInt(total_cnt);
    var arr_num=['10','25','50','75','100','200','300','400'];
    var dropdown_html="";
    var i;
    //alert(total_cnt);
    for(i=0; i<=arr_num.length; i++){
        if(parseInt(arr_num[i])<total_cnt){
            selected="";
            if(i==0 || selectedNumb==arr_num[i])
                selected="selected='selected'";

            dropdown_html+="<option value='"+arr_num[i]+"' "+selected+">Show "+arr_num[i]+" / "+total_cnt+" </option>";
        }
    }
    
    if (jQuery.inArray(total_cnt, arr_num)=='-1'){
        selected="";
        if(selectedNumb==total_cnt)
            selected="selected='selected'";

       dropdown_html+="<option value='"+total_cnt+"' "+selected+">Show "+total_cnt+" / "+total_cnt+" </option>";
    }
    
    if(dropdown_html!=""){
        return "<select class='form-control' id='common_pagesize'>"+dropdown_html+"</select> Per Page";
    }
    return "";
}

function select3Ajax(id,parentId,Url,length=0){
    $("#"+id).select3({
        allowClear: true,
        dropdownAutoWidth : false,
        dropdownParent: $("#"+parentId),
        minimumInputLength: length,
        placeholder: 'Select Option',
        ajax: {
            url: DIR+Url,
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params,val) {
                return {
                    search: params.term,
                    page: params.page || 1,
                };
            },
            processResults: function (data,params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: (params.page * 20) < data.data_cnt
                    }
                };
            },
            cache: true
        }
    }).val($("#"+id).val()).trigger('change');
}

function select3AjaxCommunity(id,parentId,Url,length=0){
    $("#"+id).select3({
        allowClear: true,
        dropdownAutoWidth : false,
        dropdownParent: $("#"+parentId),
        minimumInputLength: length,
        placeholder: 'Select Option',
        ajax: {
            url: DIR+Url,
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params,val) {
                return {
                    search: params.term,
                    page: params.page || 1,
                    payee_type:$("input[type='radio'][name='payee_type']:checked").val(),
                };
            },
            processResults: function (data,params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: (params.page * 20) < data.data_cnt
                    }
                };
            },
            cache: true
        }
    }).val($("#"+id).val()).trigger('change');
} 

function select3AjaxFalcno(id,parentId,Url,length=0){
    if($('#clientrefenere').is(":checked")){
        var isrefrence = 1; 
      }else{
        var isrefrence = 0;
      }
    $("#"+id).select3({
        allowClear: true,
        dropdownAutoWidth : false,
        dropdownParent: $("#"+parentId),
        minimumInputLength: length,
        placeholder: 'Select Falc No',
        ajax: {
            url: DIR+Url,
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params,val) {
                return {
                    search: params.term,
                    page: params.page || 1,
                    cleintid :$("#client_id option:selected").val(),
                    isrefrence :isrefrence,
                };
            },
            processResults: function (data,params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: (params.page * 20) < data.data_cnt
                    }
                };
            },
            cache: true
        }
    }).val($("#"+id).val()).trigger('change');
} 


function select3AjaxPermitno(id,parentId,Url,length=0){
    $("#"+id).select3({
        allowClear: true,
        dropdownAutoWidth : false,
        dropdownParent: $("#"+parentId),
        minimumInputLength: length,
        placeholder: 'Select Permit No',
        ajax: {
            url: DIR+Url,
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params,val) {
                return {
                    search: params.term,
                    page: params.page || 1,
                    cleintid :$("#p_code").val(),
                };
            },
            processResults: function (data,params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: (params.page * 20) < data.data_cnt
                    }
                };
            },
            cache: true
        }
    }).val($("#"+id).val()).trigger('change');
}  

function select3Ajaxorno(id,parentId,Url,length=0){
    $("#"+id).select3({
        allowClear: true,
        dropdownAutoWidth : false,
        dropdownParent: $("#"+parentId),
        minimumInputLength: length,
        ajax: {
            url: DIR+Url,
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params,val) {
                return {
                    search: params.term,
                    page: params.page || 1, 
                    id:$("#rpc_requestor_code option:selected").val(),
                    taxpayerid:$("#rpc_owner_code option:selected").val(),
                };
            },
            processResults: function (data,params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: (params.page * 20) < data.data_cnt
                    }
                };
            },
            cache: true
        }
    }).val($("#"+id).val()).trigger('change');
}  
