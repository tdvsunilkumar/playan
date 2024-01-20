var slug = 'social-welfare/setup-data/policy';

$(document).ready(function(){	
	
});


function datatablefunction() {
	drawTable(
		slug = slug,
		columns = [
			{ "data": "srno" },
			{ "data": "key" },
			{ "data": "value" },
			{ "data": "notes" },
			{ "data": "is_active" },
			{ "data": "action" }
		],
		order = [0,3],
	);
}