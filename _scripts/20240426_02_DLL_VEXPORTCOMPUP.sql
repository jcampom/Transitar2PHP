CREATE VIEW [dbo].[VExportCompUp]
AS
SELECT Comp.Tcomparendos_ID AS idcomp
	,Comp.Tcomparendos_comparendo AS comp
	,dbo.NumComp(Comp.Tcomparendos_comparendo, Comp.Tcomparendos_origen) AS COMNUMERO
	,CONVERT(VARCHAR(10), Comp.Tcomparendos_fecha, 103) AS COMFECHA
	,REPLACE(CONVERT(VARCHAR(5), Comp.Tcomparendos_fecha, 108), ':', '') AS COMHORA
	,Comp.Tcomparendos_lugar AS COMDIR
	,municipio.ciudad AS COMDIVIPOMUNI
	,localidad.ciudad AS COMDIVIPOLOCALIDADCOMUNA
	,Comp.Tcomparendos_placa AS COMPLACA
	,ISNULL(MunCiu.ciudad, 0) AS COMDIVIPOMATRI
	,Comp.Tcomparendos_tipo AS COMTIPOVEHI
	,Serv.simit AS COMTIPOSER
	,ISNULL(Radi.simit, 1) AS COMCODIGORADIO
	,Moda.id_simit AS COMCODIGOMODALIDAD
	,Comp.Tcomparendos_tipopasajero AS COMCODIGOPASAJEROS
	,Comp.Tcomparendos_idinfractor AS COMINFRACTOR
	,Tide.simit AS COMTIPODOC
	,Infr.Tciudadanos_nombres AS COMNOMBRE
	,Infr.Tciudadanos_apellidos AS COMAPELLIDO
	,(
		CASE 
			WHEN Infr.Tciudadanos_fnacimiento != '1900-01-01'
				THEN (
						SELECT datediff(YEAR, Infr.Tciudadanos_fnacimiento, getdate())
						)
			ELSE ''
			END
		) AS COMEDADINFRACTOR
	,Infr.Tciudadanos_direccion AS COMDIRINFRACTOR
	,Infr.Tciudadanos_email AS COMEMAIL
	,ISNULL(Infr.Tciudadanos_telfijo, Infr.Tciudadanos_telcelular) AS COMTELEINFRACTOR
	,InfrCiu.ciudad AS COMIDCIUDAD
	,(
		CASE Tcomparendos_tipoinfractor
			WHEN '4'
				THEN Infr.Tciudadanos_licencia_m
			ELSE Infr.Tciudadanos_licencia_a
			END
		) AS COMLICENCIA
	,(
		CASE Tcomparendos_tipoinfractor
			WHEN '4'
				THEN Infr.Tciudadanos_catLC_m
			ELSE Infr.Tciudadanos_catLC_a
			END
		) AS COMCATEGORIA
	,(
		CASE Tcomparendos_tipoinfractor
			WHEN '4'
				THEN Infr.Tciudadanos_org_LC_m
			ELSE Infr.Tciudadanos_org_LC_a
			END
		) AS COMSECREEXPIDE
	,(
		CASE Tcomparendos_tipoinfractor
			WHEN '4'
				THEN CONVERT(VARCHAR(10), Infr.Tciudadanos_vigenciaLC_m, 103)
			ELSE CONVERT(VARCHAR(10), Infr.Tciudadanos_vigenciaLC_a, 103)
			END
		) AS COMFECHAVENCE
	,Comp.Tcomparendos_tipoinfractor AS COMTIPOINFRAC
	,Comp.Tcomparendos_LT AS COMLICTRANSITO
	,Comp.Tcomparendos_OT AS COMDIVIPOLICEN
	,Comp.Tcomparendos_idprop AS COMIDENTIFICACION
	,TideP.simit AS COMIDTIPODOCPROP
	,RTRIM(Prop.Tciudadanos_nombres) + ' ' + RTRIM(Prop.Tciudadanos_apellidos) AS COMNOMBREPROP
	,empresa.nombre AS COMNOMBREEMPRESA
	,empresa.Tterceros_identifica AS COMNITEMPRESA
	,Comp.Tcomparendos_TO AS COMTARJETAOPERACION
	,ISNULL(agente.Tterceros_placa, Comp.Tcomparendos_agente) AS COMPPLACAAGENTE
	,Comp.Tcomparendos_observaciones AS COMOBSERVA
	,(
		CASE 
			WHEN Tcomparendos_fuga > 0
				THEN 'S'
			ELSE 'N'
			END
		) AS COMFUGA
	,(
		CASE 
			WHEN Tcomparendos_accidente > 0
				THEN 'S'
			ELSE 'N'
			END
		) AS COMACCI
	,(
		CASE 
			WHEN Tcomparendos_patio > 0
				THEN 'S'
			ELSE 'N'
			END
		) AS COMINMOV
	,patio.nombre AS COMPATIOINMOVILIZA
	,patio.Tterceros_dir AS COMDIRPATIOINMOVI
	,'' AS COMGRUANUMERO
	,'' AS COMPLACAGRUA
	,0 AS COMCONSECUTIINMOVI
	,Comp.Tcomparendos_idtestigo AS COMIDENTIFICACIONTEST
	,RTRIM(Test.Tciudadanos_nombres) + ' ' + RTRIM(Test.Tciudadanos_apellidos) AS COMNOMBRETESTI
	,REPLACE(Test.Tciudadanos_direccion, ',', ' ') AS COMDIRECRESTESTI
	,Test.Tciudadanos_telfijo AS COMTELETESTIGO
	,dbo.ValorCompSMLV(Comp.Tcomparendos_ID) AS COMVALOR
	,(
		SELECT TOP (1) Tparameconomicos_vadicComp
		FROM dbo.parametros_economicos
		) AS COMVALAD
	,(
		SELECT TOP (1) divipo
		FROM dbo.sedes
		) AS COMORGANISMO
	,Esta.simit AS COMESTADOCOM
	,(
		CASE Tcomparendos_origen
			WHEN '99999999'
				THEN 'S'
			ELSE 'N'
			END
		) AS COMPOLCA
	,Comp.Tcomparendos_codinfraccion AS COMINFRACCION
	,dbo.ValorCompSMLV(Comp.Tcomparendos_ID) AS COMVALINFRA
	,Comp.Tcomparendos_gradoalcohol AS COMGRADOALCOHOL
	,(
		CASE Tcomparendos_origen
			WHEN '1'
				THEN 'S'
			ELSE 'N'
			END
		) AS FOTOMULTA
	,CONVERT(VARCHAR(10), ISNULL(TN.Tnotifica_notificaf, Comp.Tcomparendos_fecha), 103) AS FECHANOTIFICACION
