$(document).ready(function(){
   $(document).on('click', 'input[type="submit"]', function(){     
       var updatetranspo = $.post($("#transpoapproval").attr('action'), 
                            {id : $('input[name="id"]').val(),
                             action : $(this).attr('id')});
                            
        updatetranspo.done(function(data){
            // Check if session has expired prior to displaying data.
            var expiredsession = data.match(/Session\sexpired/g);
            if (!expiredsession) {
                // Refresh page.
                $("#filedtranspo").submit();
            } else {                        
                // console.log("You've been logged out. Please log in.");
                alert("Your session has expired. Please log in to file or approve this transpo allowance.")
            }
        });
        
      // prevent form from submitting when the submit buttons are clicked.
      return false;
   });
});