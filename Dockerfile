FROM php:7.2

MAINTAINER carson <yuzhanwei@aliyun.com>

# Version
ENV PHPREDIS_VERSION 4.0.0
ENV SWOOLE_VERSION 4.3.1
ENV WORK_SPACE /data/wwwroot
ENV PROJECT_SPACE ${WORK_SPACE}/msfoole-kernel

# Timezone
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone

# Libs
RUN apt-get update \
    && apt-get install -y \
        curl \
        wget \
        git \
        zip \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
    && apt-get clean \
    && apt-get autoremove
  
# composer
RUN php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# PDO extension
RUN docker-php-ext-install pdo_mysql

# Bcmath extension
RUN docker-php-ext-install bcmath

# Redis extension
RUN wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
    && pecl install /tmp/redis.tar.tgz \
    && rm -rf /tmp/redis.tar.tgz \
    && docker-php-ext-enable redis

# Swoole extension
RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
        cd swoole \
        && phpize \
        && ./configure --enable-mysqlnd --enable-openssl --enable-http2 \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole

ADD . ${PROJECT_SPACE}

# 设置登录容器的默认目录
WORKDIR ${PROJECT_SPACE}

RUN chmod +x msfoole && \
	mkdir -p runtime/temp && \
	mkdir -p runtime/logs && \
	touch runtime/logs/trace.log && \
	touch runtime/logs/msfoole.log && \
	chmod -R 777 runtime

RUN composer install --no-dev \
    && composer clearcache

# 创建挂载点
# 启动的时候可以通过 -v 参数指定宿主机映射到容器的目录
VOLUME ${PROJECT_SPACE}

EXPOSE 9111
EXPOSE 9222
EXPOSE 9333
EXPOSE 9555

# 执行命令
ENTRYPOINT ./msfoole start