<?  
include_once('include.php');
include_once("db_backup.inc.php");

$id=$_POST['id'] or $id=$_GET['id'];


   
$title="Database backup";

//$dao = new DAO;

$action=$_POST['action'];
$origfilename=$filename=$_POST['filename']; 
$del=$_GET["del"];
$filepath="./backup";


if ($action=='update'){

	if (empty($filename))
		array_push($redMsg,"File Name required");

	if (empty($redMsg)){
		$tmpfile=$filepath."/".$filename;
		$revfile=strrev($filename);
		$k=1;
		while(file_exists($tmpfile))
			$tmpfile=renameDuplicate($tmpfile,$k++);
		if (strcasecmp(stristr($revfile,strrev(".tar.gz")),$revfile)!=0)
			$filename.=".tar.gz";
		$newToFile=$toFile=$filepath."/".$filename;
		$k=1;
		while(file_exists($newToFile))
			$newToFile=renameDuplicate($newToFile,$k++);
		$filename=$newToFile;
		if (!dumpdb2($tmpfile))
			array_push($redMsg,"backup failed: $tmpfile");
		/*else if (($res2 = system("tar -zcvf $filename $tmpfile"))!=$filename)
			array_push($redMsg,"compression failed: $res2");*/
		else{
			ob_start();   // Start buffering
			system("tar -zcvf $filename $tmpfile");
			$res2 = ob_get_contents();
			ob_end_clean();  // Stop buffering
			array_push($greenMsg,"backup successful");
			array_push($greenMsg,"Get the unzipped file here <a href='$tmpfile'>$tmpfile [".(int)(filesize($tmpfile)/1000)." Kbytes]</a>");
			array_push($greenMsg,"Get the zipped file here <a href='$filename'>$filename [".(int)(filesize($filename)/1000)." Kbytes]</a>");
		}
	}
}
else if (!empty($del)){
	$del=$filepath."/".$del;
	if (file_exists($del)){
		if (is_dir($del)){
			if (rmdir($del)===FALSE)
				array_push($redMsg,"Directory Deletion failed - Directory is not empty");
			else
				array_push($greenMsg,"Directory Deleted successfully");
		}
		else{
			if (unlink($del)===FALSE)
				array_push($redMsg,"File Deletion failed - Permission Denied");
			else
				array_push($greenMsg,"File Deleted successfully");
		}
	}
	else
		array_push($redMsg,"Deletion failed - Nonexistent file or directory");
}

$semiTitle="Database Backup";
 
?>
	<form name='backupfrm' action='backup.php' method='post'>
	<input type='hidden' name='action' value='update'>
	<table align="center">
		<tr>
			<th class='formTh'>Filename</th>
			<td><input type='text' name='filename' value='<?=$origfilename?>'></td>
		</tr>
		<tr><td colspan=2 align=right><input type='reset'><input type='submit' value='Backup'></td></tr>
	</table>
	</form>
	<br/>
	<table align="center">
	<tr>
		<th class='formTh' align="left">Backup File</th>
		<th class='formTh' nowrap align="right">Size (bytes)</th>
		<th class='formTh' align="right">Created</th>
		<th class='formTh'>&nbsp;</th>
	</tr>
	<?
	$dh  = opendir($filepath);
	while (false !== ($filename = readdir($dh))) {
	    $files[] = $filename;
	}
	sort($files);
	//$files=scandir($filepath); //php5 only
	$j=0;

	foreach($files as $file){
		if ($file=="." || $file=="..") continue;
		$j++;
		$file1=$filepath."/".$file;
		if (is_dir($file1)) continue;
		?><tr bgcolor="<?=$rowColor[$j%2]?>">
			<td align="left"><a href='<?=$file1?>'><b><u><?=$file?></u></b></a></td>
			<td align="right">&nbsp;<?=filesize($file1)?>&nbsp;</td>
			<td align="right">&nbsp;<?=date("d/m/Y H:i:s \G\M\TO", filectime($file1))?>&nbsp;</td>
			<td align="left"  bgcolor="<?=$rowColor[$j%2]?>">
				<a href="../../iraban/backup.php?del=<?=$file?>" onclick="return confirm('Are you sure?');">Delete</a>
			</td>
		</tr>
		<?
	}	
?>
	</table>
<?
?>
