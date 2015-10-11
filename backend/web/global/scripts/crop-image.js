   var UICropImageModals = function () {

    return {
        //main function to initiate the module
        init: function () {
        	var globalItemNo = null;
        	$("#jcrop_create-portlet").click(function() {        		
        		$.post(App.baseUri + 'media/crop', {
            		id : $('#media-tab').attr('data-id'),
            		entity: $('#media-tab').attr('data-entity'),
            		itemNo: globalItemNo,
            		x1 : document.getElementById("x").value,
            		y1 : document.getElementById("y").value,
            		x2 : document.getElementById("x2").value,
            		y2 : document.getElementById("y2").value,
            	}).done(function() {
            		
            		textResponse("บันทึกสำเร็จ");
            	});
        	});
        	
            // general settings
            $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
              '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                '<div class="progress progress-striped active">' +
                  '<div class="progress-bar" style="width: 100%;"></div>' +
                '</div>' +
              '</div>';

            $.fn.modalmanager.defaults.resize = true;

            var $modal = $('#crop-modal');   
            var jcrop_api;

            $('#picture-1').Jcrop({
            	onSelect : showCoords,
        		onChange : showCoords,
                onRelease:  clearCoords,
            	bgOpacity : 0.3,
        		disable : true,
              },function(){
                jcrop_api = this;
              });
            
            function showCoords(c) {            	
            	$('#x').val(c.x);
            	$('#y').val(c.y);
            	$('#x2').val(c.x2);
            	$('#y2').val(c.y2);
            };
         
            function clearCoords()
            {            	
                $('#coords input').val('');
            };
            $(".jc_coords").hide();

            $('#media-tab').delegate('a.thumbnail', 'dblclick', function(event) {
            	$(this).data('click', 2);
            	// create the backdrop and wait for next modal to be triggered
            	var entity= $('#media-tab').attr('data-entity');
            	var id = $('#media-tab').attr('data-id');
        		var itemNo = $(this).attr('data-itemNo');
        		globalItemNo = $(this).attr('data-itemNo');
        		
                $.get(App.baseUri + 'media/info', {
            		1: entity,
            		2: id,
            		3: itemNo
            	}).done(function(data) {
            		
                   $('#crop-modal').modal();
                    jcrop_api.setImage(App.baseUri + 'media/' + data.fullPath);
                    jcrop_api.setOptions({ bgOpacity: .6 });
                    jcrop_api.setOptions({ aspectRatio: 0 });
                    setTimeout(function() {
                    	if(!(data.cropX == ""))
                    		jcrop_api.setSelect([data.cropX, data.cropY, (parseInt(data.cropX) + parseInt(data.cropWidth)) + '', (parseInt(data.cropY) + parseInt(data.cropHeight)) + '']);
                    }, 1000);
                    return false;                    
            	});
            });

           $('div').delegate('.green-stripe, .purple-stripe', 'click', function(event) {
        	   var ratio = 0;
        	   switch ($(this).attr('data-ratio')) {
					case '16':
							ratio = 16/9;
						break;
					case '3':
						ratio = 3/4;
					break;
					case '1':
						ratio = 1;
					break;
					default:
							ratio = 0;
						break;
        	   }
        	
            	jcrop_api.setOptions({ aspectRatio: ratio });
                jcrop_api.focus();
            });

           function textResponse(text) {
        		toastr.options.positionClass = "toast-top-full-width";
        		toastr.options.timeOut = "2000";
        		toastr.success(text);
        	}           
        }
    };

}();