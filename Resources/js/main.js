$(document).ready(function() {
    $('.ask-delete').click(function(){

        $('#delete-item').attr('href', $(this).data('href'));
        $('#delete-box').modal('show');

        return false;
    });
});
