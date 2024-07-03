USE [u859387114_transitar]
GO

/****** Object:  UserDefinedFunction [dbo].[ValorLiqCon]    Script Date: 03/07/2024 10:11:57 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[ValorLiqCon] (@liqConID INT)
RETURNS BIGINT

BEGIN
	DECLARE @valor FLOAT
		,@smvl TINYINT
		,@porcen TINYINT
		,@action TINYINT
		,@fecha DATETIME
		,@anio SMALLINT
		,@tipo TINYINT
		,@doc NCHAR(10)
		,@concepto VARCHAR(70)
		,@total FLOAT
		,@salario FLOAT
		,@mult INT
		,@conceptoid VARCHAR(200)

	SELECT @valor = c.valor
		,@smvl = c.Tliqconcept_smlv
		,@porcen = c.Tliqconcept_porcentaje
		,@action = c.Tliqconcept_operacion
		,@fecha = c.Tliqconcept_fecha
		,@tipo = c.Tliqconcept_tipodoc
		,@conceptoid = cast(c.Tliqconcept_nombre AS VARCHAR)
		,@doc = c.Tliqconcept_doc
		,@concepto = c.Tliqconcept_nombre
	FROM detalle_conceptos_liquidaciones c
	WHERE c.ID = @liqConID

	SET @total = @valor
	SET @mult = 1

	IF @action = 2
	BEGIN
		SET @mult = - 1
	END

	IF @smvl > 0
		AND @valor <= 2000
	BEGIN
		SET @anio = YEAR(@fecha)

		IF @tipo = 7
			AND @concepto NOT LIKE '%SISTEMATIZACION%'
		BEGIN
			SET @anio = CAST(@doc AS SMALLINT)
		END

		IF @smvl = 1
		BEGIN
			SELECT @salario = (
					CASE 
						WHEN @anio >= 2021
							THEN smlv_original
						ELSE smlv
						END
					)
			FROM smlv
			WHERE ano = @anio

			SET @total = (@salario / 30) * @valor
		END
		ELSE IF @smvl = 2
		BEGIN
			SELECT @salario = ISNULL(Tsmlv_uvb,0)
			FROM smlv
			WHERE ano = @anio

			SET @total = @salario * @valor
		END

		IF @porcen > 0
		BEGIN
			SET @total = @total + (@mult * ((@total * @porcen) / 100))
		END
	END
	ELSE
	BEGIN
		SET @total = @mult * @total
	END

	RETURN ROUND(@total, 0)
END
GO


