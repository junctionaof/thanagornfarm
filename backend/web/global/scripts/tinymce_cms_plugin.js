tinymce.create('tinymce.plugins.cmsPlugin',{
	cmsPlugin : function(editor,url) {
		editor.addButton('cms.gallery', {
			title : 'อัลบั้ม',
			icon: 'icon gallery-icon',
			onclick: function() {
				var selector = $('#embed-popup-items');
				
                $('tbody tr:not(:first)', selector).remove();
                selector.attr('data-object', 'gallery').modal('toggle');
			}
	      });
		
		editor.addButton('cms.media', {
			title : 'รูปภาพ',
			icon: 'image',
			onclick: function() {
				selector = $('#embeded-media-items');
				$('tbody tr:not(:first)', selector).remove();
				$.get(App.baseUri + 'media', {
					id: $('#content-main').attr('data-id'),
					entity: $('#content-main').attr('data-entity')
				}).done(function(result) {
					$.each(result, function(i) {
						var tb = $('.table tbody', selector);
						tb.append(newMediaItem(tb, result[i]));
					});
					if (result.length < 1) {
						var tb = $('.table tbody', selector);
						tb.append(newMediaItem(tb, {refId: 0, itemNo:'' ,caption: '[ยังไม่มีรายการ]', thumbnail: ''}));
					}
				});				
                selector.modal('toggle');
                selector.removeClass('hide');
			}		
	      });
		
		editor.addButton('cms.person', {
			title : 'บุคคล',
			icon: 'icon porson-icon',
			onclick: function() {
				var selector = $('#embed-popup-items');
				
                $('tbody tr:not(:first)', selector).remove();
                selector.attr('data-object', 'person').modal('toggle');
			}
	      });
		
		editor.addButton('cms.video', {
			title : 'วิดีโอ',
			icon: 'media',
			onclick: function() {
				var selector = $('#embed-popup-items');
				
                $('tbody tr:not(:first)', selector).remove();
                selector.attr('data-object', 'video').modal('toggle');
			}
	      });
		
		editor.addButton('youtube_link', {
			title : 'Youtube',
			icon: 'embed youtube-embed',
			class: 'mce_image',
			onclick: function() {
				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่วิดีโอ จาก Youtube',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 200, 200)),
							type: 'textbox', 
							name: 'youtubeSource', 
							label: 'URL จากหน้า youtube'
						}
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						var youtubeUrl = e.data.youtubeSource;
						if(youtubeUrl){
			        		var match = /\?v\=([\w-]+)/.exec(youtubeUrl);
			        		var html = '<youtube key="' + match[1] + '"><iframe width="560" height="315" src="//www.youtube.com/embed/'+ match[1]+'" frameborder="0"></iframe></youtube>';
							editor.insertContent(html);
						}else{
							tinyMCE.activeEditor.windowManager.alert('กรุณาใส่ URL ของ youtube.');
						}
					}
				});
			}
		
	      });
		  
		  /* icon social */
		editor.addButton('cms.facebook', {
			title : 'Facebook',
			icon: 'icon facebook-icon',
			class: 'mce_image',
			onclick: function() {
				
				 
				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่ลิงค์ จาก Facebook',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 100, 100)),
							type: 'textbox', 
							name: 'facebookEmbeded', 
							label: 'URL จากหน้า Facebook',
							
						}
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						var facebookUrl = e.data.facebookEmbeded;
						var objectType = 'facebook';
						var objectName = 'https://graph.facebook.com/?id='+facebookUrl;	
						window.facebookUrl = e.data.facebookEmbeded;

						$.get(App.baseUri + 'content/facebookapi', {
							objectName: objectName,
						}).done(function(result) {
							
							if(result.first_name != null){
								var caption = result.first_name;
							}else{
								var caption = result.username;
							}			
							var html = '<p></p><entity type="' + objectType + '"  data-name="'+ caption +'">' +
						        			//'<img src="'+facebookUrl+'" alt="">' +
											'<p class="">' + objectType + '</p>' +
						        			'<p class="caption">' + caption + ': '+ facebookUrl + '</p>' +
						        		'</entity><p></p>';
							editor.insertContent(html);
						}).fail(function(result) {
							var facebookUrl = window.facebookUrl;
							var objectName = $($(facebookUrl)[2]).find('.fb-xfbml-parse-ignore').text();
							var facebookUrl = $($(facebookUrl)[2]).data('href');
							
							var postsId = facebookUrl.replace(/.*[/]/g, '');
							var linkName = facebookUrl.replace(/[/]post.*/g, '').replace(/.*[/]/g, '');
							
			        		var html = '<p></p><entity type="' + objectType + '" data-id="'+ postsId +'" data-name="'+ linkName +'">' +
						        			//'<img src="'+facebookUrl+'" alt="">' +
			        						'<p class="">' + objectType + '</p>' +
						        			'<p class="caption">' + linkName + ': '+ facebookUrl + '</p>' +
						        		'</entity><p></p>';
			        		editor.insertContent(html);
						  })
						  .always(function() {
						    //alert( "finished" );
						  });
					
					}
				});
			}
		
	      });
		
		editor.addButton('cms.twitter', {
			title : 'Twitter',
			icon: 'icon twitter-icon',
			class: 'mce_image',
			onclick: function() {
				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่ลิงค์ จาก Twitter',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 100, 100)),
							type: 'textbox', 
							name: 'twitterEmbeded', 
							label: 'URL จากหน้า Twitter'
						}
					],
					onsubmit: function(e) {
						var twitterUrl = e.data.twitterEmbeded;
						var objectType = 'twitter';
						if($(twitterUrl.anchor()).length > 1){
							var lengthObj = $($(twitterUrl)[0]).find('a').length-1;
							var newUrl = $($($(twitterUrl)[0]).find('a')[lengthObj]).attr('href');
							var titleName = $($(twitterUrl)[0]).find('p').text().replace(/\http.*/g, '');
							var twitterName = twitterUrl.replace(/.*\@/g, '').replace(/[)].*/g, '');
							var postsId = newUrl.replace(/.*[/]/g, '');
						}else{
							twitterName = twitterUrl.replace(/\http.*[/]/g, '');
							titleName = twitterUrl;
						}
					
						if(twitterUrl){
							var html = '<p></p><entity type="' + objectType + '" data-id="'+ postsId +'" data-name="'+ twitterName +'" data-title="'+ titleName +'">' +
					        			//'<img src="'+facebookUrl+'" alt="">' +
										'<p class="">' + objectType + '</p>' +
					        			'<p class="caption">'+ twitterName + ': '+ titleName + '</p>' +
					        		'</entity><p></p>';
							editor.insertContent(html);
						}else{
							tinyMCE.activeEditor.windowManager.alert('กรุณาใส่ URL ของ youtube.');
						}
					}
				});
			}
		
	      });
		
		editor.addButton('cms.instagram', {
			title : 'Instagram',
			icon: 'icon instagram-icon',
			class: 'mce_image',
			onclick: function() {
				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่ลิงค์ จาก Instagram',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 100, 100)),
							type: 'textbox', 
							name: 'instagramSource', 
							label: 'URL จากหน้า Instagram'
						}
					],
					onsubmit: function(e) {
						
						// Insert content when the window form is submitted
						window.instagramUrl = e.data.instagramSource;	
						window.objectType = 'instagram';
						
						if($(instagramUrl.anchor()).length > 1){
							window.instagramNewUrl = $($(instagramUrl)[0]).find('a').attr('href');
						}else{
							window.instagramNewUrl = window.instagramUrl
						}
						$.get(App.baseUri + 'content/instagramapi', {
							objectName: window.instagramNewUrl,
						}).done(function(result) {

							var instagramNewUrl = window.instagramNewUrl;
							var objectType = window.objectType;
							var postsId = instagramNewUrl.substring(0, instagramNewUrl.length-1).replace(/\http.*[/]/g, '');
							
							var html = '<p></p><entity type="' + objectType + '" data-id="'+ postsId +'" data-name="'+ result.author_name +'">' +
					        			//'<img src="'+facebookUrl+'" alt="">' +
										'<p class="">' + objectType +' : '+ result.author_name + '</p>' +
					        			'<p class="caption">' + result.title + ': '+ result.author_name + '</p>' +
					        		'</entity><p></p>';
							editor.insertContent(html);
							
						}).fail(function(result) {
							var instagramNewUrl = window.instagramNewUrl;
							var objectType = window.objectType;
							var instagramName = instagramNewUrl.substring(0, instagramNewUrl.length-1).replace(/\http.*[/]/g, '');
							
							var html = '<p></p><entity type="' + objectType + '" data-name="'+ instagramName +'">' +
						        			//'<img src="'+facebookUrl+'" alt="">' +
											'<p class="">' + objectType + '</p>' +
						        			'<p class="caption">' + objectType + ': '+ instagramName + '</p>' +
						        		'</entity><p></p>';
							editor.insertContent(html);
						  })
						  .always(function() {
						    //alert( "finished" );
						  });

					}
				});
			}
		
	      });
		
		/*---end---*/

	}
});
tinymce.PluginManager.add('cms', tinymce.plugins. cmsPlugin);

