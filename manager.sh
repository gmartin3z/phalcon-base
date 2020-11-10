#!/usr/bin/env bash

#
# setup.sh - versión 1.0
#
# Autor: gmartin3z
#
# Script encargado de mantener y administrar el entorno
# adecuadamente para hacer funcionar esta aplicación.
#

VERSION="1.0"
AUTOR="gmartin3z"

getConfig(){
    config_file="config_console.ini"
	config_route="$(pwd)/app/config/${config_file}"
    if [ -f "${config_route}" ]; then
        while IFS="=" read -r key value; do
            case "$key" in
                "mysqldump_route") mysqldump_route=$(echo "${value}" | xargs) ;;
                "mysql_route") mysql_route=$(echo "${value}" | xargs) ;;
                "mysql_host") mysql_host=$(echo "${value}" | xargs) ;;
                "mysql_database") mysql_database=$(echo "${value}" | xargs) ;;
                "mysql_user") mysql_user=$(echo "${value}" | xargs) ;;
                "mysql_password") mysql_password=$(echo "${value}" | xargs) ;;
                "gzip_route") gzip_route=$(echo "${value}" | xargs) ;;
                "php_route") php_route=$(echo "${value}" | xargs) ;;
            esac
        done < "${config_route}"
    else
        clear
        echo ""
        echo "${config_file} no existe, Leer el archivo README.MD para conocer"
        echo "y seguir las instrucciones de configuración."
        exit
    fi
}

getConfig

banner(){
    clear
    echo ""
    echo "  .::::;i"
    echo "  ;MMMMMa"
    echo "  ;MMMMMa"
    echo "  ;MMMMM2"
    echo "  iMMMMMZMMMMor"
    echo "  ;MMMMMZWMMMMMMa       manager.sh - Versión ${VERSION}"
    echo "  iMMMMMZMMMMMMMMM      autor: ${AUTOR}"
    echo "  ;MMMMMZ;;rMMMMMMX"
    echo "  ;MMMMM2   :MMMMMW     HERRAMIENTA DE CONTROL"
    echo "  ,MMMMMM.  WMMMMMS     Y MANTENIMIENTO DE LA APLICACIÓN"
    echo "   BMMMMMMMMMMMMMM"
    echo "    ZMMMMMMMMMMMM"
    echo "      i8WMMM@B;"
    echo ""
}

showIntroMenu(){
    banner
    echo ""
    echo "Esta herramienta puede realizar mantenimiento a la aplicación"
    echo "limpiando archivos temporales que fueron generados previamente"
    echo "así como otras tareas."
    echo ""
    echo "Presionar [c] para continuar o presionar [x] para salir"
    echo ""

    intro_menu_question
}

intro_menu_question(){
    read -p "Respuesta: " ans

    case "$ans" in
        [cC]) showMainMenu ;;
        [xX]) stop ;;
        *) showIntroMenu ;;
    esac
}

showMainMenu(){
    banner
    echo ""
    echo "OPCIONES DE MANTENIMIENTO"
    echo ""
    echo " [1] Limpiar caché                        [3] Respaldar base de datos"
    echo " [2] Corregir permisos necesarios         [4] Restaurar base de datos"
    echo " [5] Generar token de seguridad"
    echo ""
    echo " [x] Salir"
    echo ""

    main_menu_question
}

main_menu_question(){
    read -p "Respuesta: " ans

    case "$ans" in
        1) clearCache ;;
        2) fixPermissions ;;
        3) backupDatabase ;;
        4) restoreDatabase ;;
        5) generateSecurityToken ;;
        [xX]) stop;;
        *) showMainMenu ;;
    esac
}

stop(){
    clear
    exit
}

clearCache(){
    clear
    current_app_folder=$(pwd)
    cache_folder="${current_app_folder}/cache"
    acl_cache="${cache_folder}/acl"
    views_cache="${cache_folder}/volt"

    echo ""
    echo "RUTAS"
    echo "Carpeta raíz: ${current_app_folder}"
    echo "Cache: ${cache_folder}"
    echo "ACL: ${acl_cache}"
    echo "Vistas: ${views_cache}"
    echo ""
    echo "###################################"
    echo ""
    echo "LIMPIANDO CACHÉ"
    for view_file in "${views_cache}"/*.tpl
    do
      echo " - Borrando ${view_file}..."
      rm -r "${view_file}"
      if [ $? -eq 0 ]; then
        echo "   Hecho"
      elif [[ $? -eq 1 ]]; then
        echo "   Nada para borrar"
      elif [[ $? -eq 127 ]]; then
        echo "   rm no disponible"
      else
        echo "   Error ${?} (rm)"
      fi
    done
    echo ""
    echo "###################################"
    echo ""
    echo "LIMPIANDO ACL"
    for acl_file in "${acl_cache}"/*.acl
    do
      echo " - Borrando ${acl_file}..."
      rm -r "${acl_file}"
      if [ $? -eq 0 ]; then
        echo "   Hecho"
      elif [[ $? -eq 1 ]]; then
        echo "   Nada para borrar"
      elif [[ $? -eq 127 ]]; then
        echo "   rm no disponible"
      else
        echo "   Error ${?} (rm)"
      fi
    done
    echo ""
    echo "###################################"
    echo ""
    read -p "Presionar cualquier tecla para continuar... "

    showMainMenu
}

fixPermissions(){
    clear
    current_app_folder=$(pwd)
    backups_folder="${current_app_folder}/backups"
    cache_folder="${current_app_folder}/cache"
    logs_folder="${current_app_folder}/logs"
    status=$?
    echo ""
    echo "RUTAS"
    echo "Carpeta raíz: ${backups_folder}"
    echo "Respaldos: ${acl_cache}"
    echo "Cache: ${cache_folder}"
    echo "Logs: ${logs_folder}"
    echo ""
    echo "###################################"
    echo ""
    echo "ARREGLANDO PERMISOS EN:"
    echo " - CARPETA DE RESPALDOS"
    chmod -R 777 "${backups_folder}"
    [ $status -eq 0 ] && echo "   Hecho" || echo "   Error"
    echo ""
    echo "###################################"
    echo ""
    echo "ARREGLANDO PERMISOS EN:"
    echo " - CARPETA DE CACHE"
    chmod -R 777 "${cache_folder}"
    [ $status -eq 0 ] && echo "   Hecho" || echo "   Error"
    echo ""
    echo "###################################"
    echo ""
    echo "ARREGLANDO PERMISOS EN:"
    echo " - CARPETA DE LOGS"
    chmod -R 777 "${logs_folder}"
    [ $status -eq 0 ] && echo "   Hecho" || echo "   Error"
    echo ""
    echo "###################################"
    echo ""
    read -p "Presionar cualquier tecla para continuar... "

    showMainMenu
}

backupDatabase(){
    clear
    current_app_folder=$(pwd)
    backups_folder="${current_app_folder}/backups"
    cache_folder="${current_app_folder}/cache"
    backup_name="$(date +%Y-%m-%d__%I.%M.%S_%p)"
    mysqldump_route="${mysqldump_route}"
    gzip_route="${gzip_route}"
    echo ""
    echo "Buscando ${mysqldump_route}..."
    $mysqldump_route --version
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${mysqldump_route} --version)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${mysqldump_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${mysqldump_route} --version)"
        exit $?
    fi
    echo ""
    echo "Preparando configuración..."
    tmp_config_file="${cache_folder}/mysqldump_tmp.cnf"
    echo "[client]" > $tmp_config_file
    echo "host=\"${mysql_host}\"" >> $tmp_config_file
    echo "user=\"${mysql_user}\"" >> $tmp_config_file
    echo "password=\"${mysql_password}\"" >> $tmp_config_file
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (echo >>...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "echo >>... no disponible"
        exit $?
    else
        echo "Error ${?} (echo >>...)"
        exit $?
    fi
    echo ""
    echo "Extrayendo..."
    $mysqldump_route --defaults-extra-file=$tmp_config_file \
        --single-transaction --routines --triggers \
        --databases $mysql_database > $backups_folder/$backup_name.sql;
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${mysqldump_route} -def...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${mysqldump_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${mysqldump_route} -def...)"
        exit $?
    fi
    echo ""
    echo "Comprimiendo..."
    $gzip_route --best --force $backups_folder/$backup_name.sql
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${gzip_route} --best...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${gzip_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${gzip_route} --best)"
        exit $?
    fi
    echo ""
    echo "Borrando temporales..."
    rm -rf $tmp_config_file
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (rm -rf...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "rm no disponible"
        exit $?
    else
        echo "Error ${?} (rm -rf...)"
        exit $?
    fi
    echo ""
    echo "LISTO"
    echo ""
    echo "###################################"
    echo ""
    read -p "Presionar cualquier tecla para continuar... "

    showMainMenu
}

restoreDatabase(){
    clear
    current_app_folder=$(pwd)
    backups_folder="${current_app_folder}/backups"
    mysql_route="${mysql_route}"
    gzip_route="${gzip_route}"
    echo ""
    echo "Buscando ${mysql_route}..."
    $mysql_route --version
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${mysql_route} --version)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${mysql_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${mysql_route} --version)"
        exit $?
    fi
    count_backups="$(find $backups_folder/ -type f -name '*.gz' | wc -l)"
    echo ""
    echo "Buscando respaldos creados..."
    echo "${count_backups} encontrados"
    if [[ $count_backups -ne 0 ]]; then
        current_backups="$(find $backups_folder/ -type f -name '*.gz' | sort -nr | head -n 10)"
        options=()
        counter=1
        echo ""
        echo "Archivos disponibles:"
        echo ""
        for backup in $current_backups; do
            echo " ${counter}) ${backup}"
            file[counter]=$backup
            options+=("${counter}")
            if [[ $counter -gt 10 ]]; then
            	break
            fi
            counter=$(( counter + 1 ))
        done
        echo ""
        echo "Seleccionar un archivo del 1 al $(( counter - 1 ))"
        echo "o elegir [x] para salir"
        echo ""
        read -p "Opción: " input
        found_option=$(echo ${options[@]} | grep -o "${input}" | wc -w)
        if [[ $found_option -eq 1 ]]; then
            selected_file="${file[$input]}"
            finishRestoreDatabase $selected_file
        elif [[ $input == 'x' || $input  == 'X' ]]; then
            exit
        else
	    sleep 3
            restoreDatabase
        fi
        exit
    else
        echo "Nada por importar"
        exit
    fi

    showMainMenu
}

finishRestoreDatabase(){
    clear
    current_app_folder=$(pwd)
    cache_folder="${current_app_folder}/cache"
    mysqldump_route="${mysqldump_route}"
    mysql_route="${mysql_route}"
    gzip_route="${gzip_route}"
    selected_file="${selected_file}"
    gz_file_bkp="${selected_file}"
    sql_file_bkp="${selected_file%.*}"
    echo ""
    echo "Respaldo a restaurar:"
    echo "${selected_file}"
    echo ""
    read -p "¿Es correcto? [S/N] " confirmation
    if [[ $confirmation == "s" || $confirmation == "S" ]]; then
        echo "Restaurando ${selected_file}"
    else
    	restoreDatabase
    fi
    echo ""
    echo "Preparando configuración..."
    tmp_config_file="${cache_folder}/mysqldump_tmp.cnf"
    echo "[client]" > $tmp_config_file
    echo "host=\"${mysql_host}\"" >> $tmp_config_file
    echo "user=\"${mysql_user}\"" >> $tmp_config_file
    echo "password=\"${mysql_password}\"" >> $tmp_config_file
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (echo >>...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "echo >>... no disponible"
        exit $?
    else
        echo "Error ${?} (echo >>...)"
        exit $?
    fi
    echo ""
    echo "Descomprimiendo..."
    $gzip_route --decompress $gz_file_bkp
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${gzip_route} --dec...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${gzip_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${gzip_route} --dec...)"
        exit $?
    fi
    echo ""
    echo "Importando..."
    $mysql_route --defaults-extra-file=$tmp_config_file \
    	--database $mysql_database < $sql_file_bkp;

    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${mysql_route} --def...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${mysql_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${mysqldump_route} --def...)"
        exit $?
    fi
    echo ""
    echo "Recomprimiendo..."
    $gzip_route --best --force $sql_file_bkp
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${gzip_route} --best...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${gzip_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${gzip_route} --best...)"
        exit $?
    fi
    echo ""
    echo "Borrando temporales..."
    rm -rf $tmp_config_file
    if [ $? -eq 0 ]; then
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (rm -rf...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "rm no disponible"
        exit $?
    else
        echo "Error ${?} (rm -rf...)"
        exit $?
    fi
    echo ""
    echo "LISTO"
    echo ""
    echo "###################################"
    echo ""
    read -p "Presionar cualquier tecla para continuar... "

    showMainMenu
}

generateSecurityToken(){
    clear
    php_route="${php_route}"

    echo ""
    echo "GENERAR TOKEN"
    echo ""
    echo "Recomendado es que al modificar el código de este proyecto"
    echo "o de instalarlo desde cero se genere un token único para"
    echo "proteger las contraseñas."
    echo ""
    echo "###################################"
    echo ""
    echo "Buscando ${php_route}..."
    $php_route --version | head -c 16
    if [ $? -eq 0 ]; then
        echo ""
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${php_route} --version)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${php_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${php_route} --version)"
        exit $?
    fi
    echo ""
    echo "###################################"
    echo ""
    echo "Token:"
	$php_route -r '$token =  bin2hex(openssl_random_pseudo_bytes(24)); ;print $token;'
    if [ $? -eq 0 ]; then
        echo ""
        echo "OK..."
    elif [[ $? -eq 1 ]]; then
        echo "Error al ejecutar (${php_route} -r...)"
        exit $?
    elif [[ $? -eq 127 ]]; then
        echo "${php_route} no disponible"
        exit $?
    else
        echo "Error ${?} (${php_route} -r...)"
        exit $?
    fi
    echo ""
    echo "###################################"
    echo ""
    read -p "Presionar cualquier tecla para continuar... "

    showMainMenu
}

showIntroMenu
