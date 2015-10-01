(function(){
    $('.cert').click(function(){
        var colval;
        var url = window.location + '/update'; 
        if (this.checked) {
            colval = 'Y';
        } else {
            colval = 'N';
        }
        $.post(url, {id : this.id, colval : colval});       
    });
}(jQuery));