<?php
include("../config.php");
$json = sanitize($_GET["json"]);
if(empty($json)){die;}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--<title>LDAvis</title>-->
    <script src="d3.v3.js"></script>
    <script src="ldavis.js"></script>
    <link rel="stylesheet" type="text/css" href="lda.css">
  </head>

  <body>
    <div id = "lda"></div>
    <script>
      var vis = new LDAvis("#lda", "<?php echo $json ?>");
    </script>
  </body>

</html>
