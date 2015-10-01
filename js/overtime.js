(function(){
    $('#updateOT').focus(function(){ 
        $('#filedot').submit(function(event){
            var formdata = validate_form();            
            if ($('.msg-warning').length == 0) {
                formdata['UserlogID'] = $('input[name="userlogid"]').val();
                formdata['OTReason'] = $("#otreason").val();
                $.post($("#filedot").attr('action'), formdata).done(function(data){
                    // Check if session has expired prior to displaying data.
                    var expiredsession = data.match(/Session\sexpired/g);
                    if (!expiredsession) {
                        bootbox.alert('<h4>Overtime has been updated.</h4>', function(){
                            // resubmit ot form filter.
                            $('#filterot').submit();
                        });
                    } else {
                        alert("Your session has expired. Please log in to file or approve this over time.");
                    }
                });
            }
            event.preventDefault();
            event.stopImmediatePropagation();
        });
        
        $('#btnapproveot').click(function(event) {
            var formdata = validate_form();
            if ($('.msg-warning').length == 0) {
                formdata['UserlogID'] = $('input[name="userlogid"]').val();
                $.post($("#filedot").attr('action'), formdata).done(function(data){
                    // Check if session has expired prior to displaying data.
                    var expiredsession = data.match(/Session\sexpired/g);
                    if (!expiredsession) {
                        bootbox.alert('<h4>Overtime has been approved.</h4>', function(){
                            // resubmit ot form filter.
                            $('#filterot').submit();
                        });
                    } else {
                        alert("Your session has expired. Please log in to file or approve this over time.");
                    }
                });
            }
            event.preventDefault();
            event.stopImmediatePropagation();
        });
        
        $('#btndeclineot').click(function(event) {
            $.post($("#filedot").attr('action') + '/declined', {
                UserlogID : $('input[name="userlogid"]').val(),
                OTReason : $("#otreason").val()
                
            }).done(function(data){
                // Check if session has expired prior to displaying data.
                    var expiredsession = data.match(/Session\sexpired/g);
                    if (!expiredsession) {
                        bootbox.alert('<h4>Overtime has been declined.</h4>', function(){
                            // resubmit ot form filter.
                            $('#filterot').submit();
                        });
                    } else {
                        alert("Your session has expired. Please log in to file or approve this over time.");
                    }
            });
            event.preventDefault();
            event.stopImmediatePropagation();
        }); 
        
        function validate_form() {
            var formdata = {};           
            $('.msg-warning').remove();           
            $('.filed-ot').each(function(){
                var oldvalue = parseInt($('input[name="' + this.id + '-oldval"]').val());
                if (oldvalue < parseInt($(this).val())) {
                    $('<p class="msg-warning text-warning bigger110 orange"><i class="ace-icon fa fa-exclamation-triangle" /> New value can\'t be greater than old value.</p>').hide().fadeIn(300).insertAfter('#' + this.id);
                } else if (isNaN($(this).val())) {                          
                    $('<p class="msg-warning text-warning bigger110 orange"><i class="ace-icon fa fa-exclamation-triangle" /> Value is not a number.</p>').hide().fadeIn(300).insertAfter('#' + this.id);
                } else {
                    formdata[this.id] = $(this).val();
                }
            });
            return formdata;
        }
    });
}(jQuery));