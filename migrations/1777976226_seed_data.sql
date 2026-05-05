-- =============================================================
--  Seed de datos: Nuvia
--  Usuario de prueba: admin / nuvia1234
-- =============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- Categorías de rutina
-- -------------------------------------------------------------
INSERT INTO `routine_category` (`id`, `name`, `description`) VALUES
(1, 'Fuerza',        'Rutinas orientadas al desarrollo de la fuerza muscular'),
(2, 'Cardio',        'Rutinas de resistencia cardiovascular'),
(3, 'En casa',       'Rutinas realizables sin equipamiento especifico'),
(4, 'En gym',        'Rutinas que requieren equipamiento de gimnasio'),
(5, 'Tren superior', 'Trabajo de pecho, hombros, espalda y brazos'),
(6, 'Tren inferior', 'Trabajo de cuadriceps, isquiotibiales y gluteos'),
(7, 'Core',          'Fortalecimiento del nucleo y zona abdominal');

-- -------------------------------------------------------------
-- Ejercicios
-- -------------------------------------------------------------
INSERT INTO `exercise` (`id`, `name`, `description`, `intensity`) VALUES
( 1, 'Marcha en el sitio',               'Paso elevado de rodillas en el sitio sin desplazamiento',             'baja'),
( 2, 'Step lateral',                     'Paso lateral con elevacion de rodilla alternada',                     'baja'),
( 3, 'Elevacion de rodillas alternas',   'Elevacion alternada de rodillas al pecho de pie',                    'media'),
( 4, 'Patada de gluteo en el sitio',     'Llevar el talon al gluteo de forma alternada corriendo en el sitio', 'baja'),
( 5, 'Sentadilla con toque de talones',  'Sentadilla completa tocando los talones al subir',                   'media'),
( 6, 'Cinta de correr',                  'Carrera o marcha a ritmo moderado en cinta',                         'media'),
( 7, 'Bicicleta estatica',               'Pedaleo continuo en bicicleta estatica',                             'media'),
( 8, 'Eliptica',                         'Movimiento eliptico completo en maquina',                            'media'),
( 9, 'Remo ergometrico',                 'Remo continuo en maquina ergometrica',                               'alta'),
(10, 'Escaladora',                       'Simulacion de escalada en maquina stairmaster',                      'alta'),
(11, 'Flexiones de pecho',               'Push-up clasico con manos a anchura de hombros',                     'media'),
(12, 'Flexiones diamante',               'Push-up con manos juntas, enfasis en triceps',                       'media'),
(13, 'Pike push-up',                     'Flexion en posicion de pica, enfasis en hombros',                    'alta'),
(14, 'Dips en silla',                    'Fondos con manos apoyadas en una silla, trabajo de triceps',         'media'),
(15, 'Flexiones con manos elevadas',     'Push-up con manos en superficie elevada, mayor rango de pecho',      'media'),
(16, 'Press de banca con barra',         'Empuje horizontal con barra en banco plano',                         'alta'),
(17, 'Press militar con mancuernas',     'Empuje vertical sobre la cabeza con mancuernas',                     'alta'),
(18, 'Fondos en paralelas',              'Dips en barras paralelas para pecho y triceps',                      'alta'),
(19, 'Extension de triceps en polea',    'Extension de codo en polea alta con agarre de cuerda',               'media'),
(20, 'Aperturas con mancuernas',         'Fly en banco plano con mancuernas para pecho',                       'media'),
(21, 'Sentadilla con peso corporal',     'Sentadilla libre sin peso adicional',                                 'media'),
(22, 'Zancada alternada',                'Lunges alternos caminando',                                          'media'),
(23, 'Puente de gluteos',                'Elevacion de cadera en decubito supino',                             'baja'),
(24, 'Sentadilla bulgara',               'Sentadilla unilateral con pie trasero elevado',                      'alta'),
(25, 'Elevacion de talones en pie',      'Calf raise de pie apoyado en pared',                                 'baja'),
(26, 'Sentadilla con barra',             'Sentadilla libre con barra en espalda',                              'alta'),
(27, 'Prensa de piernas',                'Empuje de plataforma en maquina de prensa inclinada',                'alta'),
(28, 'Peso muerto rumano con barra',     'Bisagra de cadera con barra, enfasis en isquiotibiales',             'alta'),
(29, 'Extension de cuadriceps maquina',  'Extension de rodilla en maquina de cuadriceps',                      'media'),
(30, 'Curl femoral en maquina',          'Flexion de rodilla en maquina de isquiotibiales',                    'media'),
(31, 'Remo con barra',                   'Remo horizontal con barra en pronacion',                             'alta'),
(32, 'Remo con mancuerna',               'Remo unilateral apoyado en banco con mancuerna',                     'media'),
(33, 'Jalon al pecho en polea',          'Jalon vertical al pecho en polea alta con agarre amplio',            'alta'),
(34, 'Curl de biceps con barra',         'Curl bilateral con barra recta o z',                                 'media'),
(35, 'Curl martillo con mancuerna',      'Curl en posicion neutra con mancuerna',                              'media'),
(36, 'Remo invertido con mesa',          'Remo invertido bajo una mesa resistente',                            'media'),
(37, 'Superman',                         'Extension simultanea de brazos y piernas en decubito prono',         'baja'),
(38, 'Curl de biceps con banda elastica','Curl bilateral con banda de resistencia',                            'media'),
(39, 'Good morning sin peso',            'Bisagra de cadera sin carga',                                        'baja'),
(40, 'Face pull con banda elastica',     'Jalon horizontal a la cara con banda elastica',                      'media'),
(41, 'Burpees',                          'Sentadilla, plancha, flexion y salto encadenados',                   'alta'),
(42, 'Mountain climbers',                'Alternancia rapida de rodillas al pecho en posicion de plancha',     'alta'),
(43, 'Saltos con tijera',                'Jumping jacks: saltos con apertura y cierre de extremidades',        'media'),
(44, 'Sentadillas con salto',            'Jump squats: sentadilla con impulso explosivo hacia arriba',         'alta'),
(45, 'Plancha con toque de hombros',     'Plancha con toques alternos de hombro contrario',                    'media'),
(46, 'Peso muerto con barra',            'Levantamiento de barra desde el suelo, cadena posterior completa',   'alta'),
(47, 'Press militar con barra',          'Empuje vertical sobre la cabeza con barra olimpica',                 'alta'),
(48, 'Plancha frontal',                  'Isometria de core en posicion de plancha sobre codos',               'media'),
(49, 'Crunch abdominal',                 'Elevacion de hombros en decubito supino',                            'baja'),
(50, 'Rotacion de cadera en suelo',      'Rotacion controlada de cadera tumbado boca arriba',                  'baja'),
(51, 'Estiramiento de isquiotibiales',   'Estiramiento pasivo sentado con pierna extendida',                   'baja'),
(52, 'Estiramiento cadena posterior',    'Flexion de tronco de pie para estirar cadena posterior',             'baja'),
(53, 'Carrera continua',                 'Carrera a ritmo aerobico constante',                                 'media'),
(54, 'Series de velocidad',              'Sprints cortos con recuperacion activa entre series',                 'alta'),
(55, 'Carrera en cuesta',                'Carrera ascendente en pendiente pronunciada',                        'alta'),
(56, 'Cambios de ritmo',                 'Fartlek: alternancia de ritmo suave y acelerado',                    'media');

