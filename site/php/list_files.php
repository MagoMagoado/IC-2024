<?php
include_once('conexaoDB.php');
header('Content-Type: application/json');
$tableName = 'tabela_' . $DBName;

try {
    $sqlSelect = "SELECT id, col FROM $tableName";
    $stmt = $conn->prepare($sqlSelect);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
