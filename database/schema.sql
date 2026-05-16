CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategori (
    id SERIAL PRIMARY KEY,
    nama_kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE barang (
    id SERIAL PRIMARY KEY,
    kategori_id INT,
    nama_barang VARCHAR(100),
    harga INT,
    stok INT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_kategori
    FOREIGN KEY(kategori_id)
    REFERENCES kategori(id)
);

CREATE TABLE transaksi (
    id SERIAL PRIMARY KEY,
    total INT,
    bayar INT,
    kembalian INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE detail_transaksi (
    id SERIAL PRIMARY KEY,
    transaksi_id INT,
    barang_id INT,
    qty INT,
    subtotal INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_transaksi
    FOREIGN KEY(transaksi_id)
    REFERENCES transaksi(id),

    CONSTRAINT fk_barang
    FOREIGN KEY(barang_id)
    REFERENCES barang(id)
);