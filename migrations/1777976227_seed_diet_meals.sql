-- ============================================================
-- SEED: Extra dishes (2 more per meal per diet)
-- Run ONCE. Extends existing seed_data.sql data.
-- Dish IDs auto-assigned from 25 onwards.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- NEW DISHES
-- ============================================================
INSERT INTO `dish` (`name`, `ingredients`, `calories_per_serving`) VALUES

-- ── Dieta 1 (deficit, 1500 kcal) ─────────────────────────

-- desayuno
('Muesli con leche desnatada y frutas',
 'Copos de avena, leche desnatada, platano, arandanos, miel',
 200),
('Fruta de temporada con yogur desnatado',
 'Manzana, naranja, kiwi, yogur natural desnatado',
 120),

-- media-manana
('Infusion con rodajas de fruta fresca',
 'Manzanilla, naranja, kiwi',
 60),
('Palitos de zanahoria con hummus',
 'Zanahoria, hummus casero, limon',
 120),

-- almuerzo
('Dorada al horno con verduras',
 'Dorada, patata, cebolla, tomate, aceite de oliva, limon',
 280),
('Ensalada de garbanzos con verduras',
 'Garbanzos cocidos, tomate cherry, pepino, cebolla morada, perejil, aceite de oliva',
 240),

-- merienda
('Tostada integral con aceite y oregano',
 'Pan integral, aceite de oliva virgen extra, oregano',
 100),
('Gelatina de frutas sin azucar',
 'Gelatina neutra, frutas naturales variadas',
 60),

-- cena
('Calabacin relleno de verduras',
 'Calabacin, pimiento, cebolla, tomate, queso fresco, aceite de oliva',
 160),
('Sopa de pollo casera',
 'Pollo, zanahoria, apio, puerro, fideos finos, caldo de pollo',
 180),

-- ── Dieta 2 (mantenimiento, 2000 kcal) ───────────────────

-- desayuno
('Tostadas de centeno con aceite y tomate',
 'Pan de centeno, aceite de oliva virgen, tomate rallado, sal',
 280),
('Tortilla francesa con jamon york',
 'Huevo, jamon york, aceite de oliva, sal',
 250),

-- media-manana
('Queso fresco con membrillo y crackers',
 'Queso fresco, dulce de membrillo, crackers integrales',
 200),
('Batido de frutas naturales',
 'Naranja, zanahoria, manzana, jengibre',
 130),

-- almuerzo
('Arroz a la cubana',
 'Arroz blanco, huevo frito, tomate frito casero, platano',
 380),
('Fideos a la cazuela con pollo',
 'Fideos gruesos, pollo troceado, tomate, pimiento, caldo de pollo',
 350),

-- merienda
('Tostada de centeno con mermelada sin azucar',
 'Pan de centeno, mermelada de frutas sin azucar',
 180),
('Manzana asada con canela y miel',
 'Manzana, canela, miel',
 160),

-- cena
('Salmon a la plancha con ensalada',
 'Salmon, lechuga, tomate, aceitunas negras, aceite de oliva, limon',
 320),
('Revuelto de esparragos y gambas',
 'Esparragos verdes, gambas peladas, huevo, ajo, aceite de oliva',
 280),

-- ── Dieta 3 (superavit, 2700 kcal) ───────────────────────

-- desayuno
('Porridge de avena con platano y frutos secos',
 'Copos de avena, leche entera, platano, nueces, almendras, miel',
 450),
('Huevos benedictinos con salmon ahumado',
 'Huevo pochado, salmon ahumado, pan ingles, salsa holandesa, cebollino',
 500),

-- media-manana
('Bocadillo de jamon serrano y queso manchego',
 'Pan de barra, jamon serrano, queso manchego curado, aceite de oliva',
 380),
('Frutos secos con chocolate negro',
 'Nueces, almendras, avellanas, chocolate negro 85%',
 400),

-- almuerzo
('Entrecot de ternera con patatas panaderas',
 'Entrecot de ternera, patata, pimiento, cebolla, aceite de oliva, ajo',
 600),
('Lasana de carne boloñesa',
 'Pasta de lasana, carne picada mixta, bechamel, tomate triturado, queso rallado',
 550),

-- merienda
('Bocadillo de atun con huevo duro',
 'Pan de barra, atun en aceite, huevo duro, tomate, mayonesa',
 420),
('Natillas caseras con canela',
 'Leche entera, huevo, azucar, maizena, canela, limon',
 350),

-- cena
('Costillas de cerdo al horno con patatas',
 'Costillas de cerdo, patata, cebolla, ajo, pimenton, aceite de oliva',
 580),
('Macarrones con queso y bacon',
 'Macarrones, queso gouda, bacon, nata, cebolla, pimienta',
 520);

