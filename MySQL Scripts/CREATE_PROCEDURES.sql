use PBD;

CREATE PROCEDURE find_account
    @p_account VARCHAR(50)
AS
BEGIN
    SELECT 
        c.name_client AS '��� �� ������',
        c.egn AS '���',
        a.number_account AS '����� �� ������',
        a.availability_amount AS '���������',
        cu.currency AS '������',
        a.interest AS '�����'
    FROM Account a
    JOIN Client c ON a.id_client = c.id_client
    JOIN Currency cu ON a.id_currency = cu.id_currency
    WHERE a.number_account = @p_account;
END;
GO

-- �������� ����������:
EXEC find_account '14702580258749';



CREATE PROCEDURE find_client
    @p_name_client VARCHAR(200)
AS
BEGIN
    SELECT 
        c.egn AS '���',
        a.number_account AS '����� �� ������',
        a.availability_amount AS '���������',
        cu.currency AS '������',
        a.interest AS '�����'
    FROM Account a
    JOIN Client c ON a.id_client = c.id_client
    JOIN Currency cu ON a.id_currency = cu.id_currency
    WHERE c.name_client = @p_name_client;
END;
GO

-- ������:
EXEC find_client '����� ����������� ���������';


CREATE PROCEDURE find_egn
    @p_egn VARCHAR(10)
AS
BEGIN
    SELECT 
        c.name_client AS '��� �� ������',
        a.number_account AS '����� �� ������',
        a.availability_amount AS '��������� �� ������',
        cu.currency AS '������',
        a.interest AS '�����'
    FROM Account a
    JOIN Client c ON a.id_client = c.id_client
    JOIN Currency cu ON a.id_currency = cu.id_currency
    WHERE c.egn = @p_egn;
END;
GO

-- ������:
EXEC find_egn '0125478963';
GO

CREATE PROCEDURE client_transaction
    @client_name VARCHAR(200)
AS
BEGIN
    SELECT 
        tp.type AS '��� ����������',
        a.number_account AS '�� ������',
        a_a.number_account AS '� ������',
        t.date_transaction AS '���� �� ����������'
    FROM [Transaction] t
    JOIN Account a ON t.id_account = a.id_account
    JOIN Client c ON a.id_client = c.id_client
    JOIN TransactionType tp ON tp.id_type = t.id_type
    JOIN Account a_a ON t.id_account_affected = a_a.id_account
    WHERE c.name_client = @client_name
    ORDER BY t.date_transaction;
END;
GO

-- ������:
EXEC client_transaction '����� ����������� ���������';
GO

CREATE PROCEDURE client_transaction
    @client_name VARCHAR(200)
AS
BEGIN
    SELECT 
        tp.type AS '��� ����������',
        a.number_account AS '�� ������',
        a_a.number_account AS '� ������',
        t.date_transaction AS '���� �� ����������'
    FROM [Transaction] t
    JOIN Account a ON t.id_account = a.id_account
    JOIN Client c ON a.id_client = c.id_client
    JOIN TransactionType tp ON tp.id_type = t.id_type
    JOIN Account a_a ON t.id_account_affected = a_a.id_account
    WHERE c.name_client = @client_name
    ORDER BY t.date_transaction;
END;
GO

-- ������:
EXEC client_transaction '����� ����������� ���������';
GO

DROP PROCEDURE IF EXISTS employee_order
GO

CREATE PROCEDURE employee_order
    @employee_name VARCHAR(200)
AS
BEGIN
    SELECT 
        tp.type AS '����������',
        a.number_account AS '����� �� ������',
        t.amount AS '����',
        a_a.number_account AS '��������� �� ������',
        t.date_transaction AS '���� �� ����������'
    FROM [Transaction] t
    JOIN Employee e ON t.id_employee = e.id_employee
    JOIN Position p ON p.id_position = e.id_position
    JOIN TransactionType tp ON tp.id_type = t.id_type
    JOIN Account a ON a.id_account = t.id_account
    JOIN Account a_a ON a_a.id_account = t.id_account_affected
    WHERE e.name_employee = @employee_name;
END;
GO

-- ������:
EXEC employee_order '����� ���������� ������';
Go

CREATE PROCEDURE transaction_period
    @start_date DATE,
    @end_date DATE
