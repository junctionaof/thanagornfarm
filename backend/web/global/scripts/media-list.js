$('#media-tab').delegate('a.thumbnail', 'click', function(event) {
	var that = $(this);

	setTimeout(function() {
		
		 var dblclick = parseInt($(that).data('click'), 10);
	
	        if (dblclick > 0) {
	            $(that).data('click', dblclick-1);
	        } else {
	       
	        	var form = $('#media-tab .form form');
	    		if(form.length < 1) {
	    			
	    			var form = $('#media-tab .form');
	    		}
	    		
	    		//disable buttons
	    		$('button, a', form).attr('disabled', 'disabled');
	    		$("a#bt-img-delete").removeAttr('href');
	    		
	    		$.get(Metronic.getBaseUrl() + 'media/info', { 
	    			1: $('#media-tab').attr('data-entity'),
	    			2: $('#media-tab').attr('data-id'),
	    			3: $(that).attr('data-itemNo'),
	    			
	    			
	    		}).done(function(data) {
	    			
	    			var form = $('#media-tab .form form');
	    			if(form.length < 1){
	    				var form = $('#media-tab .form');
	    			}
	    			
	    			$('#media-list a.thumbnail').removeClass('active');
	    			$(that).addClass('active');
	    			
	    			var watermark = data['watermark']?data['watermark']:0;	   

	    			//put returned data into the form
	    			$('input[name=orderNo]', form).val(data['orderNo']);
	    			$('[name=media-caption]', form).val(data['caption']);
	    			
	    			//set tags
                    $('#tagGeControl').empty();
                    var ele = '<input type="text" name="media-tags" id="tag_pic" value="'+data['tags']+'" class="form-control">'; 
                    $('#tagGeControl').append(ele); 
                    recalltag();       
                    $("[name=waterMarkType] option[value="+watermark+"]").attr("selected", "selected");
	    			form.attr('data-itemNo', data['itemNo']);
	    			
	    			//enable buttons
	    			$('button, a', form).removeAttr('disabled');
	    			$("a#bt-img-delete").attr('href', '#media-modal');
	    		});
	        }
	},300);
});

