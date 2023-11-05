FROM thecodingmachine/php:8.2-v4-fpm

COPY --chown=docker:docker ./ /var/www/html/

WORKDIR /var/www/html/

CMD ["php-fpm","-R"]
#CMD ["bash"]

EXPOSE 9000
