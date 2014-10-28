<?php
	include('include.php');

	checkPermission(PERMISSION_ADMINISTRATE_USERS);

	$groupid = getParam('groupid');
	$new = true;
	if (isSave()) {
		$description = getParam('description');
		if (isNew()) {
			$sql = "insert into usergroup (groupid, description)  
			        values ($groupid, '$description')";
			sql($sql);
			header("Location: usergroups.php");
			die;
		} else {
            $updateSQL =
    			"update usergroup set
    			    description='$description'
                where groupid='$groupid'";
    		sql($updateSQL);
		}
	}
	$del_permissionid = getParam('del_permissionid');
	if (!isEmpty($del_permissionid)) {
		sql("delete from usergroup_permission where groupid='$groupid' and permissionid=$del_permissionid");
	}
	$permissionid_new = getParam('permissionid_new');
	if (!isEmpty($permissionid_new)) {
		sql("insert into usergroup_permission (groupid, permissionid) values ('$groupid', $permissionid_new)");
	}

	$permissions = null;
	$rec = new Dummy();
	if (!isEmpty($groupid)) {
	    $selectSQL =
  		"select 
		   groupid,
		   description
		from usergroup
		where groupid='$groupid'
		";
		$rec = find($selectSQL);
		if ($rec != null) {
			$new = false;
			$permissions = query("select g.permissionid, description
			                 from usergroup_permission ag
							 join permission g on g.permissionid=ag.permissionid
							 where ag.groupid='$groupid'");
		}
	}
	
	$languages = rs2array(query("select language, description from language"));
	$allpermissions = rs2array(query("select permissionid, description from permission")); 

?>
<head>
<title>ICS System Solutions - <?php etr("User group") ?></title>
<?php styleSheet() ?>
</head>

<body>
<div id=main>
<?php menubar(); ?>
<?php statusBody(); ?>
<?php startBody(); ?>

<?php

if ($new):
    $head =  tr("Create user group");
    $p_sub = "";
    //$title = tr("Create user group");
else:
    $head =  tr("Edit user group");
    $title = $rec->description;
    $p_sub = "( {$title} )";

endif;

?>
<h3><?php echo $head; ?></h3>
<p class="hsub"><?php echo $p_sub ;?></p>
<br/>
<?php
$title = $rec->description;
if ($new)
	$title = tr("Create user group");
title("<a href='usergroups.php'>" . tr("User groups") . "</a> > $title")
?>
<img src="../images/q_top.gif" />
<form action="usergroup.php" method="POST" class="q_">
<table>
<tr>
	<td><?php etr("Id") ?>:</td>
	<td>
	<?php
	if ($new)
		textbox('groupid', '');
	else {
		echo $rec->groupid;
		hidden('groupid', $rec->groupid);
	}
	?>
	</td>
</tr>
<tr><td><?php etr("Description") ?>:</td><td><?php  textbox('description', $rec->description, 60) ?></td>
</table>
<?php
if ($permissions != null) {
	echo "<br/>";
	echo "<table>";
	echo "<th>" . tr("Delete") . "</th>";	
	echo "<th>" . tr("Permission") . "</th>";
	$class = 'odd';
	while ($row = fetch($permissions)) {
		echo "<tr class=$class>";
		echo "<td align=center>";
		deleteIcon("usergroup.php?groupid=$groupid&del_permissionid=$row->permissionid");
		echo "</td>";
		echo "<td>$row->description</td>";
		echo "</tr>";
        $class = ($class == "odd" ? "even" : "odd");
	}
	echo "<tr class=$class/>";
	echo "<td/>";
	echo "<td>";
	comboBox("permissionid_new", $allpermissions, null, true);
	echo "</td>";
	echo "</tr>";
	echo "</table>";

}
?>
<br/>
    <?php if ($new): saveButton("Save new account group"); else: saveButton("Update account group"); endif; ?>
<input type="hidden" name="new" value="<?php echo $new ?>"/>
</form>
<img src="../images/q_bottom.gif" />
<?php endBody(); ?>
</body>
