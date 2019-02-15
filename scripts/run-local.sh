#!/bin/bash
set -e
docker-compose up -d
docker exec --tty deprecation_bot bash -c /etc/ansible/playbook/scripts/run-playbook.sh
