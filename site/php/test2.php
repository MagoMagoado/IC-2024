<?php
include_once('conexaoDB.php');
$tableName = 'auxiliar';
$table2 = 'tabela_' . $DBName;

try{

    $conn->beginTransaction();
    $sqlInsert = "INSERT INTO $table2 (col) SELECT col FROM $tableName";
    $conn->exec($sqlInsert);
    $conn->commit();

    $messages[] = "Colunas copiadas com sucesso!";
}
catch (PDOException $e) {
    $messages[] = "Failed to copy data: " . $e->getMessage();
}
?>