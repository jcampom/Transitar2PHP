USE u859387114_transitar
GO

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

DROP VIEW [dbo].[VExportRecaudo]
GO

CREATE VIEW [dbo].[VExportRecaudo]
AS
SELECT RTRIM(V.doc) AS doc
	,V.frecaudo
	,S.ressan_id AS resolucion_id
	,C.Tcomparendos_comparendo AS comparendo
	,(CASE C.Tcomparendos_origen WHEN 99999999 THEN 'S' ELSE 'N' END) AS origen
	,C.Tcomparendos_codinfraccion AS codigo
	,CE.nombre AS estado
	,A.TAcuerdop_numero AS numero
	,CONVERT(VARCHAR, V.frecaudo, 103) AS RECFAPL
	,SUBSTRING(CONVERT(VARCHAR, R.Trecaudos_fecha, 108), 1, 5) AS RECHORA
	,CONVERT(VARCHAR, V.frecaudo, 103) AS RECFTRAN
	,0 AS RECCANAL
	,'TAQUILLA TRANSITO' AS RECORIGEN
	,V.valor AS RECEFECTIVO
	,0 AS RECCHEQUE
	,V.valor AS RECTOTAL
	,(
		CASE 
			WHEN S.ressan_id IS NOT NULL
				THEN CONVERT(VARCHAR, s.ressan_ano) + '-' + CONVERT(VARCHAR, ressan_numero) + '-' + t.sigla
			ELSE dbo.NumComp(C.Tcomparendos_comparendo, C.Tcomparendos_origen)
			END
		) AS RECDOCUMENTO
	,( CASE Tcomparendos_origen WHEN '99999999' THEN 'S' ELSE 'N' END ) AS RECPOLCA
	,C.Tcomparendos_idinfractor AS RECNIP
	,(
		CASE 
			WHEN S.ressan_tipo = 16
				THEN 6
			WHEN A.TAcuerdop_ID IS NOT NULL
				THEN 4
			WHEN S.ressan_id IS NOT NULL
				THEN 3
			ELSE 1
			END
		) AS RECTIPOREC
	,( SELECT TOP (1) RTRIM(divipo) AS Expr1 FROM dbo.sedes ) AS RECSECRET
	,V.liq AS RECNUM
	,ISNULL(A.TAcuerdop_cuota, 0) AS NUMERO_CUTOAS
	,TI.simit AS ID_TIPO_DOC
	,V.interes AS INTERESES
	,(
		CASE 
			WHEN S.ressan_tipo IS NULL AND A.TAcuerdop_ID IS NULL THEN ABS(V.descuento)
			ELSE 0
			END
		) AS DESCUENTO
	,(
		CASE 
			WHEN A.TAcuerdop_cuota IS NULL
				THEN V.adicional
			ELSE 0
			END
		) AS VADICIONAL
FROM dbo.VCompApVDIA AS V
INNER JOIN dbo.detalle_conceptos_liquidaciones AS L ON L.liquidacion = V.liq
	AND L.terceros = 0
	--AND L.Tliqconcept_doc = V.doc
	AND L.tramite = V.doc
INNER JOIN dbo.Trecaudos AS R ON V.liq = R.Trecaudos_liquidacion
LEFT OUTER JOIN dbo.campos_ciudadanos AS CI ON R.Trecaudos_identconsig = CI.Tciudadanos_ident
LEFT OUTER JOIN dbo.tipo_identificacion AS TI ON CI.Tciudadanos_tipoid = TI.ID
LEFT OUTER JOIN dbo.acuerdos_pagos AS A ON (A.TAcuerdop_ID = V.doc AND L.tramite = 40)
INNER JOIN dbo.comparendos AS C ON (C.Tcomparendos_ID = V.doc AND L.tramite = 39)
	OR (C.Tcomparendos_comparendo = A.TAcuerdop_comparendo AND L.tramite = 40)
INNER JOIN dbo.comparendos_estados AS CE ON C.Tcomparendos_estado = CE.ID
LEFT OUTER JOIN dbo.Texportplano AS E ON E.Texportplano_tipo = 2
	AND (
		E.Texportplano_comp = C.Tcomparendos_comparendo
		AND E.Texportplano_cuota IS NULL
		OR E.Texportplano_comp = C.Tcomparendos_comparendo
		AND E.Texportplano_cuota = A.TAcuerdop_cuota
		)
LEFT OUTER JOIN dbo.VResLast AS S ON S.ressan_comparendo = C.Tcomparendos_comparendo
	AND (
		S.ressan_tipo IN ( 2 ,10 ,16 )
		AND A.TAcuerdop_numero IS NULL
		OR S.ressan_tipo = 4
		AND A.TAcuerdop_numero IS NOT NULL
		)
LEFT OUTER JOIN dbo.resolucion_sancion_tipo AS T ON S.ressan_tipo = T.id
WHERE (E.Texportplano_ID IS NULL)
	AND (R.Trecaudos_fecharecaudo >= DATEADD(YEAR, - 1, GETDATE()))
GO
