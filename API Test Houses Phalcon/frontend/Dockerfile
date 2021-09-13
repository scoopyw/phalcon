FROM  nginx:alpine

COPY ./frontend/dev-nginx.conf /etc/nginx/nginx.conf
COPY ./frontend/fastcgi_exec.conf /etc/nginx/fastcgi_exec.conf

RUN export ENVIRONMENT=dev

EXPOSE 80