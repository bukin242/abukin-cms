$(document).bind('ready', function (){
	var page = /Page([^#&]*).html/.exec(window.location.href);
	page = page ? page[1] : 1;
	
	$('#paginator').paginator({pagesTotal:30, 
		pagesSpan:11, 
		pageCurrent:page, 
		baseUrl: 'Page%number%.html',
		lang: {
			next  : "",
			last  : "",
			prior : "",
			first : "",
			arrowRight : String.fromCharCode(8594),
			arrowLeft  : String.fromCharCode(8592)
		}
	});
})
