var slug = 'social-welfare/assistance';

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
			{ "data": "assistance" },
			{ "data": "amount" },
			{ "data": "approver" },
			{ "data": "is_active" },
			{ "data": "action" }
		],
		order = [0,7],
	);
}