#!/bin/bash
clear
while :
    do
        read -p 'Escribe el nombre de tu proyecto o carpeta dentro de modulos : ' NOMBRE
        if [ -d $1/$NOMBRE ];
            then
                echo "------------------------------"
                printf "\033[0;31m[ERROR]\033[0m La carpeta $1/$NOMBRE/ ya existe \n"
                echo "------------------------------"
            else
                echo "------------------------------"
                echo "Abriendo carpeta..."
                cd $1
                echo "Copiando carpetas y archivos..."
                cp -r ../netwarelog/mvc/mvc_basico ../modulos
                mv mvc_basico $NOMBRE
                cd $NOMBRE
                printf "\033[0;33mSE HA CREADO EL PROYECTO $NOMBRE con los siguientes archivos:\033[0m\n"
                open $1/$NOMBRE
                ls
                echo $1/$NOMBRE
                break
            fi
    done


