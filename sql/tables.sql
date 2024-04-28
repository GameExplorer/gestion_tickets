CREATE TABLE departamentos (
    id_departamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_departamento varchar(32) NOT NULL
);

CREATE TABLE tickets (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_departamento INT NOT NULL,
    titulo varchar(32) NOT NULL,
    nombre varchar(32) NOT NULL,
    localizacion varchar(32) NOT NULL,
    prioridad varchar(32) NOT NULL,
    descripcion varchar(256),
    categoria varchar(32) NOT NULL,
    estado varchar(32) NOT NULL,
    check_usuario tinyint(1) NOT NULL,
    check_dept tinyint(1) NOT NULL,
    fecha_creacion DATETIME NOT NULL,
    fecha_actualizacion DATETIME NOT NULL,
    oculto tinyint(1) NOT NULL DEFAULT 0,
    CONSTRAINT fk_id_departamento FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento) ON DELETE CASCADE
);

CREATE TABLE archivos (
    id_archivo INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    CONSTRAINT fk_id_Ticket
    FOREIGN KEY (id_ticket) REFERENCES tickets(id_ticket) ON DELETE CASCADE,
    nombre_archivo varchar(32) NOT NULL
);

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario varchar(32),
    pass_usuario varchar(32),
    id_departamento int,
    CONSTRAINT un_usuario UNIQUE (id_usuario, nombre_usuario),
    FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento)
);

CREATE TABLE mensajes (
    id_mensaje INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT,
    FOREIGN KEY (id_ticket) REFERENCES tickets(id_ticket) ON DELETE CASCADE,
    emisor VARCHAR(32),
    contenido VARCHAR(255),
    hora_publicacion DATETIME
);

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    id_departamento INT NOT NULL,
    FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento) ON DELETE CASCADE,
    nombre_categoria varchar(32) NOT NULL
);

INSERT INTO departamentos (id_departamento, nombre_departamento) VALUES (0, 'Administrador');
INSERT INTO departamentos (id_departamento, nombre_departamento) VALUES (1, 'Informática');
INSERT INTO departamentos (id_departamento, nombre_departamento) VALUES (2, 'Recursos Humanos');
INSERT INTO departamentos (id_departamento, nombre_departamento) VALUES (3, 'Finanzas');
INSERT INTO departamentos (id_departamento, nombre_departamento) VALUES (4, 'Marketing');

INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (0,'Sin categoría');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (1,'Sin categoría');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (1,'Hardware');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (1,'Software');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (1,'Internet');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (2,'Sin categoría');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (2,'Empleados');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (2,'Salarios');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (2,'Turnos');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (3,'Sin categoría');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (3,'Facturas');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (3,'Pagos');
INSERT INTO categorias(id_departamento, nombre_categoria) VALUES (4,'Sin categoría');

INSERT INTO usuarios(nombre_usuario, pass_usuario, id_departamento) VALUES ('ticketadministrador','centralu2024',0);