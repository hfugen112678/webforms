$(document).ready(function(){
    function insert_warning(msg, element) {
        $('.msg-warning').remove();
        $('<p class="msg-warning text-danger">' + msg + '</p>').hide().fadeIn(300).insertAfter(element);
    }
    
    $(document).on('click', '#btnsaveot', function(event){
        var otoriginalval = $("input[name='othours_oldvalue']").val();
        var otnewval = $("#othours").val();
        
        if (!parseInt(otnewval)) {
            insert_warning('Please enter a number.', '#othours');
        } else {
            if (parseInt(otnewval) > parseInt(otoriginalval)) {
                insert_warning('Number of hours cannot be greater than the original value.', '#othours');
            } else {               
                var updateot =  $.post($("#filedot").attr('action'), 
                    {otreason : $("#otreason").val(), 
                    userlogid : $("input[name='userlogid']").val(), 
                    othours : otnewval, 
                    ottype : $("select#ottype").val()});
                
                updateot.done(function(data){
                    // Check if session has expired prior to displaying data.
                    var expiredsession = data.match(/Session\sexpired/g);
                    if (!expiredsession) {
                        // Refresh page.
                        $("#filterot").submit();
                    } else {                        
                        // console.log("You've been logged out. Please log in.");
                        alert("Your session has expired. Please log in to file or approve this over time.")
                    }
                });
                
            }
        }
    });
    
    $(document).on('click', '#btndecline', function(event){
        var updateot =  $.post($("#filedot").attr('action') + '/declined', {
                        userlogid : $("input[name='userlogid']").val(),
                        ottype : $("select#ottype").val()});
                    
        updateot.done(function(data){
                    // Check if session has expired prior to displaying data.
                    var expiredsession = data.match(/Session\sexpired/g);
                    if (!expiredsession) {
                        // Refresh page.
                        $("#filterot").submit();
                    } else {                        
                        // console.log("You've been logged out. Please log in.");
                        alert("Your session has expired. Please log in to file or approve this over time.")
                    }
                });
    });
});