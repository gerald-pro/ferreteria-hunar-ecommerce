DO $$
DECLARE
    v_user_id INT;
    v_product_id INT;
    v_order_id INT;
    v_quantity INT;
    v_max_user_id INT;
    v_price DECIMAL(10, 2);
    v_total_amount DECIMAL(10, 2);
    v_random_date TIMESTAMP;
    v_order_status TEXT;
    v_payment_status TEXT;
    v_order_status_array TEXT[] := ARRAY['PENDIENTE', 'CANCELADO', 'COMPLETADO'];
    v_payment_status_array TEXT[] := ARRAY['PENDIENTE', 'CANCELADO', 'PAGADO'];
    v_payment_method TEXT;
    v_payment_method_array TEXT[] := ARRAY['ELECTRONICO', 'CONTRA_ENTREGA'];
BEGIN
    -- Obtener el máximo ID de usuario
    SELECT MAX(id) INTO v_max_user_id FROM users;

    FOR i IN 1..1000 LOOP
        -- Seleccionar un usuario aleatorio
        v_user_id := floor(random() * v_max_user_id) + 1;

        -- Seleccionar un producto aleatorio (del 1 al 10)
        v_product_id := floor(random() * 10) + 1;

        -- Obtener el precio del producto seleccionado
        SELECT price INTO v_price FROM products WHERE id = v_product_id;

        -- Generar una cantidad aleatoria de items (entre 1 y 5)
        v_quantity := floor(random() * 5) + 1;

        -- Calcular el monto total
        v_total_amount := v_price * v_quantity;

        v_random_date := now() - (random() * interval '500 days');
        v_order_status := v_order_status_array[floor(random() * 3) + 1];
        v_payment_status := v_payment_status_array[floor(random() * 3) + 1];
        v_payment_method := v_payment_method_array[floor(random() * 2) + 1];

        -- Insertar un pedido
        INSERT INTO orders (user_id, total_amount, delivery_status, created_at, payment_method, delivery_address, billing_name, billing_id)
        VALUES (
            v_user_id, 
            v_total_amount, 
            v_order_status, 
            v_random_date, 
            v_payment_method, 
            'Dirección de entrega ' || i, 
            'Nombre de facturación ' || i, 
            'ID de facturación ' || i
        ) RETURNING id INTO v_order_id;

        -- Insertar ítems del pedido
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (v_order_id, v_product_id, v_quantity, v_price);

        -- Insertar pago
        INSERT INTO payments (order_id, paid_amount, status, created_at)
        VALUES (v_order_id, v_total_amount, v_payment_status, v_random_date);
    END LOOP;
END $$;