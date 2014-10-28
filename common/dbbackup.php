<?php
include("include.php");
include("dbbackup.inc.php");

$load = getParam("load");
$page = getParam("cbopage");
?>

<head>
    <title>ICS System Solutions - <?php etr("Security") ?></title>
    <?php styleSheet() ?>
</head>

<body>
    <div id=main>
        <?php menubar(); ?>
        <?php statusBody(); ?>
        <?php startBody(); ?>
        <h3>Database Backup</h3>
        <form action="" method="post">

            <input type="submit" name="load" value="Database Backup" />
        </form>

        <?php
        if ($load) {
            backup_tables(DBHOST, DBUSER, DBPWD, DBNAME);
//    backup_tables(DBHOST,DBUSER,DBPWD,'jabachidb');
        }
        ?>
        <?php endBody(); ?>

</body>
