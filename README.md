DEMO desarrollado en Symfony 3.0
========================

Instalacion
--------------
Para instlar seguir los siguientes pasos:

Clonamos el proyecto
```
git clone 
```
Instalamos dependencias y configuramos la base de datos
```
composer update 
```
Configurar Base de Datos en el archivo app/config/parameters.yml
```
database_name: mantasapp
database_user: root
database_password: password
```
Creamos la base de datos del anterior paso
```
php bin/console doctrine:database:create
```
Generamos las tablas de la base de datos
```
php bin/console doctrine:schema:update --force
```
Cargar Datos de Prueba
```
php bin/console doctrine:fixtures:load
```
Ejecutamos el servidor local
```
php bin/console server:run
```