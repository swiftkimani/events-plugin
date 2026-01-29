jQuery(document).ready(function($) {
    if ($('.swifty-date-picker').length) {
        flatpickr('.swifty-date-picker', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "F j, Y at h:i K",
            animate: true,
            time_24hr: false // Cute standard time with AM/PM
        });
    }
});