FROM dbo.comparendos AS Comp
INNER JOIN dbo.comparendos_codigos AS codigo ON Comp.Tcomparendos_codinfraccion = codigo.TTcomparendoscodigos_codigo
INNER JOIN dbo.campos_ciudadanos AS Infr ON CONVERT(NVARCHAR(20), Comp.Tcomparendos_idinfractor) = Infr.Tciudadanos_ident
LEFT OUTER JOIN dbo.ciudades AS InfrCiu ON Infr.Tciudadanos_cr = InfrCiu.id
LEFT OUTER JOIN dbo.ciudades AS MunCiu ON Comp.Tcomparendos_municioplaca = MunCiu.id
INNER JOIN dbo.tipo_identificacion AS Tide ON Tide.id = Infr.Tciudadanos_tipoid
LEFT OUTER JOIN dbo.Tnotifica AS TN ON Comp.Tcomparendos_ID = TN.Tnotifica_compid
INNER JOIN dbo.comparendos_estados AS Esta ON Comp.Tcomparendos_estado = Esta.id
LEFT OUTER JOIN dbo.campos_ciudadanos AS Prop ON Comp.Tcomparendos_idprop = Prop.Tciudadanos_ident
LEFT OUTER JOIN dbo.tipo_identificacion AS TideP ON TideP.id = Prop.Tciudadanos_tipoid
LEFT OUTER JOIN dbo.campos_ciudadanos AS Test ON Comp.Tcomparendos_idtestigo = Test.Tciudadanos_ident
LEFT OUTER JOIN dbo.tipo_servicio AS Serv ON Comp.Tcomparendos_servicio = Serv.id
LEFT OUTER JOIN dbo.vehiculos_modalidad AS Moda ON Comp.Tcomparendos_modalidad = Moda.id
LEFT OUTER JOIN dbo.vehiculos_radio AS Radi ON Comp.Tcomparendos_radio = Radi.id
LEFT OUTER JOIN dbo.terceros AS empresa ON Comp.Tcomparendos_empresa = empresa.id
LEFT OUTER JOIN dbo.terceros AS agente ON Comp.Tcomparendos_agente = agente.id
LEFT OUTER JOIN dbo.terceros AS patio ON Comp.Tcomparendos_patio = patio.id
LEFT OUTER JOIN dbo.terceros AS grua ON Comp.Tcomparendos_grua = grua.id
LEFT OUTER JOIN dbo.ciudades AS municipio ON Comp.Tcomparendos_municipiodir = municipio.id
LEFT OUTER JOIN dbo.ciudades AS localidad ON Comp.Tcomparendos_localidad = localidad.id
WHERE (Comp.Tcomparendos_estado IN (1))
GO


