DROP TABLE IF EXISTS fb_orderitems;
DROP TABLE IF EXISTS fb_order;
DROP TABLE IF EXISTS fb_product;
DROP TABLE IF EXISTS fb_customer;
DROP TABLE IF EXISTS fb_newsletter;

CREATE TABLE fb_newsletter (
    email VARCHAR(255) PRIMARY KEY
);

CREATE TABLE fb_customer (
    email VARCHAR(255) PRIMARY KEY, 
    fname VARCHAR(100), 
    sname VARCHAR(100), 
    postcode VARCHAR(7), 
    pass VARCHAR(41)
);

CREATE TABLE fb_product (
    pid INT AUTO_INCREMENT PRIMARY KEY , 
    name VARCHAR(100), 
    description TEXT,
    imagepath VARCHAR(100),
    price DECIMAL(10, 2)
);

CREATE TABLE fb_order (
    oid INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    FOREIGN KEY (email) REFERENCES fb_customer(email)
);

CREATE TABLE fb_orderitems (
    oid INT,
    pid INT,
    qty INT,
    PRIMARY KEY (oid, pid),
    FOREIGN KEY (oid) REFERENCES fb_order(oid),
    FOREIGN KEY (pid) REFERENCES fb_product(pid)
);

INSERT INTO fb_product VALUES
    (NULL, "Nike Magista Obra Firm Ground", "With an unprecedented fit, touch and traction you will be a step ahead of the opponents. With the upper made from Nike Flyknit and topped with a Dynamic Fit collar you get a snug, sock-like comfort.", "Images/magista.jpg", 230.00),
    (NULL, "Adidas Predator Firm Ground", "Be the Best of the Best on the pitch with these men's Predator Firm Ground football boots from adidas! A must-have for any footballer, these statement boots are crafted with a solar green and rich blue upper for exceptional flexibility", "Images/predator.jpg", 160.00),
    (NULL, "Nike Mercurial Vortex Astra Turf", "With rubber cleats and a micro-textured upper for optimal acceleration, without sacrificing touch. ", "Images/Mercurial.jpg", 45.00),
    (NULL, "Adidas Haters Pack Predito Instinct Firm Ground", "TRAXION FG studs specifically designed for use on firm ground surfaces and a synthetic BRAVO upper.", "images/Haters_Pack.jpg", 40.00),
    (NULL, "Puma EvoPOWER 3.2 Firm Ground", "The supersoft upper construction has been designed to deliver power and accuracy to allow for a barefoot kicking motion while the GripTex technology across the upper enhances grip in all weather conditions.", "images/puma.jpg", 60.00);