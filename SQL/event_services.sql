CREATE DEFINER=root@localhost 
EVENT SERVICES ON SCHEDULE EVERY 1 MONTH STARTS '2021-01-01 21:34:00' ON COMPLETION NOT PRESERVE ENABLE DO 
INSERT INTO bills(apartment, amount, description,type) 
    SELECT a.id as 'apartment',s.amount,CONCAT('PAGO DE ',s.name),s.id FROM apartment as a 
        INNER JOIN services as s ON s.condominium = a.condominium 
    WHERE a.conditions IN ('OCUPADO','ALQUILADO') AND DAY(CURDATE())= DAY(s.date_load)

DROP EVENT IF EXISTS 1;
  
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `setBills`(IN `id_service` INT, IN `condominium_sp` INT)
BEGIN
	DECLARE unique_code_ps INT DEFAULT 0;
    DECLARE id_insert_daily INT DEFAULT 0;   
    DECLARE id_insert_daily_deposit INT DEFAULT 0;
    DECLARE amount_sp DOUBLE DEFAULT 0;  
    DECLARE amount_sp_deposit DOUBLE DEFAULT 0;
    DECLARE generate_debit_ps INT DEFAULT 0;
    DECLARE generate_credit_ps INT DEFAULT 0;    
    DECLARE debit_deposit_ps INT DEFAULT 0;
    DECLARE credit_deposit_ps INT DEFAULT 0;
     DECLARE name_service VARCHAR(250) DEFAULT '';
    SET unique_code_ps = UNIX_TIMESTAMP();
    INSERT INTO bills(condominium,apartment, amount, description,type,unique_code)  
        SELECT a.condominium, a.id as 'apartment',s.amount,UPPER(CONCAT(MONTHNAME(now()),' - ',s.name)) as 'description',s.id,unique_code_ps FROM apartment as a 
            INNER JOIN services as s ON s.condominium = a.condominium 
            WHERE a.conditions IN ('OCUPADO','ALQUILADO') AND DAY(CURDATE())= DAY(s.date_load) AND s.id = id_service AND a.status = 1 AND
            0 = (SELECT COUNT(bl.id) FROM bills as bl WHERE bl.type = s.id AND bl.apartment = a.id AND DATE(bl.payment_date_prox) = DATE(CURDATE()));


            UPDATE services SET last_upload_date = now() WHERE id = id_service AND status = 1;
    
    SET generate_debit_ps = (  SELECT cc.generate_debit FROM condominium as cc WHERE id = condominium_sp); 
    SET generate_credit_ps = (  SELECT cc.generate_credit FROM condominium as cc WHERE id = condominium_sp); 

    SET debit_deposit_ps = (  SELECT cc.debit_deposit FROM condominium as cc WHERE id = condominium_sp); 
    SET credit_deposit_ps = (  SELECT cc.credit_deposit FROM condominium as cc WHERE id = condominium_sp); 


    SET name_service = (SELECT CONCAT(s.code,' - ',s.name) FROM services as s WHERE s.id = id_service); 
    SET amount_sp = (SELECT SUM(amount) FROM bills WHERE unique_code = unique_code_ps); 
    SET amount_sp_deposit = (SELECT IFNULL( SUM(amount),0) FROM bills WHERE  type = 1 AND DATE( payment_date_prox) = DATE(CURDATE())); 

      INSERT INTO daily(condominium, module, documenttype, description) VALUES (condominium_sp,6,4,UPPER(CONCAT('GENERACION DE FACTUAS DE ', MONTHNAME(now()),' - ' ,name_service)));  
 	SET id_insert_daily = LAST_INSERT_ID();  
    INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily,generate_debit_ps,amount_sp,0,condominium_sp);
     INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily,generate_credit_ps,0,amount_sp,condominium_sp);


    IF amount_sp_deposit> 0 THEN
        INSERT INTO daily(condominium, module, documenttype, description) VALUES (condominium_sp,6,4,UPPER(CONCAT('REBAJA DE ABONOS ', MONTHNAME(now()),' - ' ,name_service)));  
        SET id_insert_daily_deposit = LAST_INSERT_ID();  
        INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily_deposit,debit_deposit_ps,amount_sp_deposit,0,condominium_sp);
        INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily_deposit,credit_deposit_ps,0,amount_sp_deposit,condominium_sp);
    END IF;

END$$
DELIMITER ;