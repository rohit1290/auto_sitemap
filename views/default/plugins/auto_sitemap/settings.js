define(function(require) {
	var $ = require("jquery");

		$('.dvOtherEntities input[type=checkbox]').click(function(){
			$('#inOtherEntities').val("");
			$('.dvOtherEntities input[type=checkbox]').each(function(){
				if ( $(this).is(':checked') ) {
					if ( $('#inOtherEntities').val() == "") {
						$('#inOtherEntities').val( $(this).val() );
					} else {
						$('#inOtherEntities').val( $('#inOtherEntities').val() + ',' + $(this).val() );
					}
				}
			});
		});
});