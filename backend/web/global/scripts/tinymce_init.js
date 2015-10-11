$(function() {
	tinymce.PluginManager.load('cms',  App.baseUri + 'assets/scripts/tinymce_cms_plugin.js');
	tinymce.PluginManager.load('socialFeed', App.baseUri + 'assets/plugins/socialFeed/plugin.js');
	tinymce.PluginManager.load('qoder','http://console.qoder.tpbs.ndev.pw/sdk/tinymce-qoder.js');
	qoder.config.url = 'http://console.qoder.tpbs.ndev.pw';
	
	tinymce.init({
			selector:'textarea#content_textarea, textarea.tinymce-enabled',
			theme: "modern",
			menubar: false,
			content_css: App.baseUri + "assets/css/tinymce-content.css",
	
		    plugins : "paste code noneditable -cms -socialFeed advlist autolink link lists print preview pagebreak qoder",
		    toolbar : "bold italic underline strikethrough styleselect | alignleft aligncenter alignright alignjustify | bullist numlist link | qoder-browse-video qoder-browse-audio youtube_link | cms.video cms.document cms.media cms.gallery | cms.facebook cms.twitter cms.instagram | cms.quote cms.feedcontent | code",
			style_formats: [
			    {
			    	title: "รูปแบบ", items: [
			    	     {
			    	    	 title: "หัวข้อ", block:'h3'
			    	     },
			    	     {
			    	    	title: "quote", block: 'blockquote' 
			    	     }
			        ]
			    }
			],
					
			valid_styles: { '*': 'text-align,text-decoration' },
		    /*valid_elements : 'br,span[style|class],div[style|class],p[style|class],pre[style],blockquote[style],img[src|alt|width|height],h1,h2,h3,h4,h5,h6,strong,em,sup,sub,code,a[href|title|target],ol[class],ul[class],li[class],table[class],thead,tbody,tr,td,th',
			valid_children: '-body[img],-span[img],-div[img],-p[img],-pre[img],-blockquote[img],' +
									'+entity[img|span],+youtube[iframe]',*/
			extended_valid_elements : 'iframe[title|width|height|src|frameborder|allowfullscreen],'+
									  'media[id|class|object|itemno|width|height|src|controls|key],'+
									  'entity[data-id|type],' +
									  'youtube[key],' + 
									  'embed[width|height|src|allowfullscreen],'+
									  'i[class]',
			custom_elements : 'media,entity,youtube',
			paste_as_text: true,
	
			init_instance_callback: function(){
	            ac = tinyMCE.activeEditor;
	        },
			
		    setup: function(ed) {
				/* prevent highlight innter tag within entity node */
		    	ed.on("nodechange", function(e) {
		    		switch(e.element.parentNode.nodeName) {
		    			case 'ENTITY':
		    			case 'MEDIA':
			        		ed.selection.select(e.element.parentNode);
		    				break;
		    		}
		        });
		    }
	});

	$('#table-social-listdata').on('click', 'td .social-select', function() {
		var imgSrc = 'http://placehold.it/100x60';
		var id = $(this).attr('data-id');
		var rowId = 'row-'+$(this).attr('data-channelid')+'-'+$(this).attr('data-id');
		var content = $('#'+rowId).text();
		var postBy = $(this).attr('data-postBy');
		var iconPath = null;

		switch ($(this).attr('data-object-type')) {
			case 'facebook':
				iconPath = "/assets/img/icons/facebook-icon.png";
				break;
			case 'g+':
				iconPath = "/assets/img/icons/google-plus-icon.jpg";
				break;
			case 'instagram':
				iconPath = "/assets/img/icons/Instagram-icon.png";
				break;
			case 'twitter':
				iconPath = "/assets/img/icons/twitter-icon.png";
				break;
		}
		
		var html = '<entity type="social" data-id="'+id+'">' +
			'<span class="caption"> user : '+postBy+'</span> <span class="caption"> เนื้อหา : '+content+'</span>' +
			'<img src="'+iconPath+'" alt="" width="36"><br/>'
		'</entity>';
		
		insertContent(html);
	});

	$('#btn-search-social').click(function() {
		var inputCb = $('input[name^=socialType]');
		var arrScocialType = new Array();
		$( inputCb ).each(function(ob) {
			if($( this ).is(':checked')){
				arrScocialType.push($( this ).val());
			}
		});
		
		var jsonType = JSON.stringify(arrScocialType);

		selector = $('#table-social-listdata');
		$('tbody tr:not(:first)', selector).remove();
		$.get(App.baseUri + 'mediaObject/socialList', {
			keyword: $('#search-social').val(),
			scocialType: jsonType,
		}).done(function(result) {
			$.each(result, function(i) {
				var tb = $('.table tbody', selector);
				tb.append(newSocialItem(tb, result[i]));
			});
			if (result.length < 1) {
				var tb = $('.table tbody', selector);
				tb.append(newSocialItem(tb, {id: 0, title: '[ยังไม่มีรายการ]', url: 0}));
			}
		});
	});

	function newSocialItem(tb, data) {
		var newRow = $(tb.find('tr:first')).clone().html();
		var rowId = 'row-'+data.channelId+'-'+data.feedId;
		
		newRow = newRow.replace('{id}', data.feedId);
		newRow = newRow.replace('{rowId}', rowId);
		newRow = newRow.replace('{content}', data.content);
		newRow = newRow.replace('{id}', data.feedId);
		newRow = newRow.replace('{channelId}', data.channelId);
		newRow = newRow.replace('{type}', data.social);
		newRow = newRow.replace('{postBy}', data.postBy);
		return '<tr data-object="content" data-id="' + data.feedId + '"' + '>' + newRow + '</tr>';
	}
});

function insertContent(html) {
	tinyMCE.activeEditor.insertContent(html);
	var node = tinyMCE.activeEditor.selection.getNode();
	while(node.parentNode.tagName != 'BODY')
		node = node.parentNode;
	tinyMCE.activeEditor.selection.select(node);
	tinyMCE.activeEditor.selection.collapse();
}