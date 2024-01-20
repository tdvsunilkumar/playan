$(document).ready(function(){	
	callRemort();
});

function callRemort()
{
    if( typeof $("#method_id").val() != 'undefined' || typeof $("#method_req").val() != 'undefined' || typeof $("#method_array").val() != 'undefined' )
    {
        if( typeof $("#method_id").val() != 'undefined' ){
                var data = {
                    method_id:$("#method_id").val(),
                    method:$("#method").val(),
                    action:$("#action").val(),
                }; 
            }
        if( typeof $("#method_req").val() != 'undefined' ){
                var data = {
                    method_req:$("#method_req").val(),
                    action_req:$("#action_req").val(),
                    method_req_id:$("#method_req_id").val(),
                    method_req_er_serv_id:$("#method_req_er_serv_id").val(),
                }; 

            }
        if( typeof $("#method_array").val() != 'undefined' ){
                var data = {
                    method_array:$("#method_array").val(),
                    action_array:$("#action_array").val(),
                    method_array_ids:$("#method_array_ids").val()
                }; 

            }  
        if( typeof $("#method_req_rltn").val() != 'undefined' ){
                var data = {}; // Declare an empty data object
                // var data = {
                //     method_req_rltn:$("#method_req_rltn").val(),
                //     action_req_rltn:$("#action_req_rltn").val(),
                //     method_req_rltn_ids:$("#method_req_rltn_ids").val(),
                // }; 
                data.method_req_rltn = $("#method_req_rltn").val();
                data.action_req_rltn = $("#action_req_rltn").val();
                data.method_req_rltn_ids = $("#method_req_rltn_ids").val();
                if (typeof $("#method_array").val() !== 'undefined') {
                    data.method_array = $("#method_array").val();
                    data.action_array = $("#action_array").val();
                    data.method_array_ids = $("#method_array_ids").val();
                }
                if( typeof $("#method_id").val() != 'undefined' ){
                    data.method_id = $("#method_id").val();
                    data.method = $("#method").val();
                    data.action = $("#action").val();
                }
            }      
            $.ajax({
                type: "post",
                url: DIR+'api/remortMasterApi',
                data: data,
                dataType: "json",
                success: function(html){ 
                }
            });
    }     

    // Update Bplo Business   
    if( typeof $("#REMOTE_UPDATED_BUSINESS_TABLE").val() != 'undefined' && $("#REMOTE_UPDATED_BUSINESS_TABLE").val()>0){
        $.ajax({
            type: "post",
            url: DIR+'api/remoteUpdateBusinessTable',
            data: {
                busn_id:$("#REMOTE_UPDATED_BUSINESS_TABLE").val()
            },
            dataType: "json",
            success: function(html){ 
            }
        });
    }

}