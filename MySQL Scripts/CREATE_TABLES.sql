-- Първо премахване на таблиците 
IF OBJECT_ID('Transaction', 'U') IS NOT NULL DROP TABLE [Transaction];
IF OBJECT_ID('Account', 'U') IS NOT NULL DROP TABLE Account;
IF OBJECT_ID('Logins', 'U') IS NOT NULL DROP TABLE Logins;
IF OBJECT_ID('Employee', 'U') IS NOT NULL DROP TABLE Employee;
IF OBJECT_ID('Currency', 'U') IS NOT NULL DROP TABLE Currency;
IF OBJECT_ID('Position', 'U') IS NOT NULL DROP TABLE [Position];
IF OBJECT_ID('TRANSACTION_STATES', 'U') IS NOT NULL DROP TABLE TRANSACTION_STATES;
IF OBJECT_ID('TransactionType', 'U') IS NOT NULL DROP TABLE TransactionType;
IF OBJECT_ID('Client', 'U') IS NOT NULL DROP TABLE Client;

-- Таблица Client
CREATE TABLE Client (
	id_client INT IDENTITY(1,1) PRIMARY KEY,
	name_client VARCHAR(200),
	egn VARCHAR(10),
	phone_client VARCHAR(10),
	adress VARCHAR(200)
);

-- Таблица Position
CREATE TABLE [Position] (
	id_position INT IDENTITY(1,1) PRIMARY KEY,
	position_type VARCHAR(200)
);

-- Таблица Employee
CREATE TABLE Employee (
	id_employee INT IDENTITY(1,1) PRIMARY KEY,
	name_employee VARCHAR(200),
	phone_employee VARCHAR(10),
	id_position INT NOT NULL,
	FOREIGN KEY (id_position) REFERENCES [Position](id_position)
);

-- Таблица Currency
CREATE TABLE Currency (
	id_currency INT IDENTITY(1,1) PRIMARY KEY,
	currency VARCHAR(100)
);

-- Таблица Account
CREATE TABLE Account (
	id_account INT IDENTITY(1,1) PRIMARY KEY,
	number_account VARCHAR(50),
	id_client INT NOT NULL,
	interest FLOAT,
	availability_amount FLOAT,
	id_currency INT NOT NULL,
	FOREIGN KEY (id_client) REFERENCES Client(id_client),
	FOREIGN KEY (id_currency) REFERENCES Currency(id_currency)
);

-- Таблица TransactionType
CREATE TABLE TransactionType (
	id_type INT IDENTITY(1,1) PRIMARY KEY,
	type VARCHAR(100),
	coeff_transaction_client INT NOT NULL
);

-- Таблица TRANSACTION_STATES
CREATE TABLE TRANSACTION_STATES (
	id_status INT IDENTITY(1,1) PRIMARY KEY,
	type_status VARCHAR(200) NOT NULL
);

-- Таблица Transaction
CREATE TABLE [Transaction] (
	id_transaction INT IDENTITY(1,1) PRIMARY KEY,
	id_account INT NOT NULL,
	id_type INT NOT NULL,
	amount FLOAT,
	id_account_affected INT NULL,
	date_transaction DATE,
	id_status INT NOT NULL,
	FOREIGN KEY (id_account) REFERENCES Account(id_account),
	FOREIGN KEY (id_type) REFERENCES TransactionType(id_type),
	FOREIGN KEY (id_account_affected) REFERENCES Account(id_account),
	FOREIGN KEY (id_status) REFERENCES TRANSACTION_STATES(id_status)
);

-- Таблица Logins
CREATE TABLE Logins (
	USERNAME VARCHAR(32),
	PASSWORD VARCHAR(50),
	id_employee INT NOT NULL,
	FOREIGN KEY (id_employee) REFERENCES Employee(id_employee)
);


