/* M. Alwi Sukra */

/* Buat dan konek ke database */
CREATE DATABASE bmtc;
ALTER DATABASE bmtc SET DATESTYLE TO ISO,DMY;
\c bmtc

/* buat tipe data */
CREATE TYPE stats AS ENUM('ongoing','done');

/* membuat tabel entity */
CREATE TABLE Layanan(
    nama    TEXT PRIMARY KEY,
    harga   INT NOT NULL CHECK (harga >= 0)
);

CREATE TABLE Barang(
    id_barang   SERIAL PRIMARY KEY,
    nama        TEXT NOT NULL,
    merk        TEXT,
    jumlah      INT NOT NULL CHECK (jumlah >= 0),
    harga       INT NOT NUll CHECK (harga >= 0),
	unique(nama,merk)
);

CREATE TABLE Pelanggan(
    id_pelanggan    SERIAL PRIMARY KEY,
    nama            TEXT NOT NULL,
    no_hp           VARCHAR(12),
	UNIQUE(nama,no_hp)
);

CREATE TABLE Karyawan(
    id_karyawan SERIAL PRIMARY KEY,
    nama        TEXT NOT NULL,
    no_hp       VARCHAR(12) UNIQUE,
	UNIQUE(nama,no_hp)
);

CREATE TABLE Motor(
    id_motor        SERIAL PRIMARY KEY,
    jenis_motor     TEXT,
    plat_nomor      VARCHAR(10),
    id_pelanggan    INT REFERENCES Pelanggan(id_pelanggan) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE(jenis_motor,plat_nomor)
);

CREATE TABLE Transaksi(
    no_transaksi        SERIAL PRIMARY KEY,
    id_motor            INT REFERENCES Motor(id_motor) ON DELETE RESTRICT ON UPDATE CASCADE,
    status_transaksi    stats NOT NULL,
    tanggal_masuk       DATE NOT NULL,
    tanggal_keluar      DATE,
    total_harga         INT CHECK (total_harga >= 0)
);

/* membuat tabel relasi */
CREATE TABLE Membeli(
    no_transaksi    INT NOT NULL REFERENCES Transaksi(no_transaksi) ON DELETE CASCADE ON UPDATE CASCADE,
    id_barang       INT NOT NULL REFERENCES Barang(id_barang) ON DELETE RESTRICT ON UPDATE CASCADE,
    jumlah          INT NOT NULL check (jumlah > 0),
    PRIMARY KEY (no_transaksi,id_barang)
);


CREATE TABLE Melayani(
    no_transaksi    INT NOT NULL REFERENCES Transaksi(no_transaksi) ON DELETE CASCADE ON UPDATE CASCADE,
    nama_layanan    TEXT NOT NULL REFERENCES Layanan(nama) ON DELETE RESTRICT ON UPDATE CASCADE,
    id_karyawan	    INT NOT NULL REFERENCES Karyawan(id_karyawan) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY (no_transaksi,nama_layanan)
);

/* trigger untuk mendapatkan tanggal sekarang dan menentukan status saat membuat Transaksi baru*/
CREATE OR REPLACE FUNCTION status_dan_tanggal()
    RETURNS TRIGGER AS $$
    BEGIN
            NEW.tanggal_masuk = NOW();
            NEW.status_transaksi = 'ongoing';
    RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;
CREATE TRIGGER statusdantanggal BEFORE INSERT ON Transaksi FOR EACH ROW EXECUTE PROCEDURE status_dan_tanggal();

/* trigger untuk mendapatkan tanggal sekarang untuk menyelesaikan transaksi*/
CREATE OR REPLACE FUNCTION tanggal_selesai_transaksi()
    RETURNS TRIGGER AS $$
    BEGIN  
        IF(NEW.status_transaksi != OLD.status_transaksi AND NEW.status_transaksi = 'done') THEN
            NEW.tanggal_keluar = NOW();
        END IF;
    RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;
CREATE TRIGGER tanggalselesaitransaksi BEFORE UPDATE ON Transaksi FOR EACH ROW EXECUTE PROCEDURE tanggal_selesai_transaksi();
                
/* trigger untuk mengurangi jumlah barang */
CREATE OR REPLACE FUNCTION membeli_barang()
    RETURNS TRIGGER AS $$
    BEGIN
        IF EXISTS (SELECT * FROM Transaksi WHERE no_transaksi = NEW.no_transaksi AND status_transaksi = 'done') THEN
        RAISE EXCEPTION 'Transaksi sudah selesai';
        END IF;
        IF EXISTS (SELECT * FROM Membeli WHERE no_transaksi = NEW.no_transaksi and id_barang = NEW.id_barang) THEN
            /* jika nomor transaksi tersebut sudah pernah membeli barang tersebut */
            UPDATE Barang SET Jumlah = jumlah - NEW.jumlah WHERE Barang.id_barang = NEW.id_barang;
            UPDATE Membeli SET Jumlah = Jumlah + NEW.Jumlah WHERE id_barang = NEW.id_barang and no_transaksi = NEW.no_transaksi;
            RETURN NULL;
        ELSE
            /* jika nomor transaksi tersebut sudah belum membeli barang tersebut */ 
            UPDATE Barang SET Jumlah = jumlah - NEW.jumlah WHERE Barang.id_barang = NEW.id_barang;
            RETURN NEW;
        END IF;
    END;
    $$ LANGUAGE plpgsql;
