var slug = 'social-welfare/senior-citizen-id';

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
        	{ "data": "osca" },
        	{ "data": "fscap" },
        	{ "data": "is_active" },
        	{ "data": "action" }
		],
		order = [0,7],
	);
}