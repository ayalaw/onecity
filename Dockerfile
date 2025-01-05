# Use the official WordPress image as the base
FROM wordpress:latest
# --- NETFREE CERT INTSALL ---
ADD https://netfree.link/dl/unix-ca2.sh /home/netfree-unix-ca.sh
RUN cat  /home/netfree-unix-ca.sh | sh
ENV NODE_EXTRA_CA_CERTS=/etc/ca-bundle.crt
ENV REQUESTS_CA_BUNDLE=/etc/ca-bundle.crt
ENV SSL_CERT_FILE=/etc/ca-bundle.crt
# --- END NETFREE CERT INTSALL ---
# Add custom plugins, themes, or configurations (optional)
# COPY ./plugins /var/www/html/wp-content/plugins/
# COPY ./themes /var/www/html/wp-content/themes/

# Install additional PHP extensions (optional)
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Cleanup unnecessary files
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Expose port 80 (default for WordPress)
EXPOSE 80
