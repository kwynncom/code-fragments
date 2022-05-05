SELECT 
value_num, post_date, t.description, a.name, account_type 
FROM 
splits s,
transactions t,
accounts a
WHERE s.tx_guid = t.guid
AND   a.guid = s.account_guid
ORDER BY post_date DESC, tx_guid

-- everything is in a split, which is essentially a sub-transaction

SELECT SUM(value_num) FROM splits WHERE value_num > 0
SELECT SUM(value_num) FROM splits WHERE value_num < 0
-- sum of 2 queries should be 0

SELECT * FROM gnu.splits order by tx_guid
-- shows the ins and outs one below the other


SELECT * FROM gnu.splits WHERE value_num != quantity_num
-- stocks and such, not only in $ or your currency

SELECT 
value_num, post_date, description 
FROM 
splits s,
transactions t
WHERE s.tx_guid = t.guid
ORDER BY post_date DESC, tx_guid

SELECT 
value_num 
FROM 
splits s,
transactions t
WHERE s.tx_guid = t.guid
order by tx_guid

-- # install this to allow MySQL interaction
-- # sudo apt install libdbd-mysql