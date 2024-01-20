var slug = 'social-welfare/pwd-id';

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
			{ "data": "type" },
			{ "data": "number" },
			{ "data": "disability" },
			{ "data": "is_active" },
			{ "data": "action" }
		],
		order = [0,8],
	);
}

