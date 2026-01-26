# Instrucciones para ejecutar la base de datos

## Opción 1: Usar el script PHP (RECOMENDADO)

1. Ve a `http://localhost/EmailPhp/Practica5/setup.php` en tu navegador
2. Verás el mensaje "Base de datos lista para usar"
3. Eso es todo, la base de datos está creada

## Opción 2: Ejecutar manualmente en phpMyAdmin

1. Abre phpMyAdmin (http://localhost/phpmyadmin)
2. Abre la pestaña "SQL"
3. Copia y pega el contenido de `data.sql`
4. Haz clic en "Ejecutar"

## Opción 3: Línea de comandos MySQL

```bash
mysql -u root < data.sql
```

## Credenciales de conexión

- **Host**: localhost
- **Usuario**: root
- **Contraseña**: (vacía)
- **Base de datos**: practica5

Estas credenciales están configuradas en `src/Connections/Database.php`
