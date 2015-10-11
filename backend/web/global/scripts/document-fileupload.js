

var DocumentFileUpload = function () {


    return {
        //main function to initiate the module
        init: function () {

             // Initialize the jQuery File Upload widget:
            $('#documentfileupload').fileupload({
            	url: Metronic.getBaseUrl() + 'document/upload',//$('#fileupload').attr("act"),
                dataType: 'json',
                //disableImageResize: false,
                autoUpload: false,
                disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                maxFileSize: 10000000, //10M
                acceptFileTypes: /(\.|\/)(doc|docx|pdf|xlsx|xls|txt|mp3|wma)$/i,
                done: docUploadCompleteHandler, //the uploadCompleteHandler is in media-list.js
               // send: function(){debugger;},
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},                
            });

            // Enable iframe cross-domain access via redirect option:
            $('#documentfileupload').fileupload(
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
                        .appendTo('#documentfileupload');
                });
            }

            // Load & display existing files:
            $('#documentfileupload').addClass('fileupload-processing');
            
         // initialize uniform checkboxes  
            //Metronic.initUniform('.fileupload-toggle-checkbox');
        }

    };

}();