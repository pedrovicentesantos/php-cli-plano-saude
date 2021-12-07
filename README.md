# Projeto

Criação de um programa que permite ao usuário cadastrar um novo Plano de Saúde de uma lista de planos pré-definidos em um arquivo JSON. No fim da execução, informa o preço do plano cadastrado assim como o valor para cada beneficiário cadastrado.

## Tecnologias

- PHP na versão 7.1 para a versão CLI
- PHP na versão 8 para a versão com HTML
- Docker

## Rodando a aplicação

É possível utilizar o programa sem ter o PHP instalado na própria máquina, fazendo o uso do Docker.

Para isso deve-se clonar o repositório e executar os comandos:

```shell
  docker-compose run --rm php-form-cli  # Versão CLI
  docker-compose up   # Versão HTML
```

## Funcionamento

No caso da versão CLI, todo o programa funciona pela linha de comando. Basta seguir os comandos indicados para utilizá-lo.

Caso deseje sair do programa a qualquer momento, basta digitar a tecla `e`.

Para a versão com HTML, basta acessar [http://localhost:8181](http://localhost:8181).
