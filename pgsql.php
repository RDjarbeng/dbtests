<?php
$conn = pg_connect("host=localhost dbname=postgresql user=postgres password=1234");
if( $conn ) {
echo "Connected to PostgreSQL successfully.<br />";
} else {
echo "Failed to connect to PostgreSQL:<br />";
}
?>
