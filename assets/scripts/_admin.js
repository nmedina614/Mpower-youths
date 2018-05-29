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
} );