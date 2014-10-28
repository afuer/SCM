$(document).ready(function() {
    var k = 1;
    var AttachmentDetails = '';
    $('#file_upload').uploadify({
        // Some options
        'onSelect': function() {
            AttachmentDetails = $('#AttachmentDetails').val();
            if (AttachmentDetails == '') {
                AttachmentDetails = 'Title';
                alert('Please Enter The Tittle Of Attachment');
                //return; 
            }
            //alert('The file ' + file.name + ' was added to the queue.');
        },
        'method': 'post',
        'formData': {
            'id': '1'
        },
        'uploader': 'uploadify.php',
        'buttonClass': 'button',
        'buttonText': 'SELECT FILE',
        'onUploadSuccess': function(file, data, response) {
            $('#file_upload_done').val(data);
            $('#file_upload_done').removeClass('text_field_display')
            $('#file_upload').removeClass('uploadify-button').css('display', 'none');
            var FileName = $('#file_upload_done').val();
            $("<tr>" +
                    "<td align='center'>" + k + ".</td>" +
                    "<td align='left'>" + AttachmentDetails + "<input type='hidden' value='" + AttachmentDetails + "' name='AttachmentDetails[]'/></td>" +
                    "<input type='hidden' value='" + FileName + "' name='FileName[]'/>" +
                    "<td align='center'><a href='" + FileName + "' class='fancybox'>View </a><div class='remove float-right' onClick='$(this).parent().parent().remove();'>Remove</div></td>" +
                    "</tr>").appendTo("#attachment_tab");
            k++;
            $('#file_upload_done').addClass('text_field_display')
            $('#file_upload').addClass('uploadify-button').css('display', '');
        }
    });

    $('#file_upload_done').css('display', 'none');



    $('#AttachmentDetails').keyup(function() {
        SelectShowHide();
    });
    SelectShowHide();

    function SelectShowHide() {

        var selectFile = $('#AttachmentDetails').val().length;

        if (selectFile > 0) {
            $('#file_upload').show();
        } else {
            $('#file_upload').hide();
        }

    }



    $('#attachment_tab .remove').click(function() {
        var val = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: 'ajax_remove_attach_by_id.php?val=' + val,
            success: function(data) {
            }
        });
        $(this).parent().parent().remove();
    });

});