CREATE TRIGGER belibarang BEFORE INSERT ON Membeli FOR EACH ROW EXECUTE PROCEDURE membeli_barang();

/* trigger untuk menghitung harga dari Membeli ke Transaksi saat barang belum pernah dibeli*/
CREATE OR REPLACE FUNCTION harga_barang_awal()
RETURNS TRIGGER AS $$
    DECLARE
        hitung_harga INT;
        tetapan_harga INT;
        total_sebelum INT;
    BEGIN
        SELECT harga FROM Barang WHERE id_barang = NEW.id_barang INTO tetapan_harga;
        hitung_harga := (NEW.jumlah)*tetapan_harga;
        SELECT total_harga FROM Transaksi WHERE no_transaksi = NEW.no_transaksi INTO total_sebelum;
        IF(total_sebelum IS NOT NULL) THEN
            UPDATE Transaksi SET total_harga = total_harga + hitung_harga WHERE no_transaksi = NEW.no_transaksi;
        ELSE   
            UPDATE Transaksi SET total_harga = hitung_harga WHERE no_transaksi = NEW.no_transaksi;
        END IF;
        RETURN NULL; 
    END;
    $$ LANGUAGE plpgsql;
CREATE TRIGGER hargabarangawal AFTER INSERT ON Membeli FOR EACH ROW EXECUTE PROCEDURE harga_barang_awal();

/* trigger untuk menghitung harga dari Membeli ke Transaksi saat barang sudah pernah dibeli*/
CREATE OR REPLACE FUNCTION harga_barang_setelah()
RETURNS TRIGGER AS $$
    DECLARE
        hitung_harga INT;
        tetapan_harga INT;
        total_sebelum INT;
    BEGIN
        SELECT harga FROM Barang WHERE id_barang = NEW.id_barang INTO tetapan_harga;
        hitung_harga := (NEW.jumlah - OLD.jumlah)*tetapan_harga;
        SELECT total_harga FROM Transaksi WHERE no_transaksi = NEW.no_transaksi INTO total_sebelum;
        IF(total_sebelum IS NOT NULL) THEN
            UPDATE Transaksi SET total_harga = total_harga + hitung_harga WHERE no_transaksi = NEW.no_transaksi;
        ELSE   
            UPDATE Transaksi SET total_harga = hitung_harga WHERE no_transaksi = NEW.no_transaksi;
        END IF;
        RETURN NULL; 
    END;
    $$ LANGUAGE plpgsql;
CREATE TRIGGER hargabarangsetelah AFTER UPDATE ON Membeli FOR EACH ROW EXECUTE PROCEDURE harga_barang_setelah();

/* trigger untuk menghitung harga fari Melayani ke Transaksi */
CREATE OR REPLACE FUNCTION harga_layanan()
    RETURNS TRIGGER AS $$
    DECLARE
        hitung_harga INT;
        total_sebelum INT;
    BEGIN
        IF EXISTS (SELECT * FROM Transaksi WHERE no_transaksi = NEW.no_transaksi AND status_transaksi = 'done') THEN
            RAISE EXCEPTION 'Transaksi sudah selesai';
        END IF;
        SELECT harga FROM Layanan WHERE nama = NEW.nama_layanan INTO hitung_harga;
        SELECT total_harga FROM Transaksi WHERE no_transaksi = NEW.no_transaksi INTO total_sebelum;
        IF(total_sebelum IS NOT NULL) THEN
            UPDATE Transaksi SET total_harga = total_harga + hitung_harga WHERE no_transaksi = NEW.no_transaksi;
        ELSE   
            UPDATE Transaksi SET total_harga = hitung_harga WHERE no_transaksi = NEW.no_transaksi;
        END IF;
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql; 
CREATE TRIGGER hargalayanan BEFORE INSERT ON Melayani FOR EACH ROW EXECUTE PROCEDURE harga_layanan();

/* sample data */
insert into Layanan values ('reparasi',50000);
insert into Layanan values ('kustomisasi',100000);

insert into Karyawan (nama,no_hp) values ('Asep','0812345678');
insert into Karyawan (nama,no_hp) values ('Pesa','0887654321');

insert into Barang (nama,merk,jumlah,harga) values ('adaptor tiger revo','kt',12,50000);
insert into Barang (nama,merk,jumlah,harga) values ('aki gs 12n9-4b-1m','gs',9,200000);
insert into Barang (nama,merk,jumlah,harga) values ('asetilin',NULL,12,20000);
insert into Barang (nama,merk,jumlah,harga) values ('bak kopling kiri rx king','honda',18,100000);
insert into Barang (nama,merk,jumlah,harga) values ('ban dalam 250/275-17','irc',19,150000);

insert into Pelanggan (nama, no_hp) values ('alwi','0812909090');
insert into Pelanggan (nama, no_hp) values ('sukra','0812808080');

insert into Motor (jenis_motor,plat_nomor,id_pelanggan) values ('yamaha mio','B 123 GK',1);
insert into Motor (jenis_motor,plat_nomor,id_pelanggan) values ('honda vario','B 1234 YY',2);
