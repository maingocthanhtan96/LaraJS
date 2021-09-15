FROM nginx:alpine

LABEL maintainer="maingocthanhtan96@gmail.com"

COPY nginx.conf /etc/nginx/

RUN apk update \
    && apk upgrade \
    && apk --update add logrotate \
    && apk add --no-cache openssl \
    && apk add --no-cache bash

RUN apk add --no-cache curl

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000

# Create 'messages' file used from 'logrotate'
RUN touch /var/log/messages

# Copy 'logrotate' config file
COPY logrotate/nginx /etc/logrotate.d/

# Set upstream conf and remove the default conf
RUN rm /etc/nginx/conf.d/default.conf

ADD ./startup.sh /opt/startup.sh
RUN sed -i 's/\r//g' /opt/startup.sh
CMD ["/bin/bash", "/opt/startup.sh"]

EXPOSE 80 81 443
