var FormComponents = function () {
    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
            	format: 'yyyy-mm-dd',
                rtl: Metronic.isRTL(),
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    };
    
    var handleTimePickers = function () {

        if (jQuery().timepicker) {
            $('.timepicker-default').timepicker({
                autoclose: true
            });
            $('.timepicker-24').timepicker({
                autoclose: true,
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            });
        }
    };
    
    return {
    	//main function to initiate the module
        init: function () {
            handleDatePickers();
            handleTimePickers();
        }
    };

}();

// disable return key on text input
$('form').on('keydown', 'input[type=text]', function(e, el) {
	// ENTER key
	console.log('keypress');
	if (e.keyCode == 13) {
		if ($(this).closest('.bootstrap-tagsinput').length == 0)
			e.preventDefault();
	}		
});