$('#document-tab').delegate('a.thumbnail', 'click', function(event) {
	var that = $(this);

	setTimeout(function() {
		
		 var dblclick = parseInt($(that).data('click'), 10);
	
	        if (dblclick > 0) {
	            $(that).data('click', dblclick-1);
	        } else {
	       
	        	var form = $('#document-tab .form form');
	    		if(form.length < 1) {
	    			
	    			var form = $('#document-tab .form');
	    		}
	    		$('button, a', form).attr('disabled', 'disabled');
	    		$("#bt-detail-save").attr('disabled', 'disabled');
	    		$("#bt-download-file").attr('item-no', $(that).attr('data-itemNo'));
	    		
	    		//clear form fields
	    		$('[name=document-caption]', form).val("");
    			$('[name=document-tags]', form).val();
    			
    			
	    		$.get(Metronic.getBaseUrl() + 'document/info', {
	    			1: $('#document-tab').attr('data-entity'),
	    			2: $('#document-tab').attr('data-id'),
	    			3: $(that).attr('data-itemNo')
	    		}).done(function(data) {
	    			
	    			$("#bt-detail-save").removeAttr('disabled');
	    			$('button, a', form).removeAttr('disabled');
	    			 
	    			
	    			var form = $('#document-tab .form form');
	    			if(form.length < 1){
	    				var form = $('#document-tab .form');
	    			}
	    			
	    			$('#document-list a.thumbnail').removeClass('active');
	    			$(that).addClass('active');
	    			
	    			$('[name=document-caption]', form).val(data['caption']);
	    			//$('[name=document-tags]', form).val(data['tags']);
	    			
	    			 $('#tagGeControl-doc').empty();
                     var ele = '<input type="text" name="document-tags" id="tag_doc" value="'+data['tags']+'" class="form-control">'; 
                     $('#tagGeControl-doc').append(ele); 
                     recalltagdoc(); 
                     
	    			form.attr('data-itemNo', data['itemNo']);
	    			$('button, a', form).removeAttr('disabled');
	    		});
	        }
	},300);
});

$('#document-tab .form #bt-detail-save').click(function(event) {	
	var form = $('#document-tab .form form');
	if(form.length < 1){
		var form = $('#document-tab .form');
	}
	event.preventDefault();

	$.post(Metronic.getBaseUrl() + 'document', {
		1: $('#document-tab').attr('data-entity'),
		2: $('#document-tab').attr('data-id'),
		3: form.attr('data-itemNo'),
		caption: $('[name=document-caption]', form).val(),
		tags: $('[name=document-tags]', form).val()
	}).done(function(response) {
		toastr.options.timeOut = 1500;
		toastr['success'](response);
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
});

// confirm delete button
$('#document-modal-confirm').click(function() {
	mediaTab = $('#document-tab');
	form = $('#document-tab .form form');
	if(form.length < 1){
		form = $('#document-tab .form');
	}
	$.ajax({
	    url: Metronic.getBaseUrl() + 'document',
	    type: 'DELETE',
	    data: {
			1: mediaTab.attr('data-entity'),
			2: mediaTab.attr('data-id'),
			3: form.attr('data-itemno')
	    }
	}).done(function(response) {
		$('#document-tab a[data-itemno=' + form.attr('data-itemno') + ']').remove();
		$('button, a', form).attr('disabled', 'disabled');
		
		toastr.options.timeOut = 1500;
		toastr['success'](response);
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
});


docUploadCompleteHandler = function(e, obj) {
	var newImg = $('#document-list a:first').clone();
	$('img', newImg).attr('src', obj.result.iconSrc);
	$('p', newImg).html(obj.result.fileName);
	$('span.hidden', newImg).html(obj.result.uri);
	$('#document-list').append(newImg);
	$('.template-upload').remove();
	newImg.attr('data-itemno', obj.result.itemNo).show();
};
/*
function(e,d){
	var width = d.result.width;
	var height = d.result.height;
	var node = $('a.thumbnail:first');
	node.attr('data-itemno', d.result.itemNo);
	node.find('img').attr('src', d.result.uri);
	node.find('p').html(width + " X " + height);
	node.removeAttr('style');
	node.appendTo($('#media-list'));
	$('.template-upload').remove();
	// */