-- -------------------------------------------------------------
-- Rutinas
-- -------------------------------------------------------------
INSERT INTO `routine` (`id`, `name`, `description`, `duration_minutes`) VALUES
( 1, 'Cardio bajo impacto en casa',
     'Sesion cardiovascular de bajo impacto articular, ideal para principiantes o dias de recuperacion activa', 40),
( 2, 'Cardio en gym',
     'Circuito de maquinas cardiovasculares para mejorar la resistencia aerobica', 50),
( 3, 'Fuerza en casa - tren superior (pecho, hombro, triceps)',
     'Trabajo de pecho, hombros y triceps sin equipamiento mediante ejercicios calistenicos', 45),
( 4, 'Fuerza en gym - tren superior (pecho, hombro, triceps)',
     'Sesion de hipertrofia de pecho, hombros y triceps con barra y mancuernas', 60),
( 5, 'Fuerza en casa - tren inferior (cuadriceps, isquiotibiales, gluteos)',
     'Trabajo de piernas y gluteos sin equipamiento con ejercicios de peso corporal', 45),
( 6, 'Fuerza en gym - tren inferior (cuadriceps, isquiotibiales, gluteos)',
     'Sesion de fuerza e hipertrofia de piernas con maquinas y barra libre', 65),
( 7, 'Fuerza en gym - espalda y biceps',
     'Sesion de espalda y biceps con jalones, remos y curls con barra y mancuernas', 60),
