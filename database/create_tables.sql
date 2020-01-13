CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_status` varchar(255) NOT NULL DEFAULT "P",

  CONSTRAINT email_unique UNIQUE (`email`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE `reset_tokens` (
   `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `user_id` int(11) NOT NULL,
   `token` varchar(255) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),

  CONSTRAINT token_unique UNIQUE (`token`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE `confirmation_tokens` (
   `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `user_id` int(11) NOT NULL,
   `token` varchar(255) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),

   CONSTRAINT token_unique UNIQUE (`token`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE `order_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(255) NOT NULL,
  `template` text,

  CONSTRAINT type_unique UNIQUE (`type`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  `confirmed` TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`type_id`) REFERENCES `order_types` (`id`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE `order_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(64) NOT NULL,
  `order_id` int(11) NOT NULL,
  `extension` varchar(20) NOT NULL,
  `attachment_path` varchar(255) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB CHARSET=utf8;

INSERT INTO `order_types` (`type`, `template`) VALUES ("Banner", 
"Účel:


Cílová skupina:


Umístění:


Obsah banneru:


");

INSERT INTO `order_types` (`type`, `template`) VALUES ("Vizitky",
"Formát:


Orientace:


Papír:


Zpracování:


Obsah:


Kontaktní informace:


");


INSERT INTO `order_types` (`type`, `template`) VALUES ("Tisková inzerce",
"Předmět inzerce:


Účel:


Umístění:


Obsah:


Vizuální stránka:


");


INSERT INTO `order_types` (`type`, `template`) 
VALUES ("Jiné", 
"Předmět:


Účel:


Umístění:


Popis:


");