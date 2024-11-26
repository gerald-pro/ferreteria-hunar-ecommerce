-- Movimientos de entrada iniciales (stock inicial)
INSERT INTO inventory_movements (product_id, quantity, type, notes, created_at, updated_at) VALUES
(1, 50, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(2, 100, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(3, 20, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(4, 15, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(5, 200, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(6, 500, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(7, 30, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(8, 40, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(9, 300, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(10, 100, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(11, 50, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(12, 200, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(13, 150, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(14, 300, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days'),
(15, 75, 'IN', 'Stock inicial', NOW() - INTERVAL '30 days', NOW() - INTERVAL '30 days');

-- Movimientos de salida (ventas y consumos)
INSERT INTO inventory_movements (product_id, quantity, type, notes, created_at, updated_at) VALUES
-- Herramientas manuales (alta rotación)
(1, -5, 'OUT', 'Venta regular', NOW() - INTERVAL '25 days', NOW() - INTERVAL '25 days'),
(1, -8, 'OUT', 'Venta a constructor', NOW() - INTERVAL '20 days', NOW() - INTERVAL '20 days'),
(2, -15, 'OUT', 'Venta a contratista', NOW() - INTERVAL '18 days', NOW() - INTERVAL '18 days'),
(2, -10, 'OUT', 'Venta regular', NOW() - INTERVAL '15 days', NOW() - INTERVAL '15 days'),

-- Herramientas eléctricas (rotación media)
(3, -2, 'OUT', 'Venta regular', NOW() - INTERVAL '22 days', NOW() - INTERVAL '22 days'),
(3, -3, 'OUT', 'Venta especial', NOW() - INTERVAL '12 days', NOW() - INTERVAL '12 days'),
(4, -2, 'OUT', 'Venta regular', NOW() - INTERVAL '20 days', NOW() - INTERVAL '20 days'),

-- Materiales de construcción (alta rotación)
(5, -50, 'OUT', 'Venta a constructor', NOW() - INTERVAL '19 days', NOW() - INTERVAL '19 days'),
(5, -30, 'OUT', 'Venta regular', NOW() - INTERVAL '14 days', NOW() - INTERVAL '14 days'),
(6, -100, 'OUT', 'Venta mayorista', NOW() - INTERVAL '17 days', NOW() - INTERVAL '17 days'),

-- Pinturas y acabados (rotación media)
(7, -5, 'OUT', 'Venta regular', NOW() - INTERVAL '16 days', NOW() - INTERVAL '16 days'),
(8, -8, 'OUT', 'Venta regular', NOW() - INTERVAL '13 days', NOW() - INTERVAL '13 days'),

-- Plomería (rotación media-alta)
(9, -50, 'OUT', 'Venta a plomero', NOW() - INTERVAL '15 days', NOW() - INTERVAL '15 days'),
(10, -15, 'OUT', 'Venta regular', NOW() - INTERVAL '12 days', NOW() - INTERVAL '12 days'),

-- Material eléctrico (rotación media)
(11, -10, 'OUT', 'Venta a electricista', NOW() - INTERVAL '11 days', NOW() - INTERVAL '11 days'),
(12, -30, 'OUT', 'Venta mayorista', NOW() - INTERVAL '10 days', NOW() - INTERVAL '10 days'),

-- Ferretería general (alta rotación)
(13, -25, 'OUT', 'Venta regular', NOW() - INTERVAL '9 days', NOW() - INTERVAL '9 days'),
(14, -40, 'OUT', 'Venta a constructor', NOW() - INTERVAL '8 days', NOW() - INTERVAL '8 days'),
(15, -10, 'OUT', 'Venta regular', NOW() - INTERVAL '7 days', NOW() - INTERVAL '7 days');

-- Reposición de stock (nuevas entradas)
INSERT INTO inventory_movements (product_id, quantity, type, notes, created_at, updated_at) VALUES
(1, 20, 'IN', 'Reposición de stock', NOW() - INTERVAL '10 days', NOW() - INTERVAL '10 days'),
(2, 30, 'IN', 'Reposición de stock', NOW() - INTERVAL '9 days', NOW() - INTERVAL '9 days'),
(5, 100, 'IN', 'Reposición de stock', NOW() - INTERVAL '8 days', NOW() - INTERVAL '8 days'),
(6, 200, 'IN', 'Reposición de stock', NOW() - INTERVAL '7 days', NOW() - INTERVAL '7 days'),
(9, 100, 'IN', 'Reposición de stock', NOW() - INTERVAL '6 days', NOW() - INTERVAL '6 days'),
(12, 50, 'IN', 'Reposición de stock', NOW() - INTERVAL '5 days', NOW() - INTERVAL '5 days'),
(13, 50, 'IN', 'Reposición de stock', NOW() - INTERVAL '4 days', NOW() - INTERVAL '4 days'),
(14, 100, 'IN', 'Reposición de stock', NOW() - INTERVAL '3 days', NOW() - INTERVAL '3 days');

-- Movimientos recientes (últimos días)
INSERT INTO inventory_movements (product_id, quantity, type, notes, created_at, updated_at) VALUES
(1, -3, 'OUT', 'Venta reciente', NOW() - INTERVAL '2 days', NOW() - INTERVAL '2 days'),
(2, -5, 'OUT', 'Venta reciente', NOW() - INTERVAL '2 days', NOW() - INTERVAL '2 days'),
(5, -20, 'OUT', 'Venta reciente', NOW() - INTERVAL '1 day', NOW() - INTERVAL '1 day'),
(7, -2, 'OUT', 'Venta reciente', NOW() - INTERVAL '1 day', NOW() - INTERVAL '1 day'),
(13, -15, 'OUT', 'Venta reciente', NOW(), NOW());