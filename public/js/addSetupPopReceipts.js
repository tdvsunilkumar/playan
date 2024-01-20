function qty(){  
	var stp_qty = document.getElementById("stp_qty");
    var serial_no_from=document.getElementById("serial_no_from").value;
	var serial_no_to=document.getElementById("serial_no_to").value;
    for(var i =0; i<2; i++){
		if(serial_no_from==""){
			serial_no_from=0;
		}
        if(serial_no_to==""){
			serial_no_to=0;
		}
    }
    
    var subtotal = parseInt(serial_no_to) - parseInt(serial_no_from);
    stp_qty.value = subtotal;
    serial_no_to_validation();
}
function serial_no_to_validation(){
    'use strict';
    var serial_no_to = document.getElementById("serial_no_to");
    var serial_no_to_value = document.getElementById("serial_no_to").value;
    var serial_no_from = document.getElementById("serial_no_from");
    var serial_no_from_value = document.getElementById("serial_no_from").value;
    var numbers = /^[0-9]+$/;
    if( serial_no_to_value < serial_no_from_value||  !serial_no_to_value.match(numbers))
    {
       document.getElementById('serial_no_to_err').innerHTML = 'Please Enter maximum value.';
       serial_no_to.focus();
       document.getElementById('serial_no_to_err').style.color = "#FF0000";
    }
    else
    {
      document.getElementById('serial_no_to_err').innerHTML = '';
      document.getElementById('serial_no_to_err').style.color = "#00AF33";
      
    }
}