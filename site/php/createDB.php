<?php
include_once('conexaoDB.php');
$fileName = 'D:\Documents\Unicamp\IC\datasets\Tweets.csv';
$tableName = 'tabela_'.$DBName;

if (file_exists($fileName)) {
    // Verifica a conexão com o banco de dados
    if(isset($conn)) {
        $query = $conn->query("SHOW TABLES FROM $DBName");
        $tableExists = $query->rowCount() > 0;
        if ($tableExists) {
            echo "<p>Já existem dados no banco.</p>";
            try {
                $sqlDrop = "DROP TABLE $tableName";
                $conn->exec($sqlDrop);
                echo "<p>Dados excluídos com sucesso!</p>";
                exit();
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        } else {
            // Abre o arquivo em modo leitura
            $fileNameOpen = fopen($fileName, "r");
            if ($fileNameOpen !== FALSE) {
                // Lê a primeira linha do arquivo (cabeçalho)
                $header = fgetcsv($fileNameOpen);
                if ($header !== FALSE) {
                    // Sanatiza o cabeçalho e troca espaços por _
                    $header = array_map('sanitize', $header);
                    $header = array_map(function($word) {
                        return str_replace(' ', '_', $word);
                    }, $header);

                    // Irá criar as colunas da tabela
                    try {
                        $sqlTable = "CREATE TABLE IF NOT EXISTS $tableName (";
                        $sqlTable .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
                        foreach ($header as $columnName) {
                            $sqlTable .= "`$columnName` VARCHAR(255), ";
                        }
                        $sqlTable = rtrim($sqlTable, ", ") . ");";
                        $conn->exec($sqlTable);
                        echo '<p>Tabela criada com sucesso!</p>';
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

                    // Inserir dados
                    try {
                        // Começa uma transação
                        $conn->beginTransaction();
                        
                        // Prepara a consulta de inserção
                        $sqlInsert = "INSERT INTO $tableName (`" . implode("`, `", $header) . "`) VALUES (" . implode(',', array_fill(0, count($header), '?')) . ")";
                        $stmt = $conn->prepare($sqlInsert);

                        // Contador para controlar o número de linhas processadas
                        $rowCount = 0;

                        // Lê as linhas do arquivo e insere os dados na tabela
                        while (($row = fgetcsv($fileNameOpen)) !== FALSE) {
                            try {
                                // Preencher a linha do CSV com valores vazios se necessário
                                $row = array_pad($row, count($header), '');

                                // Atribui valores aos parâmetros do statement preparado
                                $stmt->execute($row);
                                $rowCount++;

                                // Como está no while, fazer o commit a cada 1000 linhas do csv (ajuda no desempenho)
                                if ($rowCount % 1000 == 0) {
                                    $conn->commit();
                                    // Inicia uma nova transação
                                    $conn->beginTransaction();
                                }
                            } catch (PDOException $e) {
                                // Ignora a linha problemática e continua para a próxima
                                echo '<p>Linha '.$rowCount.' apresentou problema e foi excluída.</p>';
                                continue;
                            }
                        }

                        // Faz commit da transação final
                        $conn->commit();
                        echo '<p>Dados inseridos com sucesso!</p>';
                    } catch (PDOException $e) {
                        // Se ocorrer um erro ao preparar a consulta, faz rollback da transação
                        $conn->rollback();
                        echo "Connection failed: " . $e->getMessage();
                    }
                } else {
                    echo "<p>Não foi possível ler o cabeçalho do csv.</p>";
                }
                
                // Fecha o arquivo após a leitura
                fclose($fileNameOpen);
            } else {
                echo "<p>Não foi possível ler o csv.</p>";
            }
        }
    } else {
        echo '<p>Não foi possível conectar ao banco MySQL.</p>';
    }
} else {
    echo '<p>Arquivo .csv não existe ou o caminho não foi encontrado.</p>';
}
?>
