#!/bin/bash
ansible-vault edit vars/secrets.yml --vault-password-file=~/.ansible/jenkins-vault-password.txt
