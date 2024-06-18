FROM webdevops/php-apache-dev:8.2

# Set the timezone and install required packages in one layer to optimize image size
RUN ln -sf /usr/share/zoneinfo/Europe/Paris /etc/localtime && \
    # Update package list and upgrade all packages
    apt-get update && \
    apt-get upgrade -y && \
    # Install Node.js, MySQL client, and git-flow
    apt-get install -y nodejs default-mysql-client git-flow && \
    # Install Composer
    curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php && \
    # Install necessary PHP extensions
    docker-php-ext-install mysqli pdo pdo_mysql && \
    # Install Xdebug and enable it
    docker-php-ext-enable xdebug && \
    # Enable Apache mod_rewrite module
    a2enmod rewrite && \
    # Clean up to reduce image size
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Add composer vendor bin directory to environment PATH
ENV PATH="${PATH}:/app/vendor/bin"

# Set the working directory to /app
WORKDIR /app

# Copy and rename the appropriate php.ini file
RUN if [ -f /usr/local/etc/php/php.ini-development ]; then cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini; fi

# Set xdebug to develop mode
RUN echo "xdebug.mode=develop" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini