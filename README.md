# Ticket Management

This is a ticket management system prepared for Central Uniformes needs. The features are as follows:
 - There's 2 types of users: Non-Logged Users and Logged Users
 - Non-Logged Users will use the store Location from numTienda.php, while Logged Users will require signed up in the database prior, and will be using department id
 - Any user can create a ticket, it will be based on the Location even if the user is logged.
 - Tickets are displayed on index.php and ticket_table.php depending on the user session. They can be filtered, sortered and searched dynamically.
 - Each ticket provides a list of fields that can be accessed. It also features a Comment system for each ticket that can be used for any user.
 - Files can be uploaded on each ticket, be it when creating a ticket, or adding a comment. They have a standarized name and they'll be moved into 'adjuntos' folder.
 - Each ticket will need to be closed by Non-Logged User AND Logged User
 - Once both parties mark a ticket as solved, it will be hidden and no longer be displayed on the table (but still exists on the database)
 - Logged Users can also modify some fields on each ticket
 - Lastly, for logged users, they can do some quick changes on tickets on their department from the table

The folder named 'sql' has 3 files for the database
 - tables.sql: This being the main structure of the database. Includes a few entries for departments, categories and one super user.
 - rellenarincidencias.sql: It fills the table with some tickets with different fields, for testing purposes. Note that this script doesn't include any file attachment
 - rellenarusuarios.sql: It adds some users for some departments, for testing purposes.

Esto es un sistema de gestión de tickets para cubrir las necesidades de Central Uniformes. Sus características son las siguientes:
 - Hay 2 tipos de usuarios: Los No-Logeados y los Logeados
 - Los usuarios No-Logeados usan la localización por defecto de numTienda.php, mientras que los usuarios Logueados necesitarán ser dados de alta en la base de datos con anterioridad, y usarán el id de su departamento.
 - Cualquier usuario puede crear un ticket, dependerá de la localización aunque el usuario esté logueado.
 - Los tickets se muestran en index.php y ticket_table.php dependiendo del tipo de sesión del usuario. Dichos tickets se pueden filtrar, ordenar y buscar dinámicamente.
 - Cada ticket contiene una lista de campos que se pueden acceder. Incluyen un sistema de comentario para cada ticket para cada tipo de usuario.
 - Se pueden subir archivos adjuntos, ya sea a la hora de crear el ticket, o al añadir comentarios. Tienen un nombre estandarizado y se moverán a una carpeta llamado 'adjuntos'.
 - Cada ticket necesita que el usuario No-Logueado y el usuario Logueado le den a Resuelto.
 - Cuando ambas partes hayan marcado una incidencia como resuelto, se ocultará y no se volverá a mostrar en la tabla (aunque seguirá existiendo en la base de datos)
 - Los usuarios Logueados pueden modificar algunos campos de cada ticket.
 - Por último, los usuarios logueados pueden realizar algunos cambios desde la tabla.

La carpeta llamada 'sql' tiene 3 archivos de base de datos
 - tables.sql: Es la estructura principal de la base de datos. Incluye algunas entradas para departamentos, categorías y un super usuario.
 - rellenarincidencias.sql: Rellena la tabla con algunos tickets con diversos campos, para hacer pruebas. Hay que tener en cuenta que este archivo no incluye archivos adjuntos.
 - rellenarusuarios.sql: Añade algunos usuarios para otros departamentos, para hacer pruebas.