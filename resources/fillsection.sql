INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (0, 'Бутерброды', 1);
INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (0, 'Супы', 2);
INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (0, 'Закуски', 3);
INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (2, 'Итальянские супы', 4);
INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (2, 'Еще навороченные супы', 5);
INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (2, 'Самые дерзкие супы', 6);
INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (3, 'Офигительные закуски', 7);
INSERT INTO `sections`( `parent_id`, `section_name`, `description_id`) VALUES (3, 'Закуски не менее офигительные', 8);
INSERT INTO `objects`(
    `object_name`, 
    `price`, 
    `short_desk`, 
    `section_id`, 
    `description_id`
    ) VALUES (
        'Бутерброд с вензельками сочный вкусный',
        1000,
        'попробуй очень крутой бутерброд',
        1,
        1);
INSERT INTO `objects`(
    `object_name`, 
    `price`, 
    `short_desk`, 
    `section_id`, 
    `description_id`
    ) VALUES (
        'Бутерброд с вензельками сочный еще больше вкусный',
        1000,
        'попробуй очень крутой бутерброд',
        1,
        1);
INSERT INTO `objects`(
    `object_name`, 
    `price`, 
    `short_desk`, 
    `section_id`, 
    `description_id`
    ) VALUES (
        'Суп с вензельками сочный вкусный',
        1000,
        'попробуй очень крутой суп',
        2,
        2);
INSERT INTO `objects`(
    `object_name`, 
    `price`, 
    `short_desk`, 
    `section_id`, 
    `description_id`
    ) VALUES (
        'Настолько особый бутерброд что аж на первой странице',
        1000,
        'попробуй очень крутой бутерброд',
        0,
        1);
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `descriptions`(`description`) VALUES ('Очень длинное описание, быть может html');
INSERT INTO `comments`(`user_id`,`comment`) VALUES (1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');