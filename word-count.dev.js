/* This piece of code is copyed from wp-admin/js/word-count.js, fix word counting bug */
(function($) {
	wpWordCount = {

		settings : {
			count : /\S/g // counting regexp
		},

		block : 0,

		wc : function(tx) {
			var t = this, w = $('.word-count'), tc = 0;

			if ( t.block )
				return;

			t.block = 1;

			setTimeout( function() {
				if ( tx ) {
					tx = htmlTagFilter(tx);
					tx.replace( t.settings.count, function(){tc++;} );
				}
				w.html(tc.toString());

				setTimeout( function() { t.block = 0; }, 2000 );
			}, 1 );
		}
	}
	
	function htmlTagFilter(html) {
		return $('<div />', {html: html}).text();
	}
	
	$(document).bind( 'wpcountwords', function(e, txt) {
		wpWordCount.wc(txt);
	});
}(jQuery));
