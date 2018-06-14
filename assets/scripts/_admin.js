$(document).ready(function() {
    $('.data-table').DataTable();

    $('.table-selector').click( (e) => {
        // Change button status.
        $('.table-selector.active').removeClass('active');
        $(e.target).addClass('active');

        let target = '#' + e.target.dataset.target;

        // Hide Old Table
        $('.table-container.active').hide().removeClass('active');

        // Show New
        $(target).show().addClass('active');
    });

    $('.btn-delete').click(function(event) {
        let id = $(event.currentTarget).data('id');
        $.ajax('ajax-delete-notification', {
            method : "POST",
            data : {notification : id},
            dataType : 'json',
            success : function(response) {
                if(response === true) {
                    $(event.currentTarget).parent().parent().remove();
                } else {
                    alert(response);
                }
            },
            error : function() {
                console.log("Failed to connect!");
            }
        });
    })
});