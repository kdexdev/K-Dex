FROM mysql:8.0

ARG USER_ID
ARG GROUP_ID

# Modify the MySQL user and group IDs, since we can't change the user themselves
# EVEN FUCKING IF WE'VE DEFINED THEM IN 3 BLOODY PLACES!!!
RUN usermod -u $USER_ID mysql
RUN groupmod -g $GROUP_ID mysql

# Change ownership of directories
RUN mkdir /var/log/mysql
RUN chown -R $USER_ID:$GROUP_ID /var/lib/mysql /var/log/mysql
