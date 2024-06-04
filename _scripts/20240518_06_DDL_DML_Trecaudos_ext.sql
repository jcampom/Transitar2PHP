USE [u859387114_transitar]
GO
--------------------------------------------
------- Object:  Table [dbo].[Trecaudos_ext]
--------------------------------------------
IF OBJECT_ID('dbo.Trecaudos_ext', 'U') IS NOT NULL 
  DROP TABLE dbo.Trecaudos_ext;
  
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Trecaudos_ext](
	[Trecaudos_ext_ID] [int] IDENTITY(1,1) NOT NULL,
	[Trecaudos_ext_consecutivo] [int] NOT NULL,
	[Trecaudos_ext_fapl] [date] NOT NULL,
	[Trecaudos_ext_hora] [time](7) NOT NULL,
	[Trecaudos_ext_ftran] [date] NOT NULL,
	[Trecaudos_ext_canal] [int] NOT NULL,
	[Trecaudos_ext_origen] [varchar](40) NOT NULL,
	[Trecaudos_ext_efectivo] [int] NOT NULL,
	[Trecaudos_ext_cheque] [int] NOT NULL,
	[Trecaudos_ext_total] [int] NOT NULL,
	[Trecaudos_ext_documento] [varchar](20) NOT NULL,
	[Trecaudos_ext_nip] [varchar](15) NOT NULL,
	[Trecaudos_ext_tiporec] [int] NOT NULL,
	[Trecaudos_ext_secret] [varchar](8) NOT NULL,
	[Trecaudos_ext_num] [varchar](15) NOT NULL,
	[Trecaudos_ext_otros] [text] NULL,
	[Trecaudos_ext_arch] [int] NOT NULL,
	[Trecaudos_ext_user] [varchar](12) NOT NULL,
	[Trecaudos_ext_fecha] [datetime] NOT NULL,
 CONSTRAINT [PK_Trecaudos_ext] PRIMARY KEY CLUSTERED 
(
	[Trecaudos_ext_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Consecutivo del registro no puede ser superior a 19999. Reinicia por archivo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_consecutivo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha contable de la transacción' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_fapl'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Hora de la transacción' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_hora'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha real de la transacción' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_ftran'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Codigo del canal de origen' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_canal'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Descripción del origen' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_origen'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Valor pagado en efectivo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_efectivo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Valor pagado en cheque' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_cheque'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Valor total pagado' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_total'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Numero de comparendo, liquidación o resolución que está pagando' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_documento'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'numero de identificación del infractor' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_nip'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo de recaudo,  "1" cuando es comparendo,  "2" cuando es liquidación y "3" si es resolución.' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_tiporec'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Codigo de la divipo secretaría origen del comparendo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_secret'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Numero de la consignacion o registro de recaudo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_num'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Otros campos a partir del numero 14' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_otros'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'usuario que hace la carga del archivo al sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_user'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'fecha en que se hace la carga del archivo al sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext', @level2type=N'COLUMN',@level2name=N'Trecaudos_ext_fecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'recaudo externo archivo plano' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Trecaudos_ext'
GO

--- Llenado tabla [dbo].[trecaudos_ext]
TRUNCATE TABLE [u859387114_transitar].[dbo].[trecaudos_ext];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[trecaudos_ext] ON;
INSERT INTO [u859387114_transitar].[dbo].[trecaudos_ext] (Trecaudos_ext_ID ,Trecaudos_ext_consecutivo ,Trecaudos_ext_fapl ,Trecaudos_ext_hora ,Trecaudos_ext_ftran ,Trecaudos_ext_canal ,Trecaudos_ext_origen ,Trecaudos_ext_efectivo ,Trecaudos_ext_cheque ,Trecaudos_ext_total ,Trecaudos_ext_documento ,Trecaudos_ext_nip ,Trecaudos_ext_tiporec ,Trecaudos_ext_secret ,Trecaudos_ext_num ,Trecaudos_ext_otros ,Trecaudos_ext_arch ,Trecaudos_ext_user ,Trecaudos_ext_fecha)
SELECT Trecaudos_ext_ID ,Trecaudos_ext_consecutivo ,Trecaudos_ext_fapl ,Trecaudos_ext_hora ,Trecaudos_ext_ftran ,Trecaudos_ext_canal ,Trecaudos_ext_origen ,Trecaudos_ext_efectivo ,Trecaudos_ext_cheque ,Trecaudos_ext_total ,Trecaudos_ext_documento ,Trecaudos_ext_nip ,Trecaudos_ext_tiporec ,Trecaudos_ext_secret ,Trecaudos_ext_num ,Trecaudos_ext_otros ,Trecaudos_ext_arch ,Trecaudos_ext_user ,Trecaudos_ext_fecha
FROM [cienaga].[dbo].[trecaudos_ext];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[trecaudos_ext] OFF;
-------------------------------------------------------------------------------------------------------
