var slug = 'social-welfare/travel-clearance-minor';

$(document).ready(function(){	
	
});

function datatablefunction() {
	drawTable(
		slug = slug,
		columns = [
			{ "data": "srno" },
        	{ "data": "companion_name" },
        	{ "data": "companion_add" },
        	//{ "data": "companion_age" },
        	{ "data": "companion_relation" },
        	{ "data": "is_active" },
        	{ "data": "action" }
        ],
		order = [0,5],
	);
}