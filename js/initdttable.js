/**
 * Initialize data table
 * 
 */

$(document).ready(function() {
    $('.dt-table').DataTable();
    
    // This would ensure that modal loads new content everytime 
    // the links on the search results are clicked. 
    // Reference: http://stackoverflow.com/questions/12286332/twitter-bootstrap-remote-modal-shows-same-content-everytime
    $('body').on('hidden.bs.modal', '.modal', function(){
        $(this).removeData('bs.modal');
        // This solves the problem of bootstrap modal adding a right padding of 
        // 17 pixels to the body tag after closing.
        // Source: http://stackoverflow.com/questions/31283510/bootstrap-body-auto-padding-remove-after-open-modal
        $("body").removeAttr("style");		
    });
});