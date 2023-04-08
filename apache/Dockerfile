FROM httpd:alpine3.17

ADD kursach.conf /usr/local/apache2/conf/extra/httpd-vhosts.conf
RUN echo "Include conf/extra/httpd-vhosts.conf" >> /usr/local/apache2/conf/httpd.conf
RUN mkdir -p /var/www/