-- ============================================================
-- NEW DIET_MEAL ENTRIES
-- 2 extra dishes per meal per diet (by name = safe)
-- ============================================================

-- ── DIET 1: Dieta mediterranea deficit ───────────────────

INSERT INTO `diet_meal` (`diet_id`, `dish_id`, `meal_type`, `day_of_week`) VALUES
-- desayuno
(1, (SELECT id FROM dish WHERE name = 'Muesli con leche desnatada y frutas'),      'desayuno',    NULL),
(1, (SELECT id FROM dish WHERE name = 'Fruta de temporada con yogur desnatado'),   'desayuno',    NULL),
-- media-manana
(1, (SELECT id FROM dish WHERE name = 'Infusion con rodajas de fruta fresca'),     'media-manana', NULL),
(1, (SELECT id FROM dish WHERE name = 'Palitos de zanahoria con hummus'),          'media-manana', NULL),
-- almuerzo
(1, (SELECT id FROM dish WHERE name = 'Dorada al horno con verduras'),             'almuerzo',    NULL),
(1, (SELECT id FROM dish WHERE name = 'Ensalada de garbanzos con verduras'),       'almuerzo',    NULL),
-- merienda
(1, (SELECT id FROM dish WHERE name = 'Tostada integral con aceite y oregano'),    'merienda',    NULL),
(1, (SELECT id FROM dish WHERE name = 'Gelatina de frutas sin azucar'),            'merienda',    NULL),
-- cena
(1, (SELECT id FROM dish WHERE name = 'Calabacin relleno de verduras'),            'cena',        NULL),
(1, (SELECT id FROM dish WHERE name = 'Sopa de pollo casera'),                     'cena',        NULL);

-- ── DIET 2: Dieta mediterranea mantenimiento ─────────────

INSERT INTO `diet_meal` (`diet_id`, `dish_id`, `meal_type`, `day_of_week`) VALUES
-- desayuno
(2, (SELECT id FROM dish WHERE name = 'Tostadas de centeno con aceite y tomate'),  'desayuno',    NULL),
(2, (SELECT id FROM dish WHERE name = 'Tortilla francesa con jamon york'),          'desayuno',    NULL),
-- media-manana
(2, (SELECT id FROM dish WHERE name = 'Queso fresco con membrillo y crackers'),    'media-manana', NULL),
(2, (SELECT id FROM dish WHERE name = 'Batido de frutas naturales'),               'media-manana', NULL),
-- almuerzo
(2, (SELECT id FROM dish WHERE name = 'Arroz a la cubana'),                        'almuerzo',    NULL),
(2, (SELECT id FROM dish WHERE name = 'Fideos a la cazuela con pollo'),            'almuerzo',    NULL),
-- merienda
(2, (SELECT id FROM dish WHERE name = 'Tostada de centeno con mermelada sin azucar'), 'merienda', NULL),
(2, (SELECT id FROM dish WHERE name = 'Manzana asada con canela y miel'),          'merienda',    NULL),
-- cena
(2, (SELECT id FROM dish WHERE name = 'Salmon a la plancha con ensalada'),         'cena',        NULL),
(2, (SELECT id FROM dish WHERE name = 'Revuelto de esparragos y gambas'),          'cena',        NULL);

-- ── DIET 3: Dieta mediterranea superavit ─────────────────

INSERT INTO `diet_meal` (`diet_id`, `dish_id`, `meal_type`, `day_of_week`) VALUES
-- desayuno
(3, (SELECT id FROM dish WHERE name = 'Porridge de avena con platano y frutos secos'), 'desayuno', NULL),
(3, (SELECT id FROM dish WHERE name = 'Huevos benedictinos con salmon ahumado'),        'desayuno', NULL),
-- media-manana
(3, (SELECT id FROM dish WHERE name = 'Bocadillo de jamon serrano y queso manchego'), 'media-manana', NULL),
(3, (SELECT id FROM dish WHERE name = 'Frutos secos con chocolate negro'),             'media-manana', NULL),
-- almuerzo
(3, (SELECT id FROM dish WHERE name = 'Entrecot de ternera con patatas panaderas'),   'almuerzo',  NULL),
(3, (SELECT id FROM dish WHERE name = 'Lasana de carne boloñesa'),                     'almuerzo',  NULL),
-- merienda
(3, (SELECT id FROM dish WHERE name = 'Bocadillo de atun con huevo duro'),            'merienda',  NULL),
(3, (SELECT id FROM dish WHERE name = 'Natillas caseras con canela'),                 'merienda',  NULL),
-- cena
(3, (SELECT id FROM dish WHERE name = 'Costillas de cerdo al horno con patatas'),    'cena',       NULL),
(3, (SELECT id FROM dish WHERE name = 'Macarrones con queso y bacon'),               'cena',       NULL);

SET FOREIGN_KEY_CHECKS = 1;
