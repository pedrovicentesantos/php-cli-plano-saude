# Projeto

Criação de um programa que permite ao usuário cadastrar um novo Plano de Saúde de uma lista de planos pré-definidos em um arquivo JSON. No fim da execução, informa o preço do plano cadastrado assim como o valor para cada beneficiário cadastrado.

## Tecnologias

- PHP na versão 7.1
  * Programa rodando todo na linha de comando
- Docker

## Uso com o Docker

É possível utilizar o programa sem ter o PHP instalado na própria máquina, fazendo o uso do Docker.

Para isso deve-se clonar o repositório e manter o arquivo `Dockerfile` na mesma pasta do arquivo `main.php`. Caso contrário, o Docker não encontrará o arquivo principal e não vai conseguir executar o programa.

Feito isto, para usar a aplicação com o Docker deve-se executar primeiramente:

```shell
docker build -t my-php-app .  # Cria a imagem
```
No comando anterior `my-php-app` é o nome da imagem Docker. Pode ser escolhido qualquer nome.

Para rodar o script basta executar:

```shell
docker run -it --rm --name my-running-app my-php-app  # Cria e executa o container
```
No comando anterior `my-running-app` é o nome do container, que também pode ser qualquer nome.
<br>
Importante usar o nome correto da imagem criada anteriormente. No exemplo, a imagem se chama `my-php-app`.

Ao fim da execução do programa, o Docker para e remove o container automaticamente.

## Funcionamento

Todo o programa funciona pela linha de comando. Basta seguir os comandos indicados para utilizá-lo.

Caso deseje sair do programa a qualquer momento, basta digitar a tecla `e`.
