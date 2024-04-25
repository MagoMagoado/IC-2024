<?php

//pega os dados do JavaScript, do FormData
// $files = isset($_POST['files']) ? $_POST['files'] : '';
// $message = "SUCESSO ao salvar os arquivos";


// $diretorio = "filesSent/";
// $destinoArquivo = $diretorio . md5($nomeArquivo.time()).".ini";//.ini para nao ser interpretado por padrão

// if (move_uploaded_file($files, $destinoArquivo)) {
//     //enviou com sucesso
//     } else {
//         $message = "ERRO ao salvar os arquivos";
//     }

// echo json_encode($message);




// Verifica se existem arquivos enviado

$mensagem = null;
if (!empty($_FILES['files']['name'])) {
    $files = $_FILES['files'];

    // Diretório onde os arquivos serão salvos
    $destinoArquivo = "filesSent/";

    // Verifica se o diretório de destino existe; se não, cria-o
    if (!file_exists($destinoArquivo) && !is_dir($destinoArquivo)) {
        mkdir($destinoArquivo, 0777, true);
    }

    // Loop através de cada arquivo enviado
    foreach ($files['tmp_name'] as $key => $tmp_name) {
        $nomeArquivo = $files['name'][$key];

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($tmp_name, $destinoArquivo . $nomeArquivo)) {
            $mensagem = "SUCESSO ao salvar os arquivos";
        } else {
            $mensagem = "ERRO ao salvar os arquivos";
        }
    }
} else {
    $mensagem = "Nenhum arquivo enviado";
}

echo json_encode(array('message' => $mensagem));

?>
