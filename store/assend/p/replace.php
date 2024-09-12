<?php

// Connect to your MySQL database.
$hostname = "localhost";
$username = "root";
$password = "password";
$database = "store";

mysql_connect($hostname, $username, $password);

// The find and replace strings.
$find = 'id="video-player"';
$replace = 'width="560" height="315"';

$loop = tep_db_query("
    SELECT
        concat('UPDATE ',table_schema,'.',products_description, ' SET ',products_description, '=replace(',products_description,', ''{$find}'', ''{$replace}'');') AS s
    FROM
        products_description
    WHERE
        table_schema = '{store}'")
or die ('Cant loop through dbfields: ' . mysql_error());

while ($query = mysqli_fetch_assoc($loop))
{
        tep_db_query($query['s']);
}
?>