<?php
include_once '../lib/DbManager.php';
$today = date('Y-m-d');
include '../body/header.php';


$db = new DbManager();

include_once '../body/body_header.php';
?>
<script src="../public/uploadify/jquery.uploadify-3.1.min.js"></script>
<link href="../public/uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../public/js/jquery.calculation.js"></script>

<link rel="stylesheet" type="text/css" href="../jquery-ui/jquery-ui-1.8.23.custom_smoothness/css/smoothness/jquery-ui-1.8.23.custom.css">
<script type='text/javascript' src='../jquery-ui/jquery-ui-1.8.23.custom_smoothness/js/jquery-ui-1.8.23.custom.min.js'></script>
<link rel="stylesheet" type="text/css" href="../public/combogrid/css/smoothness/jquery.ui.combogrid.css">
<script type="text/javascript" src="../public/combogrid/plugin/jquery.ui.combogrid-1.6.2.js"></script>
<script src="Requisition.js"></script>
<script type="text/javascript">
    function myformatter(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
    }
    function myparser(s) {
        if (!s)
            return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[2], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    }

    function ddmmyyyyToDate(str) {
        var parts = str.split("-");                  // Gives us ["dd", "mm", "yyyy"]
        return new Date(parseInt(parts[0], 10), // Year
                parseInt(parts[1], 10) - 1, // Month (starts with 0)
                parseInt(parts[2], 10));     // Day of month
    }



    $(document).ready(function() {

        $('#dur').hide();
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
            'buttonText': 'SELECT',
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


        //alert('AABB');
        $('#LeaveTypeID').change(function() {

            if ($('#LeaveTypeID').val() != 'Annual') {
                $('#trLeaveReleaver').fadeOut();
            }
            else
            {
                $('#trLeaveReleaver').fadeIn();
            }

            var leaveType = $('#LeaveTypeID').val();
            //alert(leaveType);
            $.ajax({
                type: "POST",
                url: 'ajax_combo.php?type=' + leaveType,
                success: function(data) {
                    $('#no_of_leave').text(data);
                }
            });
        });



        $('#save_button').click(function() {
            var s1 = $('input[name="LeaveFrom"]').val();          // ekhane id dhorte parche na
            var d1 = ddmmyyyyToDate(s1);
            var s2 = $('input[name="LeaveTo"]').val();
            var d2 = ddmmyyyyToDate(s2);
            var diff = (d2 - d1) / 86400000;
            if (diff < 0) {
                alert('Time to cannot be less than time from');
                $('input[name="To"]').css("color", "red");
                return false;
            }
            //var dur = $('input[name="duration"]').val(diff);

            var leaveNo = parseInt($('#no_of_leave').text());
            if (leaveNo < diff) {
                alert('You have not enough leaves..');
                return false;
            }
            else
                return true;
        });


        $('#calculate').click(function() {

            $('#dur').fadeIn(1000);
            var s1 = $('input[name="LeaveFrom"]').val();          // ekhane id dhorte parche na
            var d1 = ddmmyyyyToDate(s1);
            var s2 = $('input[name="LeaveTo"]').val();
            var d2 = ddmmyyyyToDate(s2);
            var diff = (d2 - d1) / 86400000;
            var dur = $('input[name="duration"]').val(diff);

            var leaveNo = parseInt($('#no_of_leave').text());
        });

        $('#cg').combogrid({
            panelWidth: 500,
            url: 'combogrid.php',
            idField: 'CARD_NO',
            textField: 'EM',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'EMPLOYEE_ID', title: 'Employee ID', width: 10},
                    {field: 'CARD_NO', title: 'Card No', align: 'right', width: 20},
                    {field: 'FIRST_NAME', title: 'Employee Name', align: 'right', width: 40}
                ]]
        });
    });

</script>  
<br/><br/>

