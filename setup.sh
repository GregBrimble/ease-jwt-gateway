#!/bin/sh
chmod 755 .;
chmod 644 .htaccess;

ssh-keygen -t rsa -b 4096 -m PEM -f jwtRS256.key -q -N "";
openssl rsa -in jwtRS256.key -pubout -outform PEM -out jwtRS256.key.pub;