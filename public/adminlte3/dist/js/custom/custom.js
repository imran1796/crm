 
 $(".datepicker").datepicker({
    dateFormat: 'yy-mm-dd',
    showButtonPanel: true,
    currentText: "Today",
    showAmim: "slideDown",

    beforeShow: function (input, inst) {
        setTimeout(function () {
            var $buttonPane = $(inst.dpDiv).find('.ui-datepicker-buttonpane');

            // Hide the "Done" button
            $buttonPane.find('.ui-datepicker-close').hide();

            // Style the "Today" button to be full-width
            var $todayButton = $buttonPane.find('.ui-datepicker-current');
            $todayButton.css({
                width: '100%',
                textAlign: 'center',
                margin: 0
            });

            // Handle click event for "Today"
            $todayButton.off('click').on('click', function () {
                var today = new Date();
                $(input).datepicker('setDate', today);
                $.datepicker._hideDatepicker(input); // Close after selecting
                $(input).blur(); // Prevent reopening
            });
        }, 1);
    }
});

$('.bs-select').selectpicker();