<div class="easyui-layout" style="width:100%; height:450px;">  
    <div data-options="region:'east', split:true, collapsed:false" title="Notifications" style="width:250px;">  
        <h3>Leave Balance:</h3>

        <br />
        <h3>Approval Pending :</h3>
        <ul class="easyui-tree">
            <li>from Shuvo (ASL0010)</li>
            <li>from Forhad (ASL 0011)</li>
            <li>from Junayed (ASL 0012)</li>
        </ul>
    </div> 
    <div data-options="region:'center'">  
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  
            <div title="Leave Application">       
                <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
                    <table>
                        <tr class='fitem'>
                            <td> Memo Reference :</td>
                            <td><input type='text' name='MEMO_REF' class='easyui-validatebox' data-options="required:true" /></td>
                        </tr>
                        <tr class='fitem'>   
                            <td>Memo Info Ref :</td>
                            <td colspan="4">
                                <table class="ui-state-default">
                                    <thead>
                                    <th>SL</th>    
                                    <th>Memo Info Ref</th>
                                    <th>Ref Memo Summary</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo++$proSL1; ?></td>
                                            <td><input type='text' name='MEMO_INFO_REF[]' class='easyui-validatebox' value='' size="20" onchange="onChange($(this), 'ref_info');"></td> 
                                            <td><label id="ref_info"></label>
                                            </td>
                                            <td align="center"><div class="remove"><?php image("delete.png"); ?></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" title="productTab" onclick="RemoveTableTr('a');">Add</a>
                            </td></tr>
                        <tr class='fitem'>                        
                            <td><strong> Subject of the Memo :</strong></td>
                            <td><input type="text" name="MEMO_SUBJECT" class="easyui-validatebox" data-options="required:true" size="45" /></td>
                        </tr>

                        <tr class='fitem'>                        
                            <td><strong> Memo Type :</strong></td>
                            <td colspan="5">
                                <input type="radio" id="board" name="MEMO_TYPE" value="board"> Board 
                                <input type="radio" id="manage" name="MEMO_TYPE" value="management"> Management</td>
                        </tr>

                        <tr class='fitem' id="boardtr">
                            <td><strong>Board no.:</strong></td>
                            <td><input type='text' name='BOARD_NO' data-options="required:true"></td>
                            <td><strong>Date:</strong></td>
                            <td><input type='text' name="BOARD_DATE" class='easyui-datebox' data-options="formatter:myformatter,parser:myparser"></td>
                        </tr>

                        <tr id="managetr">
                            <td><label id="manLabel"></label></td>
                            <td colspan="4">
                                <table id="productTab" class="ui-state-default">
                                    <thead>
                                    <th>SL</th>
                                    <th>Employee</th>
                                    <th width="50">Approval Type</th>
                                    <th width="80">Action</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo++$proSL; ?></td>
                                            <!-- <td><div id="cgi"><input class="cg" type='text' name='price[<?php echo $proSL; ?>]' size="20"></div></td>
                                            
                                            <td><input style="width:100%" name="price1[]" type="text" class="price number" id="price_product" value="" /></td> -->
                                            <td>
                                                <?php comboBox('employeeID[]', $empList, $empID, FALSE) ?>
                                            </td> 

                                            <td><select name="apprvType[]">
                                                    <option value="Initiator">Initiator</option>
                                                    <option value="Recommended">Recommended</option>
                                                    <option value="Approved">Approved</option>
                                                </select>
                                            </td>
                                            <td align="center"><div class="remove"><?php image("delete.png"); ?></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" title="productTab" onclick="RemoveTableTr('productTab');">Add</a>
                            </td>
                        </tr>

                        <tr class='fitem'>
                            <td><strong>Memo Date :</strong></td>
                            <td>
                                <input type='text' name='MEMO_DATE' class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" size="20">
                            </td>
                        </tr>


                        <tr class='fitem'>
                            <td><strong>CC Code/ Division/ Office :</strong></td>
                            <td colspan="4">
                                <table class="ui-state-default" id="cost_center">
                                    <thead>
                                    <th width="20">SL</th>
                                    <th>Division</th>
                                    <th width="80">Action</th>                         
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo++$cost; ?></td>
                                            <td> <?php comboBox('division[]', $divList, $div, TRUE) ?>   </td>
                                             <!--  <td align="center">
                                                 <select name="division[]">
                                                     <option value="1">1001->MD's Sec->Head Office</option>
                                                     <option value="2">1003->Finance->Head Office</option>
                                                 </select>
                                             </td> 
                                            -->
                                            <td align="center"><div class="remove"><?php image("delete.png"); ?></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="CheckPRAmount('cost_center');">Add</a>
                            </td>
                        </tr>


                        <tr class='fitem'>
                            <td><strong>Memo Category :</strong></td>
                            <td> 
                                <input type="radio" name="MEMO_CATEGORY" value="Opex"> Opex
                                <input type="radio" name="MEMO_CATEGORY" value="Capex"> Capex
                                <input type="radio" name="MEMO_CATEGORY" value="Both"> Both
                            </td>
                        </tr>



                        <tr class='fitem'>
                            <td><strong> Details :</strong></td>
                            <td><textarea placeholder="Enter memo details here" rows="3" cols="55" name="MEMO_DETAILS"></textarea></td>
                        </tr>
                        <tr class='fitem'>
                            <td><strong>Approved Amount :</strong></td>
                            <td><input type='text' name='APPROVED_AMOUNT' id='APPROVED_AMOUNT' class='easyui-validatebox' value='' size="20"></td>
                        </tr>
                        <tr class='fitem'>
                            <td><strong>Remarks :</strong></td>
                            <td><textarea placeholder="Enter remarks here" rows="3" cols="55" name="REMARKS"></textarea></td>

                        </tr>

                        <tr class='fitem'>
                            <td><strong>Payment Method :</strong></td>
                            <td>
                                <select name="PAYMENT_METHOD">
                                    <option value="single">Single</option>
                                    <option value="installment">Installment</option>
                                </select>
                            </td>
                        </tr>
                        <tr class='fitem'>
                            <td>Attachment Tittle</td>
                            <td><input name="textfield" type="text" size="60" id="AttachmentDetails" placeholder="Title"/></td>
                            <td><input type='file' class='uploadify-button' id='file_upload' />
                                <input id="file_upload_done" class="text_field_display" type="text" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <table class="ui-state-default" id="attachment_tab">
                                    <thead>
                                    <th width="20">SL</th>
                                    <th align="left">Attachment Tittle</th>
                                    <th width="100" align="right">Action</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="save" value="save" id='save' class="button">
                </form>
            </div>          
        </div>  
        <div title="Category List">  
            <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
        </div> 
    </div>  
</div>
<?php
include '../body/footer.php';
?>