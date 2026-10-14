        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'Pending',

    PRIMARY KEY (`order_id`),

    KEY `idx_order_customer` (`customer_id`),
    KEY `idx_order_date` (`order_date`),
    KEY `idx_order_status` (`status`),

    CONSTRAINT `fk_order_customer`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`customer_id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT `chk_order_total`
        CHECK (`total_amount` >= 0)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- ORDER ITEMS
--
-- menu_name and unit_price are saved as snapshots.
-- This preserves old order information even if a product's
-- name or price changes later.
-- =========================================================

CREATE TABLE `order_items` (
    `order_item_id` INT NOT NULL AUTO_INCREMENT,
    `order_id` INT NOT NULL,

    `menu_id` INT DEFAULT NULL,

    `menu_name` VARCHAR(100) NOT NULL,

    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (`order_item_id`),

    KEY `idx_order_item_order` (`order_id`),
    KEY `idx_order_item_menu` (`menu_id`),

    CONSTRAINT `fk_order_item_order`
        FOREIGN KEY (`order_id`)
        REFERENCES `orders` (`order_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_order_item_menu`
        FOREIGN KEY (`menu_id`)
        REFERENCES `menu_item` (`menu_id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT `chk_order_item_quantity`
        CHECK (`quantity` > 0),

    CONSTRAINT `chk_order_item_price`
        CHECK (`unit_price` >= 0),

    CONSTRAINT `chk_order_item_subtotal`
        CHECK (`subtotal` >= 0)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- REVIEWS
--
-- Only registered customers can review products.
-- One customer can review each product only once.
-- =========================================================

CREATE TABLE `reviews` (
    `review_id` INT NOT NULL AUTO_INCREMENT,
    `customer_id` INT NOT NULL,
    `menu_id` INT NOT NULL,
    `rating` INT NOT NULL,
    `comment` TEXT DEFAULT NULL,
    `review_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`review_id`),

    KEY `idx_review_customer` (`customer_id`),
    KEY `idx_review_menu` (`menu_id`),

    UNIQUE KEY `unique_customer_review`
        (`customer_id`, `menu_id`),

    CONSTRAINT `fk_review_customer`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`customer_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_review_menu`
        FOREIGN KEY (`menu_id`)
        REFERENCES `menu_item` (`menu_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `chk_review_rating`
        CHECK (`rating` BETWEEN 1 AND 5)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
