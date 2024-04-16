<div id="demo1">
				<div id="demoIzq">
					<input type="text" class="form-control" name="liquidacion" id="liquidacion" size="9" maxlength="9" onblur="nuevoEvento1('liquidacion')"  onkeypress="return IsNumber(event);">
					<button type="button" class="form-control btn btn-info" id="botonVerificacion1" onclick="nuevoEvento1('liquidacion')" >Comprobar</button>
				</div>
				<div class="mensaje" id="error1"></div>
</div>