define(function(require) {
	var $ = require("jquery");
		
		// cada vez que se pincha un checkbox se recorren todos para actualizar el hidden
		$('.dvOtherEntities input[type=checkbox]').click(function(){
			$('#inOtherEntities').val("");
			$('.dvOtherEntities input[type=checkbox]').each(function(){
				if ( $(this).is(':checked') ) {
					// ugly hack para que no pinte la primera coma
					if ( $('#inOtherEntities').val() == "") {
						$('#inOtherEntities').val( $(this).val() );
					} else {
						$('#inOtherEntities').val( $('#inOtherEntities').val() + ',' + $(this).val() );
					}
				}
			});
		});
});