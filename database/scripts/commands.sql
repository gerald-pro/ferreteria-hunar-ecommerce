-- Poblar tabla Command
INSERT INTO commands (identifier, action, target, description, example, access_level) VALUES 
('login', 'login', NULL, 'Iniciar sesión en la plataforma', '("123456789")', 'PUBLICO'),
('logout', 'logout', NULL, 'Cerrar sesión en la plataforma', NULL, 'RESTRINGIDO'),
('register', 'register', NULL, 'Crear cuenta en la plataforma', '("Amanda Miguel", "amanda123")', 'PUBLICO'),
('help', 'help', NULL, 'Muestra la lista de comandos disponibles', NULL, 'RESTRINGIDO'),

('create_role', 'create', 'role', 'Crea un nuevo rol', '("Vendedor")', 'RESTRINGIDO'),
('list_role', 'list', 'role', 'Obtiene la lista de todos los roles', NULL, 'RESTRINGIDO'),
('update_role', 'update', 'role', 'Actualiza los datos de un rol existente', '("1", "Vendedor")', 'RESTRINGIDO'),
('delete_role', 'delete', 'role', 'Elimina un rol de la base de datos', '(1)', 'RESTRINGIDO'),
('add_permission', 'add', 'permission', 'Agregar un permiso a un rol', '(2, "create_user")', 'RESTRINGIDO'),
('remove_permission', 'remove', 'permission', 'Eliminar un permiso de un rol', '(id_rol, "nombre_permiso")', 'RESTRINGIDO'),

('list_user', 'list', 'user', 'Obtiene la lista de todos los usuarios', NULL, 'RESTRINGIDO'),
('list_product', 'list', 'product', 'Obtiene la lista de todos los productos', NULL, 'RESTRINGIDO'),
('create_product', 'create', 'product', 'Crea un nuevo producto en la base de datos.', '("Camisa", "Camisa de algodón", 19.99)', 'RESTRINGIDO'),
('update_product', 'update', 'product', 'Actualiza los datos de un producto existente', '(1, "Coca colla", "Gaseosa de color negro", 12.5)', 'RESTRINGIDO'),
('delete_product', 'delete', 'product', 'Elimina un producto de la base de datos', '(1)', 'RESTRINGIDO');

-- Poblar tabla Parameter
INSERT INTO parameters (name, type, position, command_id) VALUES 
('contraseña', 'STRING', 1, (SELECT id FROM commands WHERE identifier='login')),
('nombre', 'STRING', 1, (SELECT id FROM commands WHERE identifier='register')),
('contraseña', 'STRING', 2, (SELECT id FROM commands WHERE identifier='register')),

('nombre', 'STRING', 1, (SELECT id FROM commands WHERE identifier='create_role')),
('nombre', 'STRING', 1, (SELECT id FROM commands WHERE identifier='list_role')),
('id', 'INTEGER', 1, (SELECT id FROM commands WHERE identifier='update_role')),
('nombre', 'STRING', 2, (SELECT id FROM commands WHERE identifier='update_role')),
('id', 'INTEGER', 1, (SELECT id FROM commands WHERE identifier='delete_role')),
('id_rol', 'INTEGER', 1, (SELECT id FROM commands WHERE identifier='add_permission')),
('nombre_permiso', 'STRING', 2, (SELECT id FROM commands WHERE identifier='add_permission')),
('id_rol', 'INTEGER', 1, (SELECT id FROM commands WHERE identifier='remove_permission')),
('nombre_permiso', 'STRING', 2, (SELECT id FROM commands WHERE identifier='remove_permission')),
('nombre', 'STRING', 1, (SELECT id FROM commands WHERE identifier='create_product')),
('descripción', 'STRING', 2, (SELECT id FROM commands WHERE identifier='create_product')),
('precio', 'FLOAT', 3, (SELECT id FROM commands WHERE identifier='create_product')),
('id', 'INTEGER', 1, (SELECT id FROM commands WHERE identifier='update_product')),
('nombre', 'STRING', 2, (SELECT id FROM commands WHERE identifier='update_product')),
('descripción', 'STRING', 3, (SELECT id FROM commands WHERE identifier='update_product')),
('precio', 'FLOAT', 4, (SELECT id FROM commands WHERE identifier='update_product')),
('id', 'INTEGER', 1, (SELECT id FROM commands WHERE identifier='delete_product'));