AS
BEGIN
    SELECT 
        t.date_transaction AS '����',
        c.name_client AS '������',
        e.name_employee AS '��������',
        a.number_account AS '������',
        a_a.number_account AS '������ ���������',
        t.amount AS '����'
    FROM [Transaction] t
    JOIN Account a ON t.id_account = a.id_account
    JOIN Client c ON a.id_client = c.id_client
    JOIN Employee e ON t.id_employee = e.id_employee
    JOIN TransactionType tp ON tp.id_type = t.id_type
    JOIN Account a_a ON t.id_account_affected = a_a.id_account
    WHERE t.date_transaction BETWEEN @start_date AND @end_date
    ORDER BY t.date_transaction;
END;
GO

-- ������:
EXEC transaction_period '2023-09-01', '2023-10-23';
GO

DROP PROCEDURE IF EXISTS POSITION_INS;
GO
CREATE PROCEDURE POSITION_INS
    @v_pos_type VARCHAR(200)
AS
BEGIN
    INSERT INTO [Position](position_type)
    VALUES (@v_pos_type);
END;
GO

-- ���������:
EXEC POSITION_INS 'BIG BOSS';
GO

DROP PROCEDURE IF EXISTS POSITION_UPD;
GO
CREATE PROCEDURE POSITION_UPD
    @v_pos_id INT,
    @v_pos_type VARCHAR(200)
AS
BEGIN
    UPDATE [Position]
    SET position_type = @v_pos_type
    WHERE id_position = @v_pos_id;
END;
GO

-- ���������:
EXEC POSITION_UPD 10, '���������';
GO

DROP PROCEDURE IF EXISTS CLIENT_INS;
GO
CREATE PROCEDURE CLIENT_INS
    @v_name VARCHAR(200),
    @v_egn VARCHAR(10),
    @v_phone VARCHAR(10),
    @v_address VARCHAR(200)
AS
BEGIN
    INSERT INTO Client(name_client, egn, phone_client, adress)
    VALUES (@v_name, @v_egn, @v_phone, @v_address);
END;
GO

-- ���������:
EXEC CLIENT_INS '����� ������', '1234567890', '0888123456', '��. �����, ���. �������� 1';


DROP PROCEDURE IF EXISTS EMPLOYEE_INS;
GO
CREATE PROCEDURE EMPLOYEE_INS
    @v_name VARCHAR(200),
    @v_phone VARCHAR(10),
    @v_id_position INT
AS
BEGIN
    INSERT INTO Employee(name_employee, phone_employee, id_position)
    VALUES (@v_name, @v_phone, @v_id_position);
END;
GO

-- ���������:
EXEC EMPLOYEE_INS '����� ��������', '0874523695', 9;

DROP PROCEDURE IF EXISTS CURRENCY_INS;
GO
CREATE PROCEDURE CURRENCY_INS
    @v_currency VARCHAR(100)
AS
BEGIN
    INSERT INTO Currency(currency)
    VALUES (@v_currency);
END;
GO

-- ���������:
EXEC CURRENCY_INS 'JPY';


DROP PROCEDURE IF EXISTS ACCOUNT_INS;
GO
CREATE PROCEDURE ACCOUNT_INS
    @v_number VARCHAR(50),
    @v_client_id INT,
    @v_interest FLOAT,
    @v_amount FLOAT,
    @v_currency_id INT
AS
BEGIN
    INSERT INTO Account(number_account, id_client, interest, availability_amount, id_currency)
    VALUES (@v_number, @v_client_id, @v_interest, @v_amount, @v_currency_id);
END;
GO

-- ���������:
EXEC ACCOUNT_INS '99999999999999', 1, 2.5, 1000.00, 1;

DROP PROCEDURE IF EXISTS TransactionType_INS;
GO
CREATE PROCEDURE TransactionType_INS
    @v_type VARCHAR(100),
    @v_coeff INT
AS
BEGIN
    INSERT INTO TransactionType(type, coeff_transaction_client)
    VALUES (@v_type, @v_coeff);
END;
GO

--  ���������:
EXEC TransactionType_INS '������ �� �������� ������', 1;

DROP PROCEDURE IF EXISTS TRANSACTION_INS;
GO
CREATE PROCEDURE TRANSACTION_INS
    @v_employee INT,
    @v_account INT,
    @v_amount FLOAT,
    @v_type INT,
    @v_date DATE,
    @v_affected INT
