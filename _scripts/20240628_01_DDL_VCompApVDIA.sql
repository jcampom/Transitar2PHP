USE u859387114_transitar
GO

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[VCompApVDIA]
AS
SELECT liq
	,doc
	,frecaudo
	,SUM(CASE 
			WHEN (tramite IN (61))
				THEN valor
			ELSE 0
			END) AS valor
	,SUM(CASE 
			WHEN ( tramite IN ( 39 ,40 ) AND tercero = 0 )
				THEN valor
			ELSE 0
			END) AS original
	,SUM(CASE 
			WHEN tramite IN ( 48 ,49 )
				THEN valor
			ELSE 0
			END) AS interes
	,SUM(CASE 
			WHEN tramite IN ( 58 ,59 )
				THEN valor
			ELSE 0
			END) AS descuento
	,SUM(CASE 
			WHEN tramite IN ( 50 ,52 ,39 ,40 )
				AND tercero <> 0
				THEN valor
			ELSE 0
			END) AS adicional
FROM (
	SELECT c.liquidacion AS liq
		,c.Tliqconcept_doc AS doc
		,r.Trecaudos_fecharecaudo AS frecaudo
		,c.tramite AS tramite
		,c.terceros AS tercero
		,(
			CASE 
				WHEN ( c.valor = 0 AND c.tramite = 61 )
					THEN dbo.ValorDivPor(c.liquidacion, Tliqconcept_doc, Tliqconcept_porcentaje)
				ELSE dbo.ValorLiqCon(c.ID)
				END
			) AS valor
	FROM dbo.Trecaudos AS r
	INNER JOIN dbo.detalle_conceptos_liquidaciones AS c ON c.liquidacion = r.Trecaudos_liquidacion
	WHERE ( c.tramite IN ( 39 ,40 ,58 ,59 ,48 ,49 ,50 ,52 ,61 )
		)
		AND ( c.tramite IN ( select id from tramites where tipo_documento in (4,6) ) )
	) AS T
WHERE (doc > 0)
	AND (liq <> '')
GROUP BY liq ,doc ,frecaudo
GO
