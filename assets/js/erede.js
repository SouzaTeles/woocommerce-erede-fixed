(function( $ ) {
	'use strict';

	$( function() {

		var checkInputClass = $("#checkInputClass");
		var checkIfInputHasDefaultBehavior = checkInputClass.length && checkInputClass.css("background-image").includes("url");
		
		if(!checkIfInputHasDefaultBehavior)
		{	
			return;
		}

		// Add jQuery.Payment support for Hippercard and Hyper
		if ( $.payment.cards ) {
			var cards = [];

			$.each( $.payment.cards, function( index, val ) {
				cards.push( val.type );
			});

            if ( -1 === $.inArray( 'hiper', cards ) ) {
				$.payment.cards.unshift({
					type: 'hiper',
					patterns: [637609, 637599, 637612, 637095],
					pattern: /^(637(095|612|599|609))/,
					format: /(\d{1,4})/g,
					length: [16],
					cvcLength: [3],
					luhn: true
				});

				cards.unshift('hiper');
			}

            if ( -1 === $.inArray( 'hipercard', cards ) ) {
				$.payment.cards.unshift({
					type: 'hipercard',
					patterns: [606282],
					pattern: /^606282/,
					format: /(\d{1,4})/g,
					length: [16],
					cvcLength: [3],
					luhn: true
				});

				cards.unshift('hipercard');
			}

			if ( -1 === $.inArray( 'credz', cards ) ) {
				$.payment.cards.unshift({
					type: 'credz',
					pattern: /^63(6[7-9][6-9][0-9]|70[0-3][0-2])/,
					patterns: [],
					format: /(\d{1,4})/g,
					length: [16],
					cvcLength: [3],
					luhn: true
				});

				cards.unshift('credz');
			}

		    if ($.inArray( 'jcb', cards )) {
				var index = $.inArray( 'jcb', cards );
				$.payment.cards[index].length.push(19);
			}
		}
	});

}( jQuery ));