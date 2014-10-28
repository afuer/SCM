<?php
//include './include.php';
//include '../body/header.php';







?>
<script src="../public/uploadify/jquery.uploadify-3.1.min.js"></script>
<script src="../public/js/jquery.calculation.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        //FILE UPLOAD  Attachment

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
            'buttonClass': 'uploadify-button',
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

//
//<div class='remove float-right' id="<?php echo $rowAttachment->REQUISITION_FILE_ATTACH_LIST_ID; ?>">Remove</div>



</script>

<table>
    <tr>
        <td>Attachment Tittle</td>
        <td><input name="textfield" type="text" size="60" id="AttachmentDetails" placeholder="Title" value=""/></td>
        <td><input type='file' class='uploadify-button' id='file_upload' />
            <input id="file_upload_done" class="text_field_display" type="text" />
        </td>
    </tr>
</table>
<table class="ui-state-default" id="attachment_tab">
    <thead>
    <th width="20">SL</th>
    <th align="left">Attachment Tittle</th>
    <th width="100" align="right">Action</th>
</thead>
<tbody></tbody>
</table>
<button type="submit" class="button" name="save">Save</button>