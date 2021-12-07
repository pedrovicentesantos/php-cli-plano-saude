<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultado</title>
  <link rel="stylesheet" href="styles/index.css">
</head>
<body>
  <section class="result-container">
    <button class="beginning">Início</button>

<?php
  function plano_existe($codigo_plano) {
    $planos = $GLOBALS["planos"];
    for ($i = 0; $i < count($planos); $i++) {
      if ($codigo_plano === $planos[$i]["codigo"]) {
        return $planos[$i];
      }
    }
    return false;
  }

  function valor_valido($idade) {
    if (is_numeric($idade) and intval($idade) >= 0) {
      return true;
    }
    return false;
  }

  function faixa_idade($idade) {
    switch ($idade) {
      case 0 <= $idade and $idade <= 17:
        return "faixa1";
        break;
      case  18 <= $idade and $idade <= 40:
        return "faixa2";
        break;
      case $idade > 40:
        return "faixa3";
      default:
        return "invalido";
        break;
    }
  }

  function obter_valor_beneficiario($faixa, $codigo_plano, $quantidade_beneficiarios) {
    $precos = $GLOBALS["precos"];
    for ($i = 0; $i < count($precos); $i++) {
      if ($codigo_plano === $precos[$i]["codigo"] and $quantidade_beneficiarios >= $precos[$i]["minimo_vidas"]) {
        $preco_beneficiario = floatval($precos[$i][$faixa]);
      }
    }
    return $preco_beneficiario;
  }

  function generate_error($message) {
    return "<p class='error'>".$message."</p>";
  }

  $codigo_plano = $_POST["code"];
  $quantidade_beneficiarios = $_POST["quantity"];
  if (isset($codigo_plano)) {
    $codigo_plano = intval($_POST["code"]);
  }
  if (isset($quantidade_beneficiarios)) {
    $quantidade_beneficiarios = intval($_POST["quantity"]);
  }
  $json = file_get_contents("./data/planos.json");
  $planos = json_decode($json, true);

  $plano_existe = plano_existe($codigo_plano);
  $quantidade_valida = valor_valido($quantidade_beneficiarios);
  if (!$plano_existe) {
    echo generate_error("Plano não existe, por favor entre com um valor válido.");
    return false;
  }
  
  if (!$quantidade_valida) {
    echo generate_error("Quantidade de beneficiários inválida, entre com um número entre 1 e 10.");
    return false;
  }

  $json = file_get_contents("./data/precos.json");
  $precos = json_decode($json, true);
  $beneficiarios = [];
  $valor_total = 0.0;
  
  for ($i = 1; $i <= $quantidade_beneficiarios; $i++) {
    $nome_beneficiario = $_POST["name-".$i];
    $idade_beneficiario = $_POST["age-".$i];
    if (!$nome_beneficiario or !$idade_beneficiario) {
      echo generate_error("Entre com todas as informações do beneficiário ".$i.".");
      return false;
    }
    if (!valor_valido($idade_beneficiario)) {
      echo generate_error("Entre com um valor válido para a idade do beneficiário ".$i.".");
      return false;
    }
    $faixa_beneficiario = faixa_idade($idade_beneficiario);
    $preco_beneficiario = obter_valor_beneficiario($faixa_beneficiario, $GLOBALS["codigo_plano"], $GLOBALS["quantidade_beneficiarios"]);
    $valor_total += $preco_beneficiario;
    array_push($beneficiarios, [$nome_beneficiario, $idade_beneficiario, $preco_beneficiario, substr($faixa_beneficiario, -1)]);
  }
?>

    <h1>Valores para o plano <br> <?php echo $plano_existe["nome"];?></h1>
    <section>
      <header>
        <p>Nome</p>
        <p>Idade</p>
        <p>Faixa</p>
        <p class='value'>Valor</p>
      </header>
      <?php 
        foreach ($beneficiarios as $beneficiario) {
          echo "
            <section class='beneficiary'>
              <p>".$beneficiario[0]."</p>
              <p>".$beneficiario[1]."</p>
              <p>".$beneficiario[3]."</p>
              <p class='value'>R$".number_format($beneficiario[2],2,',','.')."</p>
            </section>
          ";
        }
        echo "
          <section class='total'>
            <p>R$".number_format($valor_total,2, ',','.')."</p>
          </section>
        "
      ?>
    </section>
  </section>
  <script src="js/form.js"></script>
</body>
</html>