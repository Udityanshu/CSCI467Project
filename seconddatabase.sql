drop table if exists inventory;
drop table if exists orders;
drop table if exists customer;
drop table if exists orderedItems;

CREATE TABLE inventory (
    id int auto_increment,
    quantity int,
    PRIMARY KEY (id)
);

CREATE TABLE orders (
    id int auto_increment,
    customerId int,
    orderStatus int,
    combinedWeight decimal(5,2),
    shippingCharges decimal(5,2),
    totalPrice decimal(5,2),
    PRIMARY KEY (id)
);

CREATE TABLE orderedItems(
    orderId int,
    quantity int,
    productId int,
    PRIMARY KEY (orderId, productId)
);

CREATE TABLE customer (
    id int auto_increment,
    name varchar(30),
    email varchar(30),
    mailingAddress varchar(30),
    creditCardNumber int,
    expirationDate date,
    PRIMARY KEY (id)
);

ALTER TABLE orders add FOREIGN KEY (customerId) REFERENCES customer(Id);
ALTER TABLE orderedItems add FOREIGN KEY (orderId) REFERENCES orders(id);
