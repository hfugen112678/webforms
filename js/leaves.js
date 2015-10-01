$(document).ready(function(){
    
    // This would ensure that modal loads new content everytime 
    // the links on the search results are clicked. 
    // Reference: http://stackoverflow.com/questions/12286332/twitter-bootstrap-remote-modal-shows-same-content-everytime
    $('body').on('hidden.bs.modal', '.modal', function(){
        $(this).removeData('bs.modal');
    });
    
    $('#calendar').fullCalendar({
        header: {
            left: '',
            center : 'title'
            },
        selectable: true,
        selectHelper: true,
        select: function(start, end) {
                    start = moment(start);
                    end = moment(start);
                    $("#leaveModal").modal({remote : $(location).attr('href') + '/file?start=' + start.format('YYYY-MM-DD') + '&end=' + end.format('YYYY-MM-DD')});
                    $("#leaveModal").modal('show');
                    $('#calendar').fullCalendar('unselect');
		},
        events: $(location).attr('href') + '/leavebydate',
        eventClick: function(event, element) {
            $("#leaveModal").modal({remote : $(location).attr('href') + '/view?id=' + event.leaveid});
            $("#leaveModal").modal('show');
        }
    });
    
    // Check Holidays on load.
    check_holidays();
    
    $('.fc-prev-button').click(function(){       
        check_holidays();
    });
    
    $('.fc-next-button').click(function(){       
        check_holidays();
    });
    
    $('.fc-today-button').click(function(){       
        check_holidays();
    });
    
    // Check Holidays
    function check_holidays() {
        var currentmonth = $('#calendar').fullCalendar('getDate');
        console.log(currentmonth.format('YYYY-MM-DD'));
        $.ajax({
            dataType: "json",
            url :   $(location).attr('href') + '/monthly_holiday',
            data    : {currentmonth : currentmonth.format('YYYY-MM-DD')},
            success :   function(data) {
               var holidays = "";
               if (data.length !== 0) {
                    $.each(data, function(key, value){
                        var hdate = moment(value.Date);
                        if (value.Type === 'Legal Holiday') {
                            holidays += '<div class="external-event label-danger">';
                        } else {
                            holidays += '<div class="external-event label-yellow">';
                        }
                        holidays += '<i class="ace-icon fa fa-flag-o"></i>'
                                 + '<abbr title="' + value.Name + ' @ ' + hdate.format('MMMM D, YYYY') + '">' + value.Name.substr(0, 20) + '</abbr>'
                                 + '</div>';
                        mark_holidays(value.Type, hdate.format('YYYY-MM-DD'));
                    });
                } else {
                    holidays += '<div class="external-event label-info">'
                             + '<i class="ace-icon fa fa-calendar-times-o"></i>'
                             + 'No upcoming holidays.'
                             + '</div>';
                }
                holidays += '<label><div class="col-xs-1 label-danger">&nbsp;</div>&nbsp;'
                         + 'Legal Holiday</label><label><div class="col-xs-1 label-yellow">'
                         + '&nbsp;</div>&nbsp;Special Holiday</label>';
               $("#external-events").html(holidays);
            }
        });        
    }
    
    // Mark the holidays on the calendar.
    function mark_holidays(type, holiday) {
        $('.fc-day').each(function(){
            if ($(this).attr('data-date') == holiday) {
                if (type === 'Legal Holiday') {
                    $(this).css('background-color', '#d15b47');
                } else {
                    $(this).css('background-color', '#fee188');
                }
            }
        });
    }
});