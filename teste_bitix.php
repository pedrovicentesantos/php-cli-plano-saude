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

  function valor_valido($idade){
    if (is_numeric($idade) and intval($idade) >= 0) {
      return true;
    }
    return false;
  }

  function sair_programa($tecla) {
    if ($tecla === "e") {
      return -1;
    }
  }

  function mensagem_inicial() {
    print_r("Vamos cadastrar seu novo Plano de Saúde\n");
    print_r("Caso queira sair a qualquer momento digite a letra \"e\" e pressione ENTER\n");
    $tecla = readline("Aperte ENTER para continuar");
    return sair_programa($tecla);
  }

  function mensagem_saida() {
    print_r("Obrigado por usar o programa\n");
    print_r("Volte a usar sempre que precisar\n");
  }

  function main() {
    $json = file_get_contents("./planos.json");
    $planos = json_decode($json, true);

    $json = file_get_contents("./precos.json");
    $precos = json_decode($json, true);

    limpar_tela();
    $resultado = mensagem_inicial();
    if ($resultado === -1) {
      limpar_tela();
      mensagem_saida();
      return -1;
    }
    limpar_tela();

    $registro_plano = readline("Entre com o registro do plano desejado: ");
    if (sair_programa($registro_plano) === -1) {
      limpar_tela();
      mensagem_saida();
      return -1;
    }
    $registro_plano = intval($registro_plano);
    $plano_existe = plano_existe($registro_plano,$planos);
    while (!$plano_existe) {
      limpar_tela();
      print_r("Plano inválido\n");
      print_r("Entre com o código de algum dos seguintes planos:\n");
      $comprimento_plano = strlen($planos[0]["nome"]);
      $mask = "|%-{$comprimento_plano}s |%-6s\n";
      printf($mask, 'Plano', 'Código');
      for ($i = 0; $i < count($planos); $i++) { 
        printf($mask, $planos[$i]["nome"], $planos[$i]["codigo"]);
      }
      print_r("----------------------------------------------------------------------\n");
      $registro_plano = readline("Entre com o registro do plano desejado: ");
      if (sair_programa($registro_plano) === -1) {
        limpar_tela();
        mensagem_saida();
        return -1;
      }
      $registro_plano = intval($registro_plano);
      $plano_existe = plano_existe($registro_plano,$planos);
    }

    limpar_tela();

    $quantidade_beneficiarios = readline("Entre com a quantidade de beneficiários: ");
    if (sair_programa($quantidade_beneficiarios) === -1){
      limpar_tela();
      mensagem_saida();
      return -1;
    }
    while (!valor_valido($quantidade_beneficiarios)){
      $quantidade_beneficiarios = readline("Entre com um valor maior que 0 para a quantidade de beneficiários: ");
      if (sair_programa($quantidade_beneficiarios) === -1){
        limpar_tela();
        mensagem_saida();
        return -1;
      }
    }

    limpar_tela();

    $beneficiarios = [];
    $valor_total = 0.0;
    $maior_nome = 5;
    for ($i = 1; $i <= $quantidade_beneficiarios; $i++) {
      $nome_beneficiario = readline("Entre com o nome do beneficiário ".$i.": ");
      if (sair_programa($nome_beneficiario) === -1){
        limpar_tela();
        mensagem_saida();
        return -1;
      }
      if (strlen($nome_beneficiario) > $maior_nome) {
        $maior_nome = strlen($nome_beneficiario);
      }

      $idade_beneficiario = readline("Entre com a idade do beneficiário ".$i.": ");
      if (sair_programa($idade_beneficiario) === -1){
        limpar_tela();
        mensagem_saida();
        return -1;
      }
      while (!valor_valido($idade_beneficiario)) {
        $idade_beneficiario = readline("Entre com um valor maior que 0 para a idade do beneficiário ".$i.": ");
        if (sair_programa($idade_beneficiario) === -1){
          limpar_tela();
          mensagem_saida();
          return -1;
        }
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

  main();
?>