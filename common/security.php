<?php include("include.php") ?>

<head>
<title>ICS System Solutions - <?php etr("Security") ?></title>
<?php styleSheet() ?>
</head>

<body>
<div id=main>
<?php menubar(); ?>
<?php statusBody(); ?>
<?php startBody(); ?>
<h3>Security</h3>

<ul id="bodymenu">
<li>
<a href='users.php' class=menupage>
<?php etr("Users") ?>
</a>
</li>
<li>
<a href='usergroups.php' class=menupage>
<?php etr("User groups") ?>
</a>
</li>
<li>
<a href='sessions.php' class=menupage>
<?php etr("Sessions") ?>
</a>
</li>
<li>
<a href='menu_config.php' class=menupage>
<?php etr("Menu Configuration") ?>
</a>
</li>
<!--
<li class=menupage>
<a href='logger.php' class=menupage>
<?php etr("Logger") ?>
</a>
</li>
-->
</ul>
    <?php endBody(); ?>

</body>
