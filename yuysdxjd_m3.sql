-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 30 2026 г., 19:28
-- Версия сервера: 8.0.30
-- Версия PHP: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yuysdxjd_m3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `basket`
--

CREATE TABLE `basket` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `amount` int UNSIGNED NOT NULL DEFAULT '0',
  `sum` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `basket`
--

INSERT INTO `basket` (`id`, `user_id`, `amount`, `sum`) VALUES
(37, 3, 1, '500.00');

-- --------------------------------------------------------

--
-- Структура таблицы `basket_item`
--

CREATE TABLE `basket_item` (
  `id` int UNSIGNED NOT NULL,
  `basket_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `amount` int UNSIGNED NOT NULL DEFAULT '0',
  `price` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `sum` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `basket_item`
--

INSERT INTO `basket_item` (`id`, `basket_id`, `product_id`, `amount`, `price`, `sum`) VALUES
(118, 37, 12, 1, '500.00', '500.00');

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `extend` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `title`, `parent_id`, `extend`) VALUES
(1, 'Декоративно-лиственные', 23, NULL),
(2, 'Красивоцветущие', 23, NULL),
(3, 'Ампельные', 23, NULL),
(4, 'Суккуленты и кактусы', 23, NULL),
(11, 'Спатифиллумы', 23, NULL),
(12, 'Хищные растения', 23, NULL),
(13, 'Папоротники', 23, NULL),
(14, 'Фикусы', 23, NULL),
(15, 'Цитрусовые', 23, NULL),
(16, 'Хвойные', 23, NULL),
(17, 'Для дома', 23, 1),
(18, 'Для офиса', 23, 1),
(19, 'Уличные', 23, 1),
(20, 'Напольные', 23, 1),
(21, 'Подвесные', 23, 1),
(22, 'Экзотические', 23, 1),
(23, 'Растения', NULL, NULL),
(24, 'Всё для ухода за растениями', NULL, NULL),
(25, 'Семена', NULL, NULL),
(26, 'Грунты и удобрения', 24, NULL),
(27, 'Средства защиты от вредителей и болезней', 24, NULL),
(28, 'Инструменты и полив', 24, NULL),
(29, 'Кашпо и горшки', 24, NULL),
(30, 'Семена комнатных растений', 25, NULL),
(31, 'Семена цветов', 25, NULL),
(32, 'Семена овощей', 25, NULL),
(33, 'Семена зелени', 25, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `parent_id`, `product_id`, `text`, `user_id`, `created_at`, `updated_at`) VALUES
(6, NULL, 18, 'Похож на волосатую голову', 3, '2026-05-20 10:13:24', NULL),
(7, NULL, 17, 'Пожухлый', 3, '2026-05-20 10:14:16', NULL),
(17, NULL, 12, '11111111122', 3, '2026-05-20 12:24:20', '2026-05-21 12:58:38'),
(27, 17, 12, '3333333344', 8, '2026-05-26 18:34:54', NULL),
(28, 27, 12, '5555555566', 3, '2026-05-26 18:35:56', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `company`
--

CREATE TABLE `company` (
  `id` int UNSIGNED NOT NULL,
  `user_LE_id` int UNSIGNED NOT NULL,
  `approval` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `company`
--

INSERT INTO `company` (`id`, `user_LE_id`, `approval`) VALUES
(2, 4, 1),
(4, 5, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `company_doc`
--

CREATE TABLE `company_doc` (
  `id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `company_doc`
--

INSERT INTO `company_doc` (`id`, `company_id`, `photo`) VALUES
(5, 2, '1779657435_S2Wq8bRgp83rQkdQ3eyMdePZMRTl6i7c.pdf'),
(6, 2, '1779657435_fvD7FGymTltvAUx7ZZUH0HvR-7ETwLF5.pdf');

-- --------------------------------------------------------

--
-- Структура таблицы `company_info`
--

CREATE TABLE `company_info` (
  `company_id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `inn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `person` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `company_info`
--

INSERT INTO `company_info` (`company_id`, `title`, `inn`, `address`, `email`, `person`) VALUES
(2, 'ОАО Цветочки', '12312344', 'где-то за бугром', 'chvetochki@mail.ru', 'Пупкин Василий Иванович'),
(4, 'ОАО Ягодки', '456456', 'за морями за лесами', 'aygodki@mail.ru', 'Кузин Николай Игоревич');

-- --------------------------------------------------------

--
-- Структура таблицы `favorits`
--

CREATE TABLE `favorits` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `favorits`
--

INSERT INTO `favorits` (`id`, `user_id`, `product_id`) VALUES
(30, 3, 17),
(42, 3, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` int UNSIGNED NOT NULL DEFAULT '0',
  `sum` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status_id` int UNSIGNED NOT NULL,
  `pay_type_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order`
--

INSERT INTO `order` (`id`, `user_id`, `created_at`, `amount`, `sum`, `address`, `phone`, `date`, `time`, `status_id`, `pay_type_id`) VALUES
(6, 9, '2026-05-20 09:41:18', 4, '4600.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(111)-111-11-11', '2026-05-21', '12:40:00', 8, 1),
(7, 9, '2026-05-20 09:42:39', 3, '8350.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(111)-111-11-11', '2026-05-27', '17:45:00', 9, 2),
(8, 3, '2026-05-20 09:59:26', 4, '15500.00', 'г. Санкт-Петербург, ул. Гончарная, д. 20, лит. А, кв. 42', '+7(912)-586-46-03', '2026-05-30', '10:00:00', 8, 2),
(9, 3, '2026-05-20 10:01:57', 6, '13150.00', 'г. Санкт-Петербург, ул. Гончарная, д. 20, лит. А, кв. 42', '+7(912)-586-46-03', '2026-06-01', '13:00:00', 7, 3),
(10, 9, '2026-05-20 11:49:36', 4, '18850.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(111)-111-11-11', '2026-05-27', '14:50:00', 8, 2),
(11, 9, '2026-05-20 11:50:02', 2, '3300.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(111)-111-11-11', '2026-05-20', '14:49:00', 6, 1),
(12, 3, '2026-05-20 11:50:53', 4, '7950.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(111)-111-11-11', '2026-05-27', '14:50:00', 8, 3),
(13, 9, '2026-05-26 18:14:12', 1, '11900.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(999)-999-99-99', '2026-05-26', '21:13:00', 9, 2),
(14, 3, '2026-05-27 07:54:24', 5, '6400.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(999)-999-99-99', '2026-06-03', '10:48:00', 6, 1),
(15, 9, '2026-05-30 16:26:22', 2, '4100.00', 'г. Санкт-Петербург, ул. Ленина, д. 2, кв. 45', '+7(111)-111-11-11', '2026-06-01', '15:25:00', 8, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `order_item`
--

CREATE TABLE `order_item` (
  `id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `amount` int UNSIGNED NOT NULL DEFAULT '0',
  `price` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `sum` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `product_id`, `amount`, `price`, `sum`) VALUES
(13, 6, 12, 2, '700.00', '1400.00'),
(15, 7, 14, 1, '450.00', '450.00'),
(16, 7, 15, 1, '5300.00', '5300.00'),
(17, 7, 16, 1, '2600.00', '2600.00'),
(18, 8, 18, 1, '11900.00', '11900.00'),
(19, 8, 17, 3, '1200.00', '3600.00'),
(20, 9, 15, 2, '5300.00', '10600.00'),
(21, 9, 14, 1, '450.00', '450.00'),
(22, 9, 21, 3, '700.00', '2100.00'),
(23, 10, 18, 1, '11900.00', '11900.00'),
(24, 10, 17, 1, '1200.00', '1200.00'),
(25, 10, 14, 1, '450.00', '450.00'),
(26, 10, 15, 1, '5300.00', '5300.00'),
(27, 11, 16, 1, '2600.00', '2600.00'),
(28, 11, 21, 1, '700.00', '700.00'),
(30, 12, 12, 1, '700.00', '700.00'),
(31, 12, 14, 1, '450.00', '450.00'),
(32, 12, 15, 1, '5300.00', '5300.00'),
(33, 13, 18, 1, '11900.00', '11900.00'),
(34, 14, 16, 1, '2600.00', '2600.00'),
(35, 14, 21, 2, '700.00', '1400.00'),
(36, 14, 17, 2, '1200.00', '2400.00'),
(37, 15, 16, 1, '2600.00', '2600.00'),
(38, 15, 22, 1, '1500.00', '1500.00');

-- --------------------------------------------------------

--
-- Структура таблицы `pay_type`
--

CREATE TABLE `pay_type` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `pay_type`
--

INSERT INTO `pay_type` (`id`, `title`) VALUES
(1, 'Наличными'),
(2, 'Банковской картой'),
(3, 'QR-код');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `status_id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `preview` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `care_recommendations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `estimation` decimal(8,1) UNSIGNED NOT NULL DEFAULT '0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `user_id`, `status_id`, `title`, `preview`, `care_recommendations`, `price`, `estimation`) VALUES
(12, 8, 2, 'Кактус', 'Кактус – многолетнее цветущее растение с богатой историей. Слово \"кактус\" происходит от греческого \"мелокактус\" и переводится как \"чертополох\". Это связано с наличием колючек у чертополоха, как и у многих представителей семейства Кактусовые.', 'Домашний кактус нуждается в большом количестве солнечного света, поэтому растение в горшке лучше всего расположить на южной стороне. Температурные условия подойдут умеренные, кактус дома будет хорошо расти при температуре от 18 до 23 градусов. Полив кактуса осуществляют 1-2 раза в месяц. Весной и летом можно подкормить кактус специализированным удобрением. Регулярность подкормок – раз в месяц.', '500', '4.5'),
(14, 8, 2, 'Хавортия', 'Хавортия ретуза (Haworthia Retusa) — суккулент родом из Южной Африки. Растение достигает диаметра до 15 см, имеет треугольные, загнутые назад листья. Листья плотные, сочные и мясистые у основания, шершавые на ощупь. Окраска листьев зелёная, жёлтая, оранжевая, розовая, бордовая, коричневая и чёрная. Листья прозрачные на вершинах с прозрачными прожилками.', 'Хавортия ретуза предпочитает яркое рассеянное освещение и полутень. Рекомендуется размещать растение на западных или восточных окнах. На южном окне необходимо затенение, а на северном возможна потеря яркости листьев. Летом температура воздуха должна быть от +15°C до +20°C, зимой — +10…15°C. Хавортия устойчива к сухому воздуху и не требует опрыскивания, но зимой страдает при высокой температуре и сухости. Полив умеренный или редкий, летом можно вносить слабый раствор удобрения для кактусов. Молодые растения пересаживают каждый год, взрослые — раз в 2–3 года.', '450', '4.5'),
(15, 8, 2, 'Алоказия', 'Алоказия представляет собой декоративное растение, выделяющееся своей уникальной листвой, нижняя часть которых визуально напоминает татуировку. В отличие от традиционного сорта Фридек, характеризующегося одноцветными темно-зелеными листьями, «Тату» обладает изысканным орнаментом из светлых жилок. Этот узор создает эффект тонкой живописи на насыщенном зеленом фоне, что делает растение привлекательным для коллекционеров и ценителей экзотических культур.', 'Полив: Вода должна быть мягкой, отстоянной и подогретой до 25–30°C. \r\nТемпература: Оптимальная температура для алоказии — 22–26°C. Похолодание до 17–18°C недопустимо — куст сбрасывает корни. \r\nСвет: Алоказия любит яркий, но рассеянный свет. Прямые солнечные лучи могут обжечь листья. Лучше всего размещать растение на восточном или западном подоконнике. Зимой, когда естественное освещение менее яркое, цветок можно перемещать на южное окно.', '5300', '4.0'),
(16, 8, 2, 'Орхидея', 'Орхидея Фаленопсис (Phalaenopsis) — это изысканное комнатное растение с элегантными цветами. Фаленопсис родом из тропиков Азии и назван по форме цветка, напоминающей крылья бабочки. ', 'Он требует яркого, но рассеянного освещения, стабильной температуры 20–25 градусов днём и не ниже 15 градусов ночью, высокой влажности и осторожного полива. Для удобрения используйте специальные удобрения для орхидей, пересаживайте после цветения, обрезая мёртвые корни.', '2600', '5.0'),
(17, 10, 2, 'Спатифиллум', 'Спатифиллум , известный как «женское счастье», принадлежит к семейству ароидных. Его название происходит от греческих слов «спата» (покрывало) и «филлум» (лист). Важно помнить, что растение ядовито для домашних животных. В его листьях содержатся несъедобные волокна, которые могут вызвать у собак и кошек проблемы с желудком, диарею или даже смерть.', 'Свет: Предпочитает рассеянный свет. Лучшее место — восточные или западные окна, где достаточно естественного освещения. Можно поставить растение и у северных окон: оно достаточно теневыносливое. Температура: Хорошо себя чувствует при 18–25 °C. Если в помещении слишком жарко, грунт будет просыхать быстрее, и тогда поливать растение нужно чаще. Полив: Нужно поливать по мере просыхания грунта. Одновременно не следует допускать пересыхания земляного кома.', '1200', '3.5'),
(18, 10, 2, 'Рипсалис', 'Рипсалис, также известен как «лесной кактус» или «прутовик» — род эпифитных кустарничков семейства Кактусовые. ', 'Свет: предпочитает яркий, но рассеянный свет. Горшок с растением лучше поставить на восточный или западный подоконник. Если окна выходят на юг, то его лучше убрать в глубину комнаты. Полив: Регулярный, но умеренный полив. Почва должна оставаться слегка влажной, но не сырой.', '11900', '4.5'),
(21, 8, 3, 'Кактус', 'Кактус – многолетнее цветущее растение с богатой историей. Слово \"кактус\" происходит от греческого \"мелокактус\" и переводится как \"чертополох\". Это связано с наличием колючек у чертополоха, как и у многих представителей семейства Кактусовые.', 'Домашний кактус нуждается в большом количестве солнечного света, поэтому растение в горшке лучше всего расположить на южной стороне. Температурные условия подойдут умеренные, кактус дома будет хорошо расти при температуре от 18 до 23 градусов. Полив кактуса осуществляют 1-2 раза в месяц. Весной и летом можно подкормить кактус специализированным удобрением. Регулярность подкормок – раз в месяц.', '700', '4.0'),
(22, 8, 2, 'Венерина мухоловка', 'Ест мух и комариков', 'Кормите ее мухами ', '1500', '5.0'),
(30, 8, 3, 'Кактус', 'Колючий jhzuidbhzo;d  n;zkbjd n;zkbjd n;zkbjdn;zkbjd n;zkbjd n;zkbjd n;zkbjd  n;zkbjd \r\nn;zkbjdn;zkbjd n;zkbjd n;zkbjdn;zkbjdn;zkbjd n;zkbjd n;zkbjd', 'Поливать хотя бы раз в месяц', '250', '0.0');

-- --------------------------------------------------------

--
-- Структура таблицы `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(15, 1),
(18, 1),
(16, 2),
(12, 4),
(14, 4),
(30, 4),
(17, 11),
(22, 12),
(12, 17),
(14, 17),
(15, 17),
(17, 17),
(22, 17),
(30, 17),
(12, 18),
(14, 18),
(15, 18),
(17, 18),
(30, 18),
(15, 20),
(17, 20),
(18, 21);

-- --------------------------------------------------------

--
-- Структура таблицы `product_image`
--

CREATE TABLE `product_image` (
  `id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product_image`
--

INSERT INTO `product_image` (`id`, `product_id`, `photo`) VALUES
(35, 14, '1774286360_rxZEmzs-_NaY_3bbYgokNN9eAI-KBMS_.jpg'),
(36, 14, '1774286360_-WwHPsfOhGylDoSiyVbsUSUe_UkrB_PT.jpg'),
(37, 14, '1774286360_HWEonNizTUezR97aI7MaWmcM5ehpyX5f.jpg'),
(38, 15, '1774286785_LS8ItOtthQmbTOp3z335T-v3cYshc-sx.jpg'),
(39, 15, '1774286785_NyUJpebmVCQNO_jioK1OH4AKZUjvIy_f.jpg'),
(40, 15, '1774286785_XkYuhUj3t6gAMZWFz1LNMLkTux6WJVb-.jpg'),
(41, 16, '1774287063_jRd0oG2SKsQ5yEBri_2-GQHHwl5AKKSG.jpg'),
(42, 16, '1774287063_P3LwcZWVrsLzF5DLvxGsWCQgJyW_Cp0v.jpg'),
(43, 16, '1774287063_qGGeMifKnCv2YeVzDZrfohxcMw57vJpR.jpg'),
(44, 16, '1774287063_olsQdR_cOnGcNiQfvMP2mGAsp-x6ACbZ.jpg'),
(45, 17, '1774290946_wEID3TeXcVXN9Ysl-nZbYkNY9exteGDB.jpg'),
(46, 17, '1774290946_g3nDbSbgk_jSsfJSjDIXf7SPWAyQrLnn.jpg'),
(47, 17, '1774290946_mOFkIlcwnNJqg06ZG2yyq19ZER5d6eVI.jpg'),
(48, 17, '1774290946_KC_JkCRgTTD4bpubjZ1RreGfpxBVNlTQ.jpg'),
(49, 18, '1774291311_n21_nTYCmPLyD64YAd8Vhaqow4i6DkY4.jpg'),
(50, 18, '1774291311_eFU32R9c6CempwQeNn9ZFYiwIwXyqpSb.jpg'),
(51, 18, '1774291311_in513gJnYuZLaCZvW_nbgbbKN6fzJTRm.jpg'),
(52, 21, '1778507455_HQhHoXpjEdB48hrbMhFbSlOExBIDAjAk.png'),
(53, 21, '1778507455_PwuSxFt9YhSdVMvUwPDLLKdvc5JgQ0L7.png'),
(56, 12, '1779088943_82L1BmIqH4VRde2Gj5Nx-ySHD9YfT6U7.png'),
(57, 12, '1779088943_EpYLFycjuAj4i45g25_J9VVDGUgjm1P6.png'),
(61, 22, '1779820680__AxLNS2x1xSAI3iMmu89VH8b8Rq9rYLQ.jpg'),
(62, 22, '1779820680_joWAOxKpyaREyNVx4t08Y_Q70xzKblFO.jpg'),
(71, 30, '1780047998_neZorjTG3tFkLj6ya1DzNaqCt92j5ntC.png'),
(72, 30, '1780047998_tKh94b4I2YkNysNfXcxJJettirKIrcdp.png');

-- --------------------------------------------------------

--
-- Структура таблицы `rating`
--

CREATE TABLE `rating` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `estimation` decimal(8,1) UNSIGNED NOT NULL DEFAULT '0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `rating`
--

INSERT INTO `rating` (`id`, `user_id`, `product_id`, `estimation`) VALUES
(32, 3, 18, '5.0'),
(33, 3, 17, '2.0'),
(35, 9, 12, '5.0'),
(37, 3, 12, '4.0'),
(40, 3, 15, '3.0'),
(41, 9, 18, '4.0'),
(42, 9, 17, '5.0'),
(43, 9, 15, '5.0'),
(44, 9, 14, '4.0'),
(47, 3, 14, '5.0'),
(49, 9, 16, '5.0'),
(50, 9, 22, '5.0');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id`, `title`, `alias`) VALUES
(1, 'На рассмотрении', 'check'),
(2, 'В продаже', 'on sale'),
(3, 'Архивирован', 'arhived'),
(6, 'Новый', 'new'),
(7, 'Передан в доставку', 'in delivery'),
(8, 'Доставлен', 'finished'),
(9, 'Отменен', 'canceled');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `patronymic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` tinyint NOT NULL DEFAULT '0',
  `auth_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `patronymic`, `login`, `password`, `email`, `phone`, `role`, `auth_key`) VALUES
(3, 'Иван', 'Иванов', 'Иванович', 'ivan', '$2y$13$yosFMW6TwKbtOV/KLouDXODssbOlObDN2oGB0pGGA6Uikb.Bp8JLK', 'i@i.ru', '+79998887766', 0, 'LPeof9Zy0g1hdztAwnkHa9v_0t6Rknzt'),
(4, 'admin', 'admin', 'admin', 'admin', '$2y$13$VETGcRkTiKmxAh6MbF21e.11HaLaKHoyUTyd9YM12k73DigAKRXsm', 'admin@admin.ru', '+79999999999', 1, '0Wk-K6VlgHw43wzZwQ837MKnlymWzEuk'),
(8, 'a', 'a', 'a', 'q', '$2y$13$kYijozC2qKBzsC41Mae15ukJT1eR8tOt0udqTJs/FvhOhh.VOVtoe', 'a', 'a', 0, '22B1Y61eFG2AYWiDdmBZLDsiMTpV4ZSQ'),
(9, 'Александр', 'Александров', 'Александрович', 'alex', '$2y$13$9VtdH3URQeODxFLRBywGcOBMW/2mj0k0.893gmmCFK526u9mtE.Jq', 'a@a.ru', '+71112223344', 0, 'j5rznHvoWnBdhuogeuqVZMok_ZXzGrHp'),
(10, 'q', 'q', 'q', 'a', '$2y$13$ZQQuWXLxaX.tKCozI1L/HO.cPSVu1NTKL.Vl/Wh9qLyK7G2QIV2hm', 'q', 'q', 0, 'KgP9n50wztAH9WzjfnC53mJy7bkWx5D6'),
(14, 'w', 'w', 'w', 'w', '$2y$13$q.xBJVHKVrqiTex.YZJZS.b/kzC71oJNQFClR1lq/NLHR19hO/hdO', 'w@w.ru', 'w', 0, 'RxVHi5kBlSuiZxtSnjzyQQ7BJFj_-eHf');

-- --------------------------------------------------------

--
-- Структура таблицы `user_doc`
--

CREATE TABLE `user_doc` (
  `id` int UNSIGNED NOT NULL,
  `user_LE_id` int UNSIGNED NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user_doc`
--

INSERT INTO `user_doc` (`id`, `user_LE_id`, `photo`) VALUES
(3, 7, '1779696464_I5HLFbfVoas3asHzYevqhk4eHet1O9uD.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `user_LE`
--

CREATE TABLE `user_LE` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `inn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `snils` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `shop_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `approval` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user_LE`
--

INSERT INTO `user_LE` (`id`, `user_id`, `inn`, `snils`, `shop_title`, `approval`) VALUES
(4, 8, '1', '1', 'shop', 1),
(5, 10, '-', '-', 'q', 1),
(7, 14, 'w', 'w', 'w', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `basket_item`
--
ALTER TABLE `basket_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basket_id` (`basket_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Индексы таблицы `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_LE_id` (`user_LE_id`);

--
-- Индексы таблицы `company_doc`
--
ALTER TABLE `company_doc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Индексы таблицы `company_info`
--
ALTER TABLE `company_info`
  ADD UNIQUE KEY `company_id` (`company_id`);

--
-- Индексы таблицы `favorits`
--
ALTER TABLE `favorits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `pay_type_id` (`pay_type_id`);

--
-- Индексы таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `pay_type`
--
ALTER TABLE `pay_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Индексы таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `user_doc`
--
ALTER TABLE `user_doc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_LE_id`);

--
-- Индексы таблицы `user_LE`
--
ALTER TABLE `user_LE`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `basket`
--
ALTER TABLE `basket`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `basket_item`
--
ALTER TABLE `basket_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `company`
--
ALTER TABLE `company`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `company_doc`
--
ALTER TABLE `company_doc`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `favorits`
--
ALTER TABLE `favorits`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `pay_type`
--
ALTER TABLE `pay_type`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT для таблицы `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `user_doc`
--
ALTER TABLE `user_doc`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user_LE`
--
ALTER TABLE `user_LE`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `basket_item`
--
ALTER TABLE `basket_item`
  ADD CONSTRAINT `basket_item_ibfk_1` FOREIGN KEY (`basket_id`) REFERENCES `basket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `basket_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`user_LE_id`) REFERENCES `user_LE` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `company_doc`
--
ALTER TABLE `company_doc`
  ADD CONSTRAINT `company_doc_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `company_info`
--
ALTER TABLE `company_info`
  ADD CONSTRAINT `company_info_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorits`
--
ALTER TABLE `favorits`
  ADD CONSTRAINT `favorits_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_5` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_6` FOREIGN KEY (`pay_type_id`) REFERENCES `pay_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `product_image_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_doc`
--
ALTER TABLE `user_doc`
  ADD CONSTRAINT `user_doc_ibfk_1` FOREIGN KEY (`user_LE_id`) REFERENCES `user_LE` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_LE`
--
ALTER TABLE `user_LE`
  ADD CONSTRAINT `user_LE_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
