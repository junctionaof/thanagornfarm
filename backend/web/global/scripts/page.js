(function($) {
	
	function alert(text,status){
		toastr.options = {
		  "closeButton": false,
		  "debug": false,
		  "newestOnTop": false,
		  "progressBar": true,
		  "positionClass": "toast-top-full-width",
		  "preventDuplicates": false,
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		}
		if(status)
			Command: toastr["success"](text)
		else
			Command: toastr["warning"](text)
	}

		window.appUrl = window.location.origin+$('#baseUrl').val();
		$('tr[data-content-id="0"]').removeAttr('style');
		$( document ).ready(function() {
			$.ajax({
			     dataType: 'json',
			     type: 'GET',
			     url: 'mongodata',
			     data : {
			    	 section:$('#nameSection').val(),
			    	 _csrf:$('meta[name="csrf-token"]').attr("content"),
			     },
			     success: function(data) {
					if(data == false)
						$('tr[data-content-id="0"]').removeAttr('style');
			    	 $.each( data, function( key, value ) {
			    		 if(value){
				    		 $.each(value ,function(index , data){
				    			 if($('#tb-'+key+' ').length == 0)
				    				 return true;
				    			 if(index == 0){
									var html = newItem($('#tb-'+key+' '),data);
									$(html).appendTo($('#tb-'+key+' '));
									$('#tb-'+key+' tr[data-content-id="0"]').css('display','none');
				    			 }else{
									var html = newItem($('#tb-'+key+' '),data);
									$(html).appendTo($('#tb-'+key+' '));
									$('#tb-'+key+' tr[data-content-id="0"]').css('display','none');
								}
				    		 });
				    		 $('#tb-'+key+' .trClone').css('display','none');
			    		 }
			    	 });
			     }
			 });
		});
        $(document).delegate('#editContent','click',function(){
        	//เปิดปิด tab แต่ละหมวดตาม section config

        	// #tablist
        	$('#tablist').find('.active').removeClass('active');
        	$('#tablist li a').css('display','none');
        	$('#tablist').find('[data-tabtype="'+$(this).data('type')+'"] a').css('display','block');
        	$($('#tablist').find('[data-tabtype="'+$(this).data('type')+'"]')[0]).addClass('active');

			$('#typeName').val($(this).data('type'));
	 
        	// #itemlist
        	$('#itemlist').find('.active').removeClass('active');
        	var tabtype = $($('#tablist').find('[data-tabtype="'+$(this).data('type')+'"]')[0]).find('a').attr('data-tabtype');
        	$('#itemlist').find('[id="portlet_tab_'+tabtype+'"]').addClass('active');
        	
			$('#portlet_tab2_2').addClass('active');
			$('#portlet_tab2_3').removeClass('active');

			$('.portlet_tab2_2').addClass('active');
			$('.portlet_tab2_3').removeClass('active');
			
			$('#nameTitle').text($(this).data('typename'));
			$('#saveContent').attr('data-type',$(this).data('type'));

			$('#numberItems').text('จำนวนข่าวทั้งหมด : '+$(this).data('number'));
			$('#numberItemsConfig').val($(this).data('number'));

			// Clear Tr #sortable1
			if($('#sortable1 tr').length > 2){
				$('#sortable1').find('.thisData').remove();
			}else{
				$('#sortable1 tr[data-content-id=0]').removeAttr('style');
				$('#sortable1 tr[data-content-id="{id}"]').css('display','none');
			}
			
			// get data mongo by section
			var params = {
					actiontype:'mongodb',
					type:$(this).data('type'),
					section:$(this).data('section'),
					_csrf:$('meta[name="csrf-token"]').attr("content"),
			};
			$.get( "listmongodata",params, function( data ) {
				if(data != false){
					$('#sortable1 tr[data-content-id="0"]').css('display','none');
				}else{
					$('#sortable1 tr[data-content-id="0"]').removeAttr('style');
				}			
				$.each( data, function( key, value ) {		
					var tb = $('#sortable1');
					var html = newItem(tb, value);
					$(html).appendTo(tb);
					$('#sortable1 tr:first').css('display','none');
				});
			});
			
			if($('#items-'+tabtype+' tr').length === 1){
				var params = {
						tabtype:tabtype,
						type:$(this).data('type'),
						section:$(this).data('section'),
						_csrf:$('meta[name="csrf-token"]').attr("content"),
				};
				$.ajax({
				     dataType: 'json',
				     type: 'GET',
				     url: 'listitem',
				     data : params,
				     success: function(data) {
				    	 $.each( data, function( key, value ) {
								var thisValue = $('#items-'+tabtype+' tr:first').clone().removeAttr('style').removeClass('liClone').appendTo('#items-'+tabtype+' ');
								newItems(thisValue,value);
				    	 });
						if($('#sortable1 tr').length === 1){
							$.each( data, function( key, value ) {
								var thisValue = $('#items-'+tabtype+' tr:first').clone().removeAttr('style').removeClass('liClone').appendTo('#items-'+tabtype+' ');
								newItems(thisValue,value);
							});
						}
				     }
				 });
			}
       	 });
        
        // get data from tab click
        $(document).delegate('#tablist li a','click',function(){
        	window.tabtype = $(this).data('tabtype');
        	if($('#items-'+tabtype+' tr').length === 1){
        		$.get( "listitem",{ tabtype:$(this).data('tabtype'), _csrf:$('meta[name="csrf-token"]').attr("content") }, function( data ) {
        			$.each( data, function( key, value ) {
    					var thisValue = $('#items-'+tabtype+' tr:first').clone().removeAttr('style').removeClass('liClone').appendTo('#items-'+tabtype+' ');
    					newItems(thisValue,value);
    				});
    			});
        	}
        });

        $(".sortable2").sortable({
            connectWith: '#sortable1'
    	}).disableSelection();
        $("#sortable1").sortable({
            connectWith: '.sortable2'
    	}).disableSelection();
    	
        // event ตารางขวาไปซ้าย
        $( "#sortable1" ).sortable({
        	receive: function(event, ui) {
        		dropItem = $(ui.item[0]);
        		dropUi = ui;
        		dropUi.sender.append(dropItem.clone());
        		$.get(App.baseUri + 'web/getcontent', {
					id: dropItem.attr('data-content-id'),
					datatype: dropItem.attr('data-type'),
				}).done(function(data) {
					var dropRow = $(dropUi.item[0]);
					var tb = dropRow.parent('tbody');
					
					// clear placeholder row
					dropRow.parents('tbody').find('tr[data-content-id="0"]').css('display','none');
					dropRow.parents('tbody').find('tr[data-content-id="{id}"]').css('display','none');
					
					dropRow.after(newItem(tb, data));
					dropRow.remove();
				});
        		$('#NotFoundData').css('display','none');
        		if($('#sortable1 ').find("tr:gt(0)").length-1 > parseInt($('#numberItemsConfig').val(), 10)){
        			dropItem.remove();   			
        			alert('จำนวนข่าวเกินกำหนด !',false);
        		}
            }
         });

        // event ตารางซ้าบไปขวา
        $( ".sortable2" ).sortable({
        	receive: function(event, ui) {
        		dropItem = $(ui.item[0]);
               if($( "#sortable1 tr").length === 1)
            	   $('#NotFoundData').css('display','');
            }
         });

         $('#nestable_list_pick').nestable();
         $('#nestable_list_food').nestable();
         $('#nestable_list_travel').nestable();
         
         //event search
         $('input[name=q]').keydown(function(e) {
        		if(e.keyCode==13){      
        			serachItems(appUrl, {
        				q:$(this).val(),
        				_csrf:$('meta[name="csrf-token"]').attr("content"),
        				listtype:$(this).data('searchtype')
        			});
        		}
        	});
         
         // function Search Items
         function serachItems(url,params){
        	 window.listtype = params.listtype;
        	 $.ajax({
			     dataType: 'json',
			     type: 'GET',
			     url: 'searchitems',
			     data : params,
			     success: function(data) {
			    	 $('#items-'+listtype+' tr:gt(0)').remove();
	        		 $.each( data, function( key, value ) {
	 					var thisValue = $('#items-'+listtype+' tr:first').clone().removeAttr('style').removeClass('liClone').appendTo('#items-'+listtype+' ');
	 					newItems(thisValue,value);
	 				});  
			     }
			 });
         }
         
         // Save Highlight
         $(document).delegate('#saveContent','click',function(){
        	var items = [];
        	for (var i = 0; i < $('#sortable1 .thisData').length; i++) {
        	    var item = [
        	    	$($('#sortable1 .thisData')[i]).data('type'),
        	    	$($('#sortable1 .thisData')[i]).data('content-id'),  
        	    ];
        	    items.push(item);
        	}
        	var params = {
					type:$('#typeName').val(),
					section:$(this).data('section'),
					items:items,
					_csrf:$('meta[name="csrf-token"]').attr("content"),
			};
			$.get( "savelists",params, function( value ) {
				alert('บันทึกข้อมูลเรียบร้อย',true);

				$('#portlet_tab2_3').addClass('active');
				$('.portlet_tab2_3').addClass('active')
				
				$('#portlet_tab2_2').removeClass('active')
				$('.portlet_tab2_2').removeClass('active')
				
				$.each(value ,function(key , data ){
					if(key==0)
						$('#tb-'+data.idType+' tr:gt(0)').css('display','none');

					if(key == 0){
						var html = newItem($('#tb-'+data.idType+' '),data);
						$(html).appendTo($('#tb-'+data.idType+' '));
						$('#tb-'+data.idType+' tr:first').css('display','none');
						 //var thisValue = $('#tb-'+key+' tr:first').clone().removeAttr('style').removeClass('trClone').insertAfter('#tb-'+key+' tr:first');
					 }else{
						var html = newItem($('#tb-'+data.idType+' '),data);
						$(html).appendTo($('#tb-'+data.idType+' '));
						$('#tb-'+data.idType+' tr:first').css('display','none');
					}
				});		
			});
         });
         
         // new items trClone
         function newItems(thisValue,data){
    			 thisValue.attr('data-content-id',data.id);
    			 thisValue.find('.showimg').html('<img src="http://placehold.it/100x60"></img>');
    			 thisValue.find('.showid').text(data.id);
    			 thisValue.find('.showname').text(data.title);
         }
         
         // event mouse over
         $(document).delegate('#iconRemove','click',function(){
        	 this.closest('tr').remove(); 
			 if($('#sortable1 tr:gt(0)').length - 1 == 0)
				$('#NotFoundData').removeAttr('style');
         });
 
         function newItem(tb, data) {
 			var thumbnail = (data.preview)?data.preview:'<img src="http://placehold.it/100x60" class="media-object">';
 			var newRow = $(tb.find('tr[data-content-id="{id}"]')).clone().html();
 			newRow = newRow.replace('{id}', data.id);
 			newRow = newRow.replace('{thumbnail}', thumbnail);
 			newRow = newRow.replace('{title}', data.title);
 			newRow = newRow.replace('{view}', data.viewCount);
 			return '<tr data-object="content" data-type="'+data.type+'" class="thisData" data-content-id="' + data.id + '"' + '>' + newRow + '</tr>';
 		}
         
         //function breakingNews
         $(document).delegate('#breakingNewsStatusBt','click',function(){
        	 var params = {
 					type:$('#breakingNewsStatus').data('type'),
 					section:$('#breakingNewsStatus').data('section'),
 					status:$('#breakingNewsStatus').val(),
 					_csrf:$('meta[name="csrf-token"]').attr("content"),
 			};
 			$.get( "breakingstatus",params, function( data ) {
 				alert(data,true);
 			});

         });
         
})(jQuery);