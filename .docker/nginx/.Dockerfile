FROM nginx:1.24-alpine-slim

ARG USER
ARG USER_ID
ARG GROUP_ID

# Replace the nginx user with the current user, so file ownership isn't wonky
RUN addgroup -g $GROUP_ID $USER
RUN adduser -s /bin/false -HD -G $USER -u $USER_ID $USER
RUN sed -i -E "s/user[[:space:]]+nginx;/user $USER;/" /etc/nginx/nginx.conf

### Getting nginx HTTPS proxy
RUN apk add --update --no-cache openssl
RUN mkdir -p /etc/ssl
# Generating signing SSL private key
RUN openssl genrsa -des3 -passout pass:x -out /etc/ssl/key.pem 2048 \
 && openssl rsa -passin pass:x -in /etc/ssl/key.pem -out /etc/ssl/key.pem
# Generating certificate signing request
RUN openssl req -new \
    -key /etc/ssl/key.pem -out /etc/ssl/req.csr \
    -subj "/C=EU/O=KDex"
# Generating self-signed certificate
RUN openssl x509 -days 365 \
    -req -in /etc/ssl/req.csr \
    -signkey /etc/ssl/key.pem \
    -out /etc/ssl/cert.csr
