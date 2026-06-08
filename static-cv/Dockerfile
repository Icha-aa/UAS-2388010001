FROM nginx:alpine

# Salin config nginx langsung dari folder
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Salin semua file CV statis ke direktori nginx
COPY ./ /usr/share/nginx/html/

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
