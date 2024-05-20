USE [u859387114_transitar]
GO
---------------------------------------
------- Object:  Table [dbo].[festivos]
---------------------------------------
IF OBJECT_ID('dbo.festivos', 'U') IS NOT NULL 
  DROP TABLE dbo.festivos;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[festivos](
	[Tfestivos_id] [int] IDENTITY(1,1) NOT NULL,
	[Tfestivos_fecha] date NOT NULL,
	[Tfestivos_descripcion] [nvarchar](50) NOT NULL,
	[Tfestivos_estilo] [nvarchar](50) NOT NULL,
	[Tfestivos_tipo] tinyint NOT NULL,
 CONSTRAINT [PK_festivos] PRIMARY KEY CLUSTERED 
(
	[Tfestivos_fecha] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[festivos] ADD  CONSTRAINT [DF_festivos_Tfestivos_tipo]  DEFAULT ((1)) FOR [Tfestivos_tipo]
GO

ALTER TABLE [dbo].[festivos] ADD  CONSTRAINT [DF_festivos_Tfestivos_estilo]  DEFAULT ('highlight2') FOR [Tfestivos_estilo]
GO


EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'festivos', @level2type=N'COLUMN',@level2name=N'Tfestivos_id'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'festivos', @level2type=N'COLUMN',@level2name=N'Tfestivos_fecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Descripción' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'festivos', @level2type=N'COLUMN',@level2name=N'Tfestivos_descripcion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Dia de la semana' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'festivos', @level2type=N'COLUMN',@level2name=N'Tfestivos_estilo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Información de los días festivos de los años.' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'festivos'
GO

----------------------------------
--- Llenado tabla [dbo].[festivos]
----------------------------------
TRUNCATE TABLE [u859387114_transitar].[dbo].[festivos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[festivos] ON;
INSERT INTO [u859387114_transitar].[dbo].[festivos] (Tfestivos_id ,Tfestivos_fecha ,Tfestivos_descripcion ,Tfestivos_estilo ,Tfestivos_tipo)
SELECT Tfestivos_id ,Tfestivos_fecha ,Tfestivos_descripcion ,Tfestivos_estilo ,Tfestivos_tipo
FROM [cienaga].[dbo].[tfestivos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[festivos] OFF;
-------------------------------------------------------------------------------------------------------------------------------------------

