<?php
  function plano_existe($codigo_plano, $planos) {
    for ($i = 0; $i < count($planos); $i++) {
      if ($codigo_plano === $planos[$i]["codigo"]){
        return true;
      }
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

  function obter_valor_beneficiario($precos, $faixa, $codigo_plano, $quantidade_beneficiarios) {
    for ($i = 0; $i < count($precos); $i++) {
      if ($codigo_plano === $precos[$i]["codigo"] and $quantidade_beneficiarios >= $precos[$i]["minimo_vidas"]) {
        $preco_beneficiario = floatval($precos[$i][$faixa]);
      }
    }
    return $preco_beneficiario;
  }

  function limpar_tela() {
    $sistema_operacional = PHP_OS;
    if ($sistema_operacional === "Windows") {
      system("cls");
    } else {
      system("clear");
    }
  }

  function idade_valida($idade){
    if (is_numeric($idade) and intval($idade) >= 0) {
      return true;
    }
    return false;
  }

  function main() {
    $json = file_get_contents("./planos.json");
    $planos = json_decode($json, true);

    $json = file_get_contents("./precos.json");
    $precos = json_decode($json, true);

    $registro_plano = intval(readline("Entre com o registro do plano desejado: "));
    $plano_existe = plano_existe($registro_plano,$planos);
    if (!$plano_existe) {
      print_r("Plano inválido\n");
      return -1;
    } else {
      $quantidade_beneficiarios = intval(readline("Entre com a quantidade de beneficiários: "));
      while ($quantidade_beneficiarios <= 0){
        $quantidade_beneficiarios = intval(readline("Entre com um valor maior que 0 para quantidade de beneficiários: "));
      }

      limpar_tela();
      $beneficiarios = [];
      $valor_total = 0.0;
      $maior_nome = 0;
      for ($i = 1; $i <= $quantidade_beneficiarios; $i++) {
        $nome_beneficiario = readline("Entre com o nome do beneficiário ".$i.": ");
        if (strlen($nome_beneficiario) > $maior_nome) {
          $maior_nome = strlen($nome_beneficiario);
        }

        $idade_beneficiario = readline("Entre com a idade do beneficiário ".$i.": ");
        while (!idade_valida($idade_beneficiario)) {
          $idade_beneficiario = readline("Entre com um valor maior que 0 para a idade do beneficiário ".$i.": ");
        }

        limpar_tela();
        $faixa_beneficiario = faixa_idade($idade_beneficiario);
        $preco_beneficiario = obter_valor_beneficiario($precos,$faixa_beneficiario,$registro_plano,$quantidade_beneficiarios);
        $valor_total += $preco_beneficiario;
        array_push($beneficiarios, [$nome_beneficiario,$idade_beneficiario, $preco_beneficiario]);
      }

      print_r("Valor total do plano ".$planos[$registro_plano-1]["nome"]." para ".$quantidade_beneficiarios." beneficiário(s):\n");
      print_r("R$".number_format($valor_total,2, ',','.')."\n");
      
      print_r("----------------------------------------------------------------------\n");
      print_r("Valor para cada beneficiário:\n");
      $mask = "|%-{$maior_nome}s |%-5s |%-5s \n";
      printf($mask, 'Nome', 'Idade', 'Preço para faixa de idade');
      for ($i = 0; $i < count($beneficiarios); $i++) { 
        printf($mask, $beneficiarios[$i][0], $beneficiarios[$i][1],"R$".number_format($beneficiarios[$i][2],2,',','.'));
      }
    }
  }

  main();
?>