#!/bin/bash
ansible-galaxy install -r requirements.yml
ansible-playbook -i inventory/prod main.yml
