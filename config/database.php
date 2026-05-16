<?php

$host = "localhost";
$port = "5432";
$dbname = "kasir_app";
$user = "postgres";
$password = "nbrpemes123";

$conn = pg_connect("
    host=$host
    port=$port
    dbname=$dbname
    user=$user
    password=$password
");

if(!$conn){
    die("Koneksi database gagal");
}