<?php
include '../lib/DbManager.php';
include '../body/header.php';

function Encode($data, $pwd) {

    $pwd_length = strlen($pwd);
    for ($i = 0; $i < 255; $i++) {
        $key[$i] = ord(substr($pwd, ($i % $pwd_length) + 1, 1));
        $counter[$i] = $i;
    }
    for ($i = 0; $i < 255; $i++) {
        $x = ($x + $counter[$i] + $key[$i]) % 256;
        $temp_swap = $counter[$i];
        $counter[$i] = $counter[$x];
        $counter[$x] = $temp_swap;
    }
    for ($i = 0; $i < strlen($data); $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $counter[$a]) % 256;
        $temp = $counter[$a];
        $counter[$a] = $counter[$j];
        $counter[$j] = $temp;
        $k = $counter[(($counter[$a] + $counter[$j]) % 256)];
        $Zcipher = ord(substr($data, $i, 1)) ^ $k;
        $Zcrypt .= chr($Zcipher);
    }
    return $Zcrypt;
}

echo "<pre>";

//$secretPass = 'kljhflk73#OO#*U$O(*YO';
//$encodeThis = 'Please meet me at 05:44 time.';

/* Regular Encoding */
//echo $encoded = Encode($encodeThis, $secretPass);
/* Another pass to decode */
//echo $decoded = Encode($encoded, $secretPass);
//echo 'Encoded String: ' . $encoded;
//echo '<br/>Decoded String: ' . $decoded.'<br/>';
//print_r(getallheaders());
//print_r(headers_list());
//echo $_SERVER['HTTP_USER_AGENT'];
?>
<div Title='Requisition New' class="easyui-panel" style="width:1000px; height:700px;" >  


</div>

<?php include '../body/footer.php'; ?>
