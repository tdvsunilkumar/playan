$(document).ready(function(){	
	$("#Section").select3({ dropdownAutoWidth: false });
	select3Ajax("Section","this_is_filter","sectionAjaxList");
	$("#Division").select3({ dropdownAutoWidth: false });
	$("#Group").select3({ dropdownAutoWidth: false });
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	 $('#Section').on('change', function() {
        var section_id =$(this).val();
        divisionId(section_id);
       $.ajax({
            url :DIR+'divisionAllData', // json datasource
            type: "POST", 
            data: {
                    "section_id": section_id,
                    "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#Division").html(html);       
                 }
        })
   });
   $('#Division').on('change', function() {
        var division_id =$(this).val();
        groupId(division_id);
       $.ajax({
            url :DIR+'groupAllData', // json datasource
            type: "POST", 
            data: {
                    "division_id": division_id,
                    "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#Group").html(html);         
           }
        })
   });
   
});
function divisionId(section_id){
   $("#Division").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#this_is_filter").parent(),
    ajax: {
        url: DIR+'divisionAjaxList',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                "id": section_id, 
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}

function groupId(division_id){
   $("#Group").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#this_is_filter").parent(),
    ajax: {
        url: DIR+'groupAjaxList',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                "id": division_id, 
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}
function datatablefunction()
{
	var dropdown_html=get_page_number('1'); 
	var table = $('#Jq_datatablelist').DataTable({ 
		"language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
            oLanguage: {
	         	sLengthMenu: dropdown_html
        },
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0,7] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'psicclass/getList', // json datasource
			type: "GET", 
			"data": {
				"Section":$("#Section").val(),
				"Division":$("#Division").val(),
				"Group":$("#Group").val(),
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno" },
        	{ "data": "class_code" },
        	{ "data": "section_description" },
        	{ "data": "division_description" },
        	{ "data": "group_description" },
        	{ "data": "class_description" },
        	{ "data": "is_active" },
            { "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	    }
	});  
}
