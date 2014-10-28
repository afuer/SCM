<?php
function get_table_content2($db, $table, $crlf,$handle)
{
    $result = mysql_db_query($db, "SELECT * FROM $table") or mysql_die();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        set_time_limit(60); 
        $table_list = "(";

        for($j=0; $j<mysql_num_fields($result);$j++)
            $table_list .= mysql_field_name($result,$j).", ";

        $table_list = substr($table_list,0,-2);
        $table_list .= ")";

        $schema_insert = "INSERT INTO $table $table_list VALUES (";

        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= " NULL,";
            elseif($row[$j] != "")
                $schema_insert .= " '".addslashes($row[$j])."',";
            else
                $schema_insert .= " '',";
        }
        $schema_insert = ereg_replace(",$", "", $schema_insert);
        $schema_insert .= ")";
        fwrite($handle, htmlspecialchars(trim($schema_insert).";$crlf"));
        $i++;
    }
    return (true);
}

function get_table_def2($db, $table, $crlf)
{
    $drop=1;

    $schema_create = "";
    if(!empty($drop))
        $schema_create .= "DROP TABLE IF EXISTS $table;$crlf";

    $schema_create .= "CREATE TABLE $table ($crlf";

    $result = mysql_db_query($db, "SHOW FIELDS FROM $table") or mysql_die();
    while($row = mysql_fetch_array($result))
    {
        $schema_create .= "   $row[Field] $row[Type]";

        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
            $schema_create .= " DEFAULT '$row[Default]'";
        if($row["Null"] != "YES")
            $schema_create .= " NOT NULL";
        if($row["Extra"] != "")
            $schema_create .= " $row[Extra]";
        $schema_create .= ",$crlf";
    }
    $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
    $result = mysql_db_query($db, "SHOW KEYS FROM $table") or mysql_die();
    while($row = mysql_fetch_array($result))
    {
        $kname=$row['Key_name'];
        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
            $kname="UNIQUE|$kname";
         if(!isset($index[$kname]))
             $index[$kname] = array();
         $index[$kname][] = $row['Column_name'];
    }

    while(list($x, $columns) = @each($index))
    {
         $schema_create .= ",$crlf";
         if($x == "PRIMARY")
             $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
         elseif (substr($x,0,6) == "UNIQUE")
            $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
         else
            $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
    }

    $schema_create .= "$crlf";
    return (stripslashes($schema_create));
}

function dumpdb2($filename){
	global $DB;

	//print $filename;
	/*if (!is_writable($filename)){
         return false;
    }*/
	//print $filename;

    if (!$handle = fopen($filename, 'w')) {
         return false;
    }
	//print $filename;

    // Write $somecontent to our opened file.
    //fwrite($handle, $somecontent);
    
 
 	$tables = mysql_list_tables($DB);
	$crlf="\r\n";
	
	$num_tables = @mysql_numrows($tables);
	
    fwrite($handle, "# developed by nasa (nasarouf@gmail.com)$crlf");
    fwrite($handle, "# using -- phpMyAdmin MySQL-Dump$crlf");
    fwrite($handle, "# http://phpwizard.net/phpMyAdmin/$crlf");
    fwrite($handle, "#$crlf");
    fwrite($handle, "# time = ".date("d/M/Y H:i:s"));
    fwrite($handle, "#$crlf");

    for($i=0;$i < $num_tables;$i++)
    { 
        $table = mysql_tablename($tables, $i);

        fwrite($handle, $crlf);
        fwrite($handle,"# --------------------------------------------------------$crlf");
        fwrite($handle,"#$crlf");
        fwrite($handle,"# TABLE STRUCTURE FOR '$table'$crlf");
        fwrite($handle,"#$crlf");
        fwrite($handle,$crlf);

        fwrite ($handle,get_table_def2($DB, $table, $crlf).");$crlf$crlf");
        
        fwrite($handle,"#$crlf");
        fwrite($handle,"# DUMPING DATA FOR '$table'$crlf");
        fwrite($handle,"#$crlf");
        fwrite($handle,$crlf);
        
	    get_table_content2($DB, $table, $crlf, $handle);
    }
    fclose($handle);
	return true;
}
?>