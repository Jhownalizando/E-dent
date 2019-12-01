#!/bin/bash

set -ex

echo "${APP_IP} ${APP_DOMAIN}" >> /etc/hosts
apache2 -DFOREGROUND
