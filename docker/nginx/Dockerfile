ARG IMAGE_TAG=latest

FROM laravel_app_name/php-fpm:${IMAGE_TAG} AS php-fpm

FROM nginx:1.23.3-alpine

WORKDIR /var/www/html

COPY ./default.conf /etc/nginx/conf.d/default.conf
COPY --from=php-fpm /var/www/html .

EXPOSE 80
