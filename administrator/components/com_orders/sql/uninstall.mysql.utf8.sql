DROP TABLE IF EXISTS `#__orders_orders`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_orders.%');