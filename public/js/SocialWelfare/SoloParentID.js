var slug = 'social-welfare/solo-parent-id';

$(document).ready(function(){	
	
});

function datatablefunction() {
	drawTable(
		slug = slug,
		columns = [
			{ "data": "srno" },
			{ "data": "fullname" },
        	{ "data": "address" },
        	{ "data": "age" },
        	{ "data": "id_number" },
        	{ "data": "is_active" },
        	{ "data": "action" }
		],
		order = [0,5],
	);
}