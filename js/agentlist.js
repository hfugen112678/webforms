(function(){
    $('.agentlist-modal').on('click', function(){
       $('#agent-modal').removeData('bs.modal');
       $('#agent-modal').modal({remote :$(this).attr('href')});
       $('#agent-modal').modal('show');
       // alert($(this).attr('href'));
       return false;
    });
    
    $('#filterattendance').on('submit', function(){
        var table = $('#agent-attendance').DataTable();
        table.destroy();
        $.post($('#filterattendance').attr('action'), {
            empno    :   $('input[name="employeenumber"').val(),
            start    :   $('#startdate').val(),
            end      :   $('#enddate').val()
        }, function(data){            
            $('#agent-attendance').DataTable({
                serverside  :   true,
                data        :   data,
                columns     :   [
                    {"data": "day"},
                    {"data": "schedule"},
                    {"data": "remarks"},
                    {"data": "timein"},
                    {"data": "timeout"}
                ]
            });
        }, 'json');
        return false; 
    });
    
    if ($('#emp-audits').length) { 
        var table = $('#emp-audits').DataTable();
        table.destroy();
        $.post("http://" + window.location.host + "/trecresource/index.php/webforms/empaudits/" + $('#empno-qascores').html(),
            function(data){            
            $('#emp-audits').DataTable({
                serverside  :   true,
                data        :   data,
                columns     :   [
                    {"data": "id"},
                    {"data": "call_date"},
                    {"data": "audit_note"},
                    {"data": "audit_date"}
                ]
            });
        }, 'json');        
    }
    
    $("#print-score").on('click', function(){
        // Get the folder url 
        var url = $(this).attr('data-source');
        $('#print-emp-scores').removeData('bs.modal');
        $('#print-emp-scores').modal({remote :$(this).attr('data-source')});
        $('.modal').on('shown.bs.modal',function(){      //correct here use 'shown.bs.modal' event which comes in bootstrap3
            $(this).find('iframe').attr('src',url.match(/(\w+:.+\.php)/i)[1] + '/agentlist/printscore/' + $('#audit-id').html() + '/' + $('#emp-no').html());
          });
        $('#print-emp-scores').modal('show');
    });
    
    $("#preview-attendance").on('click', function(){       
       var empno = $('input[name="employeenumber"]').val();
       var start = $('#startdate').val();
       var end = $('#enddate').val();
       $('#emp-attendance-listing').html('<div class="embed-responsive embed-responsive-16by9"><iframe></iframe></div>').find('iframe').attr('src', $(this).attr('data-source') + '/' + empno + '/' + start + '/' + end);
    });
}(jQuery));