( 8, 'Fuerza en casa - espalda y biceps',
     'Trabajo de espalda y biceps sin equipamiento usando el propio cuerpo y bandas elasticas', 40),
( 9, 'HIIT en casa',
     'Entrenamiento intervalico de alta intensidad sin equipamiento, quema calorica maxima en poco tiempo', 30),
(10, 'Full body en gym',
     'Rutina de cuerpo completo con los movimientos basicos: sentadilla, press, peso muerto y remo', 75),
(11, 'Core y movilidad en casa',
     'Fortalecimiento del nucleo y movilidad articular, ideal como complemento o dia de descanso activo', 30),
(12, 'Cardio resistencia en exterior',
     'Sesion de carrera al aire libre con variantes de intensidad para mejorar la resistencia aerobica', 60);

-- -------------------------------------------------------------
-- Rutina - Categoria
-- -------------------------------------------------------------
INSERT INTO `routine_has_category` (`routine_id`, `category_id`) VALUES
(1, 2), (1, 3),
(2, 2), (2, 4),
(3, 1), (3, 3), (3, 5),
(4, 1), (4, 4), (4, 5),
(5, 1), (5, 3), (5, 6),
(6, 1), (6, 4), (6, 6),
(7, 1), (7, 4), (7, 5),
(8, 1), (8, 3), (8, 5),
(9, 2), (9, 3),
(10, 1), (10, 2), (10, 4),
(11, 7), (11, 3),
(12, 2);

