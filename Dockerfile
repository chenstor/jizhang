FROM webdevops/php-nginx
MAINTAINER jizhang
ADD *.* /app/ 
ADD css/ /app/css/ 
ADD data/ /app/data/
ADD img/ /app/img/
ADD inc/ /app/inc/
ADD install/ /app/install/
ADD js/ /app/js/

RUN chown -R 1000:1000 /app
#EXPOSE 映射端口
EXPOSE 80 
EXPOSE 443
