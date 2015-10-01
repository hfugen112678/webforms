(function(){
    $(document).on('click', '#btnapprove', function(){
       var formval = [];
       formval.leaveid = $('#modal-approval').find('input[name="leaveid"]').val();
       formval.employeenumber = $('#modal-approval').find('input[name="employeenumber"]').val();
       formval.leavetype = $("select#leavetypes").val();     
       formval.paylabel = $("select#leavepaylabel").val(); 
       formval.leavecount = $('#modal-approval').find('input[name="leavecount"]').val();
       formval.leavereason = $("#leavereason").val();
       formval.leavefrom = $("#leavefrom").val();
       formval.leaveto = $("#leaveto").val();
       formval.leavestatus = 0;
       
       var holidayoff = formval.leavereason.match(/holiday off/ig);
       var leavecredits = credits(formval.employeenumber, formval.leavetype);
       
       if (parseInt(formval.paylabel) == 1 && parseInt(leavecredits) < parseInt(formval.leavecount)) {
           if (!holidayoff) {
               bootbox.alert('<h4><strong>Error:</strong> Not enough leave credits.</h4>');
           } else {
               update(formval);
           }
       } else if (parseInt(formval.paylabel) != 1 && parseInt(leavecredits) >= parseInt(formval.leavecount)) {
           bootbox.confirm("<h4>Employee has enough credits: Approve without pay?</h4>", function(result){
               if(result) {
                   update(formval);
               }
           });
       } else {
           update(formval);

       }       
    });

    $(document).on('click', '#btndecline', function(){
        var formval = [];
        formval.leaveid = $('#modal-approval').find('input[name="leaveid"]').val();
        formval.employeenumber = $('#modal-approval').find('input[name="employeenumber"]').val();
        formval.leavetype = $("select#leavetypes").val();     
        formval.paylabel = $("select#leavepaylabel").val();
        formval.leavefrom = $("#leavefrom").val();
        formval.leaveto = $("#leaveto").val();
        formval.leavestatus = 1;
        bootbox.confirm("<h4>Are you sure you want to decline this request?</h4>", function(result){
            if(result) {
                update(formval);
            }
        });
    });
   
   // Check leave credits
   function credits(emp, leavetype)
   {
       // The final date an employee is eligible for SL credits
        var lastdate = new Date('May 31, 2013');   
        var credits = [];
		
        // employee has SL credits
        if (datehired(emp) > (lastdate.getTime()/1000)) {
           leavetype = 'Vacation Leave';
        }		
		
        $.ajax({
           dataType :   "json",
           url      :   $(location).attr('href') + '/credits',
           data     :   {emp : emp},
           async    :   false,
           success  :   function(data) {
                if (data.length !== 0) {
                   $.each(data, function(key, value){
                       $.each(value, function(k, v){
                           credits[k + ' leave'] = v;
                       });
                   });
                }
           }
        });
        return credits[leavetype.toLowerCase()];
   }
   
   // update leave
   function update(formval) {
        var msg;
        $.post($('#modal-approval').attr('action'),{
            leaveid : formval.leaveid,
            employeenumber : formval.employeenumber,
            leavefrom : formval.leavefrom,
            leaveto : formval.leaveto,
            count : formval.leavecount,
            leavetype : formval.leavetype,
            leavereason : formval.leavereason,
            withpay : formval.paylabel,            
            status : formval.leavestatus           
        });
        
        if (formval.leavestatus == 0) {
            msg = 'approved';
        } else {
            msg = 'denied';
        }
        
        bootbox.alert('<h4>Leave has been ' + msg + '.</h4>', function(){
            location.reload();
        });
        $('#modal-approval .btn-primary').remove();
        $('#modal-approval .btn-danger').remove();
   }
   
   // Check date hired
   function datehired(employeenumber)
   {
       var datehired;
        $.ajax({
           dataType :   "json",
           url      :   $(location).attr('href') + '/datehired' ,
           data     :   {emp : employeenumber},
           async    :   false,
           success  :   function(data) {
                if (data.length !== 0) {
                   $.each(data, function(key, value){
                       $.each(value, function(k, v){
                           datehired = v;
                       });
                   });
                }
           }
        });       
       return datehired;
   }   
}(jQuery));