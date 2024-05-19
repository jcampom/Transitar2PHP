USE [u859387114_transitar]
GO
-----------------------------------
------- Object:  Table [dbo].[menu]
-----------------------------------
IF OBJECT_ID('dbo.menu', 'U') IS NOT NULL 
  DROP TABLE dbo.menu;
  
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[menu](
	[idmenu] [int] IDENTITY(1,1) NOT NULL,
	[menulabel] [varchar](50) NOT NULL,
	[menulevel] [int] NOT NULL,
	[menulink] [varchar](100) NOT NULL,
	[menupos] [int] NOT NULL,
	[menuinto] [int] NOT NULL,
 CONSTRAINT [PK__menu__753CC8502F10007B] PRIMARY KEY CLUSTERED 
(
	[idmenu] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID MENU' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'menu', @level2type=N'COLUMN',@level2name=N'idmenu'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'NOMBRE OPCION DEL MENU' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'menu', @level2type=N'COLUMN',@level2name=N'menulabel'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'NIVEL' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'menu', @level2type=N'COLUMN',@level2name=N'menulevel'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ARCHIVO PHP' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'menu', @level2type=N'COLUMN',@level2name=N'menulink'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'UBICACION EN EL MENU' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'menu', @level2type=N'COLUMN',@level2name=N'menupos'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID DEL SUBMENU QUE DEPENDE' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'menu', @level2type=N'COLUMN',@level2name=N'menuinto'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'En esta tabla tendremos el menu de la aplicacion' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'menu'
GO
------------------------------
--- Llenado tabla [dbo].[menu]
------------------------------
TRUNCATE TABLE [u859387114_transitar].[dbo].[menu];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[menu] ON;
INSERT INTO [u859387114_transitar].[dbo].[menu] (idmenu ,menulabel ,menulevel ,menulink ,menupos ,menuinto)
SELECT idmenu ,menulabel ,menulevel ,menulink ,menupos ,menuinto
FROM [cienaga].[dbo].[menu];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[menu] OFF;

