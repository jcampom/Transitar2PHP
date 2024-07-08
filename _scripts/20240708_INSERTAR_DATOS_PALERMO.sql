------------------------------------------------------------
PRINT 'INSERT:acuerdos_pagos'
truncate table u859387114_transitar..acuerdos_pagos;
SET IDENTITY_INSERT u859387114_transitar..avisos OFF
SET IDENTITY_INSERT u859387114_transitar..avisos_resoluciones OFF
SET IDENTITY_INSERT u859387114_transitar..acuerdos_pagos ON
INSERT INTO u859387114_transitar..acuerdos_pagos(TAcuerdop_ID,TAcuerdop_numero,TAcuerdop_comparendo,TAcuerdop_valor,TAcuerdop_periodicidad,TAcuerdop_cuota,TAcuerdop_cuotas,TAcuerdop_identificacion,TAcuerdop_estado,TAcuerdop_fechapago,TAcuerdop_tipodoc,TAcuerdop_honorarios,TAcuerdop_cobranza,TAcuerdop_fecha,TAcuerdop_user,TAcuerdop_concepto,TAcuerdop_sistema,TAcuerdop_intmora,TAcuerdop_dintmora,TAcuerdop_honorario,TAcuerdop_cobranzas,TAcuerdop_useranula,TAcuerdop_fechaanula,TAcuerdop_solicitud) SELECT TAcuerdop_ID,TAcuerdop_numero,TAcuerdop_comparendo,TAcuerdop_valor,TAcuerdop_periodicidad,TAcuerdop_cuota,TAcuerdop_cuotas,TAcuerdop_identificacion,TAcuerdop_estado,TAcuerdop_fechapago,TAcuerdop_tipodoc,TAcuerdop_honorarios,TAcuerdop_cobranza,TAcuerdop_fecha,TAcuerdop_user,TAcuerdop_concepto,TAcuerdop_sistema,TAcuerdop_intmora,TAcuerdop_dintmora,TAcuerdop_honorario,TAcuerdop_cobranzas,TAcuerdop_useranula,TAcuerdop_fechaanula,TAcuerdop_solicitud FROM palermo..TAcuerdop;
SET IDENTITY_INSERT u859387114_transitar..acuerdos_pagos OFF
------------------------------------------------------------
PRINT 'INSERT:acuerdosp_estados'
truncate table u859387114_transitar..acuerdosp_estados;
SET IDENTITY_INSERT u859387114_transitar..acuerdosp_estados ON
INSERT INTO u859387114_transitar..acuerdosp_estados(id,nombre) SELECT TAcuerdopestado_ID,TAcuerdopestado_estado FROM palermo..TAcuerdopestado;
SET IDENTITY_INSERT u859387114_transitar..acuerdosp_estados OFF
------------------------------------------------------------
PRINT 'INSERT:acuerdosp_periodos'
truncate table u859387114_transitar..acuerdosp_periodos;
SET IDENTITY_INSERT u859387114_transitar..acuerdosp_periodos ON
INSERT INTO u859387114_transitar..acuerdosp_periodos(id,nombre) SELECT TAcuerdop_period_ID,TAcuerdop_period_nombre FROM palermo..TAcuerdop_period;
SET IDENTITY_INSERT u859387114_transitar..acuerdosp_periodos OFF
------------------------------------------------------------
PRINT 'INSERT:acuerdosp_plazos'
truncate table u859387114_transitar..acuerdosp_plazos;
SET IDENTITY_INSERT u859387114_transitar..acuerdosp_plazos ON
INSERT INTO u859387114_transitar..acuerdosp_plazos(id,smlv_inicio,smlv_fin,cuota_minima,cuota_minima_fin,numero_cuotas) SELECT TAcuerdop_plazos_id,TAcuerdop_plazos_smlvini,TAcuerdop_plazos_smlvfin,TAcuerdop_plazos_cuotaminini,TAcuerdop_plazos_cuotaminfin,TAcuerdop_plazos_numcuotas FROM palermo..TAcuerdop_plazos;
SET IDENTITY_INSERT u859387114_transitar..acuerdosp_plazos OFF
------------------------------------------------------------
--PRINT 'INSERT:avisos'
--truncate table u859387114_transitar..avisos;
--SET IDENTITY_INSERT u859387114_transitar..avisos ON
--INSERT INTO u859387114_transitar..avisos(id,numero,tipo,archivo,indmasiv,desfijar,fecha,usuario,fecha_actualizacion) SELECT id,numero,tipo,archivo,indmasiv,desfijar,fecha,usuario,'1900-01-01' as fecha_actualizacion FROM palermo..avisos;
--SET IDENTITY_INSERT u859387114_transitar..avisos OFF
------------------------------------------------------------
--PRINT 'INSERT:avisos_resoluciones'
--truncate table u859387114_transitar..avisos_resoluciones;
--SET IDENTITY_INSERT u859387114_transitar..avisos_resoluciones ON
--INSERT INTO u859387114_transitar..avisos_resoluciones(id,aviso,resolucion,notifica,mandamiento,fecha_actualizacion) SELECT id,aviso,resolucion,notifica,mandamiento,'1900-01-01' as fecha_actualizacion FROM palermo..avisos_resoluciones;
--SET IDENTITY_INSERT u859387114_transitar..avisos_resoluciones OFF
------------------------------------------------------------
PRINT 'INSERT:bancos'
truncate table u859387114_transitar..bancos;
SET IDENTITY_INSERT u859387114_transitar..bancos ON
INSERT INTO u859387114_transitar..bancos(id,nombre,Tbancos_direccion,Tbancos_ciudad,Tbancos_departamento,Tbancos_activo,fecha_actualizacion) SELECT Tbancos_ID,Tbancos_nombre,Tbancos_direccion,Tbancos_ciudad,Tbancos_departamento,Tbancos_activo,'1900-01-01' as fecha_actualizacion FROM palermo..Tbancos;
SET IDENTITY_INSERT u859387114_transitar..bancos OFF
------------------------------------------------------------
--PRINT 'INSERT:citaciones'
--truncate table u859387114_transitar..citaciones;
--SET IDENTITY_INSERT u859387114_transitar..citaciones ON
--INSERT INTO u859387114_transitar..citaciones(id,idref,fechahora,comparendo,estado,comentario,infractor,consultor,timestamp,archivo,video,username) SELECT id,idref,fechahora,comparendo,estado,comentario,infractor,consultor,timestamp,archivo,video,username FROM palermo..citaciones;
--SET IDENTITY_INSERT u859387114_transitar..citaciones OFF
------------------------------------------------------------
--PRINT 'INSERT:citaciones_estados'
--truncate table u859387114_transitar..citaciones_estados;
--SET IDENTITY_INSERT u859387114_transitar..citaciones_estados ON
--INSERT INTO u859387114_transitar..citaciones_estados(id,nombre) SELECT id,nombre FROM palermo..citas_estados;
--SET IDENTITY_INSERT u859387114_transitar..citaciones_estados OFF
------------------------------------------------------------
PRINT 'INSERT:ciudades'
truncate table u859387114_transitar..ciudades;
SET IDENTITY_INSERT u859387114_transitar..ciudades ON
INSERT INTO u859387114_transitar..ciudades(id,nombre,ciudad,departamento) SELECT Tciudades_ID,Tciudades_nombre,Tciudades_ciudad,Tciudades_departamento FROM palermo..Tciudades;
SET IDENTITY_INSERT u859387114_transitar..ciudades OFF
------------------------------------------------------------
PRINT 'INSERT:clase_vehiculo'
truncate table u859387114_transitar..clase_vehiculo;
SET IDENTITY_INSERT u859387114_transitar..clase_vehiculo ON
INSERT INTO u859387114_transitar..clase_vehiculo(id,nombre) SELECT Tclase_ID,Tclase_nombre FROM palermo..TVehiculos_clase;
SET IDENTITY_INSERT u859387114_transitar..clase_vehiculo OFF
------------------------------------------------------------
PRINT 'INSERT:comparendos_estados'
truncate table u859387114_transitar..comparendos_estados;
INSERT INTO u859387114_transitar..comparendos_estados(id,nombre,simit) SELECT Tcomparendosestados_ID,Tcomparendosestados_estado,Tcomparendosestados_simit FROM palermo..Tcomparendosestados;
------------------------------------------------------------
PRINT 'INSERT:comparendos_origen'
truncate table u859387114_transitar..comparendos_origen;
SET IDENTITY_INSERT u859387114_transitar..comparendos_origen ON
INSERT INTO u859387114_transitar..comparendos_origen(id,nombre) SELECT Tcomparendos_origen_ID,Tcomparendos_origen_origen FROM palermo..Tcomparendos_origen;
SET IDENTITY_INSERT u859387114_transitar..comparendos_origen OFF
------------------------------------------------------------
PRINT 'INSERT:comparendos_sanciones'
truncate table u859387114_transitar..comparendos_sanciones;
SET IDENTITY_INSERT u859387114_transitar..comparendos_sanciones ON
INSERT INTO u859387114_transitar..comparendos_sanciones(id,nombre) SELECT Tcomparendossanciones_ID,Tcomparendossanciones_sancion FROM palermo..Tcomparendossanciones;
SET IDENTITY_INSERT u859387114_transitar..comparendos_sanciones OFF
------------------------------------------------------------
PRINT 'INSERT:conceptos_operacion'
truncate table u859387114_transitar..conceptos_operacion;
SET IDENTITY_INSERT u859387114_transitar..conceptos_operacion ON
INSERT INTO u859387114_transitar..conceptos_operacion(id,nombre) SELECT Toperaciones_ID,Toperaciones_operacion FROM palermo..Toperaciones;
SET IDENTITY_INSERT u859387114_transitar..conceptos_operacion OFF
------------------------------------------------------------
PRINT 'INSERT:departamentos'
truncate table u859387114_transitar..departamentos;
INSERT INTO u859387114_transitar..departamentos(id,nombre) SELECT Tdepartamentos_ID,Tdepartamentos_nombre FROM palermo..Tdepartamentos;
------------------------------------------------------------
PRINT 'INSERT:derechos_transito'
truncate table u859387114_transitar..derechos_transito;
SET IDENTITY_INSERT u859387114_transitar..derechos_transito ON
INSERT INTO u859387114_transitar..derechos_transito(TDT_ID,TDT_placa,TDT_ano,TDT_estado,TDT_tramite,TDT_honorarios,TDT_cobranza,TDT_fecha,TDT_user,TDT_archivo,TDT_doccobro) SELECT TDT_ID,TDT_placa,TDT_ano,TDT_estado,TDT_tramite,TDT_honorarios,TDT_cobranza,TDT_fecha,TDT_user,TDT_archivo, null as TDT_doccobro FROM palermo..TDT;
SET IDENTITY_INSERT u859387114_transitar..derechos_transito OFF
------------------------------------------------------------
PRINT 'INSERT:detalle_tramites'
truncate table u859387114_transitar..detalle_tramites;
SET IDENTITY_INSERT u859387114_transitar..detalle_tramites ON
INSERT INTO u859387114_transitar..detalle_tramites(Ttramites_conceptos_ID,tramite_id,concepto_id) SELECT Ttramites_conceptos_ID,Ttramites_conceptos_T,Ttramites_conceptos_C FROM palermo..Ttramites_conceptos;
SET IDENTITY_INSERT u859387114_transitar..detalle_tramites OFF
------------------------------------------------------------
PRINT 'INSERT:empleados'
truncate table u859387114_transitar..empleados;
SET IDENTITY_INSERT u859387114_transitar..empleados ON
INSERT INTO u859387114_transitar..empleados(id,identificacion,nombres,apellidos,direccion,telefono,ciudad,fecha_nacimiento,sexo,estadoc,grupos,email,profesion,cargo,sucursal,area,tipo_contrato,fecha_ingreso,fecha_fin,salario,eps,pension,arp,cesantias,cajac,usuario,firma,idusuario,fecha_relevo) SELECT Templeados_ID,Templeados_identificacion,Templeados_nombres,Templeados_apellidos,Templeados_dir,Templeados_tel,Templeados_ciudad,Templeados_fechanac,Templeados_sexo,Templeados_estadoc,Templeados_grupos,Templeados_email,Templeados_profesion,Templeados_cargo,Templeados_sucursal,Templeados_area,Templeados_tipocontrato,Templeados_fechaingreso,Templeados_fechafin,Templeados_salario,Templeados_eps,Templeados_pension,Templeados_arp,Templeados_cesantias,Templeados_cajac,Templeados_usuario,Templeados_firma,Templeados_idusuario, null as Templeados_fecharelevo FROM palermo..Templeados;
SET IDENTITY_INSERT u859387114_transitar..empleados OFF
------------------------------------------------------------
PRINT 'INSERT:especies_venales'
truncate table u859387114_transitar..especies_venales;
SET IDENTITY_INSERT u859387114_transitar..especies_venales ON
INSERT INTO u859387114_transitar..especies_venales(id,tipo,tipo_servicio,clase_vehiculo,docasignacion,entasignacion,asignacion,inicio,fin,cantidad,proveedor,factura,fecha,usuario,fecha_factura,clasificacion) SELECT TEVadmin_ID,TEVadmin_tipoEV,TEVadmin_tiposervicio,TEVadmin_claseV,TEVadmin_docasignacion,TEVadmin_entasignacion,TEVadmin_asignacion,0 as inicio,0 as fin,TEVadmin_cantidad,TEVadmin_proveedor,TEVadmin_factura,TEVadmin_fecha,TEVadmin_user,TEVadmin_fechafactura,TEVadmin_clasificacion FROM palermo..TEVadmin;
SET IDENTITY_INSERT u859387114_transitar..especies_venales OFF
------------------------------------------------------------
PRINT 'INSERT:especies_venales_estados'
truncate table u859387114_transitar..especies_venales_estados;
SET IDENTITY_INSERT u859387114_transitar..especies_venales_estados ON
INSERT INTO u859387114_transitar..especies_venales_estados(id,nombre) SELECT TEVEstados_ID,TEVEstados_estado FROM palermo..TEVEstados;
SET IDENTITY_INSERT u859387114_transitar..especies_venales_estados OFF
------------------------------------------------------------
PRINT 'INSERT:especies_venales_tipos'
truncate table u859387114_transitar..especies_venales_tipos;
SET IDENTITY_INSERT u859387114_transitar..especies_venales_tipos ON
INSERT INTO u859387114_transitar..especies_venales_tipos(id,nombre) SELECT TEVtipo_ID,TEVtipo_tipo FROM palermo..TEVtipo;
SET IDENTITY_INSERT u859387114_transitar..especies_venales_tipos OFF
------------------------------------------------------------
PRINT 'INSERT:festivos'
truncate table u859387114_transitar..festivos;
SET IDENTITY_INSERT u859387114_transitar..festivos ON
INSERT INTO u859387114_transitar..festivos(Tfestivos_id,Tfestivos_fecha,Tfestivos_descripcion,Tfestivos_estilo,Tfestivos_tipo) SELECT Tfestivos_id,Tfestivos_fecha,Tfestivos_descripcion,Tfestivos_estilo,Tfestivos_tipo FROM palermo..Tfestivos;
SET IDENTITY_INSERT u859387114_transitar..festivos OFF
------------------------------------------------------------
PRINT 'INSERT:grupo_sanguineo'
truncate table u859387114_transitar..grupo_sanguineo;
SET IDENTITY_INSERT u859387114_transitar..grupo_sanguineo ON
INSERT INTO u859387114_transitar..grupo_sanguineo(id,nombre) SELECT Tgruposanguineo_ID,Tgruposanguineo_nombre FROM palermo..Tgruposanguineo;
SET IDENTITY_INSERT u859387114_transitar..grupo_sanguineo OFF
------------------------------------------------------------
PRINT 'INSERT:ipc'
truncate table u859387114_transitar..ipc;
INSERT INTO u859387114_transitar..ipc(TIPC_ID,TIPC_ano,TIPC_IPC) SELECT TIPC_ID,TIPC_ano,TIPC_IPC FROM palermo..TIPC;
------------------------------------------------------------
PRINT 'INSERT:liquidacion_estados'
truncate table u859387114_transitar..liquidacion_estados;
SET IDENTITY_INSERT u859387114_transitar..liquidacion_estados ON
INSERT INTO u859387114_transitar..liquidacion_estados(id,nombre) SELECT Tliquidacionestados_ID,Tliquidacionestados_nombre FROM palermo..Tliquidacionestados;
SET IDENTITY_INSERT u859387114_transitar..liquidacion_estados OFF
------------------------------------------------------------
PRINT 'INSERT:marca'
truncate table u859387114_transitar..marca;
INSERT INTO u859387114_transitar..marca(id,nombre) SELECT TVehiculos_marcas_ID,TVehiculos_marcas_descripcion FROM palermo..TVehiculos_marcas;
------------------------------------------------------------
PRINT 'INSERT:medcautcomp'
truncate table u859387114_transitar..medcautcomp;
SET IDENTITY_INSERT u859387114_transitar..medcautcomp ON
INSERT INTO u859387114_transitar..medcautcomp(id,compid,mctipo,mcestado,mcnumero,banco,valor,archivo,fecha,usuario,levnumero,levarchivo,levfecha,levusuario) SELECT id,compid,mctipo,mcestado,mcnumero,banco,valor,archivo,fecha,usuario,levnumero,levarchivo,levfecha,levusuario FROM palermo..medcautcomp;
SET IDENTITY_INSERT u859387114_transitar..medcautcomp OFF
------------------------------------------------------------
PRINT 'INSERT:mmcestado'
truncate table u859387114_transitar..mmcestado;
SET IDENTITY_INSERT u859387114_transitar..mmcestado ON
INSERT INTO u859387114_transitar..mmcestado(id,nombre) SELECT id,nombre FROM palermo..mmcestado;
SET IDENTITY_INSERT u859387114_transitar..mmcestado OFF
------------------------------------------------------------
PRINT 'INSERT:mmctipos'
truncate table u859387114_transitar..mmctipos;
SET IDENTITY_INSERT u859387114_transitar..mmctipos ON
INSERT INTO u859387114_transitar..mmctipos(id,nombre) SELECT id,nombre FROM palermo..mmctipos;
SET IDENTITY_INSERT u859387114_transitar..mmctipos OFF
------------------------------------------------------------
--PRINT 'INSERT:morososcgn_semestral'
--truncate table u859387114_transitar..morososcgn_semestral;
--INSERT INTO u859387114_transitar..morososcgn_semestral(id,tipo_deudor,numero_obligacion,ciudadano_identificacion,ciudadano_tipo,ciudadano_nombrecompleto,sumaobligaciones,detalleobligaciones,cantidadobligaciones,creado,borrado,creado_por,borrado_por,retirado,retirado_por,retirado_causal,actualizado,actualizado_por,actualizado_en,actualizado_estado) SELECT id,tipo_deudor,numero_obligacion,ciudadano_identificacion,ciudadano_tipo,ciudadano_nombrecompleto,sumaobligaciones,detalleobligaciones,cantidadobligaciones,creado,borrado,creado_por,borrado_por,retirado,retirado_por,retirado_causal,actualizado,actualizado_por,actualizado_en,actualizado_estado FROM palermo..morososCGN_semestral;
------------------------------------------------------------
PRINT 'INSERT:niveles_blindaje'
truncate table u859387114_transitar..niveles_blindaje;
SET IDENTITY_INSERT u859387114_transitar..niveles_blindaje ON
INSERT INTO u859387114_transitar..niveles_blindaje(id,nombre) SELECT Tvehiculos_blindaje_nivel_id,Tvehiculos_blindaje_nivel_n FROM palermo..Tvehiculos_blindaje_nivel;
SET IDENTITY_INSERT u859387114_transitar..niveles_blindaje OFF
------------------------------------------------------------
PRINT 'INSERT:notas_credito'
truncate table u859387114_transitar..notas_credito;
INSERT INTO u859387114_transitar..notas_credito(id,liquidacion,valor,saldo,identificacion,estado,fecha,usuario,fecha_anulacion,usuario_anulacion) SELECT Tnotascredito_ID,Tnotascredito_liquidacion,Tnotascredito_valor,Tnotascredito_saldo,Tnotascredito_identificacion,Tnotascredito_estado,Tnotascredito_fecha,Tnotascredito_user,Tnotascredito_fanulado,Tnotascredito_useranula FROM palermo..Tnotascredito;
------------------------------------------------------------
PRINT 'INSERT:notas_credito_estados'
truncate table u859387114_transitar..notas_credito_estados;
SET IDENTITY_INSERT u859387114_transitar..notas_credito_estados ON
INSERT INTO u859387114_transitar..notas_credito_estados(id,nombre) SELECT Tnotascreditoestado_ID,Tnotascreditoestado_nombre FROM palermo..Tnotascreditoestado;
SET IDENTITY_INSERT u859387114_transitar..notas_credito_estados OFF
------------------------------------------------------------
PRINT 'INSERT:notas_credito_usadas'
truncate table u859387114_transitar..notas_credito_usadas;
SET IDENTITY_INSERT u859387114_transitar..notas_credito_usadas ON
INSERT INTO u859387114_transitar..notas_credito_usadas(id,nc,liquidacion,valor,fecha,fecha_actualizacion) SELECT Tnotascreditoused_ID,Tnotascreditoused_NC,Tnotascreditoused_liquidacion,Tnotascreditoused_valor,Tnotascreditoused_fecha,'1900-01-01' as fecha_actualizacion FROM palermo..Tnotascreditoused;
SET IDENTITY_INSERT u859387114_transitar..notas_credito_usadas OFF
------------------------------------------------------------
PRINT 'INSERT:notificaciones'
truncate table u859387114_transitar..notificaciones;
SET IDENTITY_INSERT u859387114_transitar..notificaciones ON
INSERT INTO u859387114_transitar..notificaciones(id,compId,tipo,fnotant,fnotnew,archivo,documento,estadoant,fecha,username,infant,infnew,presente,nauto,resrevid) SELECT id,compId,tipo,fnotant,fnotnew,archivo,documento,estadoant,fecha,username,infant,infnew,presente,nauto,resrevid FROM palermo..notificaciones;
SET IDENTITY_INSERT u859387114_transitar..notificaciones OFF
------------------------------------------------------------
PRINT 'INSERT:parametros_economicos'
truncate table u859387114_transitar..parametros_economicos;
INSERT INTO u859387114_transitar..parametros_economicos(Tparameconomicos_ID,Tparameconomicos_honorarios,Tparameconomicos_cobranza,Tparameconomicos_perCoa,Tparameconomicos_iva,Tparameconomicos_diasinteres,Tparameconomicos_daap,Tparameconomicos_dvap,Tparameconomicos_rc,Tparameconomicos_prescribir,Tparameconomicos_vadicComp,Tparameconomicos_vadicional,Tparameconomicos_porSA,Tparameconomicos_porMP,Tparameconomicos_porctInt) SELECT Tparameconomicos_ID,Tparameconomicos_honorarios,Tparameconomicos_cobranza,Tparameconomicos_perCoa,Tparameconomicos_iva,Tparameconomicos_diasinteres,Tparameconomicos_daap,Tparameconomicos_dvap,Tparameconomicos_rc,Tparameconomicos_prescribir,Tparameconomicos_vadicComp,Tparameconomicos_vadicional,Tparameconomicos_porSA,Tparameconomicos_porMP,Tparameconomicos_porctInt FROM palermo..Tparameconomicos;
------------------------------------------------------------
PRINT 'INSERT:parametros_generales'
truncate table u859387114_transitar..parametros_generales;
INSERT INTO u859387114_transitar..parametros_generales(Tparamgenerales_ID,Tparamgenerales_nombre_app,Tparamgenerales_img_logo,Tparamgenerales_img_fondo,Tparamgenerales_titulo_app,Tparamgenerales_diasnotifica,Tparamgenerales_minutossesion,Tparamgenerales_dias_cam_pwd,Tparamgenerales_favicon) SELECT Tparamgenerales_ID,Tparamgenerales_nombre_app,Tparamgenerales_img_logo,Tparamgenerales_img_fondo,Tparamgenerales_titulo_app,Tparamgenerales_diasnotifica,Tparamgenerales_minutossesion,Tparamgenerales_dias_cam_pwd,Tparamgenerales_favicon FROM palermo..Tparamgenerales;
------------------------------------------------------------
PRINT 'INSERT:parametros_liquidacion'
truncate table u859387114_transitar..parametros_liquidacion;
INSERT INTO u859387114_transitar..parametros_liquidacion(Tparametrosliq_ID,Tparametrosliq_DVL,Tparametrosliq_DVLI,Tparametrosliq_DVT,Tparametrosliq_DVGNC,Tparametrosliq_DVNC,Tparametrosliq_logo,Tparametrosliq_ct,Tparametrosliq_leyenda1,Tparametrosliq_leyenda2,Tparametrosliq_leyenda3,Tparametrosliq_soat,Tparametrosliq_tecno,Tparametrosliq_inf,Tparametrosliq_archivos,Tparametrosliq_dfecha2,Tparametrosliq_pdesc1,Tparametrosliq_dfecha3,Tparametrosliq_pdesc2,Tparametrosliq_dt,Tparametrosliq_claserv,Tparametrosliq_pig,Tparametrosliq_mc,Tparametrosliq_rc,Tparametrosliq_vp,Tparametrosliq_despig,Tparametrosliq_cs,Tparametrosliq_veh,Tparametrosliq_gas,Tparametrosliq_inactivo,Tparametrosliq_particular,Tparametrosliq_codbarras,Tparametrosliq_gs1id,Tparametrosliq_gs1id_2,Tparametrosliq_aparece_ds,Tparametrosliq_copias,Tparametrosliq_rnc_cs,Tparametrosliq_valcomp,Tparametrosliq_genVDT,Tparametrosliq_agrupa) SELECT Tparametrosliq_ID,Tparametrosliq_DVL,Tparametrosliq_DVLI,Tparametrosliq_DVT,Tparametrosliq_DVGNC,Tparametrosliq_DVNC,Tparametrosliq_logo,Tparametrosliq_ct,Tparametrosliq_leyenda1,Tparametrosliq_leyenda2,Tparametrosliq_leyenda3,Tparametrosliq_soat,Tparametrosliq_tecno,Tparametrosliq_inf,Tparametrosliq_archivos,Tparametrosliq_dfecha2,Tparametrosliq_pdesc1,Tparametrosliq_dfecha3,Tparametrosliq_pdesc2,Tparametrosliq_dt,Tparametrosliq_claserv,Tparametrosliq_pig,Tparametrosliq_mc,Tparametrosliq_rc,Tparametrosliq_vp,Tparametrosliq_despig,Tparametrosliq_cs,Tparametrosliq_veh,Tparametrosliq_gas,Tparametrosliq_inactivo,Tparametrosliq_particular,Tparametrosliq_codbarras,Tparametrosliq_gs1id,Tparametrosliq_gs1id_2,Tparametrosliq_aparece_ds,Tparametrosliq_copias,Tparametrosliq_rnc_cs,Tparametrosliq_valcomp,Tparametrosliq_genVDT,Tparametrosliq_agrupa FROM palermo..Tparametrosliq;
------------------------------------------------------------
--PRINT 'INSERT:parametros_medidas_cautelares'
--truncate table u859387114_transitar..parametros_medidas_cautelares;
--INSERT INTO u859387114_transitar..parametros_medidas_cautelares(Tparammcc_ID,Tparammcc_banco,Tparammcc_tipo,Tparammcc_numero,Tparammcc_titular,Tparammcc_nit,Tparammcc_correo,Tparammcc_telefonos) SELECT Tparammcc_ID,Tparammcc_banco,Tparammcc_tipo,Tparammcc_numero,Tparammcc_titular,Tparammcc_nit,Tparammcc_correo,Tparammcc_telefonos FROM palermo..TPARAMMCC;
------------------------------------------------------------
PRINT 'INSERT:parametros_recaudo'
truncate table u859387114_transitar..parametros_recaudo;
SET IDENTITY_INSERT u859387114_transitar..parametros_recaudo ON
INSERT INTO u859387114_transitar..parametros_recaudo(id,permite_fecha_recaudo) SELECT Tparametrosrecaudo_ID,Tparametrosrecaudo_allowfr FROM palermo..Tparametrosrecaudo;
SET IDENTITY_INSERT u859387114_transitar..parametros_recaudo OFF
------------------------------------------------------------
PRINT 'INSERT:parametros_simit_ws'
truncate table u859387114_transitar..parametros_simit_ws;
INSERT INTO u859387114_transitar..parametros_simit_ws(TParametrosWS_id,TParametrosWS_url,TParametrosWS_secretaria,TParametrosWS_usuario,TParametrosWS_contrasena,TParametrosWS_activo) SELECT TParametrosWS_id,TParametrosWS_url,TParametrosWS_secretaria,TParametrosWS_usuario,TParametrosWS_contrasena,TParametrosWS_activo FROM palermo..TParametrosWS;
------------------------------------------------------------
PRINT 'INSERT:placas'
truncate table u859387114_transitar..placas;
SET IDENTITY_INSERT u859387114_transitar..placas ON
INSERT INTO u859387114_transitar..placas(Tplacas_ID,Tplacas_placa,Tplacas_estado,Tplacas_servicio,Tplacas_clase,Tplacas_clasif,tplacas_tercero,Tplacas_fechac,Tplacas_fechau,Tplacas_IDAdmin,Tplacas_user,Tplacas_observ) SELECT Tplacas_ID,Tplacas_placa,Tplacas_estado,Tplacas_servicio,Tplacas_clase,Tplacas_clasif,tplacas_tercero,Tplacas_fechac,Tplacas_fechau,Tplacas_IDAdmin,Tplacas_user,Tplacas_observ FROM palermo..Tplacas;
SET IDENTITY_INSERT u859387114_transitar..placas OFF
------------------------------------------------------------
PRINT 'INSERT:placas_estados'
truncate table u859387114_transitar..placas_estados;
SET IDENTITY_INSERT u859387114_transitar..placas_estados ON
INSERT INTO u859387114_transitar..placas_estados(id,nombre) SELECT Tplacasestado_ID,Tplacasestado_nombre FROM palermo..Tplacasestado;
SET IDENTITY_INSERT u859387114_transitar..placas_estados OFF
------------------------------------------------------------
PRINT 'INSERT:resan_anterior'
truncate table u859387114_transitar..resan_anterior;
SET IDENTITY_INSERT u859387114_transitar..resan_anterior ON
INSERT INTO u859387114_transitar..resan_anterior(id,compLargo,comparendo,fechaComp,cartera,sancion,fechaSancion,coactivo,fechaCoactivo,compId) SELECT id,compLargo,comparendo,fechaComp,cartera,sancion,fechaSancion,coactivo,fechaCoactivo,compId FROM palermo..resan_anterior;
SET IDENTITY_INSERT u859387114_transitar..resan_anterior OFF
------------------------------------------------------------
--PRINT 'INSERT:resolucion_revocada'
--truncate table u859387114_transitar..resolucion_revocada;
--SET IDENTITY_INSERT u859387114_transitar..resolucion_revocada ON
--INSERT INTO u859387114_transitar..resolucion_revocada(ressan_id,ressan_ano,ressan_numero,ressan_tipo,ressan_comparendo,ressan_archivo,ressan_fecha,ressan_observaciones,ressan_exportado,ressan_resant,ressan_compid,ressan_usuario,usuario,fecha) SELECT ressan_id,ressan_ano,ressan_numero,ressan_tipo,ressan_comparendo,ressan_archivo,ressan_fecha,ressan_observaciones,ressan_exportado,ressan_resant,ressan_compid,ressan_usuario,usuario,fecha FROM palermo..resolucion_revocada;
--SET IDENTITY_INSERT u859387114_transitar..resolucion_revocada OFF
------------------------------------------------------------
PRINT 'INSERT:resolucion_sancion_tipo'
truncate table u859387114_transitar..resolucion_sancion_tipo;
SET IDENTITY_INSERT u859387114_transitar..resolucion_sancion_tipo ON
INSERT INTO u859387114_transitar..resolucion_sancion_tipo(id,nombre,sigla,origen,simit) SELECT resolucion_tipo_id,resolucion_tipo_nombre,resolucion_tipo_sigla,resolucion_tipo_origen,resolucion_tipo_simit FROM palermo..resolucion_tipo;
SET IDENTITY_INSERT u859387114_transitar..resolucion_sancion_tipo OFF
------------------------------------------------------------
PRINT 'INSERT:ressan_dt'
truncate table u859387114_transitar..ressan_dt;
INSERT INTO u859387114_transitar..ressan_dt(resdt_id,resdt_tipo,resdt_identificacion,resdt_placa,resdt_numero,resdt_fechares,resdt_anioini,resdt_aniofin,resdt_fecha,resdt_user,resdt_archivo,resdt_exportado,resdt_nota) SELECT resdt_id,resdt_tipo,resdt_identificacion,resdt_placa,resdt_numero,resdt_fechares,resdt_anioini,resdt_aniofin,resdt_fecha,resdt_user,resdt_archivo,resdt_exportado,resdt_nota FROM palermo..ressan_dt;
------------------------------------------------------------
PRINT 'INSERT:sedes'
truncate table u859387114_transitar..sedes;
SET IDENTITY_INSERT u859387114_transitar..sedes ON
INSERT INTO u859387114_transitar..sedes(id,Tsedes_RS,nit,siglas,pais,departamento,municipio,direccion,tel1,tel2,email,divipo,resolucion,fecha,ppal,ciudad,director) SELECT Tsedes_ID,Tsedes_RS,Tsedes_NIT,Tsedes_siglas,Tsedes_pais,Tsedes_DPTO,Tsedes_municipio,Tsedes_DIR,Tsedes_tel1,Tsedes_tel2,Tsedes_email,Tsedes_divipo,Tsedes_resolucion,Tsedes_fecha,Tsedes_ppal,Tsedes_ciudad,Tsedes_director FROM palermo..Tsedes;
SET IDENTITY_INSERT u859387114_transitar..sedes OFF
------------------------------------------------------------
PRINT 'INSERT:sexo'
truncate table u859387114_transitar..sexo;
SET IDENTITY_INSERT u859387114_transitar..sexo ON
INSERT INTO u859387114_transitar..sexo(id,nombre) SELECT Tsexo_ID,Tsexo_nombre FROM palermo..Tsexo;
SET IDENTITY_INSERT u859387114_transitar..sexo OFF
------------------------------------------------------------
PRINT 'INSERT:si_no'
truncate table u859387114_transitar..si_no;
SET IDENTITY_INSERT u859387114_transitar..si_no ON
INSERT INTO u859387114_transitar..si_no(id,nombre) SELECT Tparametrossino_ID,Tparametrossino_descripcion FROM palermo..Tparametrossino;
SET IDENTITY_INSERT u859387114_transitar..si_no OFF
------------------------------------------------------------
PRINT 'INSERT:smlv'
truncate table u859387114_transitar..smlv;
SET IDENTITY_INSERT u859387114_transitar..smlv ON
INSERT INTO u859387114_transitar..smlv(id,ano,smlv,smlv_original,uvt_original,Tsmlv_uvb) SELECT Tsmlv_ID,Tsmlv_ano,Tsmlv_smlv,Tsmlv_smlvorginal,Tsmlv_uvt,Tsmlv_uvb FROM palermo..Tsmlv;
SET IDENTITY_INSERT u859387114_transitar..smlv OFF
------------------------------------------------------------
PRINT 'INSERT:tbancostcuentas'
truncate table u859387114_transitar..tbancostcuentas;
SET IDENTITY_INSERT u859387114_transitar..tbancostcuentas ON
INSERT INTO u859387114_transitar..tbancostcuentas(Tbancostcuentas_ID,Tbancostcuentas_nombre) SELECT Tbancostcuentas_ID,Tbancostcuentas_nombre FROM palermo..Tbancostcuentas;
SET IDENTITY_INSERT u859387114_transitar..tbancostcuentas OFF
------------------------------------------------------------
PRINT 'INSERT:tdtestado'
truncate table u859387114_transitar..tdtestado;
SET IDENTITY_INSERT u859387114_transitar..tdtestado ON
INSERT INTO u859387114_transitar..tdtestado(id,nombre) SELECT TDTestado_ID,TDTestado_estado FROM palermo..TDTestado;
SET IDENTITY_INSERT u859387114_transitar..tdtestado OFF
------------------------------------------------------------
PRINT 'INSERT:terceros'
truncate table u859387114_transitar..terceros;
SET IDENTITY_INSERT u859387114_transitar..terceros ON
INSERT INTO u859387114_transitar..terceros(id,nombre,Tterceros_ID_externo,Tterceros_tipoid,Tterceros_identifica,Tterceros_apellido,Tterceros_tipo,Tterceros_dir,Tterceros_tel,Tterceros_email,Tterceros_pweb,Tterceros_contacto,Tterceros_inscripcion,Tterceros_finscripcion,Tterceros_cupo,Tterceros_tarifa,Tterceros_fecha,Tterceros_user,Tterceros_placa,Tterceros_entidad,Tterceros_gs1id) SELECT Tterceros_ID,Tterceros_nombre,Tterceros_ID_externo,Tterceros_tipoid,Tterceros_identifica,Tterceros_apellido,Tterceros_tipo,Tterceros_dir,Tterceros_tel,Tterceros_email,Tterceros_pweb,Tterceros_contacto,Tterceros_inscripcion,Tterceros_finscripcion,Tterceros_cupo,Tterceros_tarifa,Tterceros_fecha,Tterceros_user,Tterceros_placa,Tterceros_entidad,Tterceros_gs1id FROM palermo..Tterceros;
SET IDENTITY_INSERT u859387114_transitar..terceros OFF
------------------------------------------------------------
PRINT 'INSERT:terceros_tipos'
truncate table u859387114_transitar..terceros_tipos;
SET IDENTITY_INSERT u859387114_transitar..terceros_tipos ON
INSERT INTO u859387114_transitar..terceros_tipos(Ttercerostipo_ID,Ttercerostipo_nombre) SELECT Ttercerostipo_ID,Ttercerostipo_nombre FROM palermo..Ttercerostipo;
SET IDENTITY_INSERT u859387114_transitar..terceros_tipos OFF
------------------------------------------------------------
PRINT 'INSERT:texportplano'
truncate table u859387114_transitar..texportplano;
INSERT INTO u859387114_transitar..texportplano(Texportplano_ID,Texportplano_comp,Texportplano_tipo,Texportplano_idarch,Texportplano_user,Texportplano_fecha,Texportplano_resano,Texportplano_resnumero,Texportplano_restipo,Texportplano_cuota) SELECT Texportplano_ID,Texportplano_comp,Texportplano_tipo,Texportplano_idarch,Texportplano_user,Texportplano_fecha,Texportplano_resano,Texportplano_resnumero,Texportplano_restipo,Texportplano_cuota FROM palermo..Texportplano;
------------------------------------------------------------
PRINT 'INSERT:thonocobra'
truncate table u859387114_transitar..thonocobra;
INSERT INTO u859387114_transitar..thonocobra(THonoCobra_ID,THonoCobra_deudaID,THonoCobra_deudaTipo,THonoCobra_cobroTipo,THonoCobra_tercero,THonoCobra_fecha,THonoCobra_user) SELECT THonoCobra_ID,THonoCobra_deudaID,THonoCobra_deudaTipo,THonoCobra_cobroTipo,THonoCobra_tercero,THonoCobra_fecha,THonoCobra_user FROM palermo..THonoCobra;
------------------------------------------------------------
PRINT 'INSERT:tinteresesm'
truncate table u859387114_transitar..tinteresesm;
INSERT INTO u859387114_transitar..tinteresesm(id,Tinteresesm_finicial,Tinteresesm_ffinal,Tinteresesm_TEA,Tinteresesm_TEAD,Tinteresesm_graini,Tinteresesm_grafin) SELECT Tinteresesm_ID,Tinteresesm_finicial,Tinteresesm_ffinal,Tinteresesm_TEA,Tinteresesm_TEAD,Tinteresesm_graini,Tinteresesm_grafin FROM palermo..Tinteresesm;
------------------------------------------------------------
PRINT 'INSERT:tipo_ciudadano'
truncate table u859387114_transitar..tipo_ciudadano;
SET IDENTITY_INSERT u859387114_transitar..tipo_ciudadano ON
INSERT INTO u859387114_transitar..tipo_ciudadano(id,nombre) SELECT Tciudadanostipo_ID,Tciudadanostipo_tipo FROM palermo..Tciudadanostipo;
SET IDENTITY_INSERT u859387114_transitar..tipo_ciudadano OFF
------------------------------------------------------------
PRINT 'INSERT:tipo_cuenta'
truncate table u859387114_transitar..tipo_cuenta;
SET IDENTITY_INSERT u859387114_transitar..tipo_cuenta ON
INSERT INTO u859387114_transitar..tipo_cuenta(id,nombre) SELECT Tbancostcuentas_ID,Tbancostcuentas_nombre FROM palermo..Tbancostcuentas;
SET IDENTITY_INSERT u859387114_transitar..tipo_cuenta OFF
------------------------------------------------------------
PRINT 'INSERT:tipo_identificacion'
truncate table u859387114_transitar..tipo_identificacion;
SET IDENTITY_INSERT u859387114_transitar..tipo_identificacion ON
INSERT INTO u859387114_transitar..tipo_identificacion(id,nombre,simit) SELECT Ttipoidentificacion_ID,Ttipoidentificacion_nombre,Ttipoidentificacion_simit FROM palermo..Ttipoidentificacion;
SET IDENTITY_INSERT u859387114_transitar..tipo_identificacion OFF
------------------------------------------------------------
PRINT 'INSERT:tipo_servicio'
truncate table u859387114_transitar..tipo_servicio;
SET IDENTITY_INSERT u859387114_transitar..tipo_servicio ON
INSERT INTO u859387114_transitar..tipo_servicio(id,nombre,simit) SELECT Tservicio_ID,Tservicio_servicio,Tservicio_simit FROM palermo..Tvehiculos_servicio;
SET IDENTITY_INSERT u859387114_transitar..tipo_servicio OFF
------------------------------------------------------------
PRINT 'INSERT:tipo_tramite'
truncate table u859387114_transitar..tipo_tramite;
SET IDENTITY_INSERT u859387114_transitar..tipo_tramite ON
INSERT INTO u859387114_transitar..tipo_tramite(id,nombre) SELECT Ttipodoc_ID,Ttipodoc_nombre FROM palermo..Ttipodoc;
SET IDENTITY_INSERT u859387114_transitar..tipo_tramite OFF
------------------------------------------------------------
PRINT 'INSERT:tipo_traspaso'
truncate table u859387114_transitar..tipo_traspaso;
SET IDENTITY_INSERT u859387114_transitar..tipo_traspaso ON
INSERT INTO u859387114_transitar..tipo_traspaso(id,nombre) SELECT Tvehiculos_traspaso_tipo_ID,Tvehiculos_traspaso_tipo_tipo FROM palermo..Tvehiculos_traspaso_tipo;
SET IDENTITY_INSERT u859387114_transitar..tipo_traspaso OFF
------------------------------------------------------------
PRINT 'INSERT:tipos'
truncate table u859387114_transitar..tipos;
SET IDENTITY_INSERT u859387114_transitar..tipos ON
INSERT INTO u859387114_transitar..tipos(id,nombre) SELECT Ttercerostipo_ID,Ttercerostipo_nombre FROM palermo..Ttercerostipo;
SET IDENTITY_INSERT u859387114_transitar..tipos OFF
------------------------------------------------------------
--PRINT 'INSERT:tnotifica_estados'
--truncate table u859387114_transitar..tnotifica_estados;
--INSERT INTO u859387114_transitar..tnotifica_estados(id,nombre) SELECT id,nombre FROM palermo..Tnotifica_estados;
------------------------------------------------------------
--PRINT 'INSERT:tnotifparams'
--truncate table u859387114_transitar..tnotifparams;
--INSERT INTO u859387114_transitar..tnotifparams(Tnotifparams_ID,Tnotifparams_maxactfnot,Tnotifparams_maxactinf,Tnotifparams_autonotdias,Tnotifparams_autodesfdias,Tnotifparams_autompadias,Tnotifparams_autocitmp) SELECT Tnotifparams_ID,Tnotifparams_maxactfnot,Tnotifparams_maxactinf,Tnotifparams_autonotdias,Tnotifparams_autodesfdias,Tnotifparams_autompadias,Tnotifparams_autocitmp FROM palermo..Tnotifparams;
------------------------------------------------------------
PRINT 'INSERT:trecaudos'
truncate table u859387114_transitar..trecaudos;
SET IDENTITY_INSERT u859387114_transitar..trecaudos ON
INSERT INTO u859387114_transitar..trecaudos(Trecaudos_ID,Trecaudos_liquidacion,Trecaudos_comparendo,Trecaudos_consignacion,Trecaudos_banco,Trecaudos_cuenta,Trecaudos_fecharecaudo,Trecaudos_valor,Trecaudos_documento,Trecaudos_observaciones,Trecaudos_referencia,Trecaudos_medio,Trecaudos_fecha,Trecaudos_nombreconsig,Trecaudos_telconsig,Trecaudos_identconsig,Trecaudos_Tarjeta,Trecaudos_mesVTC,Trecaudos_anoVTC,Trecaudos_Tarjetabanco,Trecaudos_autorizacion,Trecaudos_user) SELECT Trecaudos_ID,Trecaudos_liquidacion,Trecaudos_comparendo,Trecaudos_consignacion,Trecaudos_banco,Trecaudos_cuenta,Trecaudos_fecharecaudo,Trecaudos_valor,Trecaudos_documento,Trecaudos_observaciones,Trecaudos_referencia,Trecaudos_medio,Trecaudos_fecha,Trecaudos_nombreconsig,Trecaudos_telconsig,Trecaudos_identconsig,Trecaudos_Tarjeta,Trecaudos_mesVTC,Trecaudos_anoVTC,Trecaudos_Tarjetabanco,Trecaudos_autorizacion,Trecaudos_user FROM palermo..Trecaudos;
SET IDENTITY_INSERT u859387114_transitar..trecaudos OFF
------------------------------------------------------------
PRINT 'INSERT:trecaudos_arch'
truncate table u859387114_transitar..trecaudos_arch;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_arch ON
INSERT INTO u859387114_transitar..trecaudos_arch(Trecaudos_arch_ID,Trecaudos_arch_archivo,Trecaudos_arch_nombre,Trecaudos_arch_tipo,Trecaudos_arch_tamano,Trecaudos_arch_descrip,Trecaudos_arch_md5,Trecaudos_arch_expimp,Trecaudos_arch_user,Trecaudos_arch_fecha) SELECT Trecaudos_arch_ID,Trecaudos_arch_archivo,Trecaudos_arch_nombre,Trecaudos_arch_tipo,Trecaudos_arch_tamano,Trecaudos_arch_descrip,Trecaudos_arch_md5,Trecaudos_arch_expimp,Trecaudos_arch_user,Trecaudos_arch_fecha FROM palermo..Trecaudos_arch;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_arch OFF
------------------------------------------------------------
PRINT 'INSERT:trecaudos_control'
truncate table u859387114_transitar..trecaudos_control;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_control ON
INSERT INTO u859387114_transitar..trecaudos_control(Trecaudos_control_ID,Trecaudos_control_nlinea,Trecaudos_control_tabla,Trecaudos_control_tipo,Trecaudos_control_idarch,Trecaudos_control_mens,Trecaudos_control_expimp,Trecaudos_control_user,Trecaudos_control_fecha) SELECT Trecaudos_control_ID,Trecaudos_control_nlinea,Trecaudos_control_tabla,Trecaudos_control_tipo,Trecaudos_control_idarch,Trecaudos_control_mens,Trecaudos_control_expimp,Trecaudos_control_user,Trecaudos_control_fecha FROM palermo..Trecaudos_control;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_control OFF
------------------------------------------------------------
PRINT 'INSERT:trecaudos_ec'
truncate table u859387114_transitar..trecaudos_ec;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_ec ON
INSERT INTO u859387114_transitar..trecaudos_ec(Trecaudos_ec_ID,Trecaudos_ec_encab,Trecaudos_ec_control,Trecaudos_ec_numcuenta,Trecaudos_ec_fechadesde,Trecaudos_ec_fechahasta,Trecaudos_ec_divipo,Trecaudos_ec_tiporecaudo,Trecaudos_ec_numrec,Trecaudos_ec_sumrec,Trecaudos_ec_oficio,Trecaudos_ec_codchequeo,Trecaudos_ec_idarch,Trecaudos_ec_pdf,Trecaudos_ec_expimp,Trecaudos_ec_user,Trecaudos_ec_fecha) SELECT Trecaudos_ec_ID,Trecaudos_ec_encab,Trecaudos_ec_control,Trecaudos_ec_numcuenta,Trecaudos_ec_fechadesde,Trecaudos_ec_fechahasta,Trecaudos_ec_divipo,Trecaudos_ec_tiporecaudo,Trecaudos_ec_numrec,Trecaudos_ec_sumrec,Trecaudos_ec_oficio,Trecaudos_ec_codchequeo,Trecaudos_ec_idarch,Trecaudos_ec_pdf,Trecaudos_ec_expimp,Trecaudos_ec_user,Trecaudos_ec_fecha FROM palermo..Trecaudos_ec;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_ec OFF
------------------------------------------------------------
PRINT 'INSERT:trecaudos_error'
truncate table u859387114_transitar..trecaudos_error;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_error ON
INSERT INTO u859387114_transitar..trecaudos_error(Trecaudos_error_ID,Trecaudos_error_nlinea,Trecaudos_error_ncampo,Trecaudos_error_error,Trecaudos_error_idarch,Trecaudos_error_expimp,Trecaudos_error_user,Trecaudos_error_fecha) SELECT Trecaudos_error_ID,Trecaudos_error_nlinea,Trecaudos_error_ncampo,Trecaudos_error_error,Trecaudos_error_idarch,Trecaudos_error_expimp,Trecaudos_error_user,Trecaudos_error_fecha FROM palermo..Trecaudos_error;
SET IDENTITY_INSERT u859387114_transitar..trecaudos_error OFF
------------------------------------------------------------
PRINT 'INSERT:tvehiculos_tp'
truncate table u859387114_transitar..tvehiculos_tp;
INSERT INTO u859387114_transitar..tvehiculos_tp(Tvehiculos_TP_ID,Tvehiculos_TP_liquidacion,Tvehiculos_TP_tipo,Tvehiculos_TP_identificacion,Tvehiculos_TP_contrato,Tvehiculos_TP_aceptacion,Tvehiculos_TP_permisob,Tvehiculos_TP_placa,Tvehiculos_TP_cupoafiliacion,Tvehiculos_TP_actaadjudicacion,Tvehiculos_TP_fecha,Tvehiculos_TP_verificacion,Tvehiculos_TP_user,Tvehiculos_TP_permisop,Tvehiculos_TP_idproant,Tvehiculos_TP_fechat,Tvehiculos_TP_acepveh,Tvehiculos_TP_empresa,Tvehiculos_TP_LTActual,Tvehiculos_TP_LTnueva,Tvehiculos_TP_LTdenuncia,Tvehiculos_TP_fechadenuncia,Tvehiculos_TP_sustrato,Tvehiculos_TP_liquidacionCMH,Tvehiculos_TP_fechaRUNT) SELECT Tvehiculos_TP_ID,Tvehiculos_TP_liquidacion,Tvehiculos_TP_tipo,Tvehiculos_TP_identificacion,Tvehiculos_TP_contrato,Tvehiculos_TP_aceptacion,Tvehiculos_TP_permisob,Tvehiculos_TP_placa,Tvehiculos_TP_cupoafiliacion,Tvehiculos_TP_actaadjudicacion,Tvehiculos_TP_fecha,Tvehiculos_TP_verificacion,Tvehiculos_TP_user,Tvehiculos_TP_permisop,Tvehiculos_TP_idproant,Tvehiculos_TP_fechat,Tvehiculos_TP_acepveh,Tvehiculos_TP_empresa,Tvehiculos_TP_LTActual,Tvehiculos_TP_LTnueva,Tvehiculos_TP_LTdenuncia,Tvehiculos_TP_fechadenuncia,Tvehiculos_TP_sustrato,Tvehiculos_TP_liquidacionCMH,null as Tvehiculos_TP_fechaRUNT FROM palermo..Tvehiculos_TP;
------------------------------------------------------------
PRINT 'INSERT:vehiculos_cilindraje'
truncate table u859387114_transitar..vehiculos_cilindraje;
INSERT INTO u859387114_transitar..vehiculos_cilindraje(id,nombre,minimo,maximo) SELECT Tcilindraje_ID,Tcilindraje_nombre,Tcilindraje_minimo,Tcilindraje_maximo FROM palermo..Tvehiculos_cilindraje;
------------------------------------------------------------
PRINT 'INSERT:vehiculos_clase'
truncate table u859387114_transitar..vehiculos_clase;
INSERT INTO u859387114_transitar..vehiculos_clase(id,nombre) SELECT Tclase_ID,Tclase_nombre FROM palermo..TVehiculos_clase;
------------------------------------------------------------
PRINT 'INSERT:vehiculos_clasificacion'
truncate table u859387114_transitar..vehiculos_clasificacion;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_clasificacion ON
INSERT INTO u859387114_transitar..vehiculos_clasificacion(id,nombre) SELECT Tvehiculos_clasif_ID,Tvehiculos_clasif_nombre FROM palermo..Tvehiculos_clasif;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_clasificacion OFF
------------------------------------------------------------
PRINT 'INSERT:vehiculos_color'
truncate table u859387114_transitar..vehiculos_color;
INSERT INTO u859387114_transitar..vehiculos_color(id,nombre) SELECT TVehiculos_color_ID,TVehiculos_color_nombre FROM palermo..TVehiculos_color;
------------------------------------------------------------
PRINT 'INSERT:vehiculos_combustible'
truncate table u859387114_transitar..vehiculos_combustible;
INSERT INTO u859387114_transitar..vehiculos_combustible(id,nombre) SELECT Tvehiculos_combustible_ID,Tvehiculos_combustible_nombre FROM palermo..TVehiculos_combustible;
------------------------------------------------------------
PRINT 'INSERT:vehiculos_modalidad'
truncate table u859387114_transitar..vehiculos_modalidad;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_modalidad ON
INSERT INTO u859387114_transitar..vehiculos_modalidad(id,nombre,id_simit) SELECT TVehiculos_modalidad_ID,TVehiculos_modalidad_modalidad,TVehiculos_modalidad_simit FROM palermo..TVehiculos_modalidad;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_modalidad OFF
------------------------------------------------------------
PRINT 'INSERT:vehiculos_origen'
truncate table u859387114_transitar..vehiculos_origen;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_origen ON
INSERT INTO u859387114_transitar..vehiculos_origen(id,nombre) SELECT Tvehiculos_origen_ID,Tvehiculos_origen_origen FROM palermo..Tvehiculos_origen;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_origen OFF
------------------------------------------------------------
PRINT 'INSERT:vehiculos_pasajero'
truncate table u859387114_transitar..vehiculos_pasajero;
INSERT INTO u859387114_transitar..vehiculos_pasajero(id,nombre,minimo,maximo) SELECT Tpasajeros_ID,Tpasajeros_nombre,Tpasajeros_minimo,Tpasajeros_maximo FROM palermo..TVehiculos_pasajeros;
------------------------------------------------------------
PRINT 'INSERT:vehiculos_radio'
truncate table u859387114_transitar..vehiculos_radio;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_radio ON
INSERT INTO u859387114_transitar..vehiculos_radio(id,nombre,simit) SELECT Tvehiculos_radio_ID,Tvehiculos_radio_radio,Tvehiculos_radio_simit FROM palermo..Tvehiculos_radio;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_radio OFF
------------------------------------------------------------
PRINT 'INSERT:areas'
truncate table u859387114_transitar..areas;
SET IDENTITY_INSERT u859387114_transitar..areas ON
INSERT INTO u859387114_transitar..areas(id,nombre) SELECT Tareasempresa_ID,Tareasempresa_nombre FROM palermo..Tareasempresa;
SET IDENTITY_INSERT u859387114_transitar..areas OFF
------------------------------------------------------------
PRINT 'INSERT:campos_ciudadanos'
truncate table u859387114_transitar..campos_ciudadanos;
SET IDENTITY_INSERT u859387114_transitar..campos_ciudadanos ON
INSERT INTO u859387114_transitar..campos_ciudadanos(Tciudadanos_ID,Tciudadanos_tipo,Tciudadanos_tipoid,Tciudadanos_ident,Tciudadanos_fechaexp,Tciudadanos_nombres,Tciudadanos_apellidos,Tciudadanos_fnacimiento,Tciudadanos_cr,Tciudadanos_direccion,Tciudadanos_telfijo,Tciudadanos_telcelular,Tciudadanos_email,Tciudadanos_licencia_a,Tciudadanos_catLC_a,Tciudadanos_vigenciaLC_a,Tciudadanos_licencia_m,Tciudadanos_catLC_m,Tciudadanos_vigenciaLC_m,Tciudadanos_pn,Tciudadanos_estado,Tciudadanos_cn,Tciudadanos_rh,Tciudadanos_gs,Tciudadanos_sexo,Tciudadanos_foto,Tciudadanos_huellad,Tciudadanos_huellai,Tciudadanos_firma,Tciudadanos_donante,Tciudadanos_licencia_mf,Tciudadanos_licencia_af,Tciudadanos_org_LC_m,Tciudadanos_org_LC_a,Tciudadanos_user,Tciudadanos_fecha,tciudadanos_sustrato_a,tciudadanos_sustrato_m) SELECT Tciudadanos_ID,Tciudadanos_tipo,Tciudadanos_tipoid,Tciudadanos_ident,Tciudadanos_fechaexp,Tciudadanos_nombres,Tciudadanos_apellidos,Tciudadanos_fnacimiento,Tciudadanos_cr,Tciudadanos_direccion,Tciudadanos_telfijo,Tciudadanos_telcelular,Tciudadanos_email,Tciudadanos_licencia_a,Tciudadanos_catLC_a,Tciudadanos_vigenciaLC_a,Tciudadanos_licencia_m,Tciudadanos_catLC_m,Tciudadanos_vigenciaLC_m,Tciudadanos_pn,Tciudadanos_estado,Tciudadanos_cn,Tciudadanos_rh,Tciudadanos_gs,Tciudadanos_sexo,Tciudadanos_foto,Tciudadanos_huellad,Tciudadanos_huellai,Tciudadanos_firma,Tciudadanos_donante,Tciudadanos_licencia_mf,Tciudadanos_licencia_af,Tciudadanos_org_LC_m,Tciudadanos_org_LC_a,Tciudadanos_user,Tciudadanos_fecha,Tciudadanos_sustrato_a,Tciudadanos_sustrato_m FROM palermo..Tciudadanos;
SET IDENTITY_INSERT u859387114_transitar..campos_ciudadanos OFF
------------------------------------------------------------
PRINT 'INSERT:categorias_instruccion'
truncate table u859387114_transitar..categorias_instruccion;
INSERT INTO u859387114_transitar..categorias_instruccion(nombre,tciudadanos_categoriaslc_descripcion,tciudadanos_categoriaslc_catanterior) SELECT Tciudadanos_categoriasLC_ID,Tciudadanos_categoriasLC_descripcion,Tciudadanos_categoriasLC_catanterior FROM palermo..Tciudadanos_categoriasLC;
------------------------------------------------------------
PRINT 'INSERT:ciudadanos'
truncate table u859387114_transitar..ciudadanos;
SET IDENTITY_INSERT u859387114_transitar..ciudadanos ON
INSERT INTO u859387114_transitar..ciudadanos(id,numero_documento,nombres,apellidos,direccion,telefono,celular,email,fecha_expedicion,fecha_nacimiento,tipo_ciudadano,tipo_documento,donante_organos,grupo_sanguineo,pais_nacimiento,ciudad_nacimiento,ciudad_residencia,sexo,licencia_auto,categoria_licencia_auto,vigencia_licencia_auto,licencia_moto,categoria_licencia_moto,vigencia_licencia_moto,expedicion_licencia_moto,expedicion_licencia_auto,organismo_licencia_moto,organismo_licencia_auto,sustrato_licencia_auto,sustrato_licencia_moto,usuario,fecha,fecha_actualizacion,Tciudadanos_estado,Tciudadanos_foto,Tciudadanos_huellad,Tciudadanos_huellai,Tciudadanos_firma,Tciudadanos_licencia_mf,Tciudadanos_licencia_af) SELECT Tciudadanos_ID,Tciudadanos_ident,Tciudadanos_nombres,Tciudadanos_apellidos,Tciudadanos_direccion,Tciudadanos_telfijo,Tciudadanos_telcelular,Tciudadanos_email,Tciudadanos_fechaexp,Tciudadanos_fnacimiento,Tciudadanos_tipo,Tciudadanos_tipoid,Tciudadanos_donante,Tciudadanos_gs,Tciudadanos_pn,Tciudadanos_cn,Tciudadanos_cr,Tciudadanos_sexo,Tciudadanos_licencia_a,Tciudadanos_catLC_a,Tciudadanos_vigenciaLC_a,Tciudadanos_licencia_m,Tciudadanos_catLC_m,Tciudadanos_vigenciaLC_m,'1900-01-01' ,'1900-01-01' ,Tciudadanos_org_LC_m,Tciudadanos_org_LC_a,Tciudadanos_sustrato_a,Tciudadanos_sustrato_m,Tciudadanos_user,Tciudadanos_fecha,'1900-01-01 00:00:00' ,Tciudadanos_estado,Tciudadanos_foto,Tciudadanos_huellad,Tciudadanos_huellai,Tciudadanos_firma,Tciudadanos_licencia_mf,Tciudadanos_licencia_af FROM palermo..Tciudadanos;
SET IDENTITY_INSERT u859387114_transitar..ciudadanos OFF
------------------------------------------------------------
PRINT 'INSERT:comparendos'
truncate table u859387114_transitar..comparendos;
SET IDENTITY_INSERT u859387114_transitar..comparendos ON
INSERT INTO u859387114_transitar..comparendos(Tcomparendos_ID,Tcomparendos_comparendo,Tcomparendos_fecha,Tcomparendos_lugar,Tcomparendos_placa,Tcomparendos_servicio,Tcomparendos_tipo,Tcomparendos_modalidad,Tcomparendos_codinfraccion,Tcomparendos_sancion,Tcomparendos_estado,Tcomparendos_idprop,Tcomparendos_tipoinfractor,Tcomparendos_idinfractor,Tcomparendos_LT,Tcomparendos_solprop,Tcomparendos_solemp,Tcomparendos_fuga,Tcomparendos_radio,Tcomparendos_fechareg,Tcomparendos_grua,Tcomparendos_gruaestado,Tcomparendos_patio,Tcomparendos_patioestado,Tcomparendos_agente,Tcomparendos_observaciones,Tcomparendos_idtestigo,Tcomparendos_honorarios,Tcomparendos_cobranza,Tcomparendos_origen,Tcomparendos_ayudas,Tcomparendos_accidente,Tcomparendos_maldiligen,Tcomparendos_archivo,Tcomparendos_user,Tcomparendos_TO,Tcomparendos_OT,Tcomparendos_tipopasajero,Tcomparendos_empresa,Tcomparendos_notifica,Tcomparendos_municipiodir,Tcomparendos_localidad,Tcomparendos_municioplaca,Tcomparendos_gradoalcohol,Tcomparendos_reincidencia,Tcomparendos_smlv,Tcomparendos_gruazona,id_ciudadano,fecha_actualizacion) SELECT Tcomparendos_ID,Tcomparendos_comparendo,Tcomparendos_fecha,Tcomparendos_lugar,Tcomparendos_placa,Tcomparendos_servicio,Tcomparendos_tipo,Tcomparendos_modalidad,Tcomparendos_codinfraccion,Tcomparendos_sancion,Tcomparendos_estado,Tcomparendos_idprop,Tcomparendos_tipoinfractor,Tcomparendos_idinfractor,Tcomparendos_LT,Tcomparendos_solprop,Tcomparendos_solemp,Tcomparendos_fuga,Tcomparendos_radio,Tcomparendos_fechareg,Tcomparendos_grua,Tcomparendos_gruaestado,Tcomparendos_patio,Tcomparendos_patioestado,Tcomparendos_agente,Tcomparendos_observaciones,Tcomparendos_idtestigo,Tcomparendos_honorarios,Tcomparendos_cobranza,Tcomparendos_origen,Tcomparendos_ayudas,Tcomparendos_accidente,Tcomparendos_maldiligen,Tcomparendos_archivo,Tcomparendos_user,Tcomparendos_TO,Tcomparendos_OT,Tcomparendos_tipopasajero,Tcomparendos_empresa,Tcomparendos_notifica,Tcomparendos_municipiodir,Tcomparendos_localidad,Tcomparendos_municioplaca,Tcomparendos_gradoalcohol,Tcomparendos_reincidencia,Tcomparendos_smlv,Tcomparendos_gruazona,0 as id_ciudadano,'1900-01-01' as fecha_actualizacion FROM palermo..Tcomparendos;
SET IDENTITY_INSERT u859387114_transitar..comparendos OFF
------------------------------------------------------------
PRINT 'INSERT:comparendos_codigos'
truncate table u859387114_transitar..comparendos_codigos;
SET IDENTITY_INSERT u859387114_transitar..comparendos_codigos ON
INSERT INTO u859387114_transitar..comparendos_codigos(TTcomparendoscodigos_ID,TTcomparendoscodigos_codigoanterior,TTcomparendoscodigos_codigo,TTcomparendoscodigos_descripcion,TTcomparendoscodigos_valorSMLV,TTcomparendoscodigos_ano,TTcomparendoscodigos_sol,TTcomparendoscodigos_uvb) SELECT TTcomparendoscodigos_ID,TTcomparendoscodigos_codigoanterior,TTcomparendoscodigos_codigo,TTcomparendoscodigos_descripcion,TTcomparendoscodigos_valorSMLV,TTcomparendoscodigos_ano,TTcomparendoscodigos_sol,TTcomparendoscodigos_uvb FROM palermo..Tcomparendoscodigos;
SET IDENTITY_INSERT u859387114_transitar..comparendos_codigos OFF
------------------------------------------------------------
PRINT 'INSERT:cuentas'
truncate table u859387114_transitar..cuentas;
SET IDENTITY_INSERT u859387114_transitar..cuentas ON
INSERT INTO u859387114_transitar..cuentas(id,Tbancoscuentas_banco,tipo_cuenta,numero_cuenta,nombre_cuenta,oficina_cuenta,Tbancoscuentas_contacton,Tbancoscuentas_contactot,nombre_banco) SELECT Tbancoscuentas_ID,Tbancoscuentas_banco,Tbancoscuentas_tipoc,Tbancoscuentas_numeroc,Tbancoscuentas_nombrec,Tbancoscuentas_oficina,Tbancoscuentas_contacton,Tbancoscuentas_contactot,'' as nombre_banco FROM palermo..Tbancoscuentas;
SET IDENTITY_INSERT u859387114_transitar..cuentas OFF
------------------------------------------------------------
PRINT 'INSERT:conceptos'
truncate table u859387114_transitar..conceptos;
SET IDENTITY_INSERT u859387114_transitar..conceptos ON
INSERT INTO u859387114_transitar..conceptos(id,nombre,tipo_documento,clase_vehiculo,servicio_vehiculo,persona_indeterminada,valor_concepto,valor_modificacble,valor_SMLV_UVT,IPC,renueva,fecha_vigencia_inicial,fecha_vigencia_final,terceros,porcentaje,operacion,repetir,decreto,codigo_infraccion_descuento,Tconceptos_fechainif,Tconceptos_fechafinf,Tconceptos_origen,Tconceptos_ayudas,Tconceptos_observ,Tconceptos_ppi,Tconceptos_ppf,Tconceptos_CodPresupuestal,Tconceptos_usuariosasignados) SELECT Tconceptos_ID,Tconceptos_nombre,Tconceptos_tipodoc,Tconceptos_clase,Tconceptos_servicioVeh,Tconceptos_persoindet,Tconceptos_valor,Tconceptos_valormod,Tconceptos_smlv,Tconceptos_IPC,Tconceptos_renueva,Tconceptos_fechaini,Tconceptos_fechafin,Tconceptos_terceros,Tconceptos_porcentaje,Tconceptos_operacion,Tconceptos_repetir,Tconceptos_decreto,Tconceptos_infraccion,Tconceptos_fechainif,Tconceptos_fechafinf,Tconceptos_origen,Tconceptos_ayudas,Tconceptos_observ,Tconceptos_ppi,Tconceptos_ppf,Tconceptos_CodPresupuestal,null as Tconceptos_usuariosasignados FROM palermo..Tconceptos;
SET IDENTITY_INSERT u859387114_transitar..conceptos OFF
------------------------------------------------------------
PRINT 'INSERT:lineas'
truncate table u859387114_transitar..lineas;
SET IDENTITY_INSERT u859387114_transitar..lineas ON
INSERT INTO u859387114_transitar..lineas(id,marca,nombre,TVehiculos_lineas_idlinea) SELECT Tlineas_ID,TVehiculos_lineas_Idmarca,TVehiculos_lineas_linea,TVehiculos_lineas_idlinea FROM palermo..TVehiculos_lineas;
SET IDENTITY_INSERT u859387114_transitar..lineas OFF
------------------------------------------------------------
PRINT 'INSERT:notas_credito_cambio'
truncate table u859387114_transitar..notas_credito_cambio;
INSERT INTO u859387114_transitar..notas_credito_cambio(id,liquidacion,identificacion,identificacion_cambio,fecha,usuario) SELECT Tnotascredito_ID,Tnotascredito_liquidacion,Tnotascredito_identificacion,Tnotascredito_identificacion_cambio,Tnotascredito_fecha,Tnotascredito_user FROM palermo..Tnotascredito_cambio;
------------------------------------------------------------
PRINT 'INSERT:Trecaudos'
truncate table u859387114_transitar..Trecaudos;
SET IDENTITY_INSERT u859387114_transitar..Trecaudos ON
INSERT INTO u859387114_transitar..Trecaudos(Trecaudos_ID,Trecaudos_liquidacion,Trecaudos_comparendo,Trecaudos_consignacion,Trecaudos_banco,Trecaudos_cuenta,Trecaudos_fecharecaudo,Trecaudos_valor,Trecaudos_documento,Trecaudos_observaciones,Trecaudos_referencia,Trecaudos_medio,Trecaudos_fecha,Trecaudos_nombreconsig,Trecaudos_telconsig,Trecaudos_identconsig,Trecaudos_Tarjeta,Trecaudos_mesVTC,Trecaudos_anoVTC,Trecaudos_Tarjetabanco,Trecaudos_autorizacion,Trecaudos_user) SELECT Trecaudos_ID,Trecaudos_liquidacion,Trecaudos_comparendo,Trecaudos_consignacion,Trecaudos_banco,Trecaudos_cuenta,Trecaudos_fecharecaudo,Trecaudos_valor,Trecaudos_documento,Trecaudos_observaciones,Trecaudos_referencia,Trecaudos_medio,Trecaudos_fecha,Trecaudos_nombreconsig,Trecaudos_telconsig,Trecaudos_identconsig,Trecaudos_Tarjeta,Trecaudos_mesVTC,Trecaudos_anoVTC,Trecaudos_Tarjetabanco,Trecaudos_autorizacion,Trecaudos_user FROM palermo..trecaudos;
SET IDENTITY_INSERT u859387114_transitar..Trecaudos OFF
------------------------------------------------------------
PRINT 'INSERT:resolucion_sancion'
truncate table u859387114_transitar..resolucion_sancion;
SET IDENTITY_INSERT u859387114_transitar..resolucion_sancion ON
INSERT INTO u859387114_transitar..resolucion_sancion(ressan_id,ressan_ano,ressan_numero,ressan_tipo,ressan_comparendo,ressan_archivo,ressan_fecha,ressan_observaciones,ressan_exportado,ressan_resant,ressan_compid,ressan_fechahasta,ressan_decision_jud,ressan_reincidencia,ressan_embriaguez,ressan_muerte,ressan_codinfraccion,ressan_UsarLcSuspendida,ressan_fraude,ressan_horascomuni,ressan_cia,ressan_ciafecha,ressan_ciacertificado,ressan_usuario,generada,fecha_actualizacion) SELECT ressan_id,ressan_ano,ressan_numero,ressan_tipo,ressan_comparendo,ressan_archivo,ressan_fecha,ressan_observaciones,ressan_exportado,ressan_resant,ressan_compid,ressan_fechahasta,ressan_decision_jud,ressan_reincidencia,ressan_embriaguez,ressan_muerte,ressan_codinfraccion,ressan_UsarLcSuspendida,ressan_fraude,ressan_horascomuni,ressan_cia,ressan_ciafecha,ressan_ciacertificado,ressan_usuario,'' as generada,'1900-01-01' as fecha_actualizacion FROM palermo..resolucion_sancion;
SET IDENTITY_INSERT u859387114_transitar..resolucion_sancion OFF
------------------------------------------------------------
PRINT 'INSERT:tercero'
truncate table u859387114_transitar..tercero;
SET IDENTITY_INSERT u859387114_transitar..tercero ON
INSERT INTO u859387114_transitar..tercero(id,nombre,id_externo,tipo_identificacion,identificacion,apellidos,Tterceros_tipo,Tterceros_dir,Tterceros_tel,Tterceros_email,Tterceros_pweb,Tterceros_contacto,Tterceros_inscripcion,Tterceros_finscripcion,Tterceros_cupo,Tterceros_tarifa,Tterceros_fecha,Tterceros_user,Tterceros_placa,Tterceros_entidad,Tterceros_gs1id) SELECT Tterceros_ID,Tterceros_nombre,Tterceros_ID_externo,Tterceros_tipoid,Tterceros_identifica,Tterceros_apellido,Tterceros_tipo,Tterceros_dir,Tterceros_tel,Tterceros_email,Tterceros_pweb,Tterceros_contacto,Tterceros_inscripcion,Tterceros_finscripcion,Tterceros_cupo,Tterceros_tarifa,Tterceros_fecha,Tterceros_user,Tterceros_placa,Tterceros_entidad,Tterceros_gs1id FROM palermo..Tterceros;
SET IDENTITY_INSERT u859387114_transitar..tercero OFF
------------------------------------------------------------
PRINT 'INSERT:tipo_inventario'
truncate table u859387114_transitar..tipo_inventario;
SET IDENTITY_INSERT u859387114_transitar..tipo_inventario ON
INSERT INTO u859387114_transitar..tipo_inventario(id,nombre) SELECT patios_tipoinv_ID,patios_tipoinv_desc FROM palermo..patios_tipoinv;
SET IDENTITY_INSERT u859387114_transitar..tipo_inventario OFF
------------------------------------------------------------
PRINT 'INSERT:titulos'
truncate table u859387114_transitar..titulos;
SET IDENTITY_INSERT u859387114_transitar..titulos ON
INSERT INTO u859387114_transitar..titulos(id,numero,valor,fecha,liquidacion,empresa,Tliquidacion_comparendoid) SELECT Tliquidacion_titulos_ID,(case when isnumeric(Tliquidacion_titulos_num) = 0 then 0 else cast(Tliquidacion_titulos_num as bigint) end) as Tliquidacion_titulos_num,Tliquidacion_titulos_val,Tliquidacion_titulos_fec,cast(Tliquidacion_liquidacion as int),1 as empresa,Tliquidacion_comparendoid FROM palermo..Tliquidacion_titulos;
SET IDENTITY_INSERT u859387114_transitar..titulos OFF
------------------------------------------------------------
PRINT 'INSERT:tnotifica'
truncate table u859387114_transitar..tnotifica;
SET IDENTITY_INSERT u859387114_transitar..tnotifica ON
INSERT INTO u859387114_transitar..tnotifica(Tnotifica_ID,Tnotifica_comparendo,Tnotifica_compid,Tnotifica_tipociu,Tnotifica_ident,Tnotifica_estado,Tnotifica_notificaf,Tnotifica_faviso,Tnotifica_fecha,Tnotifica_user) SELECT Tnotifica_ID,Tnotifica_comparendo, null as Tnotifica_compid,Tnotifica_tipociu,Tnotifica_ident,null as Tnotifica_estado,Tnotifica_notificaf,null as Tnotifica_faviso,Tnotifica_fecha,Tnotifica_user FROM palermo..Tnotifica;
SET IDENTITY_INSERT u859387114_transitar..tnotifica OFF
------------------------------------------------------------
PRINT 'INSERT:tramites'
truncate table u859387114_transitar..tramites;
SET IDENTITY_INSERT u859387114_transitar..tramites ON
INSERT INTO u859387114_transitar..tramites(id,nombre,tipo_documento,Ttramites_tabla) SELECT Ttramites_ID,Ttramites_nombre,Ttramites_tipodoc,Ttramites_tabla FROM palermo..Ttramites;
SET IDENTITY_INSERT u859387114_transitar..tramites OFF
------------------------------------------------------------
PRINT 'INSERT:vehiculos_carroceria'
truncate table u859387114_transitar..vehiculos_carroceria;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_carroceria ON
INSERT INTO u859387114_transitar..vehiculos_carroceria(id,nombre,clase,TVehiculos_carrocerias_Clase,TVehiculos_carrocerias_migracion) SELECT TVehiculos_carrocerias_ID,TVehiculos_carrocerias_c,TVehiculos_carrocerias_IDClase,TVehiculos_carrocerias_Clase,TVehiculos_carrocerias_migracion FROM palermo..Tvehiculos_carrocerias;
SET IDENTITY_INSERT u859387114_transitar..vehiculos_carroceria OFF
