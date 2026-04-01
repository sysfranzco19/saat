-- SCRIPT DE REPARACION DE BASE DE DATOS (Strict 8 Items + HTML Entities)
-- Ejecuta este script en phpMyAdmin o tu gestor de base de datos.
-- Usa Entidades HTML para evitar problemas de codificación de Emojis en la BD.

-- 1. No presentó tarea (-5 pts) -> 📝 (Memo)
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (1, 'No present&oacute; tarea', '&#128221;', -5, 'negative')
ON DUPLICATE KEY UPDATE name='No present&oacute; tarea', icon='&#128221;', points=-5, type='negative';

-- 2. Participación Positiva (+5 pts) -> 🌟 (Glowing Star)
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (2, 'Participaci&oacute;n Positiva', '&#127775;', 5, 'positive')
ON DUPLICATE KEY UPDATE name='Participaci&oacute;n Positiva', icon='&#127775;', points=5, type='positive';

-- 3. Llegada tardía (-5 pts) -> ⏰
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (3, 'Llegada tard&iacute;a', '&#9200;', -5, 'negative')
ON DUPLICATE KEY UPDATE name='Llegada tard&iacute;a', icon='&#9200;', points=-5, type='negative';

-- 4. Comer en clases (-5 pts) -> 🍔
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (4, 'Comer en clases', '&#127828;', -5, 'negative')
ON DUPLICATE KEY UPDATE name='Comer en clases', icon='&#127828;', points=-5, type='negative';

-- 5. Uso de celular (-5 pts) -> 📱
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (5, 'Uso de celular', '&#128241;', -5, 'negative')
ON DUPLICATE KEY UPDATE name='Uso de celular', icon='&#128241;', points=-5, type='negative';

-- 6. Indisciplina/ruido (-5 pts) -> � (PA Loudspeaker - Alternative Megaphone)
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (6, 'Indisciplina/ruido', '&#128226;', -5, 'negative')
ON DUPLICATE KEY UPDATE name='Indisciplina/ruido', icon='&#128226;', points=-5, type='negative';

-- 7. Uniforme incompleto (-5 pts) -> � (T-Shirt)
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (7, 'Uniforme incompleto', '&#128085;', -5, 'negative')
ON DUPLICATE KEY UPDATE name='Uniforme incompleto', icon='&#128085;', points=-5, type='negative';

-- 8. Otro (-5 pts) -> 📌
INSERT INTO behavior_types (id, name, icon, points, type) VALUES (8, 'Otro', '&#128204;', -5, 'negative')
ON DUPLICATE KEY UPDATE name='Otro', icon='&#128204;', points=-5, type='negative';

-- Borrar cualquier extra que no sea 1-8 (Limpieza)
DELETE FROM behavior_types WHERE id > 8;

-- Asegurar codificación (aunque con entities es menos crítico)
ALTER TABLE behavior_types CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Ensure UTF-8
ALTER TABLE behavior_types CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