-- -------------------------------------------------------------
-- Rutina - Ejercicio  (sets, reps, rest_seconds, order_index)
-- -------------------------------------------------------------
INSERT INTO `routine_has_exercise` (`routine_id`, `exercise_id`, `sets`, `reps`, `rest_seconds`, `order_index`) VALUES
(1,  1, 3, NULL, 30, 1),
(1,  2, 3,   20, 30, 2),
(1,  3, 3,   20, 30, 3),
(1,  4, 3,   20, 30, 4),
(1,  5, 3,   15, 45, 5),
(2,  6, 1, NULL, NULL, 1),
(2,  7, 1, NULL, NULL, 2),
(2,  8, 1, NULL, NULL, 3),
(2,  9, 1, NULL,   60, 4),
(2, 10, 1, NULL,   60, 5),
(3, 11, 4,   12, 60, 1),
(3, 12, 3,   10, 60, 2),
(3, 13, 3,   10, 75, 3),
(3, 14, 3,   12, 60, 4),
(3, 15, 3,   12, 60, 5),
(4, 16, 4,   10,  90, 1),
(4, 17, 3,   10,  90, 2),
(4, 18, 3,   10,  90, 3),
(4, 19, 3,   12,  60, 4),
(4, 20, 3,   12,  60, 5),
(5, 21, 4,   15, 60, 1),
(5, 22, 3,   12, 60, 2),
(5, 23, 3,   15, 45, 3),
(5, 24, 3,   10, 75, 4),
(5, 25, 3,   20, 45, 5),
(6, 26, 4,    8, 120, 1),
(6, 27, 4,   10,  90, 2),
(6, 28, 3,   10, 120, 3),
(6, 29, 3,   12,  60, 4),
(6, 30, 3,   12,  60, 5),
(7, 31, 4,    8, 120, 1),
(7, 32, 3,   10,  90, 2),
(7, 33, 4,   10,  90, 3),
(7, 34, 3,   10,  60, 4),
(7, 35, 3,   12,  60, 5),
(8, 36, 4,   10, 75, 1),
(8, 37, 3,   12, 45, 2),
(8, 38, 3,   15, 60, 3),
(8, 39, 3,   12, 45, 4),
(8, 40, 3,   15, 45, 5),
(9, 41, 4,   10, 30, 1),
(9, 42, 4,   20, 30, 2),
(9, 43, 4,   20, 20, 3),
(9, 44, 4,   15, 30, 4),
(9, 45, 3,   20, 30, 5),
(10, 46, 4,   5, 180, 1),
(10, 26, 4,   6, 150, 2),
(10, 16, 4,   8, 120, 3),
(10, 31, 4,   8, 120, 4),
(10, 47, 3,   8, 120, 5),
(11, 48, 3, NULL, 45, 1),
(11, 49, 3,   15, 30, 2),
(11, 50, 3,   10, 30, 3),
(11, 51, 2, NULL, 20, 4),
(11, 52, 2, NULL, 20, 5),
(12, 53, 1, NULL, NULL, 1),
(12, 54, 5, NULL,   90, 2),
(12, 55, 4, NULL,   60, 3),
(12, 56, 1, NULL, NULL, 4);

-- -------------------------------------------------------------
-- Platos
-- -------------------------------------------------------------
INSERT INTO `dish` (`id`, `name`, `ingredients`, `calories_per_serving`) VALUES
( 1, 'Gazpacho andaluz',
     'Tomate, pepino, pimiento, ajo, aceite de oliva, vinagre', 80),
( 2, 'Ensalada verde con atun',
     'Lechuga, tomate, atun al natural, aceitunas, aceite de oliva', 150),
( 3, 'Pechuga de pollo a la plancha',
     'Pechuga de pollo, ajo, limon, aceite de oliva, hierbas provenzales', 180),
( 4, 'Crema de verduras',
     'Calabacin, zanahoria, patata, cebolla, caldo de verduras', 120),
( 5, 'Merluza al vapor con verduras',
     'Merluza, brocoli, zanahoria, aceite de oliva, limon', 130),
( 6, 'Yogur desnatado con fruta',
     'Yogur desnatado, fresas, arandanos, kiwi', 90),
( 7, 'Sopa de verduras casera',
     'Puerro, zanahoria, apio, guisantes, caldo de pollo', 110),
( 8, 'Tostadas de jamon serrano con tomate y aceite',
     'Pan tostado, jamon serrano, tomate rallado, aceite de oliva virgen extra', 280),
( 9, 'Tortilla de patatas',
     'Huevo, patata, cebolla, aceite de oliva', 300),
(10, 'Lentejas estofadas',
     'Lentejas, chorizo, zanahoria, cebolla, pimiento, ajo, laurel', 280),
(11, 'Merluza a la vasca',
     'Merluza, almejas, esparragos, ajo, perejil, aceite de oliva', 250),
(12, 'Ensalada de frutos secos y queso de cabra',
     'Lechuga variada, queso de cabra, nueces, pasas, aceite de oliva, miel', 350),
(13, 'Pollo al ajillo',
     'Pollo troceado, ajo, vino blanco, aceite de oliva, perejil', 300),
