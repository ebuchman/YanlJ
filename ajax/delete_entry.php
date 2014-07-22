<?php  

function delete_entry($name, $con){
        $result = pg_prepare($con, "check_entry", 'SELECT * FROM Entries WHERE entry_name = $1');
        $result = pg_execute($con, "check_entry", array($name));

        if ($result){
            $result = pg_prepare($con, "del_entry", 'DELETE FROM Entries WHERE entry_name=$1');
            $result = pg_execute($con, "del_entry", array($name));
        }
		pg_free_result($result);
}
delete_entry($_POST['name'], $con);
?>
