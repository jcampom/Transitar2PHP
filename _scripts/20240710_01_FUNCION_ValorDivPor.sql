USE [u859387114_transitar]
GO

/****** Object:  UserDefinedFunction [dbo].[ValorDivPor]    Script Date: 10/07/2024 15:48:17 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

IF object_id('dbo.ValorDivPor', 'FN') IS NOT NULL
BEGIN
    DROP FUNCTION [dbo].[ValorDivPor] 
END
GO
-------------------------------------------------------

CREATE FUNCTION [dbo].[ValorDivPor] (
	@liq INT
	,@docId NCHAR(10)
	,@porcen TINYINT
	)
RETURNS BIGINT

BEGIN
	DECLARE @total FLOAT
		,@valor FLOAT
		,@cant TINYINT

	SELECT @cant = COUNT(c.valor)
		,@valor = ISNULL(SUM(c.valor), 0)
	FROM detalle_conceptos_liquidaciones c
	INNER JOIN liquidaciones l on c.liquidacion = l.id
	WHERE c.liquidacion = @liq
		--AND c.Tliqconcept_doc = @docId
		AND l.tipo_tramite  =@docId
		AND c.tramite = 39

	IF @cant = 0
	BEGIN
		SELECT @valor = ISNULL(SUM(c.valor), 0)
		FROM detalle_conceptos_liquidaciones c
		INNER JOIN liquidaciones l on c.liquidacion = l.id
		WHERE c.liquidacion = @liq
			--AND c.Tliqconcept_doc = @docId
			AND l.tipo_tramite  =@docId
			AND c.tramite = 40
	END

	DECLARE @vhoncob FLOAT

	SELECT @cant = COUNT(c.valor)
		,@vhoncob = ISNULL(SUM(c.valor), 0)
	FROM detalle_conceptos_liquidaciones c
	INNER JOIN liquidaciones l on c.liquidacion = l.id
	WHERE c.liquidacion = @liq
	--AND c.Tliqconcept_doc = @docId
	AND l.tipo_tramite  =@docId
	AND c.tramite IN (50,52)

	IF @cant > 0
	BEGIN
		SET @valor = @valor - @vhoncob
	END

	SET @total = (@valor * @porcen) / 100

	RETURN ROUND(@total, 0)
END
GO


