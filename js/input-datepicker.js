$(document).ready(function(){
    //datepicker plugin
    //link
    $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
    })
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
            $(this).prev().focus();
    });

    //or change it into a date range picker
    $('.input-daterange').datepicker({autoclose:true});
});