--Тригер за транзакция (специален тригер - before)


DROP TRIGGER IF EXISTS trg_transaction_validation;
GO

CREATE TRIGGER trg_transaction_validation
ON [Transaction]
INSTEAD OF INSERT
AS
BEGIN
    SET NOCOUNT ON;

    -- Проверка за недостатъчна наличност
    IF EXISTS (
        SELECT 1
        FROM inserted i
        JOIN TransactionType t ON i.id_type = t.id_type
        JOIN Account a ON i.id_account = a.id_account
        WHERE 
            t.coeff_transaction_client = -1 AND 
            (a.availability_amount + (t.coeff_transaction_client * i.amount)) < 0
    )
    BEGIN
        THROW 51000, 'Недостатъчна наличност в една или повече от сметките.', 1;
    END;

    -- Ако всички проверки са преминати, вмъкваме записите
    INSERT INTO [Transaction] (
        id_employee, id_account, amount, id_type, date_transaction, id_account_affected, id_status
    )
    SELECT 
        i.id_employee, i.id_account, i.amount, i.id_type, 
        i.date_transaction, i.id_account_affected, 1
    FROM inserted i;
END;
GO

-----Тригери за транзакция(специален тригер - след )
DROP TRIGGER IF EXISTS trg_transaction_balance_update;
GO
CREATE TRIGGER trg_transaction_balance_update
ON [Transaction]
AFTER INSERT
AS
BEGIN
    DECLARE @id_type INT, @coeff INT, @amount FLOAT, @id_account INT, @id_account_affected INT;

    SELECT TOP 1
        @id_type = i.id_type,
        @amount = i.amount,
        @id_account = i.id_account,
        @id_account_affected = i.id_account_affected
    FROM inserted i;

    SELECT @coeff = coeff_transaction_client FROM TransactionType WHERE id_type = @id_type;

    -- Актуализиране на сметка подател
    UPDATE Account
    SET availability_amount = availability_amount + (@coeff * @amount)
    WHERE id_account = @id_account;

    -- Ако не е теглене от банкомат (тип 1), актуализирай и другата сметка
    IF (@id_type != 1)
    BEGIN
        UPDATE Account
        SET availability_amount = availability_amount - (@coeff * @amount)
        WHERE id_account = @id_account_affected;
    END
END;
GO


EXEC TRANSACTION_INS 1, 100, 100, 2, '2023-12-31', 101;


EXEC TRANSACTION_INS 1, 100, 1000, 3, '2012-12-10', 103;
