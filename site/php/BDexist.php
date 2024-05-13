<?php
include_once('conexaoDB.php');
$tableName = 'tabela_' . $DBName;
$BDjaCriado = NULL;

try {
    $query = $conn->query("SHOW TABLES FROM $DBName");
    $tableExists = $query->rowCount() > 0;
    if ($tableExists) {
        $messages[] = "Já existem dados no banco.";
        $BDjaCriado = 1;
    }
    else{
        $BDjaCriado = 0;
    }
}
catch (PDOException $e) {
    echo ("Connection failed: " . $e->getMessage());
}
?>