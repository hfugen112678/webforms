/*
 *  Plugin to update Transportation allowance amount in 
 *  the Employeelogs table.
 */
(function($) {
    $.fn.updateapprovedtranspo = function(status, value) {        
        var $form = $('#' + $(this).closest('form')[0].id);
        var url = $form.attr('action');
        var empno = $form.find('input[name="employeenumber"]').val();
        var transpoid = $form.find('input[name="transpoid"]').val();        
        $.post(url, {status : status, userlogid : value, empno : empno, transpoid : transpoid});        
    };
} (jQuery));

(function($){
    $.fn.updatemedcert = function(id, status) {
        var colval;
        var url = window.location + '/updatemedcert'; 
        if (status) {
            colval = 'Y';
        } else {
            colval = 'N';
        }
        $.post(url, {id : id, colval : colval});
    };
} (jQuery));