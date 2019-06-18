FROM webdevops/php-nginx
MAINTAINER jizhang
ADD * /app/

#EXPOSE 映射端口
EXPOSE 80 
EXPOSE 443
