<?php

//pega os dados do JavaScript, do FormData
$files = isset($_POST['name']) ? $_POST['name'] : '';

echo json_encode($files);

?>