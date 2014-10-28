<?php

include '../lib/DbManager.php';
include '../body/header.php';
?>

<style>
    tr{text-align:center;}
    a:link {text-decoration:none;}
</style>

</head>

<body>
    <h3>Real Estate Requisitions</h3>
    <table width="231" border="1" class="ui-state-default">
        <thead>
        <th width="124" scope="col">Requisition Type </th>
        <th width="91" scope="col">Total</th>
    </thead>

    <tbody>
        <tr>
            <td><div align="left">ATM</div></td>
            <td><a href="R5.php" target="_blank">5</a></td>
        </tr>
        <tr>
            <td><div align="left">Branch</div></td>
            <td><a href="R10.php" target="_blank">10</a></td>
        </tr>
        <tr>
            <td><div align="left">Department</div></td>
            <td><a href="R2.php" target="_blank">2</a></td>
        </tr>
    </tbody>
</table>

<?php include '../body/footer.php'; ?>