#!/bin/bash

# Checks
VER=$1
if [ -z $VER ]; then
    echo "Provide major Wordpress version (e.g. 6)"
    exit 255
fi
if [ ! -d "vendor/cakephp/bake" ]; then
    echo "First, \`cd\` to a CakePHP project folder. Currently in: $(pwd)"
    exit 255
fi

INFLECTOR="$(dirname ${0})/inflector.php"
if [ ! -f "${INFLECTOR}" ]; then
    echo "The $(basename ${INFLECTOR}) script needs to be available in the same folder with this script. Checked for: ${INFLECTOR}"
    exit 255
fi

# Configuration
PREFIX="wp_" # Clean Wordpress table prefix, if any
CONNECTION="Wordpress${VER}-clean" # Clean Wordpress table prefix, if any
PLUGIN="CakePHPWordpress" # Name of the plugin to hold Wordpress connector code
# Path to plugin files, i.e src/Model/Table/ExampleTable.php
#PLUGIN_PATH="plugins/${PLUGIN}" # can be inside an existing CakePHP site
#PLUGIN_PATH="/var/www/cakephp-${PLUGIN}" # can be inside separate folder
PLUGIN_PATH="/var/www/cakephp-wordpress"

# Start baking
T="${PLUGIN_PATH}/src/Model/Table" # Shorthand to table path
E="${PLUGIN_PATH}/src/Model/Entity" # Shorthand to entity path
# Clean up
rm -rf $(pwd)/plugins/${PLUGIN}
rm -rf ${T}/Wordpress${VER}
mkdir -p ${T}/Wordpress${VER}
#rm -rf ${T}/WordpressAbstract # DON'T. classes may be removed between versions
mkdir -p ${T}/WordpressAbstract
rm -rf ${E}/Wordpress${VER}
mkdir -p ${E}/Wordpress${VER}
#rm -rf ${E}/WordpressAbstract # DON'T. classes may be removed between versions
mkdir -p ${E}/WordpressAbstract

# Get the list of all bake-able models. Results will be in CamelCase
LIST=$(bin/cake bake model --connection ${CONNECTION} | tail -n +2 -)
# Loop through each table
for NAME in ${LIST}; do
    if [ "${NAME}" = "-" ]; then
        continue;
    fi
    # Replace known database table prefix, if any
    PREFIX_CAMELCASE=$(php "${INFLECTOR}" camelize ${PREFIX})
    ALIAS=$(echo "${NAME}" | sed "s/${PREFIX_CAMELCASE}//")
    # Get the Entity name
    ENTITY=$(php "${INFLECTOR}" classify ${ALIAS})
    # Get original database table name
    TABLE=$(php "${INFLECTOR}" underscore ${NAME})
    # Debug
    echo "NAME:${NAME} ALIAS:${ALIAS} TABLE:${TABLE} ENTITY:${ENTITY}"
    # Do the actual baking
    bin/cake bake model ${ALIAS} --table ${TABLE} --force --no-fixture --no-test --plugin ${PLUGIN} --connection ${CONNECTION}

    #
    # TABLE
    #
    # Move freshly baked Table to destination folder
    mv "$(pwd)/plugins/${PLUGIN}/src/Model/Table/${ALIAS}Table.php" "${T}/Wordpress${VER}/${ALIAS}Table.php"
    F="${T}/Wordpress${VER}/${ALIAS}Table.php" # shorthand to table class
    # Correct namespace from generic to Wordpress version specific
    sed -i "s/${PLUGIN}\\\Model\\\Table/${PLUGIN}\\\Model\\\Table\\\Wordpress${VER}/g" "${F}"
    # Table: Abstract
    mkdir -p "${T}/WordpressAbstract"
    if [ ! -f "${T}/WordpressAbstract/Abstract${ALIAS}Table.php" ]; then
        cat > "${T}/WordpressAbstract/Abstract${ALIAS}Table.php" << EOF
<?php

namespace ${PLUGIN}\Model\Table\WordpressAbstract;

use Cake\ORM\Table;

abstract class Abstract${ALIAS}Table extends Table
{



}
EOF
    fi
    # Make Table extend shared abstract parent instead of Cake\ORM\Table
    sed -i "s/class ${ALIAS}Table extends Table/class ${ALIAS}Table extends \\\\${PLUGIN}\\\Model\\\Table\\\WordpressAbstract\\\\Abstract${ALIAS}Table/g" "${F}"
    # Delete `use Cake\ORM\Table;`
    sed -i '/^use Cake\\\ORM\\\Table;/d' "${F}"

    #
    # ENTITY
    #
    # Move freshly baked Entity class to destination folder
    mv "$(pwd)/plugins/${PLUGIN}/src/Model/Entity/${ENTITY}.php" "${E}/Wordpress${VER}/${ENTITY}.php"
    F="${E}/Wordpress${VER}/${ENTITY}.php" # shorthand to entity class
    sed -i "s/${PLUGIN}\\\Model\\\Entity/${PLUGIN}\\\Model\\\Entity\\\Wordpress${VER}/g" "${F}"
    # Entity: Abstract
    mkdir -p "${E}/WordpressAbstract"
    if [ ! -f "${E}/WordpressAbstract/Abstract${ENTITY}.php" ]; then
        cat > "${E}/WordpressAbstract/Abstract${ENTITY}.php" << EOF
<?php

namespace ${PLUGIN}\Model\Entity\WordpressAbstract;

use Cake\ORM\Entity;

abstract class Abstract${ENTITY} extends Entity
{



}
EOF
    fi
    # Make Entity extend shared abstract parent instead of Cake\ORM\Entity
    sed -i "s/class ${ENTITY} extends Entity/class ${ENTITY} extends \\\\${PLUGIN}\\\Model\\\Entity\\\WordpressAbstract\\\\Abstract${ENTITY}/g" "${F}"
    # Delete `use Cake\ORM\Entity;` and the empty line after it
    sed -i '/^use Cake\\\ORM\\\Entity;/{N;d;}' "${F}"
    # Fix inflector bug that singularizes Meta as Metum
    case "${ENTITY}" in
        *metum)
            ENTITY2=$(echo "${ENTITY}" | sed "s/metum/meta/")
            #mv "${E}/Wordpress${VER}/${ENTITY}.php" "${E}/Wordpress${VER}/${ENTITY2}.php"
            ;;
    esac
done

# Clean up
rm -rf $(pwd)/plugins/${PLUGIN}
