USE [u859387114_transitar]
GO

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

IF object_id('dbo.ValorLiqCon', 'FN') IS NOT NULL
BEGIN
    DROP FUNCTION [dbo].[ValorLiqCon] 
END
GO
-------------------------------------------------------
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
		,@smvl = (select s.smlv from dbo.smlv s where s.ano=year(l.fecha)) 		--,@smvl = c.Tliqconcept_smlv
		,@porcen = con.porcentaje 		--,@porcen = c.Tliqconcept_porcentaje
		,@action = con.operacion  --,@action = c.Tliqconcept_operacion   --JLCM PENDIENTE
		,@fecha = l.fecha --,@fecha = c.Tliqconcept_fecha
		,@tipo =(CASE WHEN c.comparendo is not null THEN 4 when c.cuota is not null then 6 end) 
		,@conceptoid = con.nombre 		--,@conceptoid = cast(c.Tliqconcept_nombre AS VARCHAR)
		,@doc = (CASE WHEN c.comparendo is not null THEN c.comparendo when c.cuota is not null then c.cuota end)  --c.Tliqconcept_doc
		,@concepto = con.nombre -- c.Tliqconcept_nombre
	FROM detalle_conceptos_liquidaciones c
	INNER JOIN liquidaciones l on l.id = c.liquidacion
	LEFT JOIN conceptos con on c.concepto = con.id
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


