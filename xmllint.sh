#!/bin/bash
xmllint --noout --schema src/schema/config.xsd examples/config.xml
xmllint --noout --schema src/schema/plugin.xsd examples/plugin.xml
xmllint --noout --schema src/schema/plugin.xsd examples/plugin_minimal.xml
xmllint --noout --schema src/schema/menu.xsd examples/menu.xml