function newMediaItem(tb, data) {
	var newRow = $(tb.find('tr:first')).clone().html();
	
	newRow = newRow.replace(/{id}/g, data.refId + '_' + data.itemNo);
	newRow = newRow.replace(/{thumbnail}/g, data.fullPath);
	newRow = newRow.replace(/{title}/g, data.caption);
	newRow = newRow.replace(/{id}/g, data.refId);
	newRow = newRow.replace(/{itemNo}/g, data.itemNo);
	newRow = newRow.replace(/{caption}/g, data.caption);
	newRow = newRow.replace(/{fullPath}/g, data.fullPath);

	return '<tr data-object="' + data.objectType + '" data-id="' + data.refId + '"' + '>' + newRow + '</tr>';
}

function newListItem(tb, data) {
	var newRow = $(tb.find('tr:first')).clone().html();
	
	newRow = newRow.replace(/{thumbnail}/g, data.preview);
	newRow = newRow.replace(/{title}/g, data.title);
	newRow = newRow.replace(/{id}/g, data.id);
	newRow = newRow.replace(/{itemNo}/g, data.itemNo);
	
	return '<tr data-object="' + data.objectType + '" data-id="' + data.id + '">' + newRow + '</tr>';
}

// popup entity embeder
$('#embeded-media-items').on('click', 'td .media-select', function() {
	var container = $(this).closest('tr');
	var id = container.attr('data-id');
	var objectType = container.attr('data-object');
	var itemNo = $(this).attr('data-itemno');
	var caption = $(this).attr('data-caption');	
	var imgSrc = 'http://placehold.it/100x60';
	if($(this).attr('data-source')){
		imgSrc = '/media/'+$(this).attr('data-source');
	}
	var html = '<media object="' + objectType + '" id="' + id + '" itemno="'+ itemNo +'">' +
		'<img src="'+imgSrc+'" alt=""><br/>' +
		'<span class="caption">' + caption + '</span>' +
	'</media>';	
	insertContent(html);
});

