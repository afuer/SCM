<?php
	include('include.php');
	
	$selectSQL = <<<SQL
	select
		sessionid,
	    username,
	    unix_timestamp(logintime) as logintime,
	    remote_host
	from session
	order by logintime desc
	limit 50
SQL;

?>

<head>
<?php metatag() ?>
<title>ICS System Solutions - <?php echo tr("Sessions") ?></title>
<?php styleSheet() ?>
</head>

<body>
<div id=main>
<?php menubar(); ?>
<?php statusBody(); ?>
<?php startBody(); ?>
<h3>Sessions</h3>
<br/>
<table width="50%">
<th><?php echo tr("Id") ?></th>
<th><?php echo tr("Time") ?></th>
<th><?php echo tr("User") ?></th>
<th><?php echo tr("Remote host") ?></th>
<?php
    $rs = query($selectSQL);
    $class = "odd";
	$i = 0;
    while ($row = fetch_object($rs)) {
        echo "<tr class='$class'>";
		echo "<td>$row->sessionid</td>";
		echo "<td>" . formatDate($row->logintime) . ' ' . date('H:i', $row->logintime) . "</td>";
		echo "<td>$row->username</td>";
		echo "<td>$row->remote_host</td>";
        echo "</tr>";
        $class = ($class == "odd" ? "even" : "odd");
		$i++;
    }
?>
</table>
<?php endBody(); ?>
</body>
