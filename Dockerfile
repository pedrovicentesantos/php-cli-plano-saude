FROM php:7.1-alpine
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD [ "php", "./teste_bitix.php" ]