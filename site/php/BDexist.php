<?php
include_once('conexaoDB.php');
$BDjaCriado = 0;
$tableName = 'tabela_' . $DBName;

if (isset($conn)) {
    $query = $conn->query("SHOW TABLES FROM $DBName");
    $tableExists = $query->rowCount() > 0;
    if ($tableExists) {
        $messages[] = "Já existem dados no banco.";
        $BDjaCriado = 1;
    }
}

echo json_encode(array(    
    'BD' => $BDjaCriado,
    'message' => $messages
));
?>