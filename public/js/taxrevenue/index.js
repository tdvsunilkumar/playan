$(document).ready(function(){	
    $('#rptPropertySearchByPkCode').select3({dropdownAutoWidth : false,dropdownParent : '#this_is_filter'});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();

 	});	
    $(document).on('change','#rptPropertySearchByPkCode',function(){
        datatablefunction();
        
    });

 	$(document).on('change','.basic_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.basic_tfoc_id_desc').val(jsonData[selectedValue]);
 		
 	});

 	$(document).on('change','.sef_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.sef_tfoc_id_desc').val(jsonData[selectedValue]);
 	});

 	$(document).on('change','.sh_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.sh_tfoc_id_desc').val(jsonData[selectedValue]);
 	});
    $(document).on('change','.basic_discount_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.basic_discount_tfoc_id_desc').val(jsonData[selectedValue]);
    });
    $(document).on('change','.basic_penalty_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.basic_penalty_tfoc_id_desc').val(jsonData[selectedValue]);
    });
    $(document).on('change','.sef_discount_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.sef_discount_tfoc_id_desc').val(jsonData[selectedValue]);
    });
    $(document).on('change','.sef_penalty_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.sef_penalty_tfoc_id_desc').val(jsonData[selectedValue]);
    });
    $(document).on('change','.sh_discount_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.sh_discount_tfoc_id_desc').val(jsonData[selectedValue]);
    });
    $(document).on('change','.sh_penalty_tfoc_id',function(){
        var jsonData = $('#taxFeesJsonData').val();
        jsonData = JSON.parse(jsonData);
        var selectedValue = $(this).val();
        $('.sh_penalty_tfoc_id_desc').val(jsonData[selectedValue]);
    });
 	$(document).on('submit','#taxRevenueForm',function(e){
 		e.preventDefault();
 		var id   = $(this).data('id');
 		var data = $(this).serialize();
 		var url  = DIR+'taxrevenue/store';
 		$.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            if(html.status == 'success'){
            	$('#commonModal').modal('hide');
            	Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    });
                datatablefunction();
                
            }if(html.error == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    });
            	datatablefunction();
            }
        }
    });

 	});


});

function datatablefunction()
{
	var dropdown_html=get_page_number('1'); 
	var table = $('#Jq_datatablelist').DataTable({ 
		"language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'trform>>" +"<'row'<'col-sm-12'p>>",
            oLanguage: {
	         	sLengthMenu: dropdown_html
        },
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'taxrevenue/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#rptPropertySearchByText").val(),
				/*'year':$('#yeardate').val(),
				'approve':$('#aproved').val(),*/
				'pk_code':$('#rptPropertySearchByPkCode').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "sr_no" },
            { "data": "kind" },
        	{ "data": "tax_name" },
			{ "data": "tax_description" },
        	{ "data": "tax_what_year" },
        	{ "data": "basic_tfoc_id" },
        	{ "data": "basic_discount_tfoc_id" },
            { "data": "basic_penalty_tfoc_id" },
            { "data": "sef_tfoc_id" },
            { "data": "sef_discount_tfoc_id" },
            { "data": "sef_penalty_tfoc_id" },
            { "data": "sh_tfoc_id" },
            { "data": "sh_discount_tfoc_id" },
            { "data": "sh_penalty_tfoc_id" },
            { "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	        $('.makeForSelect3').select3({});
	    }
	});  
    
}