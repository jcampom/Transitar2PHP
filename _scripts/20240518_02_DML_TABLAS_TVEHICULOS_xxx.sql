--- Llenado tabla [dbo].[Tvehiculos]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos] (Tvehiculos_ID ,Tvehiculos_placa ,Tvehiculos_fechaprop ,Tvehiculos_identificacion ,Tvehiculos_identificacion2 ,Tvehiculos_tipocarroceria ,Tvehiculos_clase ,Tvehiculos_marca ,Tvehiculos_linea ,Tvehiculos_modelo ,Tvehiculos_color ,Tvehiculos_tiposervicio ,Tvehiculos_modalidad ,Tvehiculos_capacidadpasajeros ,Tvehiculos_cilindraje ,Tvehiculos_chasis ,Tvehiculos_motor ,Tvehiculos_serie ,Tvehiculos_VIN ,Tvehiculos_puertas ,Tvehiculos_combustible ,Tvehiculos_ejes ,Tvehiculos_peso ,Tvehiculos_origen ,Tvehiculos_declaracion ,Tvehiculos_fdeclaracion ,Tvehiculos_paisorigen ,Tvehiculos_potencia ,Tvehiculos_clasificacion ,Tvehiculos_anofabricacion ,Tvehiculos_transportador ,Tvehiculos_actaimportacion ,Tvehiculos_blindado ,Tvehiculos_blindajonivel ,Tvehiculos_factura ,Tvehiculos_ffactura ,Tvehiculos_pignorado ,Tvehiculos_estado ,Tvehiculos_acreedorp ,Tvehiculos_verificacion ,Tvehiculos_fecha ,Tvehiculos_liquidacion ,Tvehiculos_SOAT ,Tvehiculos_SOATfecha ,Tvehiculos_mecanica ,Tvehiculos_mecanicafecha ,Tvehiculos_LT ,Tvehiculos_sustrato ,Tvehiculos_cartaacepta ,Tvehiculos_radio ,Tvehiculos_tipopasajero ,Tvehiculos_user ,Tvehiculos_medidacautelar ,Tvehiculos_chasisind ,Tvehiculos_ftc ,Tvehiculos_ftch ,Tvehiculos_ffc ,Tvehiculos_carrocero ,Tvehiculos_capacidadcarga ,Tvehiculos_regrabmotor ,Tvehiculos_regrabchasis ,Tvehiculos_adaptadoense ,Tvehiculos_polarizado ,Tvehiculos_fabricante ,Tvehiculos_observaciones ,Tvehiculos_OT ,Tvehiculos_TO ,Tvehiculos_compa ,Tvehiculos_inmovilizado)
SELECT Tvehiculos_ID ,Tvehiculos_placa ,Tvehiculos_fechaprop ,Tvehiculos_identificacion ,Tvehiculos_identificacion2 ,Tvehiculos_tipocarroceria ,Tvehiculos_clase ,Tvehiculos_marca ,Tvehiculos_linea ,Tvehiculos_modelo ,Tvehiculos_color ,Tvehiculos_tiposervicio ,Tvehiculos_modalidad ,Tvehiculos_capacidadpasajeros ,Tvehiculos_cilindraje ,Tvehiculos_chasis ,Tvehiculos_motor ,Tvehiculos_serie ,Tvehiculos_VIN ,Tvehiculos_puertas ,Tvehiculos_combustible ,Tvehiculos_ejes ,Tvehiculos_peso ,Tvehiculos_origen ,Tvehiculos_declaracion ,Tvehiculos_fdeclaracion ,Tvehiculos_paisorigen ,Tvehiculos_potencia ,Tvehiculos_clasificacion ,Tvehiculos_anofabricacion ,Tvehiculos_transportador ,Tvehiculos_actaimportacion ,Tvehiculos_blindado ,Tvehiculos_blindajonivel ,Tvehiculos_factura ,Tvehiculos_ffactura ,Tvehiculos_pignorado ,Tvehiculos_estado ,Tvehiculos_acreedorp ,Tvehiculos_verificacion ,Tvehiculos_fecha ,Tvehiculos_liquidacion ,Tvehiculos_SOAT ,Tvehiculos_SOATfecha ,Tvehiculos_mecanica ,Tvehiculos_mecanicafecha ,Tvehiculos_LT ,Tvehiculos_sustrato ,Tvehiculos_cartaacepta ,Tvehiculos_radio ,Tvehiculos_tipopasajero ,Tvehiculos_user ,Tvehiculos_medidacautelar ,Tvehiculos_chasisind ,Tvehiculos_ftc ,Tvehiculos_ftch ,Tvehiculos_ffc ,Tvehiculos_carrocero ,Tvehiculos_capacidadcarga ,Tvehiculos_regrabmotor ,Tvehiculos_regrabchasis ,Tvehiculos_adaptadoense ,Tvehiculos_polarizado ,Tvehiculos_fabricante ,Tvehiculos_observaciones ,Tvehiculos_OT ,Tvehiculos_TO ,Tvehiculos_compa ,Tvehiculos_inmovilizado
FROM [cienaga].[dbo].[Tvehiculos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos] OFF;
-------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_carroceros]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_carroceros];
--SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_carroceros] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_carroceros] (Tcarroceros_ID ,Tcarroceros_inscripcion ,Tcarroceros_nombre ,Tcarroceros_representante ,Tcarroceros_identificacion ,Tcarroceros_direccion ,Tcarroceros_telefono ,Tcarroceros_ciudad)
SELECT Tcarroceros_ID ,Tcarroceros_inscripcion ,Tcarroceros_nombre ,Tcarroceros_representante ,Tcarroceros_identificacion ,Tcarroceros_direccion ,Tcarroceros_telefono ,Tcarroceros_ciudad
FROM [cienaga].[dbo].[Tvehiculos_carroceros];
--SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_carroceros] OFF;
-----------------------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_ccolor]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_ccolor];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_ccolor] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_ccolor](Tvehiculos_ccolor_ID ,Tvehiculos_ccolor_liquidacion ,Tvehiculos_ccolor_placa ,Tvehiculos_ccolor_verificacion ,Tvehiculos_ccolor_cactual ,Tvehiculos_ccolor_cnuevo ,Tvehiculos_ccolor_LTActual ,Tvehiculos_ccolor_LTdenuncia ,Tvehiculos_ccolor_fechadenuncia ,Tvehiculos_ccolor_LTnueva ,Tvehiculos_ccolor_sustrato ,Tvehiculos_ccolor_user ,Tvehiculos_ccolor_fecha ,Tvehiculos_ccolor_fechaRUNT)
SELECT Tvehiculos_ccolor_ID ,Tvehiculos_ccolor_liquidacion ,Tvehiculos_ccolor_placa ,Tvehiculos_ccolor_verificacion ,Tvehiculos_ccolor_cactual ,Tvehiculos_ccolor_cnuevo ,Tvehiculos_ccolor_LTActual ,Tvehiculos_ccolor_LTdenuncia ,Tvehiculos_ccolor_fechadenuncia ,Tvehiculos_ccolor_LTnueva ,Tvehiculos_ccolor_sustrato ,Tvehiculos_ccolor_user ,Tvehiculos_ccolor_fecha ,Tvehiculos_ccolor_fechaRUNT
FROM [cienaga].[dbo].[Tvehiculos_ccolor];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_ccolor] OFF;
---------------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_CM]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_CM];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_CM] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_CM](Tvehiculos_CM_ID ,Tvehiculos_CM_liquidacion ,Tvehiculos_CM_ddt ,Tvehiculos_CM_ddtf ,Tvehiculos_CM_auto_ddt ,Tvehiculos_CM_verificacion ,Tvehiculos_CM_tipo ,Tvehiculos_CM_placa ,Tvehiculos_CM_docdesc ,Tvehiculos_CM_docdescf ,Tvehiculos_CM_auto_docdesc ,Tvehiculos_CM_docnorec ,Tvehiculos_CM_docnorecf ,Tvehiculos_CM_auto_docnorec ,Tvehiculos_CM_resolucion ,Tvehiculos_CM_resolucionf ,Tvehiculos_CM_auto_resolucion ,Tvehiculos_CM_fecha ,Tvehiculos_CM_user ,Tvehiculos_CM_LTActual ,Tvehiculos_CM_LTdenuncia ,Tvehiculos_CM_fechadenuncia ,Tvehiculos_CM_docacc ,Tvehiculos_CM_docaccf ,Tvehiculos_CM_docdet ,Tvehiculos_CM_docdetf ,Tvehiculos_CM_nrespdf ,Tvehiculos_CM_fechaRUNT)
SELECT Tvehiculos_CM_ID ,Tvehiculos_CM_liquidacion ,Tvehiculos_CM_ddt ,Tvehiculos_CM_ddtf ,Tvehiculos_CM_auto_ddt ,Tvehiculos_CM_verificacion ,Tvehiculos_CM_tipo ,Tvehiculos_CM_placa ,Tvehiculos_CM_docdesc ,Tvehiculos_CM_docdescf ,Tvehiculos_CM_auto_docdesc ,Tvehiculos_CM_docnorec ,Tvehiculos_CM_docnorecf ,Tvehiculos_CM_auto_docnorec ,Tvehiculos_CM_resolucion ,Tvehiculos_CM_resolucionf ,Tvehiculos_CM_auto_resolucion ,Tvehiculos_CM_fecha ,Tvehiculos_CM_user ,Tvehiculos_CM_LTActual ,Tvehiculos_CM_LTdenuncia ,Tvehiculos_CM_fechadenuncia ,Tvehiculos_CM_docacc ,Tvehiculos_CM_docaccf ,Tvehiculos_CM_docdet ,Tvehiculos_CM_docdetf ,Tvehiculos_CM_nrespdf ,Tvehiculos_CM_fechaRUNT
FROM [cienaga].[dbo].[Tvehiculos_CM];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_CM] OFF;
-------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_CM_tipo]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_CM_tipo];
--SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_CM_tipo] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_CM_tipo] SELECT * FROM [cienaga].[dbo].[Tvehiculos_CM_tipo];
--SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_CM_tipo] OFF;
-----------------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_estado]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_estado];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_estado] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_estado] (Tvehiculos_estado_ID ,Tvehiculos_estado_nombre)
SELECT Tvehiculos_estado_ID ,Tvehiculos_estado_nombre 
FROM [cienaga].[dbo].[Tvehiculos_estado];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_estado] OFF;
---------------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_mc]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_mc];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_mc] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_mc] (Tvehiculos_mc_ID ,Tvehiculos_mc_placa ,Tvehiculos_mc_departamento ,Tvehiculos_mc_municipio ,Tvehiculos_mc_tipoid ,Tvehiculos_mc_ident ,Tvehiculos_mc_dnombre ,Tvehiculos_mc_oj ,Tvehiculos_mc_foj ,Tvehiculos_mc_entidad ,Tvehiculos_mc_tlimitacion ,Tvehiculos_mc_tproceso ,Tvehiculos_mc_verificacion ,Tvehiculos_mc_identpropietario ,Tvehiculos_mc_numero ,Tvehiculos_mc_tipomc ,Tvehiculos_mc_levantar ,Tvehiculos_mc_destino ,Tvehiculos_mc_cargo ,Tvehiculos_mc_lugar ,Tvehiculos_mc_direccion ,Tvehiculos_mc_ciudad ,Tvehiculos_mc_user ,Tvehiculos_mc_fecha ,Tvehiculos_mc_fechaRUNT)
SELECT Tvehiculos_mc_ID ,Tvehiculos_mc_placa ,Tvehiculos_mc_departamento ,Tvehiculos_mc_municipio ,Tvehiculos_mc_tipoid ,Tvehiculos_mc_ident ,Tvehiculos_mc_dnombre ,Tvehiculos_mc_oj ,Tvehiculos_mc_foj ,Tvehiculos_mc_entidad ,Tvehiculos_mc_tlimitacion ,Tvehiculos_mc_tproceso ,Tvehiculos_mc_verificacion ,Tvehiculos_mc_identpropietario ,Tvehiculos_mc_numero ,Tvehiculos_mc_tipomc ,Tvehiculos_mc_levantar ,Tvehiculos_mc_destino ,Tvehiculos_mc_cargo ,Tvehiculos_mc_lugar ,Tvehiculos_mc_direccion ,Tvehiculos_mc_ciudad ,Tvehiculos_mc_user ,Tvehiculos_mc_fecha ,Tvehiculos_mc_fechaRUNT
FROM [cienaga].[dbo].[Tvehiculos_mc];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_mc] OFF;
-------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_MI]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_MI];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_MI] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_MI] (Tvehiculos_MI_ID ,Tvehiculos_MI_liquidacion ,Tvehiculos_MI_placa ,Tvehiculos_MI_verificacion ,Tvehiculos_MI_fecha ,Tvehiculos_MI_user ,Tvehiculos_MI_tiporeg ,Tvehiculos_MI_organismo ,Tvehiculos_MI_acto ,Tvehiculos_MI_facto ,Tvehiculos_MI_placa1 ,Tvehiculos_MI_poliza ,Tvehiculos_MI_fpoliza ,Tvehiculos_MI_certificado ,Tvehiculos_MI_fcertificado ,Tvehiculos_MI_LT ,Tvehiculos_MI_sustrato ,Tvehiculos_MI_fechaRUNT)
SELECT Tvehiculos_MI_ID ,Tvehiculos_MI_liquidacion ,Tvehiculos_MI_placa ,Tvehiculos_MI_verificacion ,Tvehiculos_MI_fecha ,Tvehiculos_MI_user ,Tvehiculos_MI_tiporeg ,Tvehiculos_MI_organismo ,Tvehiculos_MI_acto ,Tvehiculos_MI_facto ,Tvehiculos_MI_placa1 ,Tvehiculos_MI_poliza ,Tvehiculos_MI_fpoliza ,Tvehiculos_MI_certificado ,Tvehiculos_MI_fcertificado ,Tvehiculos_MI_LT ,Tvehiculos_MI_sustrato ,Tvehiculos_MI_fechaRUNT
FROM [cienaga].[dbo].[Tvehiculos_MI];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_MI] OFF;
-------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_pasajerostipo]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_pasajerostipo];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_pasajerostipo] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_pasajerostipo] (Tvehiculos_pasajerostipo_ID ,Tvehiculos_pasajerostipo_tipo)
SELECT Tvehiculos_pasajerostipo_ID ,Tvehiculos_pasajerostipo_tipo
FROM [cienaga].[dbo].[Tvehiculos_pasajerostipo];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_pasajerostipo] OFF;
-----------------------------------------------------------------------------------------------------------------------------
--- Llenado tabla [dbo].[Tvehiculos_pig]
TRUNCATE TABLE [u859387114_transitar].[dbo].[Tvehiculos_pig];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_pig] ON;
INSERT INTO [u859387114_transitar].[dbo].[Tvehiculos_pig] (Tvehiculos_pig_ID ,Tvehiculos_pig_liquidacion ,Tvehiculos_pig_doc ,Tvehiculos_pig_entidad ,Tvehiculos_pig_placa ,Tvehiculos_pig_verificacion ,Tvehiculos_pig_user ,Tvehiculos_pig_fecha ,Tvehiculos_pig_LTActual ,Tvehiculos_pig_LTdenuncia ,Tvehiculos_pig_fechadenuncia ,Tvehiculos_pig_LTnueva ,Tvehiculos_pig_obs ,Tvehiculos_pig_sustrato ,Tvehiculos_pig_fechaRUNT)
SELECT Tvehiculos_pig_ID ,Tvehiculos_pig_liquidacion ,Tvehiculos_pig_doc ,Tvehiculos_pig_entidad ,Tvehiculos_pig_placa ,Tvehiculos_pig_verificacion ,Tvehiculos_pig_user ,Tvehiculos_pig_fecha ,Tvehiculos_pig_LTActual ,Tvehiculos_pig_LTdenuncia ,Tvehiculos_pig_fechadenuncia ,Tvehiculos_pig_LTnueva ,Tvehiculos_pig_obs ,Tvehiculos_pig_sustrato ,Tvehiculos_pig_fechaRUNT
FROM [cienaga].[dbo].[Tvehiculos_pig];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[Tvehiculos_pig] OFF;
---------------------------------------------------------------------------------------------------------
