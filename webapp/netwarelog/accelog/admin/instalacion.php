<?php

	//Clase para registro en catalog
	include("clsetup.php");


    //TABLA DE ORGANIZACIONES Y EMPLEADOS EN CASO DE NO EXISTIR

    if($crear_tablas_organizacion_empleados==1){                

        //Tabla Organización...
        if(!$conexion->existetabla($tabla_organizacion)){

                $sql = "
                        -- -----------------------------------------------------
                        -- Table `ORGANIZACIONES`
                        -- -----------------------------------------------------
                        CREATE TABLE IF NOT EXISTS ".$tabla_organizacion." (
                            ".$campo_idorganizacion." INT NOT NULL AUTO_INCREMENT,
                            ".$campo_nombre_org." VARCHAR(45) NULL,
                            PRIMARY KEY (".$campo_idorganizacion.")  )
                           ENGINE = InnoDB
                        ";
                  $conexion->consultar($sql);

                //CREA LA CAPTURA EN EL CATALOG

                //Añadiendo la estructura
                $sql = " insert into catalog_estructuras values(null,'organizaciones','Catálogo de Organizaciones',now(),now(),'A',0,'') ";
                $conexion->consultar($sql);
                $idestructura_organizacion=mysql_insert_id();

                //Añadiendo los campos...
                $setup->agregacampocatalog($idestructura_organizacion, $campo_idorganizacion, "Id. Organización", "0", "auto_increment", "0", "1", "-1",$conexion);
                $setup->agregacampocatalog($idestructura_organizacion, $campo_nombre_org, "Razón Social", "45", "varchar", "-1", "2", "0",$conexion);


                //Inserta la organización inicial
                $sql = " insert into ".$tabla_organizacion." values (null, '".$super_nombre_org."')";
                $conexion->consultar($sql);
                $super_idorganizacion = mysql_insert_id();
                
        }

        //Tabla de Empleados...
        if(!$conexion->existetabla($tabla_empleados)){
            
                $sql = "
                        -- -----------------------------------------------------
                        -- Table `EMPLEADOS`
                        -- -----------------------------------------------------
                        CREATE TABLE IF NOT EXISTS ".$tabla_empleados." (
                            ".$campo_idempleado." INT NOT NULL AUTO_INCREMENT,
                            ".$campo_nombre_emp." VARCHAR(45) NULL,
                            ".$campo_paterno_emp." VARCHAR(45) NULL,
                            ".$campo_materno_emp." VARCHAR(45) NULL,
                            ".$campo_idorganizacion." INT NOT NULL,
                            PRIMARY KEY (".$campo_idempleado.")  )
                           ENGINE = InnoDB
                        ";
                  $conexion->consultar($sql);

                //CREA LA CAPTURA EN EL CATALOG

                //Añadiendo la estructura
                $sql = " insert into catalog_estructuras values(null,'empleados','Catálogo de Empleados',now(),now(),'A',0,'') ";
                $conexion->consultar($sql);
                $idestructura_empleados=mysql_insert_id();

                //Añadiendo los campos...
                $setup->agregacampocatalog($idestructura_empleados, $campo_idempleado, "Id. Empleado", "0", "auto_increment", "0", "1", "-1",$conexion);
                $setup->agregacampocatalog($idestructura_empleados, $campo_nombre_emp, "Nombre", "45", "varchar", "-1", "2", "0",$conexion);
                $setup->agregacampocatalog($idestructura_empleados, $campo_paterno_emp, "Apellido Paterno", "45", "varchar", "-1", "3", "0",$conexion);
                $setup->agregacampocatalog($idestructura_empleados, $campo_materno_emp, "Apellido Materno", "45", "varchar", "-1", "3", "0",$conexion);
                $setup->agregacampocatalog($idestructura_empleados, $campo_idorganizacion, "Organización", "45", "varchar", "-1", "4", "0",$conexion);
                $setup->agregadependencia(mysql_insert_id(), $tabla_organizacion, $campo_idorganizacion, $campo_nombre_org, $conexion);

                //Inserta el empleado inicial
                $sql = " insert into ".$tabla_empleados." values (null, '".$super_nombre."', '".$super_paterno."', '".$super_materno."', ".$idestructura_organizacion."  )";
                $conexion->consultar($sql);
                $super_idempleado = mysql_insert_id();
                


        }


    }






    // TABLA DE CATEGORIAS

    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_categorias' ";
    if(!$conexion->existe($sql)){

            //CREA LA TABLA FISICAMENTE EN LA BASE DE DATOS
            $sql = "
                    -- -----------------------------------------------------
                    -- Table `accelog_categorias`
                    -- -----------------------------------------------------
                    CREATE  TABLE IF NOT EXISTS `accelog_categorias` (
                      `idcategoria` INT NOT NULL AUTO_INCREMENT ,
                      `nombre` VARCHAR(45) NULL ,
                      `icono` TINYINT(1) NULL ,
                      `orden` INT NULL ,
                      PRIMARY KEY (`idcategoria`) )
                    ENGINE = InnoDB;
            ";
            $conexion->consultar($sql);

            //CREA LA CAPTURA EN EL CATALOG

            //Añadiendo la estructura
            $sql = " insert into catalog_estructuras values(null,'accelog_categorias','Categorías del accelog',now(),now(),'A',0,'') ";
            $conexion->consultar($sql);
            $ide_categorias=mysql_insert_id();
            //Añadiendo los campos...
            $setup->agregacampocatalog($ide_categorias, "idcategoria", "Id. Categoria", "0", "auto_increment", "0", "1", "-1",$conexion);
            $setup->agregacampocatalog($ide_categorias, "nombre", "Nombre", "45", "varchar", "-1", "2", "0",$conexion);
            $setup->agregacampocatalog($ide_categorias, "icono", "Usa icono", "0", "boolean", "0", "3", "0",$conexion);
            $setup->agregacampocatalog($ide_categorias, "orden", "Orden", "0","int", "0", "4", "0",$conexion);
        }

             
    ////





    // TABLA DE MENUS

    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_menu' ";
    if(!$conexion->existe($sql)){
    
            $sql = "
                    -- -----------------------------------------------------
                    -- Table `accelog_menu`
                    -- -----------------------------------------------------
                    CREATE  TABLE IF NOT EXISTS `accelog_menu` (
                      `idmenu` INT NOT NULL AUTO_INCREMENT ,
                      `nombre` VARCHAR(45) NULL ,
                      `idmenupadre` INT NULL ,
                      `url` VARCHAR(300) NULL ,
                      `idcategoria` INT NOT NULL ,
                      `icono` TINYINT(1) NULL ,
                      `orden` INT NULL ,
                      PRIMARY KEY (`idmenu`, `idcategoria`) ,
                      INDEX `menu_categorias` (`idcategoria` ASC) ,
                      CONSTRAINT `menu_categorias`
                        FOREIGN KEY (`idcategoria` )
                        REFERENCES `accelog_categorias` (`idcategoria` )
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION)
                    ENGINE = InnoDB;
            ";
            $conexion->consultar($sql);

            //CREA LA CAPTURA EN EL CATALOG

            //Añadiendo la estructura
            $sql = " insert into catalog_estructuras values(null,'accelog_menu','Lista de menús para accelog',now(),now(),'A',0,'') ";
            $conexion->consultar($sql);
            $ide_menus=mysql_insert_id();
            //Añadiendo los campos...           
            $setup->agregacampocatalog($ide_menus, "idmenu", "Id. Menú", "0", "auto_increment", "0", "1", "-1",$conexion);
            $setup->agregacampocatalog($ide_menus, "nombre", "Nombre", "45", "varchar", "-1", "2", "0",$conexion);
            $setup->agregacampocatalog($ide_menus, "idmenupadre", "Id. Menu Padre", "5", "int", "-1", "3", "0",$conexion);
            $setup->agregacampocatalog($ide_menus, "url", "URL o vínculo", "300", "varchar", "0", "4", "0",$conexion);
            
            $setup->agregacampocatalog($ide_menus, "idcategoria", "Categoría", "5", "int", "-1", "5", "0",$conexion);
            $setup->agregadependencia(mysql_insert_id(), "accelog_categorias", "idcategoria", "nombre", $conexion);
                    
            $setup->agregacampocatalog($ide_menus, "icono", "Icono", "0", "boolean", "0", "6", "0",$conexion);
            $setup->agregacampocatalog($ide_menus, "orden", "Orden", "5", "int", "0", "7", "0",$conexion);
            

    }

    //////








    // TABLA DE PERFILES

    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_perfiles' ";
    if(!$conexion->existe($sql)){


            $sql = "
                    -- -----------------------------------------------------
                    -- Table `accelog_perfiles`
                    -- -----------------------------------------------------
                    CREATE  TABLE IF NOT EXISTS `accelog_perfiles` (
                      `idperfil` INT NOT NULL AUTO_INCREMENT ,
                      `nombre` VARCHAR(50) NULL ,
                      PRIMARY KEY (`idperfil`) )
                    ENGINE = InnoDB;
            ";
            $conexion->consultar($sql);

            //CREA LA CAPTURA EN EL CATALOG

            //Añadiendo la estructura
            $sql = " insert into catalog_estructuras values(null,'accelog_perfiles','Lista de perfiles de usuarios de accelog',now(),now(),'A',0,'') ";
            $conexion->consultar($sql);
            $ide_perfiles=mysql_insert_id();
            //Añadiendo los campos...
            $setup->agregacampocatalog($ide_perfiles, "idperfil", "Id. Perfil", "0", "auto_increment", "0", "1", "-1", $conexion);
            $setup->agregacampocatalog($ide_perfiles, "nombre", "Nombre", "50", "varchar", "-1", "2", "0",$conexion);
            
    }






    // TABLA DE PERFILES MENU

    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_perfiles_me' ";
    if(!$conexion->existe($sql)){

            $sql = "
                    -- -----------------------------------------------------
                    -- Table `accelog_perfiles_me`
                    -- -----------------------------------------------------
                    CREATE  TABLE IF NOT EXISTS `accelog_perfiles_me` (
                      `idperfil` INT NOT NULL ,
                      `idmenu` INT NOT NULL ,
                      PRIMARY KEY (`idperfil`, `idmenu`) ,
                      INDEX `perfiles_menu` (`idmenu` ASC) ,
                      INDEX `perfiles_perfiles_me` (`idperfil` ASC) ,
                      CONSTRAINT `perfiles_menu`
                        FOREIGN KEY (`idmenu` )
                        REFERENCES `accelog_menu` (`idmenu` )
                        ON DELETE CASCADE
                        ON UPDATE CASCADE,
                      CONSTRAINT `perfiles_perfiles_me`
                        FOREIGN KEY (`idperfil` )
                        REFERENCES `accelog_perfiles` (`idperfil` )
                        ON DELETE CASCADE
                        ON UPDATE CASCADE)
                    ENGINE = InnoDB;
            ";
            $conexion->consultar($sql);

            //CREA LA CAPTURA EN EL CATALOG

            //Añadiendo la estructura
            $sql = " insert into catalog_estructuras values(null,'accelog_perfiles_me','Lista de menús por perfil',now(),now(),'A',0,'') ";
            $conexion->consultar($sql);
            $ide_perfil_menu=mysql_insert_id();

            //Añadiendo los campos...

            $setup->agregacampocatalog($ide_perfil_menu, "idperfil", "Perfil", "0", "int", "0", "1", "-1",$conexion);
            $setup->agregadependencia(mysql_insert_id(), "accelog_perfiles", "idperfil", "nombre", $conexion);

            $setup->agregacampocatalog($ide_perfil_menu, "idmenu", "Menú", "0", "int", "0", "2", "-1",$conexion);
            $setup->agregadependencia(mysql_insert_id(), "accelog_menu", "idmenu", "nombre", $conexion);            

    }







    // TABLA DE OPCIONES

    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_opciones' ";
    if(!$conexion->existe($sql)){

            $sql = "
                        -- -----------------------------------------------------
                        -- Table `accelog_opciones`
                        -- -----------------------------------------------------
                        CREATE  TABLE IF NOT EXISTS `accelog_opciones` (
                          `idopcion` VARCHAR(45) NOT NULL ,
                          `nombre` VARCHAR(45) NULL ,
                          PRIMARY KEY (`idopcion`) )
                        ENGINE = InnoDB;
                ";
            $conexion->consultar($sql);


            //CREA LA CAPTURA EN EL CATALOG

            //Añadiendo la estructura
            $sql = " insert into catalog_estructuras values(null,'accelog_opciones','Lista de opciones',now(),now(),'A',0,'') ";
            $conexion->consultar($sql);
            $ide_opciones=mysql_insert_id();

            //Añadiendo los campos...

            $setup->agregacampocatalog($ide_opciones, "idopcion", "Id. Opción", "45", "varchar", "0", "1", "-1",$conexion);
            $setup->agregacampocatalog($ide_opciones, "nombre", "Nombre","45", "varchar", "1","2", "0", $conexion);

    }




    // TABLA DE PERFILES-OPCIONES

    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_perfiles_op' ";
    if(!$conexion->existe($sql)){
   
            $sql = "
                        -- -----------------------------------------------------
                        -- Table `accelog_perfiles_op`
                        -- -----------------------------------------------------
                        CREATE  TABLE IF NOT EXISTS `accelog_perfiles_op` (
                          `idperfil` INT NOT NULL ,
                          `idopcion` VARCHAR(45) NOT NULL ,
                          PRIMARY KEY (`idperfil`, `idopcion`) ,
                          INDEX `perfiles_opciones` (`idopcion` ASC) ,
                          INDEX `opciones_perfiles_op` (`idperfil` ASC) ,
                          CONSTRAINT `perfiles_opciones`
                            FOREIGN KEY (`idopcion` )
                            REFERENCES `accelog_opciones` (`idopcion` )
                            ON DELETE CASCADE
                            ON UPDATE CASCADE,
                          CONSTRAINT `opciones_perfiles_op`
                            FOREIGN KEY (`idperfil` )
                            REFERENCES `accelog_perfiles` (`idperfil` )
                            ON DELETE CASCADE
                            ON UPDATE CASCADE)
                        ENGINE = InnoDB;
                    ";
            $conexion->consultar($sql);



            //CREA LA CAPTURA EN EL CATALOG

            //Añadiendo la estructura
            $sql = " insert into catalog_estructuras values(null,'accelog_perfiles_op','Lista de opciones por perfil',now(),now(),'A',0,'') ";
            $conexion->consultar($sql);
            $ide_perfil_opcion=mysql_insert_id();

            //Añadiendo los campos...

            $setup->agregacampocatalog($ide_perfil_opcion, "idperfil", "Perfil", "0", "int", "0", "1", "-1",$conexion);
            $setup->agregadependencia(mysql_insert_id(), "accelog_perfiles", "idperfil", "nombre", $conexion);

            $setup->agregacampocatalog($ide_perfil_opcion, "idopcion", "Opción", "45", "varchar", "0", "2", "-1",$conexion);
            $setup->agregadependencia(mysql_insert_id(), "accelog_opciones", "idopcion", "nombre", $conexion);            

    }







    

    // TABLA DE USUARIOS PERFILES - EMPLEADOS

    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_usuarios_per' ";
    if(!$conexion->existe($sql)){

            $sql = "
                        -- -----------------------------------------------------
                        -- Table `accelog_usuarios_per`
                        -- -----------------------------------------------------
                        CREATE  TABLE IF NOT EXISTS `accelog_usuarios_per` (
                          `idperfil` INT NOT NULL  ,
                          `idempleado` INT NOT NULL ,
                          PRIMARY KEY (`idperfil`, `idempleado`) ,
                          INDEX `empleado_perfiles` (`idperfil` ASC) ,
                          CONSTRAINT `empleado_perfiles`
                            FOREIGN KEY (`idperfil` )
                            REFERENCES `accelog_perfiles` (`idperfil` )
                            ON DELETE CASCADE
                            ON UPDATE CASCADE)
                        ENGINE = InnoDB;
            ";
            $conexion->consultar($sql);


            //CREA LA CAPTURA EN EL CATALOG

            //Añadiendo la estructura
            $sql = " insert into catalog_estructuras values(null,'accelog_usuarios_per','Lista de perfiles por usuario',now(),now(),'A',0,'') ";
            $conexion->consultar($sql);
            $ide_perfil_empleado=mysql_insert_id();

            //Añadiendo los campos...

            $setup->agregacampocatalog($ide_perfil_empleado, "idperfil", "Perfil", "0", "int", "0", "0", "-1",$conexion);
            $setup->agregadependencia(mysql_insert_id(), "accelog_perfiles", "idperfil", "nombre", $conexion);

            $setup->agregacampocatalog($ide_perfil_empleado, "idempleado", "Empleado", "0", "int", "0", "0", "-1",$conexion);
            $setup->agregadependencia(mysql_insert_id(), $tabla_empleados, $campo_idempleado, $campo_nombre_emp, $conexion);


    }







    //TABLA DE USUARIOS


    //Revisando si ya existe la estructura...
    $sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_usuarios' ";
    if(!$conexion->existe($sql)){

        $sql = "
                    -- -----------------------------------------------------
                    -- Table `accelog_usuarios`
                    -- -----------------------------------------------------
                    CREATE  TABLE IF NOT EXISTS `accelog_usuarios` (
                      `idempleado` INT NOT NULL ,
                      `usuario` VARCHAR(10) NOT NULL ,
                      `clave` VARCHAR(10) NOT NULL ,
                      PRIMARY KEY (`idempleado` ))
                    ENGINE = InnoDB;
        ";
        $conexion->consultar($sql);


        //CREA LA CAPTURA EN EL CATALOG

        //Añadiendo la estructura
        $sql = " insert into catalog_estructuras values(null,'accelog_usuarios','Lista de usuarios',now(),now(),'A',0,'') ";
        $conexion->consultar($sql);
        $ide_usuarios=mysql_insert_id();

        //Añadiendo los campos...
        $setup->agregacampocatalog($ide_usuarios, "idempleado", "Empleado", "0", "int", "0", "1", "-1",$conexion);
        $setup->agregadependencia(mysql_insert_id(), $tabla_empleados, $campo_idempleado , $campo_nombre_emp, $conexion);        

        $setup->agregacampocatalog($ide_usuarios, "usuario", "Usuario", "10", "varchar", "0", "2", "-1",$conexion);
        $setup->agregacampocatalog($ide_usuarios, "clave", "Clave", "10", "varchar", "0", "3", "0", $conexion);

    }

    //// OPCIONES INICIALES DE ACCELOG


        //Revisando si ya esta la categoria en la configuración
        $sql = "select nombre from accelog_categorias where nombre='Configuración' ";
        if(!$conexion->existe($sql)){


                //Se añade la CATEGORIA
                $sql = "insert into accelog_categorias values (null, 'Configuración', -1, 100000) ";
                $conexion->consultar($sql);
                $id_categoria_accelog=mysql_insert_id();


                $idmenu_superperfil = array();

                //Se añade al menu CATALOG
                $sql = "
                    insert into accelog_menu values
                    (null,'CataLog',0,'".$url_catalog."', ".$id_categoria_accelog.", -1, 0)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[0]=mysql_insert_id();
              

                //Se añade al menu ORGANIZACIONES
                $sql = "
                    insert into accelog_menu values
                    (null,'Organizaciones',0,'".$url_catalog."gestor.php?idestructura=".$idestructura_organizacion."&ticket=testing', ".$id_categoria_accelog.", -1, 2)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[1]=mysql_insert_id();
                

                //Se añade al menu EMPLEADOS
                $sql = "
                    insert into accelog_menu values
                    (null,'Empleados',0,'".$url_catalog."gestor.php?idestructura=".$idestructura_empleados."&ticket=testing', ".$id_categoria_accelog.", -1, 3)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[2]=mysql_insert_id();


                //Se añade al menu CATEGORIAS
                $sql = "
                    insert into accelog_menu values
                    (null,'Categorías',0,'".$url_catalog."gestor.php?idestructura=".$ide_categorias."&ticket=testing', ".$id_categoria_accelog.", -1, 4)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[3]=mysql_insert_id();

                
                //Se añade al menu MENUS
                $sql = "
                    insert into accelog_menu values
                    (null,'Menús',0,'".$url_catalog."gestor.php?idestructura=".$ide_menus."&ticket=testing', ".$id_categoria_accelog.", -1, 5)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[4]=mysql_insert_id();


                //Se añade al menu OPCIONES
                $sql = "
                    insert into accelog_menu values
                    (null,'Opciones',0,'".$url_catalog."gestor.php?idestructura=".$ide_opciones."&ticket=testing', ".$id_categoria_accelog.", -1, 6)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[5]=mysql_insert_id();
                

                //Se añade al menu PERFILES
                $sql = "
                    insert into accelog_menu values
                    (null,'Perfiles',0,'".$url_catalog."gestor.php?idestructura=".$ide_perfiles."&ticket=testing', ".$id_categoria_accelog.", -1, 7)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[6]=mysql_insert_id();


                //Se añade al menu PERFIL-MENU
                $sql = "
                    insert into accelog_menu values
                    (null,'Perfiles - Menús',0,'".$url_catalog."gestor.php?idestructura=".$ide_perfil_menu."&ticket=testing', ".$id_categoria_accelog.", -1, 8)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[7]=mysql_insert_id();


                //Se añade al menu PERFIL-OPCIONES
                $sql = "
                    insert into accelog_menu values
                    (null,'Perfiles - Opciones',0,'".$url_catalog."gestor.php?idestructura=".$ide_perfil_opcion."&ticket=testing', ".$id_categoria_accelog.", -1, 9)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[8]=mysql_insert_id();


                //Se añade al menu USUARIOS
                $sql = "
                    insert into accelog_menu values
                    (null,'Usuarios',0,'".$url_catalog."gestor.php?idestructura=".$ide_usuarios."&ticket=testing', ".$id_categoria_accelog.", -1, 10)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[9]=mysql_insert_id();


                //Se añade al menu USUARIOS - PERFILES
                $sql = "
                    insert into accelog_menu values
                    (null,'Perfiles a Usuarios',0,'".$url_catalog."gestor.php?idestructura=".$ide_perfil_empleado."&ticket=testing', ".$id_categoria_accelog.", -1, 11)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[10]=mysql_insert_id();

                
                //Se añade al menu REPOLOG
                $sql = "
                    insert into accelog_menu values
                    (null,'RepoLog',0,'".$url_repolog."admin/', ".$id_categoria_accelog.", -1, 1)
                    ";
                $conexion->consultar($sql);
                $idmenu_superperfil[11]=mysql_insert_id();




                //CREANDO DATOS INICIALES...

                //Creando el super PERFIL ...
                $sql = " insert into accelog_perfiles values (null,'".$super_perfil."')";
                $conexion->consultar($sql);
                $idperfil_superperfil = mysql_insert_id();

                //Añadiendo los PERFIL - MENU
                for($idm=0;$idm<=11;$idm++){
                    $sql = "
                        insert into accelog_perfiles_me
                        values (".$idperfil_superperfil.",".$idmenu_superperfil[$idm].")
                        ";
                    $conexion->consultar($sql);
                }

                //Añadiendo el PERMISO para entrar a otras organizaciones
                $sql = "
                    insert into accelog_opciones 
                    values ('".$permiso_otras_organizaciones_id."', '".$permiso_otras_organizaciones_desc."')
                    ";
                $conexion->consultar($sql);

                //Añadir el PERMISO - PERFIL
                $sql = "
                     insert into accelog_perfiles_op
                     values (".$idperfil_superperfil.", '".$permiso_otras_organizaciones_id."') ";
                $conexion->consultar($sql);

                
                //Creando el super USUARIO
                $sql = "
                    insert into accelog_usuarios
                    values (".$super_idempleado.",'".$super_usu."', '".$super_pwd."') 
                    ";
                $conexion->consultar($sql);

                //Añadiendo el PERFIL USUARIO
                $sql = "
                    insert into accelog_usuarios_per
                    values (".$idperfil_superperfil.", ".$super_idempleado." )
                    ";
                $conexion->consultar($sql);                             

        }


        
    //Instalación de tablas del repolog
    include($url_repolog."admin/instalacion.php");

                

    echo "
        <font size=1 color=gray>
         NOTA: Se ha instalado la base de datos para deshabilitar esta función modifique el parametro desde el archivo <b>accelog/webconfig.php</b>
        </font>
        ";



    


?>
