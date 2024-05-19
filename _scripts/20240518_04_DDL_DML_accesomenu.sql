USE [u859387114_transitar]
GO
-----------------------------------------
------- Object:  Table [dbo].[accesomenu]
-----------------------------------------
IF OBJECT_ID('dbo.accesomenu', 'U') IS NOT NULL 
  DROP TABLE dbo.accesomenu;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[accesomenu](
	[idacceso] [int] IDENTITY(1,1) NOT NULL,
	[accesoperfil] [int] NOT NULL,
	[accesomenu] [int] NOT NULL,
	[accesouser] [varchar](20) NULL,
 CONSTRAINT [PK__accesome__F7B9EC7129572725] PRIMARY KEY CLUSTERED 
(
	[idacceso] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[accesomenu] ADD  CONSTRAINT [DF__accesomen__acces__2B3F6F97]  DEFAULT ('0') FOR [accesoperfil]
GO

ALTER TABLE [dbo].[accesomenu] ADD  CONSTRAINT [DF__accesomen__acces__2C3393D0]  DEFAULT ('0') FOR [accesomenu]
GO

ALTER TABLE [dbo].[accesomenu]  WITH CHECK ADD  CONSTRAINT [FK_accesomenu_menu] FOREIGN KEY([accesomenu])
REFERENCES [dbo].[menu] ([idmenu])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

ALTER TABLE [dbo].[accesomenu] CHECK CONSTRAINT [FK_accesomenu_menu]
GO

ALTER TABLE [dbo].[accesomenu]  WITH CHECK ADD  CONSTRAINT [FK_accesomenu_perfiles] FOREIGN KEY([accesoperfil])
REFERENCES [dbo].[perfiles] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

ALTER TABLE [dbo].[accesomenu] CHECK CONSTRAINT [FK_accesomenu_perfiles]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID ACCESO' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'accesomenu', @level2type=N'COLUMN',@level2name=N'idacceso'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID PERFIL' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'accesomenu', @level2type=N'COLUMN',@level2name=N'accesoperfil'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID MENU' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'accesomenu', @level2type=N'COLUMN',@level2name=N'accesomenu'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'En esta tabla se asignaran los permisos al menu dependiendo del perfil' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'accesomenu'
GO
------------------------------------
--- Llenado tabla [dbo].[accesomenu]
------------------------------------
TRUNCATE TABLE [u859387114_transitar].[dbo].[accesomenu];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[accesomenu] ON;
INSERT INTO [u859387114_transitar].[dbo].[accesomenu] ( idacceso ,accesoperfil ,accesomenu ,accesouser )
SELECT idacceso ,accesoperfil ,accesomenu ,accesouser
FROM [cienaga].[dbo].[accesomenu];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[accesomenu] OFF;
-------------------------------------------------------------------------------------------------
