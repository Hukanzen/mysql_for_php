<?php
require_once("./mysqli_connection.php");

$linkid=db_connect("localhost","test_user","password","test_db");
var_dump(db_a_fetch_assoc(array("select * from test_db.test_table;"),$linkid));
db_close($linkid);

?>