/*
if (typeof uploadCompleteHandler == 'undefined') {
	uploadCompleteHandler = function() {
		$('.template-upload').remove();
	};
}



var FormFileUpload = function () {
    return {
        //main function to initiate the module
        init: function () {
        	
            $('#fileupload').fileupload({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                autoUpload: false,
                url: Metronic.getBaseUrl() + 'media/upload',
                done: uploadCompleteHandler,
               
            });

            debugger;
            // initialize uniform checkboxes  
            Metronic.initUniform('.fileupload-toggle-checkbox');
        }

    };

}();

*/




var FormFileUpload = function () {


    return {
        //main function to initiate the module
        init: function () {

             // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
            	url: Metronic.getBaseUrl() + 'media/upload',//$('#fileupload').attr("act"),
                dataType: 'json',
                disableImageResize: false,
                autoUpload: false,
                disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                maxFileSize: 5000000,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                done: uploadCompleteHandler, //the uploadCompleteHandler is in media-list.js
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},                
            });

            // Enable iframe cross-domain access via redirect option:
            $('#fileupload').fileupload(
                'option',
                'redirect',
                window.location.href.replace(
                    /\/[^\/]*$/,
                    '/cors/result.html?%s'
                )
            );

            // Upload server status check for browsers with CORS support:
            if ($.support.cors) {
                $.ajax({
                    type: 'HEAD'
                }).fail(function () {
                    $('<div class="alert alert-danger"/>')
                        .text('Upload server currently unavailable - ' +
                                new Date())
                        .appendTo('#fileupload');
                });
            }

            // Load & display existing files:
            $('#fileupload').addClass('fileupload-processing');
            
         // initialize uniform checkboxes  
            Metronic.initUniform('.fileupload-toggle-checkbox');
        }

    };

}();