(14, 'Pan con tomate y aceite',
     'Pan de cristal, tomate, aceite de oliva virgen extra, sal', 200),
(15, 'Yogur natural con nueces y miel',
     'Yogur natural, nueces, miel', 250),
(16, 'Huevos revueltos con champinones',
     'Huevo, champinones, ajo, perejil, aceite de oliva', 280),
(17, 'Fabada asturiana',
     'Fabes, chorizo asturiano, morcilla, lacon, azafran, laurel', 520),
(18, 'Paella valenciana',
     'Arroz, pollo, conejo, judia verde, garrofon, tomate, azafran', 450),
(19, 'Cocido madrileno',
     'Garbanzos, ternera, pollo, chorizo, morcilla, tocino, patata, verduras', 600),
(20, 'Croquetas de jamon serrano',
     'Jamon serrano, leche entera, harina, mantequilla, pan rallado, huevo', 420),
(21, 'Huevos rotos con jamon y patatas',
     'Huevo frito, jamon iberico, patatas fritas, aceite de oliva', 480),
(22, 'Arroz con leche cremoso',
     'Arroz, leche entera, azucar, canela, limon', 320),
(23, 'Bocadillo de tortilla espanola',
     'Pan de barra, tortilla de patatas, tomate', 450),
(24, 'Escalivada con anchoas sobre pan',
     'Berenjena, pimiento rojo, cebolla, anchoas, pan tostado, aceite de oliva', 400);

-- -------------------------------------------------------------
-- Dietas
-- -------------------------------------------------------------
INSERT INTO `diet` (`id`, `name`, `description`, `total_daily_calories`, `goal`, `diet_type`) VALUES
(1, 'Dieta mediterranea deficit',
   'Plan mediterraneo hipocalorico para reduccion de grasa corporal',
   1500, 'perder', 'mediterranea'),
(2, 'Dieta mediterranea mantenimiento',
   'Plan mediterraneo normocalorico para mantener el peso actual',
   2000, 'mantener', 'mediterranea'),
(3, 'Dieta mediterranea superavit',
   'Plan mediterraneo hipercalorico para ganancia de masa muscular',
   2700, 'ganar', 'mediterranea');

-- -------------------------------------------------------------
-- Dieta - Plato  (meal_type: desayuno | media-manana | almuerzo | merienda | cena)
-- -------------------------------------------------------------
INSERT INTO `diet_meal` (`diet_id`, `dish_id`, `meal_type`, `day_of_week`) VALUES
(1,  6, 'desayuno',      NULL),
(1,  1, 'media-manana',  NULL),
(1,  5, 'almuerzo',      NULL),
(1,  7, 'merienda',      NULL),
(1,  3, 'cena',          NULL),
(2,  8, 'desayuno',      NULL),
(2, 15, 'media-manana',  NULL),
(2, 10, 'almuerzo',      NULL),
(2, 14, 'merienda',      NULL),
(2, 11, 'cena',          NULL),
(3, 23, 'desayuno',      NULL),
(3, 20, 'media-manana',  NULL),
(3, 19, 'almuerzo',      NULL),
(3, 22, 'merienda',      NULL),
(3, 17, 'cena',          NULL);

-- -------------------------------------------------------------
-- Usuario de prueba  (contrasena: nuvia1234)
-- -------------------------------------------------------------
INSERT INTO `user` (`name`, `username`, `email`, `password`, `height_cm`, `birthdate`, `sex`, `activity_level`, `goal`, `default_diet_id`, `default_routine_id`, `is_admin`) VALUES
('Admin Nuvia', 'admin', 'admin@nuvia.app',
 '$2y$10$CIhORsjUAd5.xLn5BEe1.OvYb5RmgK4N0zyMRw5gr9J/pBdz0fX7.',
 175, '1995-06-15', 'masculino', 'moderado', 'maintain', 2, 2, TRUE);

SET FOREIGN_KEY_CHECKS = 1;
