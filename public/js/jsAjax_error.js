throw "Catchpoint JavaScript Error 2";

function testAjax() {
	jQuery.ajax({
		url: setState,
		//async: false,
		type: 'GET',
		success: function() {
			console.log('Success');
		}		
	});
}

testAjax();
