#!/bin/bash
xmllint --noout --schema schema/config.xsd tests/_files/config.xml
xmllint --noout --schema schema/plugin.xsd tests/_files/plugin.xml
xmllint --noout --schema schema/menu.xsd tests/_files/menu.xml

