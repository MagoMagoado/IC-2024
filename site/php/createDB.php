<?php
    include_once('conexaoDB.php');
    $fileName = 'D:\Documents\Unicamp\IC\datasets\Tweets.csv';
    $tableName = 'tabelaTeste';

    if (file_exists($fileName)) {
        // Abre o arquivo em modo leitura
        $fileNameOpen = fopen($fileName, "r");
        if ($fileNameOpen !== FALSE) {
            // Lê a primeira linha do arquivo (cabeçalho)
            // $header = fgetcsv($fileNameOpen, 0, ';');
            $header = fgetcsv($fileNameOpen);
            if ($header !== FALSE) {
                // sanatiza o cabeçalho e troca espaços por _
                $header = array_map('sanitize', $header);
                $header = array_map(function($word) {
                    return str_replace(' ', '_', $word);
                }, $header);
                /////////////////////////////////////////////
                if(isset($conn)) {
                    $query = $conn->query("SHOW TABLES FROM $DBName");
                    $tableExists = $query->rowCount() > 0;
                    if ($tableExists) {
                        echo "<p>Já existem dados no banco.</p>";
                    } else {
                        // irá criar as colunas da tabela
                        try {
                            $sqlTable = "CREATE TABLE IF NOT EXISTS $tableName (";
                            $sqlTable .= "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
                            foreach ($header as $columnName) {
                                $sqlTable .= "`$columnName` VARCHAR(255), ";
                            }
                            $sqlTable = rtrim($sqlTable, ", ") . ");";
                            $conn->exec($sqlTable);
                            echo '<p>Tabela criada com sucesso!</p>';
                        }
                        catch (PDOException $e){
                            echo "Connection failed: " . $e->getMessage();
                        }
                        /////////////////////////////////////////////////////////////
                        // Inserir dados
                        try {
                            while (($row = fgetcsv($fileNameOpen)) !== FALSE) {
                                $sqlInsert = "INSERT INTO $tableName (";
                                $sqlInsert .= implode(", ", array_map(function($column) { return "`$column`"; }, $header));
                                $sqlInsert .= ") VALUES (";
                                $sqlInsert .= implode(",", array_map('sanitize', $row));
                                $sqlInsert .= ");";
                                $conn->exec($sqlInsert);
                            }
                            echo '<p>Dados inseridos com sucesso!</p>';
                        }
                        catch (PDOException $e){
                            echo "Connection failed: " . $e->getMessage();
                        }
                    }
                    fclose($fileNameOpen);
                    /////////////////////////////////////////////////////////////
                }
                // caso isset($conn) dê errado
                else {
                    echo '<p>Não foi possível conectar ao banco MySQL.</p>'; exit;
                }
            }
            // caso ($header !== FALSE) dê errado
            else {
                echo "<p>Não foi possível ler o cabeçalho do csv.</p>";
            }
        }
        // caso ($fileNameOpen !== FALSE) dê errado
        else {
            echo "<p>Não foi possível ler o csv.</p>";
        }
    }
    // caso file_exists($filename) dê errado
    else {
        echo '<p>Arquivo .csv não existe ou o caminho não foi encontrado.</p>';
    }
?>