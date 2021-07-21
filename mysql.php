<?php
$conn = new mysqli("localhost", "root", "", "sqldb");
if(mysqli_connect_errno()){
    echo("Failed to connect to MySql ".mysqli_connect_error());
}else{
    echo ("Connection to mysql successful");
    $conn -> close();
}
?>