AS
BEGIN
    INSERT INTO [Transaction](id_employee, id_account, amount, id_type, date_transaction, id_account_affected)
    VALUES (@v_employee, @v_account, @v_amount, @v_type, @v_date, @v_affected);
END;
GO

-- ���������:
EXEC TRANSACTION_INS 1, 108, 100.50, 3, '2023-09-12', 117;

DROP PROCEDURE IF EXISTS CLIENT_UPD;
GO
CREATE PROCEDURE CLIENT_UPD
    @v_id INT,
    @v_name VARCHAR(200),
    @v_egn VARCHAR(10),
    @v_phone VARCHAR(10),
    @v_address VARCHAR(200)
AS
BEGIN
    UPDATE Client
    SET name_client = @v_name,
        egn = @v_egn,
        phone_client = @v_phone,
        adress = @v_address
    WHERE id_client = @v_id;
END;
GO

--  ���������:
EXEC CLIENT_UPD 3, '������� ���������� ���������', '7741288963', '0887414752', '�� ��������, ��. ������ ������� ��.30 �� 31';

DROP PROCEDURE IF EXISTS EMPLOYEE_UPD;
GO
CREATE PROCEDURE EMPLOYEE_UPD
    @v_id INT,
    @v_name VARCHAR(200),
    @v_phone VARCHAR(10),
    @v_position INT
AS
BEGIN
    UPDATE Employee
    SET name_employee = @v_name,
        phone_employee = @v_phone,
        id_position = @v_position
    WHERE id_employee = @v_id;
END;
GO

--  ���������:
EXEC EMPLOYEE_UPD 7, '��������� ���������� �������', '089365478', 2;

DROP PROCEDURE IF EXISTS CURRENCY_UPD;
GO
CREATE PROCEDURE CURRENCY_UPD
    @v_id INT,
    @v_currency VARCHAR(100)
AS
BEGIN
    UPDATE Currency
    SET currency = @v_currency
    WHERE id_currency = @v_id;
END;
GO

--  ���������:
EXEC CURRENCY_UPD 4, 'RUB';

DROP PROCEDURE IF EXISTS Position_UPD;
GO
CREATE PROCEDURE Position_UPD
    @v_id INT,
    @v_position VARCHAR(200)
AS
BEGIN
    UPDATE [Position]
    SET position_type = @v_position
    WHERE id_position = @v_id;
END;
GO

--  ���������:
EXEC Position_UPD 7, '������ ������� � ����� ������ � ��������������';

DROP PROCEDURE IF EXISTS ACCOUNT_UPD;
GO
CREATE PROCEDURE ACCOUNT_UPD
    @v_id INT,
    @v_number VARCHAR(50),
    @v_client INT,
    @v_interest FLOAT,
    @v_amount FLOAT,
    @v_currency INT
AS
BEGIN
    UPDATE Account
    SET number_account = @v_number,
        id_client = @v_client,
        interest = @v_interest,
        availability_amount = @v_amount,
        id_currency = @v_currency
    WHERE id_account = @v_id;
END;
GO

--  ���������:
EXEC ACCOUNT_UPD 112, '98547856325007', 10, 2.8, 12000.00, 2;

DROP PROCEDURE IF EXISTS TransactionType_UPD;
GO
CREATE PROCEDURE TransactionType_UPD
    @v_id INT,
    @v_type VARCHAR(100),
    @v_coeff INT
AS
BEGIN
    UPDATE TransactionType
    SET type = @v_type,
        coeff_transaction_client = @v_coeff
    WHERE id_type = @v_id;
END;
GO

--  ���������:
EXEC TransactionType_UPD 2, '������� � ������ �� ��������', -1;

DROP PROCEDURE IF EXISTS Transaction_UPD;
GO
CREATE PROCEDURE Transaction_UPD
    @v_id INT,
    @v_employee INT,
    @v_account INT,
    @v_type INT,
    @v_amount FLOAT,
    @v_affected INT,
    @v_date DATE
AS
BEGIN
    UPDATE [Transaction]
    SET id_employee = @v_employee,
        id_account = @v_account,
        id_type = @v_type,
        amount = @v_amount,
        id_account_affected = @v_affected,
        date_transaction = @v_date
    WHERE id_transaction = @v_id;
END;
GO

--  ���������:
EXEC Transaction_UPD 4007, 1, 108, 3, 982.8, 117, '2023-09-12';
