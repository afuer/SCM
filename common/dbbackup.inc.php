
<?php
global $dirName;
$dirName = "../backup/";

function create_zip($files = array(),$destination = '',$overwrite = true) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

		//close the zip -- done!
		$zip->close();

		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name, $tables = '*')
{
        global $dirName;
        $today = date("F j, Y");
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);

	//get all of the tables
	if($tables == '*')
	{
                $label = " [ Tables - All ] $today ";
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
                $label = " [ Tables - $tables ] $today ";
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}

	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);

		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";

		for ($i = 0; $i < $num_fields; $i++)
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++)
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}

	//save file
        $name = strtoupper($name);
        $filesaved = '[ '. $name. ' ]' . $label . ' - '.time().'-'.(md5(implode(',',$tables))).'.ics';
//	$handle = fopen('[ '. $name. ' ]' . $label . ' - '.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
        $handle = fopen($filesaved,'w+');


        //ZIp
        
	fwrite($handle,$return);
	fclose($handle);

	// make a zip file
	$files_to_zip = array(
	$filesaved
	);
	//if true, good; if false, zip creation failed
	$result = create_zip($files_to_zip,$filesaved);
//	if($result) @unlink($dirName.$fileName);

        //End Zip
//	fwrite($handle,$return);
//        if (fwrite) {
//            echo "Done";
//        } else {
//            echo "Ouch";
//        }
//	fclose($handle);
}



function filesInDir($tdir)
{
        $tdir = backup;
		$cnt = 1;
        foreach($dirs as $file)
        {
                if (($file == '.')||($file == '..'))
                {
                }
                elseif (is_dir($tdir.'/'.$file))
                {
                        filesInDir($tdir.'/'.$file);
                }
                else
                {
                        echo $cnt.")&nbsp;&nbsp;<a href=\"backup/$file\">".$file."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"index.php?dfname=$file\" onclick=\"javascript:return confirm('Are you sure to delete?')\">Delete</a><br>";
						$cnt++;
                }

        }
}