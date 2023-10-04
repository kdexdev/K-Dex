FROM nginx:1.24-alpine-slim

ARG USER
ARG USER_ID
ARG GROUP_ID

# Replace the nginx user with the current user, so file ownership isn't wonky
RUN addgroup -g $GROUP_ID $USER
RUN adduser -s /bin/false -HD -G $USER -u $USER_ID $USER
RUN sed -i -E "s/user[[:space:]]+nginx;/user $USER;/" /etc/nginx/nginx.conf
