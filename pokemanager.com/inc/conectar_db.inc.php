<?php
    $dbnombre="pokemanager";
    $dbruta="localhost";
    $dbusuario="root";
    $dbcontrasenya="";

try{
    $pdo=new PDO("mysql:host={$dbruta};dbname={$dbnombre}",
    $dbusuario,$dbcontrasenya);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Ha fallado la conexión" .$e -> getMessage();
}
?>