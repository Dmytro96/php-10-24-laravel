import './bootstrap';


$(document).ready(function() {
    $('#per_page').on('change', function() {
        $(this).parents('form')?.submit();
    });
});
