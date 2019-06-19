function sender(requestPath) {
    let fd = new FormData();
    let file = jQuery('#csv_file').prop('files');
    let individual_file = file[0];
    fd.append("file", individual_file);
    fd.append('action', 'csv');

    jQuery.ajax({
        url: requestPath,
        cache: false,
        contentType: false,
        processData: false,
        data: fd ,
        type: 'POST',
        success: function(data){
            alert(data);
            location.reload();
        }
    });
}