#!/bin/bash

kdesudo -n -d -i "/usr/share/icons/oxygen/48x48/actions/user-properties.png" --comment "This script fixes ACL permissions after installation Joomla extensions. Please enter Super User password" -c "setfacl -R -M ../properties/acl/public_html.acl ../bundle" || sudo setfacl -R -M ../properties/acl/public_html.acl ../bundle