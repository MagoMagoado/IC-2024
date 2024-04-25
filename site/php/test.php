<?php

// Diretório onde os arquivos estão localizados
$diretorio = "filesSent/";

// Obtém uma lista de todos os arquivos no diretório
$arquivos = glob($diretorio . "*");

// Verifica se algum arquivo foi encontrado
if ($arquivos !== false) {
    // Percorre cada arquivo encontrado
    foreach ($arquivos as $arquivo) {
        // Verifica se o arquivo existe
        if (file_exists($arquivo)) {
            // Abre o arquivo para leitura
            $fileNameOpen = fopen($arquivo, "r");

            // Verifica se o arquivo foi aberto com sucesso
            if ($fileNameOpen !== FALSE) {
                // Lê o arquivo linha por linha
                while (($row = fgetcsv($fileNameOpen)) !== FALSE) {
                    // Exibe cada linha do arquivo
                    echo implode(",", $row);
                }
                // Fecha o arquivo
                fclose($fileNameOpen);
            } else {
                echo "Erro ao abrir o arquivo $arquivo<br>";
            }
        } else {
            echo "Arquivo $arquivo não encontrado<br>";
        }
    }
} else {
    // Se nenhum arquivo foi encontrado, exibe uma mensagem
    echo "Nenhum arquivo encontrado na pasta $diretorio";
}

?>
