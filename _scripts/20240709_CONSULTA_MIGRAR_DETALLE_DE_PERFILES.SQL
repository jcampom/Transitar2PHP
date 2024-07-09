USE u859387114_transitar
------------------------------------------------------------
PRINT 'INSERT:perfiles' 
--truncate table u859387114_transitar..detalle_perfiles;
--delete from u859387114_transitar..perfiles;
INSERT INTO u859387114_transitar..detalle_perfiles(perfil_id, opcion_id, fecha, fechayhora) 
SELECT 
  accesoperfil,
  accesomenu,
  GETDATE(), 
  SYSDATETIME()
FROM 
  palermo..accesomenu
where accesoperfil != 1
------------------------------------------------------------