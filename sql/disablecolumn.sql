ALTER TABLE `usuarios`
ADD `disabled` BOOLEAN NOT NULL DEFAULT FALSE AFTER `id_departamento`;

ALTER TABLE `departamentos`
ADD `disabled` BOOLEAN NOT NULL DEFAULT FALSE AFTER `nombre_departamento`;

ALTER TABLE `categorias`
ADD `disabled` BOOLEAN NOT NULL DEFAULT FALSE AFTER `nombre_categoria`;