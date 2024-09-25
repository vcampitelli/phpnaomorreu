FROM php:8.3-cli-alpine

# Instalando watchexec
RUN tar xvfJ - -C /usr/local \
    --transform "s/^watchexec-1.20.5-x86_64-unknown-linux-musl/watchexec/" \
    < <(wget https://github.com/watchexec/watchexec/releases/download/cli-v1.20.5/watchexec-1.20.5-x86_64-unknown-linux-musl.tar.xz -qO-) && \
    ln -s /usr/local/watchexec/watchexec /usr/local/bin

WORKDIR /app

# Instalando dependências
COPY ["composer.json", "composer.lock", "./"]
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN composer install

# Copiando resto da aplicação
COPY ["./", "./"]

# Executando o watchexec
CMD ["watchexec", "-i", "vendor/**", "-i", "public/index.html", "composer run build"]
