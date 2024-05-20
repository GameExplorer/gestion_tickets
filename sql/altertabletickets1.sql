ALTER TABLE `tickets`
ADD `leido_localizacion` BOOLEAN NOT NULL DEFAULT FALSE AFTER `oculto`,
ADD `leido_departamento` BOOLEAN NOT NULL DEFAULT FALSE AFTER `leido_localizacion`;