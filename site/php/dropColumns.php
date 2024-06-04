<?php
include_once('conexaoDB.php');
$tableName = 'auxiliar';

try {
    $sqlSelect = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$tableName."';";
    $stmt = $conn->query($sqlSelect);
    try{
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $columnsName = array_column($result, 'COLUMN_NAME');
        
        foreach ($columnsName as $column) {
            if ($column === 'id' || $column === 'col' || $column === $columnDrop) {
                continue;
            }
            $sqlSelect = "ALTER TABLE $tableName DROP COLUMN $column;";
            $conn->query($sqlSelect);
        };
        //troca nome da coluna que ficou para 'col'. Não trocar esse nome pois é usado no python
        $sqlSelect = "ALTER TABLE $tableName CHANGE $columnDrop col VARCHAR(255) NOT NULL;";
        $conn->query($sqlSelect);
        $messages[] = "Colunas auxiliar dropadas com sucesso!";
    }
    catch (PDOException $e) {
        $messages[] = "Failed to drop columns: " . $e->getMessage();
        $respostaAjax = 0;
    }
}
catch (PDOException $e) {
    $messages[] = "Connection failed: " . $e->getMessage();
    $respostaAjax = 0;
}
?>