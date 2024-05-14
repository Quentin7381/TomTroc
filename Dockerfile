FROM webdevops/php-apache-dev:8.2

# Timezone setup
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

# Add composer vendor bin dir to env PATH.
ENV PATH="${PATH}:/app/vendor/bin"

# Install current LTS of NodeJS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

# apt packages
RUN apt-get update 
RUN apt-get upgrade -y
RUN apt-get install -y nodejs default-mysql-client git-flow

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

# Then go back as root
USER root

# Set the final working directory
WORKDIR /app