<script src = "../public/uploadify/jquery.uploadify-3.1.min.js" ></script>
<script src = "../public/js/jquery.calculation.js" type = "text/javascript" ></script>
<script src = "include.js" type = "text/javascript" ></script>
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
<tbody>
    <?php
    $j = 1;
    $ResultAttachment = attachResult($search_id, 'requisition');
    while ($rowAttachment = fetch_object($ResultAttachment)) {
        ?>
    <input type='hidden' value='<?php echo $rowAttachment->ATTACH_FILE_PATH; ?>' name='FileName[<?php echo $rowAttachment->FILE_ATTACH_LIST_ID; ?>]'/>
    <input type='hidden' value='<?php echo $rowAttachment->ATTACH_TITTLE; ?>' name='AttachmentDetails[<?php echo $rowAttachment->FILE_ATTACH_LIST_ID; ?>]'/>
    <tr>
        <td><?php echo $j; ?>.</td>
        <td><?php echo $rowAttachment->ATTACH_TITTLE; ?></td>
        <td align="center"> 
            <a href='<?php echo $rowAttachment->ATTACH_FILE_PATH; ?>' class="fancybox">View </a> 
            <div class='remove float-right' id="<?php echo $rowAttachment->FILE_ATTACH_LIST_ID; ?>">Remove</div>
        </td>
    </tr>

    <?php
    $j++;
}
?>
</tbody>
</table>
