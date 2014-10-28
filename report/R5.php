<?php

include '../lib/DbManager.php';
include '../body/header.php';
?>

<style>
    tr{text-align:center;}
</style>

</head>

<body>
    <h3>Real Estate Requizitions Details</h3>
    <table width="432" border="1" class="ui-state-default">
        <thead>
        <th width="36" scope="col">Code</th>
        <th width="73" scope="col">Location</th>
        <th width="123" scope="col">Proposed By </th>
        <th width="107" scope="col">Req. No</th>
    </thead>
    <tbody>
        <tr>
            <td>002</td>
            <td><div align="left">Dhanmondi</div></td>
            <td><div align="left">Md Rakib Hasan (101) </div></td>
            <td>00000007</td>
        </tr>
        <tr>
            <td>003</td>
            <td><div align="left">Lalmatia</div></td>
            <td><div align="left">Shovon Hasan (102)</div></td>
            <td>00000008</td>
        </tr>
        <tr>
            <td>005</td>
            <td><div align="left">Mirpur</div></td>
            <td><div align="left">Asha Ahmed (103)</div></td>
            <td>00000009</td>
        </tr>
        <tr>
            <td>009</td>
            <td><div align="left">Banasree</div></td>
            <td><div align="left">Zakaria Ahmed (104)</div></td>
            <td>00000010</td>
        </tr>
        <tr>
            <td>0011</td>
            <td><div align="left">Rampura</div></td>
            <td><div align="left">Mukul Hasan (105)</div></td>
            <td>00000011</td>
        </tr>
    </tbody>
</table>
<?php include '../body/footer.php'; ?>