// Search button event
$('#embed-popup-items #btn-search').click(function() {
	selector = $('#embed-popup-items');
	var objectType = selector.attr('data-object');
	var params = {
		q: $('#search-key').val(),
	};
	
	// special condition for each objectType
	switch(objectType) {
		case 'video':
			params.type = 1;	// TYPE_NEWSCLIP
		break;
	}
	
	$('tbody tr:not(:first)', selector).remove();
	
	$.get(App.baseUri + objectType, params).done(function(result) {		
		if (result.length < 1) {
			var tb = $('.table tbody', selector);
			tb.append(newListItem(tb, {id: 0, title: '[ยังไม่มีรายการ]', url: 0}));
		}
		else {
			$.each(result, function(i) {
				var tb = $('.table tbody', selector);
				tb.append(newListItem(tb, result[i]));
			});
		}
	});
});

objectMap = {
	gallery: 'อัลบั้ม',
	person: 'บุคคล',
	video: 'วิดีโอ'
};

// insert button clicked
$('#embed-popup-items').on('click', 'a', function() {
	var container = $(this).closest('tr');
	var id = container.attr('data-id');
	var objectType = container.attr('data-object');	
	var title = $('td:eq(2)', container).text();
	
	var imgSrc = $('td:eq(1) img', container).attr('src');
	if (imgSrc == undefined)
		imgSrc = 'http://placehold.it/100x60';

	var html = '<entity type="' + objectType + '" data-id="'+id+'">' +
		'<img src="'+imgSrc+'" alt="">' +
		'<p class="caption">' + objectMap[objectType] + ': '+ title + '</p>' +
	'</entity>';	
	insertContent(html);
});