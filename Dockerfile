FROM nginx:latest

RUN rm /var/www/html/index.html

Copy app-ads.txt /var/www/html/app-ads.txt