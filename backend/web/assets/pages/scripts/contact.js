var Contact = function () {

    return {
        //main function to initiate the module
        init: function () {
			var map;
			$(document).ready(function(){
			  map = new GMaps({
				div: '#gmapbg',
				lat: 7.826983,
				lng: 100.327778
			  });
			   var marker = map.addMarker({
		            lat: 7.826983,
					lng: 100.327778,
		            title: 'ธนกรฟาร์ม',
		            infoWindow: {
		                content: "<b>ธนกรฟาร์ม</b> 795 หาดใหญ่, , สงขลา 80455"
		            }
		        });

			   marker.infoWindow.open(map, marker);
			});
        }
    };

}();

jQuery(document).ready(function() {    
   Contact.init(); 
});

