
# Features

### English
Key features of the application include:

🛠️ **Incident Ticket Creation**: Users, both logged and non-logged, can create incident tickets. For non-logged users, ticket location is determined by the `numTienda.php` script, while logged users use department IDs.

🔍 **Ticket Viewing and Management**: Users can view tickets from their respective departments, with options to sort and filter them based on various criteria.

💬 **Communication and File Attachment**: Users can engage in communication with their department to address ticket issues. Additionally, users have the ability to attach files to tickets and modify certain fields.

🔐 **Ticket Closure**: Tickets can only be closed once both the department and the user mark them as completed. Closed tickets are not deleted from the database but are hidden from the frontend interface.


## Database

The 'sql' folder contains three essential files for database management:

📊 **tables.sql**: This file defines the core structure of the database, including entries for departments, categories, and a super user.

📝 **rellenarincidencias.sql**: It populates the database with sample tickets containing various fields for testing purposes. Note that this script does not include file attachments.

👤 **rellenarusuarios.sql**: This script adds sample users to different departments for testing purposes.

-----

### Spanish

Las características clave de la aplicación incluyen:

🛠️ **Creación de tickets de incidente**: los usuarios, tanto registrados como no registrados, pueden crear tickets de incidente. Para los usuarios que no han iniciado sesión, la ubicación del ticket está determinada por el script `numTienda.php`, mientras que los usuarios registrados utilizan los ID de departamento.

🔍 **Visualización y gestión de tickets**: los usuarios pueden ver los tickets de sus respectivos departamentos, con opciones para ordenarlos y filtrarlos según varios criterios.

💬 **Comunicación y archivos adjuntos**: los usuarios pueden comunicarse con su departamento para abordar problemas de tickets. Además, los usuarios tienen la posibilidad de adjuntar archivos a los tickets y modificar ciertos campos.

🔐 **Cierre de Tickets**: Los tickets sólo se podrán cerrar una vez que tanto el departamento como el usuario los marquen como completados. Los tickets cerrados no se eliminan de la base de datos, pero se ocultan de la interfaz de usuario.


## Base de datos

La carpeta 'sql' contiene tres archivos esenciales para la gestión de bases de datos:

📊 **tables.sql**: este archivo define la estructura central de la base de datos, incluidas las entradas para departamentos, categorías y un superusuario.

📝 **rellenarincidencias.sql**: Llena la base de datos con tickets de muestra que contienen varios campos para fines de prueba. Tenga en cuenta que este script no incluye archivos adjuntos.

👤 **rellenarusuarios.sql**: Este script agrega usuarios de muestra a diferentes departamentos con fines de prueba.
