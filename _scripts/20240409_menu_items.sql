USE [u859387114_transitar]
GO

/****** Object:  Table [dbo].[menu_items]    Script Date: 09/04/2024 1:17:26 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[menu_items](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [varchar](100) NOT NULL,
	[enlace] [varchar](255) NOT NULL,
	[padre_id] [int] NULL,
	[icono] [varchar](30) NOT NULL,
	[empresa] [int] NOT NULL,
	[fecha] [date] NULL,
	[fechayhora] [datetime2](7) NULL,
	[usuario] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO


SET IDENTITY_INSERT u859387114_transitar..menu_items ON
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (13, 'Datos', '#', 0, 'edit', 1, '2023-06-03', '2023-06-03 18:44:28', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (31, 'vehiculos', '#', 13, 'directions_car', 0, '2023-06-13', '2023-06-13 15:40:01', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (32, 'terceros', '#', 13, 'accessibility', 0, '2023-06-13', '2023-06-13 15:40:42', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (33, 'bancos', '#', 13, 'account_balance', 0, '2023-06-13', '2023-06-13 15:41:03', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (34, 'Conceptos / Tramites', '#', 13, 'assignment', 0, '2023-06-13', '2023-06-13 15:41:52', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (35, 'organismo de transito', '#', 13, 'car_rental', 0, '2023-06-13', '2023-06-13 15:42:22', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (36, 'ciudadanos', 'ciudadanos.php', 13, 'group', 0, '2023-06-13', '2023-06-13 15:44:51', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (37, 'inventario', '#', 13, 'all_inbox', 0, '2023-06-13', '2023-06-13 15:45:14', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (38, 'gruas', 'formulario_dinamico_datos.php?id=16', 31, 'car_repair', 0, '2023-06-13', '2023-06-13 16:00:57', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (40, 'tipos', 'formulario_dinamico_datos.php?id=18', 32, 'loyalty', 0, '2023-06-13', '2023-06-13 16:54:40', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (41, 'Agregar empresa de transporte', 'formulario_dinamico_datos.php?id=19', 32, 'local_shipping', 0, '2023-06-13', '2023-06-13 16:55:40', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (42, 'Bancos', 'formulario_dinamico_datos.php?id=20', 33, 'account_balance', 0, '2023-06-13', '2023-06-13 17:27:11', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (43, 'cuentas', 'formulario_dinamico_datos.php?id=21', 33, 'account_balance_wallet', 0, '2023-06-13', '2023-06-13 17:32:31', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (46, 'Relacionar conceptos/tramites', 'conceptos_tramites.php', 34, 'account_tree', 0, '2023-06-13', '2023-06-13 20:41:54', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (47, 'areas', 'formulario_dinamico_datos.php?id=24', 35, 'autofps_select', 0, '2023-06-13', '2023-06-13 21:24:09', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (50, 'lineas', 'formulario_dinamico_datos.php?id=27', 31, 'info', 0, '2023-06-13', '2023-06-13 21:47:02', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (51, 'color', 'formulario_dinamico_datos.php?id=28', 31, 'palette', 0, '2023-06-13', '2023-06-13 21:47:48', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (52, 'marcas', 'formulario_dinamico_datos.php?id=29', 31, 'agriculture', 0, '2023-06-13', '2023-06-13 21:48:32', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (53, 'toneladas', 'formulario_dinamico_datos.php?id=30', 31, 'local_shipping', 0, '2023-06-13', '2023-06-13 21:49:24', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (54, 'tipo inventario', 'formulario_dinamico_datos.php?id=31', 37, 'add_box', 0, '2023-06-13', '2023-06-13 22:00:30', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (55, 'items inventario', 'formulario_dinamico_datos.php?id=32', 37, 'all_inbox', 0, '2023-06-13', '2023-06-13 22:01:01', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (56, 'estado inventario', 'formulario_dinamico_datos.php?id=33', 37, 'inbox', 0, '2023-06-13', '2023-06-13 22:01:19', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (58, 'Vehiculo Libre', 'vehiculos.php', 31, 'directions_car', 1, '2023-06-25', '2023-06-25 09:46:31', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (59, 'Conceptos', 'formulario_dinamico_datos.php?id=35', 34, 'assignment_turned_in', 1, '2023-06-26', '2023-06-26 22:50:57', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (60, 'tramites', '#', 34, 'assignment', 1, '2023-06-26', '2023-06-26 23:01:30', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (61, 'liquidaciones', '#', 0, 'attach_money', 1, '2023-06-28', '2023-06-28 18:50:33', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (63, 'tramites', '#', 0, 'assignment', 1, '2023-07-31', '2023-07-31 18:33:54', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (64, 'Configuracion', '#', 0, 'app_registration', 1, '2023-07-31', '2023-07-31 18:34:33', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (65, 'Recaudo', '#', 0, 'account_balance_wallet', 1, '2023-07-31', '2023-07-31 18:34:52', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (66, 'Parametros', '#', 0, 'info', 1, '2023-07-31', '2023-07-31 18:35:47', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (67, 'Especies venales', '#', 0, 'approval', 1, '2023-07-31', '2023-07-31 18:36:37', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (68, 'Informes', '#', 0, 'description', 1, '2023-07-31', '2023-07-31 18:37:25', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (69, 'Cobros/Amnistias', '#', 0, 'local_atm', 1, '2023-07-31', '2023-07-31 18:38:48', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (71, 'Citaciones', '#', 0, 'alarm_add', 1, '2023-07-31', '2023-07-31 18:40:09', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (72, 'Historicos', '#', 0, 'timeline', 1, '2023-07-31', '2023-07-31 18:43:00', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (73, 'Recaudo(Bancario, por caja)', 'recaudo.php', 65, 'attach_money', 1, '2023-08-07', '2023-08-07 10:31:39', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (74, 'Liquidacion', 'formulario_dinamico_datos.php?id=38', 66, 'attach_money', 1, '2023-08-22', '2023-08-22 18:23:16', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (75, 'SMLV', 'formulario_dinamico_datos.php?id=39', 66, 'account_balance', 1, '2023-08-22', '2023-08-22 18:30:03', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (76, 'Tasas de interes moratorio', 'formulario_dinamico_datos.php?id=40', 66, 'account_balance_wallet', 1, '2023-08-22', '2023-08-22 18:38:53', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (77, 'Generales', 'formulario_dinamico_datos.php?id=41', 66, 'home', 1, '2023-08-22', '2023-08-22 18:53:20', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (78, 'Economicos', 'formulario_dinamico_datos.php?id=42', 66, 'timeline', 1, '2023-08-22', '2023-08-22 18:56:38', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (79, 'Terceros', 'formulario_dinamico_datos.php?id=43', 32, 'accessibility', 1, '2023-08-28', '2023-08-28 11:47:12', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (80, 'RNA', 'tramites.php?tipo_tramite=1', 63, 'car_rental', 1, '2023-09-12', '2023-09-12 10:39:50', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (81, 'RNC', 'tramites.php?tipo_tramite=2', 63, 'accessibility', 1, '2023-09-12', '2023-09-12 10:41:55', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (82, 'Comparendos', 'comparendos.php', 63, 'attach_money', 1, '2023-09-12', '2023-09-12 10:52:19', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (83, 'Notas Credito', 'notas_credito.php', 63, 'local_atm', 1, '2023-09-12', '2023-09-12 17:28:02', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (84, 'Resoluciones', '#', 63, 'gavel', 1, '2023-09-18', '2023-09-18 21:11:09', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (85, 'Acuerdos de pago', 'acuerdos_pago.php', 84, 'attach_money', 1, '2023-09-18', '2023-09-18 21:13:07', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (86, 'Plantillas resoluciones', 'plantillas_resoluciones.php', 64, 'view_list', 1, '2023-09-21', '2023-09-21 08:48:17', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (87, 'Oficio de notificación', 'resoluciones.php?id=7', 84, 'gavel', 1, '2023-09-21', '2023-09-21 11:07:14', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (89, 'Audiencia con contraventor', 'resoluciones.php?id=8', 84, 'gavel', 1, '2023-09-21', '2023-09-21 16:05:59', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (91, 'Constancia no presentación', 'sanciones_alert.php?documento=6', 84, 'gavel', 1, '2023-09-21', '2023-09-21 16:30:57', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (93, 'Audiencia', 'sanciones_alert.php?documento=31', 84, 'gavel', 1, '2023-09-21', '2023-09-21 20:05:25', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (94, 'Mandamientos de pago', 'masivoFormMP.php', 84, 'gavel', 1, '2023-09-26', '2023-09-26 15:12:52', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (95, 'Fallo Audiencia', 'resoluciones.php?id=12', 84, 'gavel', 1, '2023-09-26', '2023-09-26 16:41:47', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (96, 'Alerta notificaciones', 'resoluciones.php?id=13', 84, 'gavel', 1, '2023-09-29', '2023-09-29 22:48:12', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (97, 'Avisos y notificaciones', '#', 63, 'access_alarms', 1, '2023-09-30', '2023-09-30 10:22:30', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (98, 'Actualizar notificación', 'notificaciones.php?tipo=1', 97, 'notifications_active', 1, '2023-09-30', '2023-09-30 21:17:49', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (99, 'Actualizar infractor', 'notificaciones.php?tipo=2', 97, 'accessibility', 1, '2023-09-30', '2023-09-30 21:18:19', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (100, 'Parametros SIMIT WS', 'formulario_dinamico_datos.php?id=70', 66, 'airplay', 1, '2023-10-03', '2023-10-03 15:01:43', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (101, 'AP Parametros', 'formulario_dinamico_datos.php?id=71', 66, 'account_balance_wallet', 1, '2023-10-03', '2023-10-03 19:47:54', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (102, 'Notificaciones y avisos', 'formulario_dinamico_datos.php?id=72', 66, 'access_alarms', 1, '2023-10-03', '2023-10-03 20:41:11', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (103, 'Parametros recaudo', 'formulario_dinamico_datos.php?id=73', 66, 'account_balance', 1, '2023-10-03', '2023-10-03 21:00:50', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (104, 'Medidas Cautelares', 'formulario_dinamico_datos.php?id=74', 66, 'car_rental', 1, '2023-10-03', '2023-10-03 21:09:31', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (105, 'Calendario', 'calendario.php', 71, 'date_range', 1, '2023-10-06', '2023-10-06 10:14:13', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (106, 'Calendario(Festivos)', 'festivos.php', 64, 'date_range', 1, '2023-10-18', '2023-10-18 16:27:06', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (107, 'Agendar', 'ingresar_agenda.php', 71, 'update', 1, '2023-10-18', '2023-10-18 18:30:42', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (108, 'Licencia de Transito', 'licencias_transito.php', 67, 'badge', 1, '2023-10-20', '2023-10-20 12:59:20', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (109, 'Licencia de Conducción', 'licencias_conduccion.php', 67, 'airport_shuttle', 1, '2023-10-20', '2023-10-20 12:59:57', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (110, 'Sustratos de licencia de transito', 'licencias_transito_sustrato.php', 67, 'assignment_ind', 1, '2023-10-20', '2023-10-20 13:00:54', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (111, 'Comparendos', 'ev_comparendos.php', 67, 'attach_money', 1, '2023-10-20', '2023-10-20 13:01:58', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (112, 'Placas', 'placas.php', 67, 'aspect_ratio', 1, '2023-10-20', '2023-10-20 13:02:35', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (113, 'Sustrato de licencia de conducción', 'licencias_conduccion_sustrato.php', 67, 'directions_car', 1, '2023-10-20', '2023-10-20 13:21:15', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (114, 'Anular sustrato', 'anular_sustrato.php', 67, 'delete', 1, '2023-10-20', '2023-10-20 15:55:43', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (115, 'Entrega Comparendos', 'entregar_comparendos.php', 67, 'arrow_forward', 1, '2023-10-23', '2023-10-23 14:22:29', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (116, 'Consultar Comparendos', 'consultar_comparendos.php', 67, 'search', 1, '2023-10-23', '2023-10-23 14:22:53', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (117, 'Honorarios/Cobranzas', 'honorarios_cobranzas.php', 69, 'attach_money', 1, '2023-10-30', '2023-10-30 10:48:28', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (118, 'Medidas cautelares comparendos', '#', 69, 'auto_stories', 1, '2023-11-01', '2023-11-01 20:10:43', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (119, 'Inscripción de medidas', 'medidas_cautelares.php', 118, 'add', 1, '2023-11-01', '2023-11-01 20:11:33', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (120, 'Levantamiento de medidas', 'levantamiento_medidas_cautelares.php', 118, 'settings_backup_restore', 1, '2023-11-01', '2023-11-01 20:12:46', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (121, 'Informe de medidas cautelares', 'informe_medidas_cautelares.php', 118, 'assignment', 1, '2023-11-02', '2023-11-02 13:40:26', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (122, 'AP Historicos', 'historial_ap.php', 72, 'attach_money', 1, '2023-11-03', '2023-11-03 16:36:26', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (123, 'Recaudo historico', 'recaudo_historico.php', 72, 'addchart', 1, '2023-11-03', '2023-11-03 16:38:36', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (124, 'Historico Tramites', 'historico_tramites.php', 72, 'assignment', 1, '2023-11-04', '2023-11-04 10:13:07', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (125, 'Historico resoluciones', 'historico_resoluciones.php', 72, 'gavel', 1, '2023-11-07', '2023-11-07 21:09:33', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (126, 'Recaudos', '#', 68, 'attach_money', 1, '2023-11-08', '2023-11-08 08:51:45', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (127, 'Recaudo bancario o caja', 'informe_recaudo.php', 126, 'account_balance', 1, '2023-11-08', '2023-11-08 09:02:23', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (128, 'Acuerdos de pago', 'informe_ap.php', 68, 'account_balance_wallet', 1, '2023-11-08', '2023-11-08 14:24:32', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (129, 'Liquidaciones', '#', 68, 'done_all', 1, '2023-11-08', '2023-11-08 20:56:13', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (130, 'Ver liq estado', 'ver_liq_estado.php', 129, 'attach_money', 1, '2023-11-08', '2023-11-08 20:57:20', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (131, 'Liquidaciones Generales', 'liquidaciones_generales.php', 129, 'auto_stories', 1, '2023-11-08', '2023-11-08 21:43:01', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (132, 'Informe Recaudos', 'informe_recaudos.php', 129, 'account_balance_wallet', 1, '2023-11-09', '2023-11-09 12:57:00', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (133, 'Informe vehiculos', 'formulario_dinamico_datos.php?id=75', 68, 'directions_car', 1, '2023-11-09', '2023-11-09 16:30:19', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (134, 'Informe Ciudadanos', 'formulario_dinamico_datos.php?id=76', 68, 'group', 1, '2023-11-09', '2023-11-09 16:43:39', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (135, 'Terceros', '#', 68, 'accessibility_new', 1, '2023-11-09', '2023-11-09 17:13:49', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (136, 'Informes recaudos terceros', 'info_terceros_recaudo.php', 135, 'person', 1, '2023-11-09', '2023-11-09 17:14:42', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (137, 'Terceros AP-COMP', 'terceros_ap_comp.php', 135, 'directions_car', 1, '2023-11-14', '2023-11-14 13:12:33', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (138, 'Comparendos', '#', 68, 'account_balance_wallet', 1, '2023-11-14', '2023-11-14 14:53:50', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (139, 'Comparendos generales', 'comparendos_generales.php', 138, 'directions_car', 1, '2023-11-14', '2023-11-14 14:56:35', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (140, 'Derechos de transito', '#', 68, 'directions_bus', 1, '2023-11-15', '2023-11-15 13:38:39', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (141, 'Doc.Cobro', 'documento_cobro.php', 140, 'account_balance_wallet', 1, '2023-11-15', '2023-11-15 13:40:12', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (142, 'Informe resoluciones', 'informe_dt.php', 140, 'analytics', 1, '2023-11-15', '2023-11-15 19:26:57', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (143, 'Notas credito', 'info_notas_credito.php', 68, 'account_balance', 1, '2023-11-15', '2023-11-15 19:54:21', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (144, 'Actualizaciones y avisos', '#', 68, 'notifications_active', 1, '2023-11-18', '2023-11-18 17:01:08', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (145, 'Informe de actualizar fecha de notificación', 'infactucomp.php', 144, 'add_alert', 1, '2023-11-18', '2023-11-18 17:02:33', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (146, 'Informe Actualizacion de Infractor de Comparendo', 'infactuinfcomp.php', 144, 'accessibility', 1, '2023-11-18', '2023-11-18 17:43:23', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (147, 'Informe Avisos de Notificacion de Comparendo', 'infavisoscomp.php', 144, 'directions_car', 1, '2023-11-18', '2023-11-18 18:00:08', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (148, 'Informe Avisos de Notificacion de Mandamiento de Pago', 'infavisosmp.php', 144, 'account_balance_wallet', 1, '2023-11-18', '2023-11-18 18:56:36', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (149, 'Informe Revocatorias Novedad 34 de Comparendo', 'infacturev.php', 144, 'auto_delete', 1, '2023-11-18', '2023-11-18 19:29:39', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (150, 'Informe Fijaciones de Audiencia de Comparendos Electronicos', 'infcitasaud.php', 144, 'assistant_photo', 1, '2023-11-18', '2023-11-18 19:40:09', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (151, 'Especies venales', '#', 68, 'aspect_ratio', 1, '2023-11-19', '2023-11-19 10:55:03', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (152, 'EV General', 'especiesvenales.php', 151, 'aspect_ratio', 1, '2023-11-19', '2023-11-19 11:00:28', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (153, 'Sustratos Usados', 'sustratos_usados.php', 151, 'backspace', 1, '2023-11-19', '2023-11-19 13:09:42', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (154, 'Paz y Salvo', 'paz_salvo_dt.php', 140, 'directions_car', 1, '2023-11-19', '2023-11-19 19:12:52', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (155, 'Contaduria Gen. de la nacion', '#', 68, 'account_balance', 1, '2023-11-19', '2023-11-19 19:59:01', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (156, 'Registro semestral', 'infocomparendosCGN.php', 155, 'date_range', 1, '2023-11-19', '2023-11-19 19:59:59', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (157, 'Exportar planos', '#', 63, 'auto_awesome_motion', 1, '2023-11-22', '2023-11-22 11:41:42', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (158, 'Comparendos', 'expplanocomp.php', 157, 'attach_money', 1, '2023-11-22', '2023-11-22 11:42:39', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (159, 'Resoluciones', 'expplanores.php', 157, 'approval', 1, '2023-11-22', '2023-11-22 17:17:01', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (160, 'Actualizaciones', 'expplanotifica.php', 157, 'update', 1, '2023-11-23', '2023-11-23 08:52:07', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (161, 'Recaudos', 'expplanorec.php', 157, 'account_balance_wallet', 1, '2023-11-23', '2023-11-23 10:20:42', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (162, 'Aviso de notificación', 'aviso_notificaciones.php', 97, 'notifications', 1, '2023-11-23', '2023-11-23 20:14:20', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (163, 'Crear Liquidación', 'liquidaciones.php', 61, 'account_balance_wallet', 1, '2023-11-24', '2023-11-24 16:36:30', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (164, 'Anular liquidación', 'ver_liq_estado.php', 61, 'backspace', 1, '2023-11-24', '2023-11-24 16:37:11', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (165, 'Sedes', 'formulario_dinamico_datos.php?id=77', 35, 'account_balance', 1, '2023-11-27', '2023-11-27 09:01:59', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (166, 'Empleados', 'formulario_dinamico_datos.php?id=78', 35, 'accessibility', 1, '2023-11-27', '2023-11-27 09:08:31', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (167, 'Informe resoluciones', 'infores', 158, 'info', 1, '2023-12-05', '2023-12-05 08:22:42', 1);
INSERT INTO menu_items (id, nombre, enlace, padre_id, icono, empresa, fecha, fechayhora, usuario) VALUES (169, 'Informe resoluciones', 'infores.php', 138, 'info', 1, '2023-12-05', '2023-12-05 08:30:29', 1);
SET IDENTITY_INSERT u859387114_transitar..menu_items OFF