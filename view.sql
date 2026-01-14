CREATE OR REPLACE VIEW productview AS
SELECT products.* , categories.* FROM products
INNER JOIN categories on products.product_cat = categories.categories_id


CREATE OR REPLACE VIEW myfavorite AS
SELECT favorite.* , products.* ,users.users_id FROM favorite
INNER JOIN users ON users.users_id = favorite.favorite_usersid
INNER JOIN products ON products.product_id = favorite.favorite_productid

CREATE OR REPLACE VIEW cartview AS
SELECT SUM(products.product_price - products.product_price * product_discount /100) as productsprice , COUNT(cart_productid) as countproducts , cart.* , products.* FROM cart
INNER JOIN products ON products.product_id = cart.cart_productid
WHERE cart_orders = 0
GROUP BY cart.cart_productid , cart.cart_usersid

-- DtGq_SV+gCa6M
CREATE OR REPLACE VIEW salon AS
SELECT salon_order.* , barbar.* FROM salon_order
INNER JOIN barbar on salon_order.salon_barbar_id = barbar.barbar_id
WHERE salon_reservation > 0
GROUP BY salon_order.order_time

CREATE OR REPLACE VIEW onborderview AS
SELECT categories.* , onborder.* FROM onborder
INNER JOIN categories on  categories.categories_id  = onborder.onb_cat


CREATE OR REPLACE VIEW onborderview AS
SELECT categories.* , home_cat.* FROM categories
INNER JOIN home_cat on  home_cat.ho_ca_id = categories.cat_ho_ca



CREATE OR REPLACE VIEW catview AS
SELECT main_categories.* , categories.* FROM categories
INNER JOIN categories on categories.cat_main = main_categories.main_id
