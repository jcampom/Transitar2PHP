USE [u859387114_transitar]
GO

--------------------------------------
------- Object:  Table [dbo].[Ticonos]
--------------------------------------
IF OBJECT_ID('dbo.Ticonos', 'U') IS NOT NULL 
  DROP TABLE dbo.Ticonos;
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Ticonos](
	[Ticonos_ID] [int] IDENTITY(1,1) NOT NULL,
	[Ticonos_modulotabla] [nchar](30) NOT NULL,
	[Ticonos_icono] [nchar](20) NOT NULL,
	[Ticonos_titulo] [varchar](90) NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Ticonos', @level2type=N'COLUMN',@level2name=N'Ticonos_ID'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tabla' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Ticonos', @level2type=N'COLUMN',@level2name=N'Ticonos_modulotabla'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Nombre de Icono' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Ticonos', @level2type=N'COLUMN',@level2name=N'Ticonos_icono'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Título del módulo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Ticonos', @level2type=N'COLUMN',@level2name=N'Ticonos_titulo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tabla que contiene la información de los íconos de las tablas o módulos' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Ticonos'
GO

--- Llenado tabla [dbo].[ticonos]
TRUNCATE TABLE [u859387114_transitar].[dbo].[ticonos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[ticonos] ON;
INSERT INTO [u859387114_transitar].[dbo].[ticonos] (Ticonos_ID ,Ticonos_modulotabla ,Ticonos_icono ,Ticonos_titulo)
SELECT Ticonos_ID ,Ticonos_modulotabla ,Ticonos_icono ,Ticonos_titulo
FROM [cienaga].[dbo].[ticonos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[ticonos] OFF;
-------------------------------------------------------------------------------------------
