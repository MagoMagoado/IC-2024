<?php
$messages = array();
//resposta Ajax vai ser 0 se der erro ou se BD já existe
$respostaAjax = 0;
//BDjaCriado = 0: vai excluir BD existindo ou não
//BDjaCriado = 1: existe BD e vai fazer pergunta
//BDjaCriado = 2: existe BD e vai adicionar mais dados ao que já tem
$BDjaCriado = isset($_POST['BDjaCriado']) ? $_POST['BDjaCriado'] : 0;
$header = NULL;

if($BDjaCriado != 2){
    //verifica se o BD já existe
    include_once('BDexist.php');
}else{
    $messages[] = "escolheu número 2";
}

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
            $messages[] = "SUCESSO ao salvar os arquivos";
            $respostaAjax = 1;
            include_once('test.php');
        } else {
            $messages[] = "ERRO ao salvar os arquivos";
            $respostaAjax = 0;
        }
    }
} else {
    $messages[] = "Nenhum arquivo enviado";
    $respostaAjax = 0;
}

echo json_encode(array(
    'BD' => $BDjaCriado,
    'columnsName' => $header,
    'response' => $respostaAjax,
    'message' => $messages
));
?>
