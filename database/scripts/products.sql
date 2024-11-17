-- Insertar Categorías para una Ferretería
INSERT INTO categories (name, description) VALUES
('Herramientas Manuales', 'Martillos, destornilladores, llaves inglesas, y más'),
('Herramientas Eléctricas', 'Taladros, sierras eléctricas, amoladoras, etc.'),
('Materiales de Construcción', 'Cemento, ladrillos, arena, gravilla, etc.'),
('Pinturas y Acabados', 'Pinturas, barnices, brochas, y rodillos'),
('Fontanería', 'Tubos, conexiones, válvulas, grifos, etc.'),
('Electricidad', 'Cables, enchufes, interruptores, bombillas, etc.'),
('Ferretería General', 'Tornillos, clavos, bisagras, y otros accesorios');

-- Insertar Productos con referencia a Categorías
INSERT INTO products (name, description, price, image_url, category_id, stock) VALUES
('Martillo de Uña', 'Martillo de acero con mango de goma antideslizante', 15.00, NULL, 1, 50),
('Destornillador de Estrella', 'Destornillador punta de estrella con mango ergonómico', 5.00, NULL, 1, 100),
('Taladro Eléctrico', 'Taladro eléctrico con función percutora y velocidad ajustable', 80.00, NULL, 2, 20),
('Sierra Circular', 'Sierra circular de 1500W con hoja de corte para madera', 120.00, NULL, 2, 15),
('Cemento Portland', 'Saco de 50 kg de cemento tipo Portland', 10.00, NULL, 3, 200),
('Ladrillos de Arcilla', 'Paquete de 50 ladrillos de arcilla cocida', 25.00, NULL, 3, 500),
('Pintura Blanca Mate', 'Galón de pintura blanca mate de alta cobertura', 20.00, NULL, 4, 30),
('Barniz Transparente', 'Litro de barniz transparente para madera', 12.00, NULL, 4, 40),
('Tubo de PVC 1"', 'Tubo de PVC de 1 pulgada para agua potable', 3.00, NULL, 5, 300),
('Llave de Paso', 'Llave de paso de latón para conexiones de agua', 7.00, NULL, 5, 100),
('Cable Eléctrico 2.5mm', 'Rollo de 100 metros de cable eléctrico', 30.00, NULL, 6, 50),
('Bombilla LED 10W', 'Bombilla LED de 10W con luz cálida', 3.50, NULL, 6, 200),
('Tornillos de Madera', 'Caja de 100 tornillos para madera de 2 pulgadas', 8.00, NULL, 7, 150),
('Clavos de Acero', 'Caja de 1 kg de clavos de acero galvanizado', 5.00, NULL, 7, 300),
('Bisagras para Puertas', 'Par de bisagras de acero inoxidable para puertas', 10.00, NULL, 7, 75);