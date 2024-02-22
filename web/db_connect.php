<?php
$host = 'dpg-cna5toa1hbls73discdg-a.oregon-postgres.render.com';
$dbname = 'csci4140hw1_db';
$username = 'csci4140hw1_db_user';
$password = '1XJjUdOH6GTZ40bp1WQzlFJUufKYE3Rx';

try {
    $conn =  pg_connect("host=$host dbname=$dbname user=$username password=$password");
    // $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;user=$username;password=$password");
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $stmt = $conn->query('SELECT version()');
    // $version = $stmt->fetchColumn();
} catch(PDOException $e) {
    echo "<p>Unable to connect to the database123: " . $e->getMessage() . "</p>";
}
?>