$('#media-tab .form #bt-detail-save').click(function(event) {	
	var form = $('#media-tab .form form');
	if(form.length < 1){
		var form = $('#media-tab .form');
	}
	event.preventDefault();

	$.post(Metronic.getBaseUrl() + 'media', {
		1: $('#media-tab').attr('data-entity'),
		2: $('#media-tab').attr('data-id'),
		3: form.attr('data-itemNo'),
		alpha: $('[name=media-alpha]', form).val(),
		caption: $('[name=media-caption]', form).val(),
		watermark: $('[name=waterMarkType]', form ).val(),
		tags: $('[name=media-tags]', form).val(),
		_csrf:$('meta[name="csrf-token"]').attr("content")
	}).done(function(response) {
		toastr.options.timeOut = 1500;
		toastr['success'](response);
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
});

// confirm delete button
$('#media-modal-confirm').click(function() {
	mediaTab = $('#media-tab');
	form = $('#media-tab .form form');
	if(form.length < 1){
		form = $('#media-tab .form');
	}
	$.ajax({
	    url: Metronic.getBaseUrl() + 'media',
	    type: 'DELETE',
	    data: {
			1: mediaTab.attr('data-entity'),
			2: mediaTab.attr('data-id'),
			3: form.attr('data-itemno'),
			_csrf:$('meta[name="csrf-token"]').attr("content")
	    }
	}).done(function(response) {
		$('#media-tab a[data-itemno=' + form.attr('data-itemno') + ']').remove();
		$('button, a', form).attr('disabled', 'disabled');
		
		toastr.options.timeOut = 1500;
		toastr['success'](response);
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
});

// highlight buttons
$('#media-button-highlight').click(function() {
	doHighlight();
});

$('#media-button-highlight-pano').click(function() {
	doPanorama(1);
});

$('#saveHighlight').click(function(event) {
	if(!$('#tagHighlights').val()) {
		deleteHighlight();
	}else{
	var result = $('#tagHighlights').val();
	doHighlight(result);
	}
});

//บันทึกการจัดลำดับ
$("#bt-order-save").click(function(){
	mediaTab = $('#media-tab');
	itemno = [];
	orderno = [];
	elements = [];
	
	$("#media-list a:not(:first)").each(function(index, element){
		itemno[index] = element.dataset.itemno;
		orderno[index] = $(element).find('input.orderNo').val();
		elements[$(element).find('input.orderNo').val()] = element;
	});
	
	$.ajax({
	    url: Metronic.getBaseUrl() + 'media/order',
	    type:'POST',
	    data: {
			type: mediaTab.attr('data-entity'),
			refId: mediaTab.attr('data-id'),
			itemNo: itemno,
			orderNo: orderno,
			_csrf:$('meta[name="csrf-token"]').attr("content")
	    }
	}).done(function(response) {
		
		var arr = [];
		var highlightItemNo;
		
		$("#media-list a:not(:first)").each(function(i,e){
			if($(e).find("#highlightButton").hasClass('displayBlock')){
				highlightItemNo = e.dataset.itemno;
			}
			$(e).remove();
		});
		
		$.each(response.data, function(i,e){
			var thumbnail = addMedia(response.data[i]);
			if(thumbnail.attr("data-itemno") ==  highlightItemNo)
			
			$(thumbnail).find("#highlightButton").addClass('displayBlock')
		});
		
		toastr.options.timeOut = 1500;
		toastr['success'](response.message);
		
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
});

doHighlight = function(tag) {
	
	var form = $('#media-tab .form form');
	if(form.length < 1){
		
		var form = $('#media-tab .form');
	}

	data = {
		1: $('#media-tab').attr('data-entity'),
		2: $('#media-tab').attr('data-id'),
		3: form.attr('data-itemNo'),
		_csrf:$('meta[name="csrf-token"]').attr("content")
	};

	
	if (tag != undefined)
		data.tag = tag;
	$.post(Metronic.getBaseUrl() + 'media/highlight', data).done(function(response) {

			if(typeof response == "string"){
				var response = $.parseJSON(response);
			}
			
		if (response.success) {

			toastr.options.timeOut = 1500;
			toastr['success'](response.message);
			
			$('.selectHighlight').removeClass('displayBlock');
			
			var arrImg = $('.thumbnail');
			
			$.each(arrImg, function(key, val) {
				if($(this).attr('data-itemno') == response.highlight){
					$(this).find('#highlightButton').addClass(' displayBlock');
				}
			});	
			/*$.each(response.highlights, function(key, val) {
				if (key == 0) {
					$('#media-list a[data-itemno=' + val + '] div #highlightButton').addClass(' displayBlock');
				}
				else if (key == 1) {
					$('#media-list a[data-itemno=' + val + '] div #panoramaButton').addClass(' displayBlock');
				}else{
					$('#media-list a[data-itemno=' + val + '] div #tagsButton').addClass(' displayBlock');
				}
			});*/
			
		}
		else {
			toastr.options.timeOut = 5000;
			toastr['warning'](response.message);
		}
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
}

doPanorama = function(tag) {
	
	var form = $('#media-tab .form form');
	if(form.length < 1){
		
		var form = $('#media-tab .form');
	}

	data = {
		1: $('#media-tab').attr('data-entity'),
		2: $('#media-tab').attr('data-id'),
		3: form.attr('data-itemNo')
	};

	if (tag != undefined)
		data.tag = tag;
	$.post(Metronic.getBaseUrl() + 'media/highlight', data).done(function(response) {

			if(typeof response == "string"){
				var response = $.parseJSON(response);
			}
			
		if (response.success) {

			toastr.options.timeOut = 1500;
			toastr['success'](response.message);
			
			$('.selectPanorama').removeClass('displayBlock');
			
			var arrImg = $('.thumbnail');
			
			$.each(arrImg, function(key, val) {
				if($(this).attr('data-itemno') == response.highlight){
					$(this).find('#panoramaButton').addClass(' displayBlock');
				}
			});	
		}
		else {
			toastr.options.timeOut = 5000;
			toastr['warning'](response.message);
		}
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
}


deleteHighlight = function(tag) {
	var form = $('#media-tab .form');

	data = {
			1: $('#media-tab').attr('data-entity'),
			2: $('#media-tab').attr('data-id'),
			3: form.attr('data-itemNo')
		};

	$.post(Metronic.getBaseUrl() + 'media/deletehighlight', data).done(function(response) {

		if (response.success) {
	
			toastr.options.timeOut = 1500;
			toastr['success'](response.message);
	
			$('.circleHighlight').removeClass('displayBlock');
			$.each(response.highlights, function(key, val) {
				if (key == 0) {
					$('#media-list a[data-itemno=' + val + '] div #highlightButton').addClass(' displayBlock');
				}
				else if (key == 1) {
					$('#media-list a[data-itemno=' + val + '] div #panoramaButton').addClass(' displayBlock');
				}else{
					$('#media-list a[data-itemno=' + val + '] div #tagsButton').addClass(' displayBlock');
				}
			});
		}
		else {
			toastr.options.timeOut = 5000;
			toastr['warning'](response.message);
		}
	}).error(function(xhr) {
		toastr.options.timeOut = 5000;
		toastr['warning'](xhr.responseText);
	});
}

uploadCompleteHandler = function(e, obj) 
{
	addMedia(obj.result);
	var arrThumbnail = $("#media-list a:not(:first)");
	if(arrThumbnail.length >= 2){
		$("#bt-order-save").removeClass("hidden");
	}
	
};

function addMedia(data)
{
	var newImg = $('#media-list a:first').clone();
	$('img', newImg).attr('src', data.uri);
	$('span.width-height', newImg).html(data.width + " X " + data.height);
	$('input.orderNo', newImg).val(data.orderNo);
	newImg.insertAfter('#media-list a:last');
	$('.template-upload').remove();
	newImg.attr('data-itemno', data.itemNo).show();
	
	return newImg;
}