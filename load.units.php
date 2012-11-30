<?php

/* THIS FILE LOADS UNITS INTO VIEW FOR CONSTRUCTION */


$linkID = @mysql_connect("flourcityshows.com","flour_rvb","wh1tegreenblue")
or die("Could not connect to MYSQL server");
@mysql_select_db("flour_rvb") or die("Could not select Database");


$_SESSION['allUnits']=array();


$query="SELECT * FROM Units";
			$result=mysql_query($query);
			if (!$result) {
				echo 'Could not run query: ' . mysql_error();
				exit;
			}
			
			while ($row=mysql_fetch_assoc($result)){
			$x=$row["Unit_Code"];
			$_SESSION['allUnits'][$x]=$row;
			}
mysql_close();			
// print_r($_SESSION['allUnits']);

?>