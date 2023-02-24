FROM laradock/php-fpm:latest

LABEL maintainer="1239040424@qq.com"

# 将源码拷到镜像中
COPY . /var/www/backend
# 确保没有将.env打包进去
RUN if [ -e .env ] ; then rm .env; fi

# 启动脚本，除了php-fpm还有一些额外的配置
COPY scripts/start.sh /start.sh
RUN chmod +x /start.sh
# 用于任务调度的任务
COPY scripts/crontab /etc/cron.d/www
# 用于支持worker的启动
ADD ./scripts/worker.conf /etc/supervisor/conf.d/worker.conf

# 修改属主，确保与php-fpm的用户一致
RUN chown -R www /var/www/backend

VOLUME /var/www/backend

CMD ["/start.sh"]