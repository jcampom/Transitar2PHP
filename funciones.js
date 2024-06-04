//// Remplaza comas de valores tipo moneda ////



function Extract_old(Obj){
	var str=Obj;
	return(str.replace(/,/g, ""));
	}
//// Remplaza comas de valores tipo moneda ////	
function Extract(Obj){
	var str=Obj.replace(/\$/g, "");
	str=str.replace(/ /g, "");
	str=str.replace(/\./g, "");
	str=str.replace(/,/g, "");
	if(str==""){return "0";}
	else{return(str);}		
	}
//// Agrega comas a valores tipo moneda ////
function formatCurrency(num){
	num = num.toString().replace(/ |,/g,'');
	if(isNaN(num)) 
	num = "0";
	cents = Math.floor((num*100+0.5)%100);
	num = Math.floor((num*100+0.5)/100).toString();
	if(cents < 10) 
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	num = '$ '+num;
	return (num);
	}  
//// Agrega comas a valores tipo moneda ////
function FormatoMoneda(num){
	num = num.toString().replace(/ |,/g,'');
	if(isNaN(num)) 
	num = "0";
	cents = Math.floor((num*100+0.5)%100);
	num = Math.floor((num*100+0.5)/100).toString();
	if(cents < 10) 
	cents = "0" + cents;	
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	return (num);
	}
//// Agrega comas a valores tipo moneda ////
function puntitos(donde,caracter){
	pat = /[\*,\+,\(,\),\?,\,$,\[,\],\^]/;
	valor = donde.value;
	largo = valor.length;
	crtr = true;
	if(isNaN(caracter) || pat.test(caracter) == true){
		if (pat.test(caracter)==true){ 
			caracter="/"+caracter;
			}
		carcter = new RegExp(caracter,"g");
		valor = valor.replace(carcter,"");
		donde.value = valor;
		crtr = false;
		}
	else{
		var nums = new Array();
		cont = 0;
		for(m=0;m<largo;m++){
			if(valor.charAt(m) == "," || valor.charAt(m) == " ")
				{continue;}
			else{
				nums[cont] = valor.charAt(m);
				cont++;
				}
			}
		}
	var cad1="",cad2="",tres=0;
	if(largo > 3 && crtr == true){
		for (k=nums.length-1;k>=0;k--){
			cad1 = nums[k];
			cad2 = cad1 + cad2;
			tres++;
			if((tres%3) == 0){
				if(k!=0){
					cad2 = "," + cad2;
					}
				}
			}
		donde.value = cad2;
		}
	}	 
//// Agrega comas a valores tipo moneda ////  
function tabular(e,obj) {
	tecla=(document.all) ? e.keyCode : e.which; 
	if(tecla!=13) return; 
	frm=obj.form; 
	for(i=0;i<frm.elements.length;i++) 
		if(frm.elements[i]==obj){ 
			if(i==frm.elements.length-1) i=-1; 
			break;
			} 
		frm.elements[i+1].focus(); 
	return false; 
	} 
//// funcion reloj ejemplo o utilizado ////	
function reloj() {
	var fObj = new Date() ; 
	var horas = fObj.getHours() ; 
	var minutos = fObj.getMinutes() ; 
	var segundos = fObj.getSeconds() ; 
	if (horas <= 9) horas = "0" + horas; 
	if (minutos <= 9) minutos = "0" + minutos; 
	if (segundos <= 9) segundos = "0" + segundos; 
	window.status = horas+":"+minutos+":"+segundos;
	}
////  Valida que solo se ingresen numeros ////
function numeros(e){
	//Inicio De La Funcion
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla<= 13 ||  tecla >= 48 && tecla <= 57) return true;
	patron = /\d/;
	te = String.fromCharCode(tecla);
	return patron.test(te); 
	// onKeyPress="return numeros(event)" forma de llamar la funcion en el form
	}
////  Rmplaza los simbolos & por | para poder pasar variables por java ////
function Remplazar(Obj){
	var str=Obj;
	str=str.replace(/&/g, "|");
	document.form.lin.value=str;
	return(str);
	}
//// Validar formato correo electronico ////
function MM_validateForm(){//v4.0
	if(document.getElementById){
		var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
		for(i=0; i<(args.length-2); i+=3){
			test=args[i+2]; val=document.getElementById(args[i]);
			if(val){
				nm=val.name; 
				if((val=val.value)!=""){
					if(test.indexOf('isEmail')!=-1){ 
						p=val.indexOf('@');
						if(p<1 || p==(val.length-1)){
							errors+='Digite una dirección de correo electrónico válida.\nEjemplo: nombre@dominio.com';
							}
						}
					}
				}
			if(errors){
				alert('Ocurrió el siguiente error:\n'+errors); 
				document.getElementById(nm).focus();
				document.MM_returnValue = (errors == '');
				}
			}
		}
	}
////  validar tipo de archivo en un campo tipo file ////
function ValidaFile(f){
	var e=f.name;
    var ext=['gif','jpg','jpeg','png','pdf'];
    var v=f.value.split('.').pop().toLowerCase();
    for(var i=0,n;n=ext[i];i++){
        if(n.toLowerCase()==v)
            return
    }
    var t=f.cloneNode(true);
    t.value='';
    f.parentNode.replaceChild(t,f);
    alert('Tipo de archivo no válido, debe ser una imagen (.gif, .jpg, .jpeg, .pdf o .png)');
	setTimeout("document.getElementById('"+e+"').focus()",1);
	}
//  Valida si el radio esta chequeado y habilita o desabilita campos ////
function CampoVisibleOculto(a,b){
	if(document.getElementById(a).value==1){document.getElementById(b).style.display='block';}
	else{document.getElementById(b).style.display='none';}
	}
////  valida los campos del formulario ingreso nuevo menu que son requeridos y envia los datos a guardar ////
function ValidaMenu(f){
		if(document.form.nivel.value.length<1){
				alert("Seleccione un nivel");
				//FAjax('depende.php','depen','','post');
				f.nivel.focus();
				return false;
			}
		if((document.form.dep.value.length<1)&&(document.form.nivel.value>1)){
				alert("Seleccione una dependencia");
				//FAjax('depende.php','depen','','post');
				f.dep.focus();
				return false;
			}		
		if(document.form.posic.value.length<1){
				alert("Digite la posici\xf3n");
				//FAjax('depende.php','depen','','post');
				f.posic.focus();
				return false;
			}
		if(document.form.nombre.value.length<1){
				alert("Digite el nombre");
				//FAjax('depende.php','depen','','post');
				f.nombre.focus();
				return false;
			}
		if((document.form.lin.value.length<1)&&(document.form.nivel.value>2)){
				alert("Digite el link");
				//FAjax('depende.php','depen','','post');
				f.lin.focus();
				return false;
			}
		if(confirm('¿Est\xe1 seguro que desea ingresar el registro?')) {
			FAjax('insmenu.php?accion=2','formula','','post');
			}
	}
////  valida los campos del formulario editar menu que son requeridos y envia los datos a guardar ////
function ValidaEditMenu(f){
		if(document.form.nivel.value.length<1){
				alert("Seleccione un nivel");
				//FAjax('depende.php','depen','','post');
				f.nivel.focus();
				return false;
			}
		if((document.form.dep.value.length<1)&&(document.form.nivel.value>1)){
				alert("Seleccione una dependencia");
				//FAjax('depende.php','depen','','post');
				f.dep.focus();
				return false;
			}		
		if(document.form.posic.value.length<1){
				alert("Digite la posici\xf3n");
				//FAjax('depende.php','depen','','post');
				f.posic.focus();
				return false;
			}
		if(document.form.nombre.value.length<1){
				alert("Digite el nombre");
				//FAjax('depende.php','depen','','post');
				f.nombre.focus();
				return false;
			}
		if((document.form.lin.value.length<1)&&(document.form.nivel.value>2)){
				alert("Digite el link");
				//FAjax('depende.php','depen','','post');
				f.lin.focus();
				return false;
			}
		if(confirm('¿Est\xe1 seguro que desea actualizar el registro?')) {
			FAjax('insmenu.php?accion=1','formula','','post');
			}		
	}
////  Valida que el campo nivel del menu no este vacio ////
function ValidaNivel(f){
	if(document.form.nivel.value.length<1){
		alert("Seleccione un nivel");
		//FAjax('depende.php','depen','','post');
		f.nivel.focus();
		return false;
		}	
	}
////  Valida que el campo dependencia del menu no este vacio si el nivel es diferente de 1 ////
function ValidaDepende(f){
	if((document.form.dep.value.length<1)&&(document.form.nivel.value>1)){
		alert("Seleccione una dependencia");
		//FAjax('depende.php','depen','','post');
		f.dep.focus();
		return false;
		}	
	}
////  Valida que el campo posicion del menu no este vacio ////
function ValidaPosicion(f){
	if(document.form.posic.value.length<1){
		alert("Digite la posici\xf3n");
		//FAjax('depende.php','depen','','post');
		f.posic.focus();
		return false;
		}	
	}
////  Valida que el campo nombre del menu no este vacio ////
function ValidaNombre(f){
	if(document.form.nombre.value.length<1){
		alert("Digite el nombre");
		//FAjax('depende.php','depen','','post');
		f.nombre.focus();
		return false;
		}	
	}
////  Valida que el campo link del menu no este vacio ////
function ValidaLink(f){
	if((document.form.lin.value.length<1)&&(document.form.nivel.value>2)){
		alert("Digite el link");
		//FAjax('depende.php','depen','','post');
		f.lin.focus();
		return false;
		}
	}
////  Recoge los datos enviados en el campo liquidacion de los tramites y los envia al archivo liq.php via java para hacer las tareas requeridas  ////
function BuscaLiquida(v,b,c,d,f,g,h,j){
	var a=v.value;
	var i=v.name;
	if(document.getElementById(i).value.length == ""){
		alert("Digite un n\xfamero de liquidaci\xf3n");
		document.getElementById(i).className='campoRequerido';
		document.getElementById(i).value='';
		//setTimeout(document.getElementById(i).focus(),1);
		return false;
		}
	document.getElementById(i).className='';
	FAjax('liq.php?dato='+a+'|'+b+'|'+c+'|'+d+'|'+i+'|'+f+'|'+g+'|'+h+'|'+j,'2'+i,'','get');
	}
//// Recoge los datos enviados en el campo liquidacion CMH del tramite traslado y los envia al archivo liq2.php via ajax para hacer las tareas requeridas ////
function BuscaLiquida2(){
	if(document.getElementById('Tvehiculos_TP_liquidacionCMH').value.length<1){
		alert("Digite un n\xfamero de liquidaci\xf3n");
		document.getElementById('Tvehiculos_TP_liquidacionCMH').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_TP_liquidacionCMH').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_TP_liquidacionCMH').className='';
	FAjax('liq2.php?dato='+document.getElementById('Tvehiculos_TP_liquidacionCMH').value+'|'+document.getElementById('Tvehiculos_TP_placa').value,'liqcmh','','get');
	}
////  Recoge los datos enviados en el campo nombre de los tramites y los envia al archivo nom.php via java para hacer las tareas requeridas  ////
function BuscaNombre(v,b,c,d,f,g,h,j){
	var a=v.value;
	var e=v.name;
	//alert('Ciudadano');
	FAjax('nom.php?dato='+a+'|'+b+'|'+c+'|'+d+'|'+e+'|'+f+'|'+g+'|'+j,'2'+e,'','get');
	}
////  Recoge los datos enviados en el campo placa de los tramites y los envia al archivo plac.php via java para hacer las tareas requeridas  ////
function BuscaPlaca(v,b,c,d,f,g,h,j){
	var a=v.value;
	var e=v.name;
	if(document.getElementById(j+'_liquidacion').value.length<1){
		alert("Digite un n\xfamero de liquidaci\xf3n");
		document.getElementById(j+'_liquidacion').className='campoRequerido';
		document.getElementById(j+'_liquidacion').value='';
		setTimeout("document.getElementById(j+'_liquidacion').focus()",1);
		return false;
		}
	if(document.getElementById(e).value.length<1){
		alert("Digite un n\xfamero de placa");
		document.getElementById(e).className='campoRequerido';
		setTimeout("document.getElementById("+e+").focus()",1);
		return false;
		}
	document.getElementById(j+'_liquidacion').className='';
	document.getElementById(e).className='';
	FAjax('plac.php?dato='+a+'|'+b+'|'+c+'|'+d+'|'+e+'|'+f+'|'+g+'|'+h+'|'+j,'2'+e,'','get');
	}
////  Recoge los datos enviados en el campo placa de los tramites y los envia al archivo plac.php via java para hacer las tareas requeridas  ////
function BuscaPlaca2(v,b,c,d,f,g,h,j){
	//alert("placa");
	var a=v.value;
	var e=v.name;
	//alert('Placa 2');
	FAjax('plac.php?dato='+a+'|'+b+'|'+c+'|'+d+'|'+e+'|'+f+'|'+g+'|'+h+'|'+j,'2'+e,'','get');
	}
////  Recoge los datos enviados en el campo ejemplo de los tramites y los envia al archivo plac2.php via java para hacer las tareas requeridas  ////
function BuscaEjemplo(v,b,c,d,f,g,h,j){
	var a=v.value;
	var e=v.name;
	alert("placa "+e);
	FAjax('plac2.php?dato='+a+'|'+b+'|'+c+'|'+d+'|'+e+'|'+f+'|'+g+'|'+h+'|'+j,'verb'+e,'','get');
	}
var currfield = '';
////  Valida los campos requeridos en los tramites ////
function CampoVacio(field){
	var a=field.value;
	var e=field.name;
	if(document.getElementById(e).value.length<1){
		alert ("El campo es requerido no debe ser vacio");
		setTimeout("document.getElementById('"+e+"').focus()",1);
		return false;
		}
	}
////  Valida que se seleccione un valor en el campo tipo de servicio en la liquidacion ////
function TipoServicio(v) {
    var a = v.value;
    var e = v.name;
    if (a === "") {
        alert("Seleccione un tipo de servicio");
        v.className = 'campoRequerido';
        setTimeout("document.getElementById('" + e + "').focus()", 1);
        return false;
    }
    document.getElementById('tiposerv').className = '';
    TipoClaseMIVal();
}

//// Valida y envia los datos de placa de matricula inicial en liquidacion ////
function TipoClaseMIVal() {
    var s = document.getElementById('tiposerv').value;
    var c = document.getElementById('tipoclas').value;
    var f = document.getElementById('tipoclasif').value;
    if (s !== "" && c !== "" && f !== "") {
        FAjax('placa.php?dato=' + s + '|' + c + '|' + f, 'placa', '', 'get');
    }
}
////  Valida que se seleccione un valor en el campo clase de vehiculo en la liquidacion ////
function TipoClase() {
    if (document.getElementById('tipoclas').value.length < 1) {
        alert("Seleccione una clase de veh\xedculo");
        document.getElementById('tipoclas').className = 'campoRequerido';
        setTimeout("document.getElementById('tipoclas').focus()", 1);
        return false;
    }
    document.getElementById('tipoclas').className = '';
    TipoClaseMIVal();
}
////  Valida que se seleccione un valor en el campo clasificacion de vehiculo en la liquidacion ////
function TipoClasifica() {
    if (document.getElementById('tipoclasif').value.length < 1) {
        alert("Seleccione una clasificaci\xf3n de veh\xedculo");
        document.getElementById('tipoclasif').className = 'campoRequerido';
        setTimeout("document.getElementById('tipoclasif').focus()", 1);
        return false;
    }
    document.getElementById('tipoclasif').className = '';
    TipoClaseMIVal();
}
////  Valida que se seleccione un valor en el campo placa de vehiculo en la liquidacion ////
function TipoPlaca(v) {
    var a = v.value;
    var e = v.name;
    if (document.getElementById('tipoplaca').value.length < 1) {
        alert("Seleccione una placa de veh\xedculo");
        document.getElementById(e).className = 'campoRequerido';
        setTimeout("document.getElementById(" + e + ").focus()", 1);
        return false;
    }
    Nada('clasifplacas');
    FAjax('valplaca.php?idpl=' + a,'valplaca','','post');
}
//// Valida que se seleccione un valor en el campo placa de vehiculo en la liquidacion ////
function VerificaTipoTrasp(v) {
    if (v.value.length < 1) {
        alert("Seleccione el tipo de traspaso a realizar");
        v.className = 'campoRequerido';
        setTimeout("document.getElementById('tipotrasp').focus()", 1);
        return false;
    }
    v.className = '';
    document.getElementById('vtipotrasp').value = v.value;
    if (document.getElementById('tipoplaca') === null) {
        FAjax('digitaplaca.php', 'digitaplacas', '', 'post');
    } else {
        var c = document.getElementById('tipotram').value;
        var b = document.getElementById('numtram').value;
        document.getElementById('plus').style.display = 'block';
        FAjax('concept.php?dato=' + c + '|' + b, 'concept' + b, '', 'post');
    }
}
//// Valida que se seleccione un valor en el campo placa de vehiculo en la liquidacion ////
function VerificaPlaca(v) {
    if (document.getElementById('tipoplaca').value.length < 1) {
        alert("Digite una placa de veh\xedculo");
        document.getElementById('tipoplaca').className = 'campoRequerido';
        setTimeout("document.getElementById('tipoplaca').focus()", 1);
        return false;
    }
    document.getElementById('tipoplaca').className = '';
    FAjax('valplaca.php?dato=' + v, 'valplaca', '', 'post');
}
////  Recoge los datos enviados en el campo liquidacion de los tramites y los envia al archivo noma.php via java para hacer las tareas requeridas  ////
function BuscarPropiet() {
    var e = document.getElementById('Tciudadanos_tipo').value;
    var i = document.getElementById('tipodoc').value;
    var a = document.getElementById('identificacion').value.trim();
    if (e.length < 1) {
        alert("Seleccione el tipo de ciudadano");
        document.getElementById('Tciudadanos_tipo').className = 'campoRequerido';
        setTimeout("document.getElementById('Tciudadanos_tipo').focus()", 1);
        return false;
    }
    document.getElementById('Tciudadanos_tipo').className = '';
    if (i.length < 1) {
        alert("Seleccione un tipo de documento");
        document.getElementById('tipodoc').className = 'campoRequerido';
        setTimeout("document.getElementById('tipodoc').focus()", 1);
        return false;
    }
    document.getElementById('tipodoc').className = '';
    if (a.length < 1) {
        //alert("Digite un n\xfamero de documento");
        document.getElementById('identificacion').className = 'campoRequerido';
        setTimeout("document.getElementById('identificacion').focus()", 1);
        return false;
    }
    document.getElementById('identificacion').className = '';
    if (e === "1") {
        FAjax('noma.php?dato=' + a + '&tipodoc=' + i, 'nomapell', '', 'post');
    } else {
        FAjax('noma2.php?dato=' + a + '&tipodoc=' + i, 'nomapell', '', 'post');
    }
}
//// Equivaliente de devolver peticion ajax a archivo nada.php
function Nada(id) {
    var i = document.getElementById(id);
    if (i !== null) {
        i.innerHTML = "";
    }
}
//// Alias de ResetDatos
function VerTramites() {
    ResetDatos();
}

////  Vuelve el formulario a 0 por cambio de datos o error  ////
function ResetDatos() {
    document.getElementById('numtram').value = 0;
    var f = document.getElementById('ntramliq').value;
    var b = parseInt(f) + 1;
    for (var i = 0; i < b; i++) {
        Nada('tramiteconc' + i);
    }
    document.getElementById('ncsn2').checked = true;
    Notacredito2();
    Nada('indeter');
    Nada('valplaca');
    Nada('clasifplacas');
    Nada('digitaplacas');
    FAjax('tramite.php', 'tramite', '', 'post');
    FAjax('tramiteconcep.php?num=0', 'tramiteconc0', '', 'get');
}
////  Recoge los datos enviados en el campo liquidacion de los tramites y los envia al archivo nomat.php via java para hacer las tareas requeridas  ////
function BuscarPropiett() {
    var a = document.getElementById('identificaciont').value;
    var i = document.getElementById('tipodoct').value;
    if (i.length < 1) {
        alert("Seleccione un tipo de documento");
        document.getElementById('tipodoct').className = 'campoRequerido';
        setTimeout("document.getElementById('tipodoct').focus()", 1);
        return false;
    }
    if (a.length < 1) {
        alert("Digite un n\xfamero de documento");
        document.getElementById('identificaciont').className = 'campoRequerido';
        setTimeout("document.getElementById('identificaciont').focus()", 1);
        return false;
    }
    document.getElementById('tipodoct').className = '';
    document.getElementById('identificaciont').className = '';
    FAjax('nomat2.php?dato=' + a, 'nomapellt', '', 'post');
}
////  Recoge los datos enviados en el campo liquidacion de los tramites y los envia al archivo nomat.php via java para hacer las tareas requeridas  ////
function VerComparendos(){
	var a=document.getElementById('identificacion').value;
	if(document.getElementById('identificacion').value.length<1){
		alert("Digite un n\xfamero de documento");
		document.getElementById(e).className='campoRequerido';
		setTimeout("document.getElementById("+e+").focus()",1);
		return false;}
		document.getElementById('identificacion').className='';
	//alert('llama esta funcion'+a+'|'+b+'|'+c+'|'+d+'|'+e);
	FAjax('comparendos.php?dato='+a,'comparendos','','get');
	document.getElementById('ncsn').checked=false;
	document.getElementById('nnotacred').value='';
	document.getElementById('nnotacred').disabled='disabled';
	document.getElementById('valorrnc').value=formatCurrency(0);
	document.getElementById('valorpnc').value=formatCurrency(0);
	document.getElementById('valornc').value=formatCurrency(0);
	document.getElementById('valortotal').value=formatCurrency(0);
	var vvivat=document.getElementById('vivat').value;
	if(vvivat>0){document.getElementById('viva').value=formatCurrency(0);}
	document.getElementById('valortotalt').value=formatCurrency(0);
	}
//// Recibe el valor del tramite seleccionado y envia parametros para buscar y mostrar los conceptos del tramite ////
function ConceptTramite(v, b) {
    var a = v.value;
    var n = document.getElementById('ntramliq').value;        
    var c = parseInt(n) - 1;
    var d =  (b < c && a !== "") ? 'block': 'none';
    var tt = document.getElementById('idtramite').value;
    if (tt === "1") {
        if (b === 0) {
            if (document.getElementById('matriini').value === "1") {
                Nada('clasifplacas');
                document.getElementById('matriini').value = '';
            }
            if (document.getElementById('traspprop').value === "1") {
                Nada('indeter');
                document.getElementById('traspprop').value = '';
            }
            if (document.getElementById('radcuenta').value === "1") {
                Nada('valplaca');
                document.getElementById('radcuenta').value = '';
            }
            if (a === "1") {//si el tramite es matricula inicial
                Nada('digitaplacas');
                FAjax('clasifplaca.php', 'clasifplacas', '', 'post');
                document.getElementById('matriini').value = 1;
            } else if (a === "5") {//si el tramite es traspaso de propiedad
                Nada('digitaplacas');
                FAjax('indeter.php', 'indeter', '', 'post');
                document.getElementById('traspprop').value = 1;
            } else if (a === "8") {//si el tramite es traspaso de propiedad
                FAjax('digitaplaca.php', 'digitaplacas', '', 'post');
                document.getElementById('radcuenta').value = "1";
            } else if (document.getElementById('tipoplaca') === null) {
                FAjax('digitaplaca.php', 'digitaplacas', '', 'post');
            } else if (document.getElementById('tipoplaca').value.trim() !== "") {
                document.getElementById('plus').style.display = d;
                FAjax('concept.php?dato=' + a + '|' + b, 'concept' + b, '', 'post');
            }
        } else {//si el tramite es diferente a matricula inicial y traspaso de propiedad
            var t = '';
            for (i = 0; i <= b; i++) {//Busca entre todos los tramites existentes
                var ti = document.getElementById('idtramn' + i).value;
                if (ti === "5" || a === "5") {//si el tramite es traspaso de propiedad
                    t = '1';
                }
            }
            document.getElementById('traspprop').value = t;
            if (a === "5") {
                FAjax('indeter.php', 'indeter', '', 'post');
            } else {
                document.getElementById('plus').style.display = d;
                FAjax('concept.php?dato=' + a + '|' + b, 'concept' + b, '', 'post');
            }
        }
    } else if (tt === "2") {
        if (document.getElementById('rnc_cs').value === "1") {
            if (document.getElementById('servicio') === null) {
                FAjax('claseservicio.php', 'valplaca', '', 'post');
            } else if (b === '0') {
                document.getElementById('plus').style.display = 'none';
                ValidaClaseServicio();
            } else {
                document.getElementById('plus').style.display = d;
                FAjax('concept.php?dato=' + a + '|' + b, 'concept' + b, '', 'post');
            }
        } else {
            document.getElementById('plus').style.display = d;
            FAjax('concept.php?dato=' + a + '|' + b, 'concept' + b, '', 'post');
        }
    } else if (tt === "9") {
        document.getElementById('plus').style.display = d;
        FAjax('concept.php?dato=' + a + '|' + b, 'concept' + b, '', 'post');
    }
}

//// valida si el campo placa no es vacio  ////
function ValidaTramiteSel() {
    var b = document.getElementById('tipoplaca');
    if (b !== null) {
        if (b.value !== '' && b.readOnly === false) {
            FAjax('valplaca.php?dato=' + b.value, 'valplaca', '', 'post');
        }
    }
}
//// Recibe el valor del tramite seleccionado y envia parametros para buscar y mostrar los conceptos del tramite ////
function ValidaTramiteSel2() {
    if (document.getElementById('identificacion').value.length < 1) {
        alert("Digite un n\xfamero de documento");
        document.getElementById('identificacion').className = 'campoRequerido';
        setTimeout("document.getElementById('identificacion').focus()", 1);
        return false;
    }
    document.getElementById('identificacion').className = '';
    /*var b=document.getElementById('identificacion').value;
     if(b!=''){FAjax('valdoc.php?dato='+b,'valplaca','','post');}*/
}
////  cambiar un numero de placa seleccionada y valida el tipo de forma en que se cambiara  ////
function CambioPlaca() {
    var a = document.getElementById('numtram').value;
    var b = parseInt(a) + 1;
    var m = 0;
    for (i = 0; i < b; i++) {
        if (document.getElementById('idtramn' + i).value === "1") {
            m += 1;
        }
    }
    if (m < 1) {
        FAjax('digitaplaca.php', 'digitaplacas', '', 'post');
    } else {
        Nada('digitaplacas');
        FAjax('clasifplaca.php', 'clasifplacas', '', 'post');
    }
    document.getElementById('plus').style.display = 'none';
}
////  Agrega un nuevo espacio para un nuevo tramite  ////
function NewTramite(v) {
    var a = '1,8,';
    var n = parseInt(v) + 1;
    var vtt = false;
    for (i = 0; i < n; i++) {
         var idtram = document.getElementById('idtramn' + i).value;
        a += idtram + ',';
        if (idtram === "5") {
            vtt = true;
        }
    }
    document.getElementById('numtram').value = n;
    document.getElementById('menos' + v).style.display = 'none';
    document.getElementById('tramiteconc' + n).style.display = 'block';
    document.getElementById('tipotram').disabled = true;
    document.getElementById('plus').style.display = 'none';
    if (document.getElementById('editplaca') !== null) {
        document.getElementById('editplaca').style.display = 'none';
    }
    if (document.getElementById('servicio') !== null && document.getElementById('clase') !== null) {
        document.getElementById('servicio').disabled = true;
        document.getElementById('clase').disabled = true;
    }
    if (!vtt) {
        document.getElementById('vtipotrasp').value = '';
    }
    if (document.getElementById('tipotrasp') !== null){
        Nada('indeter');
    }
    FAjax('tramiteconcep.php?num=' + n, 'tramiteconc' + n, '', 'get');
    FAjax('tramite.php?num=' + n + '|' + a, 'tramite', '', 'get');
}
//// Quita el div o espacio del tramite que se desea quitar ////
function QuitarTramite(v) {
    var n = parseInt(v) - 1;
    var f = (n > 0) ? '1,' : '';
    var vtt = false;
    for (i = 0; i < n; i++) {
        var idtram = document.getElementById('idtramn' + i).value;
        f += idtram + ',';
        if (idtram === "5") {
            vtt = true;
        }
    }
    document.getElementById('numtram').value = n;
    document.getElementById('menos' + n).style.display = 'block';
    if (n === 0) {
        if (document.getElementById('editplaca') !== null) {
            document.getElementById('editplaca').style.display = 'inline';
        }
        if (document.getElementById('servicio') !== null && document.getElementById('clase') !== null) {
            document.getElementById('servicio').disabled = false;
            document.getElementById('clase').disabled = false;
        }
    }
    if (!vtt) {
        document.getElementById('vtipotrasp').value = '';
    }
    Nada('tramiteconc' + v);
    FAjax('tramite.php?num=' + n + '|' + f, 'tramite', '', 'get');
    FAjax('tramiteconcep.php?num=' + n, 'tramiteconc' + n, '', 'get');
    SumarValor();
}
//// Sumar el valor de los conceptos de un tramite agregado al total de la liquidacion ////
function SumarValor() {
    var iva = 0;
    var suma = 0;
    var a = parseInt(document.getElementById('numtram').value) + 1;
    for (i = 0; i < a; i++) {
        var e = document.getElementById('vconcept' + i);
        if (e !== null) {
            suma += parseInt(Extract(e.value));
        }
    }
    var notac = Extract(document.getElementById('valornc').value);
    var vvivat = document.getElementById('vivat').value;
    if (vvivat > 0) {
        iva = Math.round((suma * parseInt(vvivat)) / 100);
    }
    var sumat = suma - parseInt(notac) + iva;
    document.getElementById('valortotal').value = formatCurrency(suma);
    document.getElementById('valortotalt').value = formatCurrency(sumat);
    var vn = Extract(document.getElementById('valorrnc').value);
    if (vn !== "0") {
        SumarNC();
    }
}
//// Sumar Valor del concepto con campo texto ////
function SumarValorConcepto(v,p,s){
	var a=Extract(document.getElementById('vconcept'+p).value);
	var b=Extract(document.getElementById('valtemconceptoant'+p+s).value);
	var valor=parseInt(a)-parseInt(b);
	var newvalor=parseInt(valor)+parseInt(Extract(v));	
	document.getElementById('vconcept'+p).value=formatCurrency(newvalor);
	document.getElementById('valtemconceptoant'+p+s).value=Extract(v);
	SumarValor();
	}
//// Restar el valor de los conceptos de un tramite a eliminar del total de la liquidacion ////
function RestarValor(v){
	var a=0;var e=0;var resta=0;
	if(document.getElementById('vconcept'+v).value.length<1){var a=0;}
	else{var a=Extract(document.getElementById('vconcept'+v).value);}
	if(document.getElementById('valortotal').value.length<1){var e=0;}
	else{var e=Extract(document.getElementById('valortotal').value);}
	resta=parseInt(e)-parseInt(a);
	if(resta<0){resta=0;}
	var notac=Extract(document.getElementById('valornc').value);
	menosnc=parseInt(resta)-parseInt(notac);
	var vvivat=document.getElementById('vivat').value;
	if(vvivat>0){var iva=(parseInt(resta)*parseInt(vvivat))/100;}
	else{var iva=0;}
	var restat=parseInt(menosnc)+parseInt(iva);
	resta=Math.round(resta);
	menosnc=Math.round(menosnc);
	iva=Math.round(iva);
	restat=Math.round(restat);
	document.getElementById('valortotal').value=formatCurrency(resta);
	var nn=document.getElementById('nnotacred').value;
	var nd=document.getElementById('identificacion').value;
        document.getElementById('agregar').disabled=true;
	FAjax('valnc.php?datos='+nn+'|'+nd,'valornc','','post');
	document.getElementById('numtram').value=v;}
////  Valida que se seleccione un valor en el campo clasificacion de vehiculo en la liquidacion ////
function ValidarLiquida(r) {
    var camposl = document.getElementById('campreql').value;
    var camposc = document.getElementById('campreqc').value;
    var campost = document.getElementById('campreqt').value;
    var camposcc = document.getElementById('campreqcc').value;
    var campos = camposl + camposc + campost + camposcc;
    var nombrecampo = campos.split(",");
    for (var i = 0; i < nombrecampo.length; i++) {
        if (nombrecampo[i] !== '') {
           var cv = document.getElementById(nombrecampo[i]);
            if (cv.value.length < 1 && cv.disabled === false) {
                alert("Este campo no debe ser vacio");
                document.getElementById(nombrecampo[i]).className = 'campoRequerido';
                setTimeout("document.getElementById('" + nombrecampo[i] + "').focus()", 1);
                return false;
            }
            document.getElementById(nombrecampo[i]).className = '';
        }
    }
    var t = document.getElementById('numtram').value;
    var st = parseInt(t) + 1;
    for (i = 0; i < st; i++) {
        if (document.getElementById('idtramn' + i).value < 1) {
            alert("Seleccione un tramite");
            document.getElementById('tipotram').className = 'campoRequerido';
            setTimeout("document.getElementById('tipotram').focus()", 1);
            return false;
        }
    }
    if (document.getElementById('idtramite').value === "1") {
        if (document.getElementById('tipoplaca').value.length < 1) {
            alert("Seleccione una Placa de veh\xedculo");
            document.getElementById('tipoplaca').className = 'campoRequerido';
            setTimeout("document.getElementById('tipoplaca').focus()", 1);
            return false;
        }
        document.getElementById('tipoplaca').className = '';
        if (document.getElementById('vservicio').value.length < 1) {
            alert("El tipo de servicio de la especie venal es vacia, seleccione la informacion requerida.");
            document.getElementById('servicio').className = 'campoRequerido';
            setTimeout("document.getElementById('servicio').focus()", 1);
            return false;
        }
        if (document.getElementById('vclase').value.length < 1) {
            alert("La clase de la especie venal placa es vacia, seleccione la informacion requerida.");
            document.getElementById('clase').className = 'campoRequerido';
            setTimeout("document.getElementById('clase').focus()", 1);
            return false;
        }
    }
    if ((document.getElementById('ncsn').checked) && (document.getElementById('nnotacred').value.length < 1)) {
        alert("Digite un n\xfamero de nota credito");
        setTimeout("document.getElementById('nnotacred').focus()", 1);
        return false;
    }
    if (confirm('¿Est\xe1 seguro que desea generar la liquidaci\xf3n?')) {
        document.form.action = 'liquidacion.php?tram=' + r;
        document.form.submit();
    }
}
////  Valida los campos requeridos en la liquidacion de comparendos ////
function ValidarLiquidaComp(){
	var camposl=document.getElementById('campreql').value;
	var camposc=document.getElementById('campreqc').value;
	var campreqcc=document.getElementById('campreqcc').value;
	var campos=camposl+camposc+campreqcc;
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	var valort=Extract(document.getElementById('valortotal').value);
	if(valort<1){
			alert("Seleccione al menos un comparendo a liquidar");
			document.getElementById('compar1').className='campoRequerido';
			setTimeout("document.getElementById('compar1').focus()",1);
			return false;
		}
	if((document.getElementById('ncsn').checked)&&(document.getElementById('nnotacred').value.length<1)){
			alert("Digite un n\xfamero de nota credito");
			setTimeout("document.getElementById('nnotacred').focus()",1);
			return false;
		}
	if(confirm('¿Est\xe1 seguro que desea generar la liquidaci\xf3n?')){
		if(document.getElementById('curso').value==1){document.form.action='liquidacion.php?tram=4&curso=1';}
		else{document.form.action='liquidacion.php?tram=4';}		
		document.form.submit();
		}
	}
////  Valida los campos requeridos en la liquidacion de Acuerdos de pago ////
function ValidarAcuerdoPago(){
	var camposl=document.getElementById('campreql').value;
	var camposc=document.getElementById('campreqc').value;
	var campost=document.getElementById('campreqt').value;
	var campos=camposl+camposc+campost;
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	var valort=Extract(document.getElementById('valortotal').value);
	if(valort<1){
			alert("Seleccione al menos un acuerdo de pago a liquidar");
			document.getElementById('valorac0').className='campoRequerido';
			setTimeout("document.getElementById('valorac0').focus()",1);
			return false;
		}
	if((document.getElementById('ncsn').checked)&&(document.getElementById('nnotacred').value.length<1)){
			alert("Digite un n\xfamero de nota credito");
			setTimeout("document.getElementById('nnotacred').focus()",1);
			return false;
		}
	if(confirm('¿Est\xe1 seguro que desea generar la liquidaci\xf3n?')) {
		document.form.action='liquidacion.php?tram=6';
		document.form.submit();
		}
	}
////  Valida los campos requeridos en la liquidacion de Derechos de Transito ////
function ValidarDerechoTrans(){
	var camposl=document.getElementById('campreql').value;
	var camposc=document.getElementById('campreqc').value;
	var campost=document.getElementById('campreqt').value;
	var campos=camposl+camposc+campost;
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	var valort=Extract(document.getElementById('valortotal').value);
	if(valort<1){
		alert("Seleccione al menos un derecho de transito a liquidar");
		document.getElementById('derecho00').className='campoRequerido';
		setTimeout("document.getElementById('derecho00').focus()",1);
		return false;
		}
	if((document.getElementById('ncsn').checked)&&(document.getElementById('nnotacred').value.length<1)){
		alert("Digite un n\xfamero de nota credito");
		setTimeout("document.getElementById('nnotacred').focus()",1);
		return false;
		}
	if(confirm('¿Est\xe1 seguro que desea generar la liquidaci\xf3n?')) {
		document.form.action='liquidacion.php?tram=7';
		document.form.submit();
		}
	}
//// Sumar o restar los valores de los comparendos seleccionados ////
function SumarComparendos(t,v,w){
	var e=t.name;var i=0;var suma=0;var notac=0;var iva=0;var sumat=0;var menosnc=0;j=0;
	var vintmoradt=Extract(document.getElementById('intmoracomp'+w).value);
	var vintmora=Extract(document.getElementById('intmora').value);
	i=Extract(document.getElementById('valortotal').value);
	//j=Extract(document.getElementById('poramnist'+w+'1').value);
	var poramnis = document.getElementsByClassName('poramnist'+w)[0];
if(document.getElementById(e).name == ('levantamiento'+w) && !!document.getElementById('divtitulos'+w)){
		myarray[w]=new Array();
		document.getElementById('divtitulos'+w).innerHTML=" ";
	}
	if(document.getElementById(e).checked){
		suma=parseInt(v)+parseInt(i);
		var sumaintmora=parseInt(vintmora)+parseInt(vintmoradt);		
		if (document.getElementById(e).name == ('compar'+w) && poramnis !== undefined ){
			poramnis.readOnly = true;
		}
///  nuevo  jimmy 31-08-2021  ///
		if(!!document.getElementById('checkembargo'+w) && document.getElementById(e).name == ('levantamiento'+w)){
			document.getElementById('checkembargo'+w).disabled=false;
		}
///  nuevo  jimmy 17-11-2022  ///
		if(document.getElementById(e).name == ('levantamiento'+w)){
			suma= suma - document.getElementById(e).value;
			document.getElementById(e).value = parseInt(v);
			document.getElementById('valMC'+w).setAttribute("readonly","readonly");
		}
	}else{
///  nuevo  jimmy 17-11-2022  ///
		if(document.getElementById(e).name == ('levantamiento'+w)){
			suma=parseInt(i) - document.getElementById(e).value;
			document.getElementById(e).value = 0;
			document.getElementById('valMC'+w).removeAttribute("readonly");  
		} else {
			suma=parseInt(i)-parseInt(v);
		}
		var sumaintmora=parseInt(vintmora)-parseInt(vintmoradt);
		if (document.getElementById(e).name == ('compar'+w) && poramnis !== undefined ){
			poramnis.readOnly = false;
		}
		if(!!document.getElementById('checkembargo'+w) && document.getElementById(e).name == ('levantamiento'+w)){
			document.getElementById('checkembargo'+w).checked=false
			document.getElementById('checkembargo'+w).disabled=true;
			
		}
	}
	if(suma<0){
		sumat=0;suma=0;
	}
	if(suma<=0){
	///  nuevo  jimmy 31-08-2021  ///
		if(!!document.getElementById('checkembargo'+w) && document.getElementById(e).name == ('levantamiento'+w)){
			document.getElementById('checkembargo'+w).checked=false
			document.getElementById('checkembargo'+w).disabled=true;
			
		}
		if(!!document.getElementById('divtitulos'+w)){
			document.getElementById('divtitulos'+w).innerHTML=" ";
		}
	}	
	notac=Extract(document.getElementById('valornc').value);
	menosnc=parseInt(suma)-parseInt(notac);
	var vvivat=document.getElementById('vivat').value;
	if(vvivat>0){var iva=(parseInt(suma)*parseInt(vvivat))/100;}
	else{var iva=0;}
	sumat=parseInt(menosnc)+parseInt(iva);
	suma=Math.round(suma);
	menosnc=Math.round(menosnc);
	iva=Math.round(iva);
	sumat=Math.round(sumat);
	document.getElementById('intmora').value=sumaintmora;
	document.getElementById('valortotal').value=formatCurrency(suma);
	var vvivat=document.getElementById('vivat').value;
	if(vvivat>0){document.getElementById('viva').value=formatCurrency(iva);}
	document.getElementById('valortotalt').value=formatCurrency(sumat);
	}
//// Sumar Valor del concepto con campo texto en comparendos ////
function SumarValorConceptoC(v,p,ant){
	var a=Extract(document.getElementById('valorcompconc'+p).value);
	var b=Extract(document.getElementById('valtemconceptoant'+p+ant).value);
	var valor=parseInt(a)-parseInt(b);
	var newvalor=parseInt(valor)+parseInt(Extract(v));
	document.getElementById('valorcompconc'+p).value=formatCurrency(newvalor);
	document.getElementById('valtemconceptoant'+p+ant).value=formatCurrency(Extract(v));
	document.getElementById('valtemconcepto'+p+ant).value=formatCurrency(Extract(v));
	var nconcepca=document.getElementById('numconceptosa'+p).value;
	var vconcepca=0;
	if(nconcepca>0){
		for(var k=0; k<nconcepca; k++){
			vconcepca=parseInt(vconcepca)+parseInt(Extract(document.getElementById('poramnist'+p+k).value))
			}
		}
	else{vconcepca=0;}
	var valorcompamn=parseInt(newvalor)-parseInt(vconcepca);
	if(valorcompamn<0){valorcompamn=0;}
	else{valorcompamn=valorcompamn;}
	document.getElementById('valorcompamn'+p).value=formatCurrency(valorcompamn);	
	FAjax('calcularvalor.php?num='+p,'calcularvalor'+p,'','post');	
	}
	
	
function checkTotalValorMC(t){  
	var e   = t.name;
	var est = document.getElementById(e).checked;
	
	//sessionStorage.setItem('estadoCheckMC', est);
	//alert(sessionStorage.getItem('estadoCheckMC'));
	//FAjax('estadoCheckMC.php?est='+est, get);		
	//alert(idComp_medCautelar);
	  $.ajax({
			  type: "POST", 
			  url: "estadoCheckMC.php",  
			  data: "est="+est, 
			  success:function(msg){ 	
				  msg = msg.trim();				
			   } 
	  });
}


//// Sumar Valores de los conceptos Amnistias en comparendos ////
function SumarValorConceptoCA(v,p,ant){
    var vamnin = parseInt(Extract(v));
    var poramnant = document.getElementById('poramnistant'+p+ant);
    var vamninant = parseInt(Extract(poramnant.value));
    var newvalor = vamninant-vamnin;	
    var vcompamn = document.getElementById('valorcompamn'+p);
    vcompamn.value = formatCurrency(parseInt(Extract(vcompamn.value))+newvalor);
    var totaldtt = document.getElementById('totaldtt'+p);
    totaldtt.value = formatCurrency(parseInt(Extract(totaldtt.value))+newvalor);
    document.getElementById('poramnistant'+p+ant).value=vamnin;
    document.getElementById('poramnist'+p+ant).value=formatCurrency(vamnin);
    var compar = document.getElementById('compar'+p);
    compar.value = parseInt(compar.value)+newvalor;
	
    FAjax('calcularvalor.php?num='+p,'calcularvalor'+p,'','post');
}
        
        
//// Sumar Valores de los conceptos amnistias Interes de Mora en comparendos ////
function SumarValorConceptoCAIM(v,p,ant){
	var a=Extract(document.getElementById('vmor'+p).value);
	var b=Extract(document.getElementById('voperaant'+p+ant).value);	
	var valor=parseInt(a)-parseInt(b);
	var newvalor=parseInt(valor)+parseInt(Extract(v));	
	document.getElementById('vmor'+p).value=formatCurrency(newvalor);
	document.getElementById('voperaant'+p+ant).value=formatCurrency(Extract(v));
	document.getElementById('vopera'+p+ant).value=formatCurrency(Extract(v));
	FAjax('calcularvalor.php?num='+p,'calcularvalor'+p,'','post');
	}
//// Sumar Valores de los conceptos amnistias Honorarios en comparendos ////
function SumarValorConceptoCAH(v,p,ant){
	var a=Extract(document.getElementById('totalcomh'+p).value);
	var b=Extract(document.getElementById('voperahant'+p+ant).value);	
	var valor=parseInt(a)-parseInt(b);
	var newvalor=parseInt(valor)+parseInt(Extract(v));	
	document.getElementById('totalcomh'+p).value=formatCurrency(newvalor);
	document.getElementById('voperahant'+p+ant).value=formatCurrency(Extract(v));
	document.getElementById('voperah'+p+ant).value=formatCurrency(Extract(v));
	FAjax('calcularvalor.php?num='+p,'calcularvalor'+p,'','post');
	}
//// Sumar Valor del concepto con campo texto en Acuerdos de pago ////
function SumarValorConceptoAP(v,p,ant){
	var a=Extract(document.getElementById('valorcompconc'+p).value);
	var b=Extract(document.getElementById('valtemconceptoant'+p+ant).value);
	var valor=parseInt(a)-parseInt(b);
	var newvalor=parseInt(valor)+parseInt(Extract(v));	
	document.getElementById('valorcompconc'+p).value=formatCurrency(newvalor);
	document.getElementById('valtemconceptoant'+p+ant).value=formatCurrency(Extract(v));
	document.getElementById('valtemconcepto'+p+ant).value=formatCurrency(Extract(v));	
	var nconcepca=document.getElementById('numconceptosa'+p).value;
	var vconcepca=0;
	if(nconcepca>0){
		for(var k=0; k<nconcepca; k++){
			vconcepca=parseInt(vconcepca)+parseInt(Extract(document.getElementById('poramnist'+p+k).value))
			}
		}
	else{vconcepca=0;}
	var valorcompamn=parseInt(newvalor)-parseInt(vconcepca);
	if(valorcompamn<0){valorcompamn=0;}
	else{valorcompamn=valorcompamn;}
	document.getElementById('valorcompamn'+p).value=formatCurrency(valorcompamn);	
	FAjax('calcularvalorap.php?num='+p,'calcularvalor'+p,'','post');	
	}
//// Sumar Valores de los conceptos Amnistias en Acuerdos de pago ////
function SumarValorConceptoAPA(v,p,ant){
	var a=Extract(document.getElementById('valorcompamn'+p).value);
	var b=Extract(document.getElementById('poramnistant'+p+ant).value);	
	var valor=parseInt(a)+parseInt(b);
	var newvalor=parseInt(valor)-parseInt(Extract(v));	
	document.getElementById('valorcompamn'+p).value=formatCurrency(newvalor);
	document.getElementById('poramnistant'+p+ant).value=formatCurrency(Extract(v));
	document.getElementById('poramnist'+p+ant).value=formatCurrency(Extract(v));
	FAjax('calcularvalorap.php?num='+p,'calcularvalor'+p,'','post');
	}
//// Sumar Valores de los conceptos amnistias Interes de Mora en Acuerdos de pago ////
function SumarValorConceptoAPAIM(v,p,ant){
	var a=Extract(document.getElementById('vmor'+p).value);
	var b=Extract(document.getElementById('voperaant'+p+ant).value);	
	var valor=parseInt(a)-parseInt(b);
	var newvalor=parseInt(valor)+parseInt(Extract(v));	
	document.getElementById('vmor'+p).value=formatCurrency(newvalor);
	document.getElementById('voperaant'+p+ant).value=formatCurrency(Extract(v));
	document.getElementById('vopera'+p+ant).value=formatCurrency(Extract(v));
	FAjax('calcularvalorap.php?num='+p,'calcularvalor'+p,'','post');
	}
//// Sumar Valores de los conceptos amnistias Honorarios en Acuerdos de pago ////
function SumarValorConceptoAPAH(v,p,ant){
	var a=Extract(document.getElementById('totalcomh'+p).value);
	var b=Extract(document.getElementById('voperahant'+p+ant).value);	
	var valor=parseInt(a)-parseInt(b);
	var newvalor=parseInt(valor)+parseInt(Extract(v));
	document.getElementById('totalcomh'+p).value=formatCurrency(newvalor);
	document.getElementById('voperahant'+p+ant).value=formatCurrency(Extract(v));
	document.getElementById('voperah'+p+ant).value=formatCurrency(Extract(v));
	FAjax('calcularvalorap.php?num='+p,'calcularvalor'+p,'','post');
	}
//// Sumar Valores de los conceptos amnistias Interes de Mora en derecho de transito          ////
//  ojo : agragada condiciones para calcular que los descuentos no superen el valor de la mora  //
/// Se crearon inputs  descuentosmora  y  valormora   que deben actualizarse en cada onChange  //
function SumarValorConceptoDTAIM(v,w,z){
	v = parseInt(Extract(v));
	if(document.getElementById('nconceptoi'+w+z)==null || document.getElementById('descuentosmora'+w)==null || document.getElementById('nconceptoi'+w+z).value.toUpperCase().indexOf("AUTORIZADO")==-1){
		var maxv = document.getElementById('naintmora'+w).value;
		if (maxv < v){v = maxv;}else if(v < 0){v = 0;}
		var a=Extract(document.getElementById('derecho'+w).value);
		var c=Extract(document.getElementById('voperaant'+w+z).value);	
		var valor=parseInt(a)+parseInt(c);
		var newvalor=parseInt(valor)-parseInt(v);	
		document.getElementById('derecho'+w).value=newvalor;
		document.getElementById('derechov'+w).value=formatCurrency(newvalor);
		document.getElementById('voperaant'+w+z).value=v;
		document.getElementById('voperad'+w+z).value=formatCurrency(v);
	} else {
		if(document.getElementById('nconceptoi'+w+z)!=null && document.getElementById('descuentosmora'+w)!=null && document.getElementById('nconceptoi'+w+z).value.toUpperCase().indexOf("AUTORIZADO")>=0){
			var maxv = document.getElementById('naintmora'+w).value;
			var m=Extract(document.getElementById('descuentosmora'+w).value);
			var c=Extract(document.getElementById('voperaant'+w+z).value); 
			var nuevodesc=-1*(parseInt(m)-parseInt(v)+parseInt(c));
			var vmora=parseInt(Extract(document.getElementById('valormora'+w).value));
			if (nuevodesc < 0){
				v = c;
				alert("los descuentos deben ser mayores de cero!");
			} else { 
			  if(nuevodesc > vmora){
				  v=c;
				  alert("Los descuentos no deben superar el valor de la mora!");
			  } else {
				  if(v < 0){
					  v = 0;
					  alert("El valor del descuento en cero es invalido!");
					}
			  }
			}
			
			var a=Extract(document.getElementById('derecho'+w).value);
			var valor=parseInt(a)+parseInt(c);
			var newvalor=parseInt(valor)-parseInt(v);
			document.getElementById('descuentosmora'+w).value=parseInt(m)+parseInt(c)-parseInt(v);	
			document.getElementById('derecho'+w).value=newvalor;
			document.getElementById('derechov'+w).value=formatCurrency(newvalor);
			document.getElementById('voperaant'+w+z).value=v;
			document.getElementById('voperad'+w+z).value=formatCurrency(v);
			
		}		
	}
		
}
//// copia los datos del ciudadano al tramitador o los borra ////
function CopiarDatosT() {
    if (document.getElementById('tipodoc').value.length < 1) {
        alert("Seleccione un tipo de documento para el ciudadano");
        document.getElementById('tipodoc').className = 'campoRequerido';
        setTimeout("document.getElementById('tipodoc').focus()", 1);
        return false;
    }
    if (document.getElementById('identificacion').value.length < 1) {
        alert("Digite un n\xfamero de documento para el ciudadano");
        document.getElementById('identificacion').className = 'campoRequerido';
        setTimeout("document.getElementById('identificacion').focus()", 1);
        return false;
    }
    if (document.getElementById('Tciudadanos_nombres').value.length < 1) {
        alert("Digite los nombres del ciudadano");
        document.getElementById('Tciudadanos_nombres').className = 'campoRequerido';
        setTimeout("document.getElementById('Tciudadanos_nombres').focus()", 1);
        return false;
    }
    if (document.getElementById('Tciudadanos_apellidos').value.length < 1) {
        alert("Digite los apellidos del ciudadano");
        document.getElementById('Tciudadanos_apellidos').className = 'campoRequerido';
        setTimeout("document.getElementById('Tciudadanos_apellidos').focus()", 1);
        return false;
    }
    if (document.getElementById('copytram_no').checked) {
        document.getElementById('tipodoct').value = document.getElementById('tipodoc').value;
        document.getElementById('identificaciont').value = document.getElementById('identificacion').value;
        document.getElementById('Tterceros_nombre').value = document.getElementById('Tciudadanos_nombres').value;
        document.getElementById('Tterceros_apellido').value = document.getElementById('Tciudadanos_apellidos').value;
        document.getElementById('tipodoct').disabled = true;
        document.getElementById('identificaciont').disabled = true;
        FAjax('nomat.php', 'nomapellt', '', 'post');
    } else if (document.getElementById('copytram_si').checked) {
        LimpiarDatosT();
        document.getElementById('tipodoct').disabled = false;
        document.getElementById('identificaciont').disabled = false;
        FAjax('nomat2.php', 'nomapellt', '', 'post');
    }
}    
//// Vaciar la informacion de los tramitadores.
function LimpiarDatosT() {
    document.getElementById('tipodoct').value = '';
    document.getElementById('identificaciont').value = '';
    document.getElementById('Tterceros_nombre').value = '';
    document.getElementById('Tterceros_apellido').value = '';
}
//// redireccionar a pagina enviada ////
function redirec(redir){ 
	location.href=redir;
	} 
/// Abrir ventana tipo modal ///
function modalWin(pag, w, h) {
    var child = undefined;
    if (window.showModalDialog) {
        child = window.showModalDialog(pag, "", "dialogWidth:" + w + "px;dialogHeight:" + h + "px");
    } else {
        child = window.open(pag, '', 'height=' + h + ',width=' + w + ',toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no ,modal=yes');
    }
    return child;
}
//// Habilita el campo nota credito para digitar el numero de nota credito ////
function Notacredito() {
    if (document.getElementById('ncsn').checked) {
        var tt = document.getElementById('idtramite').value;
        var ok = true;
        if ((tt === "1") || (tt === "2")) {
            if (document.getElementById('identificaciont').value.length < 1) {
                alert("Digite un n\xfamero de documento para el tramitador\no copie los datos del ciudadano");
                setTimeout("document.getElementById('identificaciont').focus()", 1);
                ok = false;
            }
        } else {
            if (document.getElementById('identificacion').value.length < 1) {
                alert("Digite un n\xfamero de documento del ciudadano");
                setTimeout("document.getElementById('identificacion').focus()", 1);
                ok = false;
            }
        }
        if (ok) {
            document.getElementById('nnotacred').disabled = '';
            document.getElementById('ncsn2').checked = false;
        } else {
            Notacredito2();
            return false;
        }
    }
}
//// Deshabilita el campo nota credito y sus modificaciones ////
function Notacredito2() {
    if (document.getElementById('ncsn2').checked) {
        document.getElementById('ncsn').checked = false;
        document.getElementById('nnotacred').value = '';
        document.getElementById('nnotacred').disabled = 'disabled';
        document.getElementById('valorrnc').value = 0;
        document.getElementById('valorpnc').value = 0;
        document.getElementById('valornc').value = 0;
        SumarNC();
    }
}
//// Desactiva el proceso de nota credito.
function CancelaNC() {
    if (document.getElementById('ncsn2')) {
        document.getElementById('ncsn2').checked = true;
        Notacredito2();
    }
}
//// Suma valores segun valor total de nota credito 
function SumarNC() {
    var vn = Extract(document.getElementById('valorrnc').value);
    var vt = Extract(document.getElementById('valortotal').value);
    var suma = parseInt(vt) - parseInt(vn);
    if (suma < 0) {
        var pnc = parseInt(suma) * -1;
        var van = vt;
        suma = 0;
    } else {
        var pnc = 0;
        var van = vn;
    }
    var vvivat = document.getElementById('vivat').value;
    if (vvivat > 0) {
        var iva = Math.round((parseInt(vt) * parseInt(vvivat)) / 100);
    } else {
        var iva = 0;
    }
    var sumat = parseInt(suma) + parseInt(iva);
    document.getElementById('valorpnc').value = formatCurrency(pnc);
    document.getElementById('valorrnc').value = formatCurrency(vn);
    document.getElementById('valornc').value = formatCurrency(van);
    if (vvivat > 0) {
        document.getElementById('viva').value = formatCurrency(iva);
    }
    document.getElementById('valortotalt').value = formatCurrency(sumat);
    document.getElementById('agregar').disabled = false;
}

//// Buscar nota credito por numero de nota y numero de documento ////
function BuscarNC() {
    var nn = document.getElementById('nnotacred').value;
    var tt = document.getElementById('idtramite').value;
    if ((tt === "1") || (tt === "2") ||(tt === "7") ) {
        var nd = document.getElementById('identificaciont').value;
    } else {
        var nd = document.getElementById('identificacion').value;
    }
    document.getElementById('agregar').disabled = true;
    FAjax('valnc.php?datos=' + nn + '|' + nd, 'valornc', '', 'post');
}
////  Ver derechos de transito  ////
function VerDerTrans(){
	var a=document.getElementById('identificacion').value;
	if(document.getElementById('identificacion').value.length<1){
			alert("Digite un n\xfamero de documento");
			document.getElementById('identificacion').className='campoRequerido';
			setTimeout("document.getElementById('identificacion').focus()",1);
			return false;
		}
	document.getElementById('identificacion').className='';
	//alert('llama esta funcion'+a+'|'+b+'|'+c+'|'+d+'|'+e);
	FAjax('derechotransito.php?dato='+a,'dertrans','','get');
	document.getElementById('ncsn').checked=false;
	document.getElementById('ncsn2').checked='checked';
	document.getElementById('nnotacred').value='';
	document.getElementById('nnotacred').disabled='disabled';
	document.getElementById('valorrnc').value=formatCurrency(0);
	document.getElementById('valorpnc').value=formatCurrency(0);
	document.getElementById('valornc').value=formatCurrency(0);
	document.getElementById('valortotal').value=formatCurrency(0);
	var vvivat=document.getElementById('vivat').value;
	if(vvivat>0){
		document.getElementById('viva').value=formatCurrency(0);}
	document.getElementById('valortotalt').value=formatCurrency(0);}    
//// Deshabilitar checkbox de derecho de transito a partir del deseleccionado ////
function DesChecV(j, k){ 
	var m=document.getElementById('tdertr'+j).value;
	for(t=(k+1);t<m;t++){
		check = document.getElementById('derecho'+j+t);
		if (check.checked){
			check.checked = false;
			SumarDerTrans('derecho'+j+t, check.value, j, t, 1);
		}
		check.disabled = 'disabled';
	}
}    
//// Habilitar checkbox de derecho de transito siguente al seleccionado ////
function HabChecV(j, k){
	var m=k+1;
	if (document.getElementById('derecho'+j+m)){	
		document.getElementById('derecho'+j+m).disabled='';
	}
} 
//// Desactivar activar campos editables en derechos de transito
function ModificableBloqDT(w, z, read) {
    namora = document.getElementById('namora' + w + z).value;
    for (j = 0; j < namora; j++) {
        var ant = document.getElementById('voperaant' + w + z + j);
        if (ant != null) {
            document.getElementById('voperad' + w + z + j).readOnly = read;
        }
    }
}
//Bloqua los demas derechos de transito menos el enviado.
function LimitDerTrans(act, sub, block) {
    if (sub == 0) {
        var f = document.getElementById('tplac').value;
        for (j = 0; j < f; j++) {
            if (act != j && block) {
                DesChecV(j, -1);
            } else if (act != j && !block) {
                HabChecV(j, -1);
            }
        }
        var placa = document.getElementById('idplaca'+act).value;
        document.getElementById('idtipoplaca').value = placa;
    }
}
//// Sumar o restar los valores de los derechos de transito seleccionados ////
function SumarDerTrans(e, v, w, z, c) {
    var i = 0; var suma = 0; var notac = 0; var iva = 0; var sumat = 0; var menosnc = 0;
    if (c == 1) {
        var f = document.getElementById('tplac').value;
        for (j = 0; j < f; j++) {
            var d = document.getElementById('tdertr' + j).value;
            for (k = 0; k < d; k++) {
                if (document.getElementById('derecho' + j + k).checked) {
                    HabChecV(j, k);
                } else {
                    DesChecV(j, k);
                }
            }
        }
    }
    CancelaNC();
    i = Extract(document.getElementById('valortotal').value);
    if (document.getElementById(e).checked) {
        suma = parseInt(v) + parseInt(i);
        ModificableBloqDT(w, z, true);
        LimitDerTrans(w, z, true);
    } else {
        suma = parseInt(i) - parseInt(v);
        ModificableBloqDT(w, z, false);
        LimitDerTrans(w, z, false);
    }
    if (suma < 0) {
        sumat = 0;
        suma = 0;
    }
    notac = Extract(document.getElementById('valornc').value);
    menosnc = parseInt(suma) - parseInt(notac);
    var vvivat = document.getElementById('vivat').value;
    if (vvivat > 0) {
        var iva = (parseInt(suma) * parseInt(vvivat)) / 100;
    } else {
        var iva = 0;
    }
    sumat = parseInt(menosnc) + parseInt(iva);
    suma = Math.round(suma);
    menosnc = Math.round(menosnc);
    iva = Math.round(iva);
    sumat = Math.round(sumat);
    var vintmoradt = document.getElementById('intmoradt' + w + z).value;
    var vintmora = document.getElementById('intmora').value;
    var sumaintmora = 0;
    if (document.getElementById(e).checked) {
        sumaintmora = parseInt(vintmora) + parseInt(vintmoradt);
    } else {
        sumaintmora = parseInt(vintmora) - parseInt(vintmoradt);
    }
    document.getElementById('intmora').value = sumaintmora;
    document.getElementById('valortotal').value = formatCurrency(suma);
    var vvivat = document.getElementById('vivat').value;
    if (vvivat > 0) {
        document.getElementById('viva').value = formatCurrency(iva);
    }
    document.getElementById('valortotalt').value = formatCurrency(sumat);
}
////  Ver acuerdos de pago  ////
function VerAcuerdosPago(){
	var a=document.getElementById('identificacion').value;
	if(document.getElementById('identificacion').value.length<1){
		alert("Digite un n\xfamero de documento");
		document.getElementById(e).className='campoRequerido';
		setTimeout("document.getElementById("+e+").focus()",1);
		return false;
		}
	document.getElementById('identificacion').className='';
	FAjax('acuerdospago.php?dato='+a,'acuerdopago','','get');
	document.getElementById('ncsn').checked=false;
	document.getElementById('nnotacred').value='';
	document.getElementById('nnotacred').disabled='disabled';
	document.getElementById('valorrnc').value=formatCurrency(0);
	document.getElementById('valorpnc').value=formatCurrency(0);
	document.getElementById('valornc').value=formatCurrency(0);
	document.getElementById('valortotal').value=formatCurrency(0);
	var vvivat=document.getElementById('vivat').value;
	if(vvivat>0){
		document.getElementById('viva').value=formatCurrency(0);}
	document.getElementById('valortotalt').value=formatCurrency(0);}
//// Sumar o restar los valores de los acuerdo de pago seleccionados ////
function SumarAcuerdosPago(d,w){
	if(document.getElementById('decreto678').checked){
		if(document.getElementById('valorac'+w).checked === false){
			document.getElementById('valorac'+w).checked = true;
			return false;
		}
	}
		var e=0;var i=0;var suma=0;var notac=0;var iva=0;var sumat=0;var menosnc=0;	
		var f=document.getElementById('nap').value;
		var z=parseInt(w)+1;
		var v=Extract(d);
		if(document.getElementById('valorac'+w).checked){
			if(z<f){
			document.getElementById('valorac'+z).disabled=false;}
			i=Extract(document.getElementById('valortotal').value);
			suma=parseInt(Extract(v))+parseInt(i);
			}
		else{
			for(j=z;j<f;j++){
				if(document.getElementById('valorac'+j).checked){
					e=parseInt(e)+parseInt(document.getElementById('valorac'+j).value);}
				}
			i=Extract(document.getElementById('valortotal').value);
			suma=parseInt(i)-parseInt(e)-parseInt(v);
			for(j=z;j<f;j++){
				document.getElementById('valorac'+j).checked=false;
				document.getElementById('valorac'+j).disabled='disabled';
				}
			}
		if(suma<0){sumat=0;suma=0;}
		if((document.getElementById('ncsn').checked)&&(document.getElementById('nnotacred').value.length>0)){
			var nn=document.getElementById('nnotacred').value;
			var nd=document.getElementById('identificacion').value;
	                document.getElementById('agregar').disabled=true;
			FAjax('valnc.php?datos='+nn+'|'+nd,'valornc','','post');
			}
		notac=Extract(document.getElementById('valornc').value);
		menosnc=parseInt(suma)-parseInt(notac);
		var vvivat=document.getElementById('vivat').value;
		if(vvivat>0){
			var iva=(parseInt(suma)*parseInt(vvivat))/100;}
		else{
			var iva=0;}
		sumat=parseInt(menosnc)+parseInt(iva);
		suma=Math.round(suma);
		menosnc=Math.round(menosnc);
		iva=Math.round(iva);
		sumat=Math.round(sumat);
		var vintmoradt=document.getElementById('intmoraap'+w).value;
		var vintmora=document.getElementById('intmora').value;
		var sumaintmora=parseInt(vintmora)+parseInt(vintmoradt);
		document.getElementById('intmora').value=sumaintmora;
		document.getElementById('valortotal').value=formatCurrency(suma);
		var vvivat=document.getElementById('vivat').value;
		if(vvivat>0){
			document.getElementById('viva').value=formatCurrency(iva);}
	document.getElementById('valortotalt').value=formatCurrency(sumat);}
//// Muestra u Oculta un div o elemento especifico ////
function MostrarOcultar(v) {
    obj = document.getElementById(v);
    if (obj !== null) {
        obj.style.display = (obj.style.display === 'none') ? 'block' : 'none';
    }
}
/////funcion de actualizacion de datos de conceptos de cuotas de AP para decreto 678
function validar678(){
	CuotasAcuerdo(document.getElementById('acuerdo').value);
}

//// Recibe el valor del Acuerdo de pago seleccionado y envia parametros para buscar y mostrar las cuotas del acuerdo pendientes ////
function CuotasAcuerdo(v){
	FAjax('apcuotas.php?idac='+v+'&decreto='+document.getElementById('decreto678').checked,'cuotasap','','get');
	document.getElementById('ncsn').checked=false;
	document.getElementById('ncsn2').checked='checked';
	document.getElementById('nnotacred').value='';
	document.getElementById('nnotacred').disabled='disabled';
	document.getElementById('valorrnc').value=formatCurrency(0);
	document.getElementById('valorpnc').value=formatCurrency(0);
	document.getElementById('valornc').value=formatCurrency(0);
	document.getElementById('valortotal').value=formatCurrency(0);
	var vvivat=document.getElementById('vivat').value;
	if(vvivat>0){
		document.getElementById('viva').value=formatCurrency(0);}
	document.getElementById('valortotalt').value=formatCurrency(0);
}
////  Valida que se digite un valor en el campo liquidacion ////
function ValidaLiq(){
	if(document.getElementById('liquida').value.length<1){
		alert("Digite un n\xfamero de liquidaci\xf3n");
		document.getElementById('liquida').className='campoRequerido';
		DisableRecaudo();
		return false;
		}
	document.getElementById('liquida').className='';
	var a=document.getElementById('liquida').value;
	FAjax('estvalliq.php?dato='+a,'estliq','','post');
	
	if(!!myarray[0] && myarray[0].length>0){
		cant=myarray[0].length;
		for(cuen=0;cuen<cant;cuen++){
			myarray[0].pop();
		}
	}
}
//// Limpia la vista de Recaudo
function LimpiaRecaudo() {
    FAjax('nada.php', 'tiporecconsig', '', 'post');
    FAjax('nada.php', 'tiporecvent', '', 'post');
    document.getElementById('tiprecc').checked = false;
    document.getElementById('tiprecv').checked = false;
    document.getElementById('tiprece').checked = false;
    document.getElementById('nomconsig').value = '';
    document.getElementById('telconsig').value = '';
    document.getElementById('docconsig').value = '';
}

function DisableRecaudo(){
    LimpiaRecaudo();
    document.getElementById('aplica').value = '';
    document.getElementById('tiprecc').disabled = 'disabled';
    document.getElementById('tiprecv').disabled = 'disabled';
    document.getElementById('tiprece').disabled = 'disabled';
    document.getElementById('nomconsig').disabled = 'disabled';
    document.getElementById('telconsig').disabled = 'disabled';
    document.getElementById('docconsig').disabled = 'disabled';
    setTimeout("document.getElementById('liquida').focus()", 2);
}

function ActivaRecaudo() {
    LimpiaRecaudo();
    document.getElementById('aplica').value = '1';
    document.getElementById('tiprecc').disabled = '';
    document.getElementById('tiprecv').disabled = '';
    document.getElementById('tiprece').disabled = '';
    document.getElementById('nomconsig').disabled = '';
    document.getElementById('telconsig').disabled = '';
    document.getElementById('docconsig').disabled = '';
}
////  Traer el numero de cuentas del banco seleccionado ////
function NoCuentas(v, i) {
    FAjax('cuentas.php?dato=' + v, 'cuentas', '', 'post');
}
////  trae los campo dependiendo del tipo de pago ////
function Tipopago(v){
	FAjax('dattarjeta.php?dato='+v,'datostarj','','post');
	}
////  Valida que se digite un valor en el campo liquidacion y trae los campos de la consignacion o del pago ////
function CalValPend() {
    var tiprecc = document.getElementById('tiprecc').checked;
    var tiprecv = document.getElementById('tiprecv').checked;
    var tiprece = document.getElementById('tiprece').checked;
    var h = null;
    if (tiprecv) {
        h = document.getElementById('vconsigv');
    } else if (tiprece) {
        document.getElementById('forpconsig').value = 6;
        h = document.getElementById('vconsigv');
    } else if (tiprecc) {
        h = document.getElementById('vconsig');
    }
    if (h !== null) {
        var f = Extract(h.value);
        var numv = f.charAt(0);
        if (numv !== "0") {
            f = parseInt(f);
        } else {
            f = 0;
        }
        var pend = parseInt(Extract(document.getElementById('pendiente').value));
        if (pend > 0) {
            var e = pend - f;
            if (e < 1) {
                document.getElementById('vpendiente').value = formatCurrency(0);
                document.getElementById('vrecaudo').value = formatCurrency(pend);
                document.getElementById('pendiente').value = 0;
                document.getElementById('recaudo').value = pend;
            } else {
                alert("Error en calculo de saldo reintentar recaudo.");
            }
        } else {
            ValidaPendiente();
        }
    }
}
//// Valida si hay saldo previamente recaudado y lo devuelve a pendiente
function ValidaPendiente() {
    var rec = document.getElementById('recaudo').value;
    var pen = document.getElementById('pendiente').value;
    if (pen === "0" && rec !== "0") {
        document.getElementById('vpendiente').value = formatCurrency(rec);
        document.getElementById('vrecaudo').value = formatCurrency(0);
        document.getElementById('pendiente').value = rec;
        document.getElementById('recaudo').value = 0;
    }
}
////  Valida que se digite un valor en el campo liquidacion y trae los campos de la consignacion o del pago ////
function ValidaLiqTR() {
    if (document.getElementById('liquida').value.length < 1) {
        alert("Digite un n\xfamero de liquidaci\xf3n");
        document.getElementById('liquida').className = 'campoRequerido';
        DisableRecaudo();
        return false;
    }
    if (document.getElementById('tiprecc').checked == true) {
        FAjax('nada.php', 'tiporecvent', '', 'post');
        ValidaPendiente();
        FAjax('datcomprobc.php', 'tiporecconsig', '', 'post');
    } else {
        FAjax('nada.php', 'tiporecconsig', '', 'post');
        ValidaPendiente();
    }
}
////  Valida que se digite un valor en el campo liquidacion y trae los campos de la consignacion o del pago ////
function ValidaLiqTR2() {
    if (document.getElementById('liquida').value.length < 1) {
        alert("Digite un n\xfamero de liquidaci\xf3n");
        document.getElementById('liquida').className = 'campoRequerido';
        DisableRecaudo();
        return false;
    }
    if (document.getElementById('tiprecv').checked === true || document.getElementById('tiprece').checked === true) {
        FAjax('nada.php', 'tiporecconsig', '', 'post');
        ValidaPendiente();
        FAjax('datcomprobv.php', 'tiporecvent', '', 'post');
    } else {
        FAjax('nada.php', 'tiporecvent', '', 'post');
        ValidaPendiente();
    }
}

////  Valida que se digite un valor en el campo liquidacion y trae los campos de la consignacion o del pago ////
function ValidaLiqTR3() {
    if (document.getElementById('liquida').value.length < 1) {
        alert("Digite un n\xfamero de liquidaci\xf3n");
        document.getElementById('liquida').className = 'campoRequerido';
        DisableRecaudo();
        return false;
    }
    if (document.getElementById('tiprecv').checked === true || document.getElementById('tiprece').checked === true) {
        FAjax('nada.php', 'tiporecconsig', '', 'post');
        ValidaPendiente();
        FAjax('datcomprobe.php', 'tiporecvent', '', 'post');
		var rec = document.getElementById('recaudo').value;
		var pen = document.getElementById('pendiente').value;
		if(pen!=="0" && rec==="0"){
			document.getElementById('recaudo').value=pen;
			document.getElementById('pendiente').value=rec;
			document.getElementById('vrecaudo').value= formatCurrency(pen);
			document.getElementById('vpendiente').value=formatCurrency(0);
			setTimeout("document.getElementById('vconsigv').value=formatCurrency('"+pen+"');",250);
		}
    } else {
        FAjax('nada.php', 'tiporecvent', '', 'post');
        ValidaPendiente();
    }
}

function ValidaLiqLiqTR3(check,obj,comparendo,pos) {
	/*
	ar =array[];
	cont=0;
    for (i=0;i<document.form.elements.length;i++){
		if(document.form.elements[i].type == "checkbox" && document.form.elements[i].checked==1 && document.form.elements[i].name.indexOf('compar')>=0){
			ar[cont]=document.form.elements[i].name.substr(document.form.elements[i].name.indexOf('compar')+1)
			cont++;
		}
	}
	*/
        //FAjax('nada.php', 'tiporecconsig', '', 'post');
		//comparxx
		
		if(check.checked === true){
			document.getElementById('cambioC').value=comparendo;
			document.getElementById('cambioP').value=pos;
            FAjax('tituloshtml.php?cambioC='+comparendo+'&cambioP='+pos, obj, '', 'get');
			document.getElementById('cambioC').value="";
			document.getElementById('cambioP').value="";
		} else {
			myarray[pos]=new Array();
			document.getElementById(obj).innerHTML=" ";
		}
}
/// Agregar Titulos //
var myarray=[];
myarray[0]=new Array();
myarray[1]=new Array();
myarray[2]=new Array();
myarray[3]=new Array();
myarray[4]=new Array();
myarray[5]=new Array();
myarray[6]=new Array();
myarray[7]=new Array();
myarray[8]=new Array();
myarray[9]=new Array();
myarray[10]=new Array();
myarray[11]=new Array();
myarray[12]=new Array();
function addTitulosArray(pos){
	valTitulo = document.getElementById('TtxtVal'+pos).value;
	fecTitulo = document.getElementById('TtxtFecha'+pos).value;
	numTitulo = document.getElementById('TtxtNum'+pos).value;
	if(valTitulo== "" || fecTitulo=="" || numTitulo==""){
		alert("Por favor llene todos los campos");
	}else{
		parametros = {
			valor : valTitulo,
			fecha : fecTitulo,
			titulo: numTitulo
		}
		myarray[pos].push(JSON.stringify(parametros));
		$("#TtxtVal"+pos+", #TtxtFecha"+pos+", #TtxtNum"+pos).val("");
		createTablaTitulo(pos);

	}
}


// Eliminar TitulodelArray
function deleteTableTituo(id,pos){
	for(i in myarray[pos]){
	
		dato = JSON.parse(myarray[pos][i]);
		if(dato.titulo==id){
			myarray[pos].splice(i, 1 );
		}
	  }
	  createTablaTitulo(pos);
}

///Crear Tabla en base al Array
function createTablaTitulo(pos){
	tabla = document.getElementById('tbTitulo'+pos);
	tbody = "";
	suma=0;
	if(myarray[pos].length< 1){
		tabla.innerHTML="<tr><td>Agregue información</td></tr>";
		document.getElementById('totaltitulos'+pos).innerHTML="0";
	}else{
		for( i in myarray[pos]){
			dato = JSON.parse(myarray[pos][i]);
			formato='"'+dato.titulo+'"';
			tbody+="<tr><td><input type='hidden' id='titulo"+pos+"_"+i+"' name='titulo"+pos+"_"+i+"' value='"+dato.titulo+"'>"+dato.titulo+"</td><td><input type='hidden' id='fecha"+pos+"_"+i+"' name='fecha"+pos+"_"+i+"' value='"+dato.fecha+"' >"+dato.fecha+"</td><td><input type='hidden' id='valor"+pos+"_"+i+"' name='valor"+pos+"_"+i+"' value='"+dato.valor+"' >"+dato.valor+"</td><td><button type='button' class='btn btn-sm btn-outline-danger' onclick='deleteTableTituo("+formato+","+pos+")'  >Quitar</button></td></tr>";
			suma = suma + parseInt(dato.valor);
			//alert(dato.valor+"  - " + suma);
		}
		tabla.innerHTML=tbody;
		
		if(!!document.getElementById('totaltitulos'+pos)){
			document.getElementById('totaltitulos'+pos).innerHTML=suma;
		}
	}
}

////  Agregar nueva consignaci\xf3n ////
function AgregaConsig(){
	var a=document.getElementById('ntconsig').value;
	var b=parseInt(a)+1;
	document.getElementById('ntconsig').value=b;
	var pend=document.getElementById('vpendiente').value;
	FAjax('datcomprobc.php?dato='+b,'tiporecconsig','','post');
	//alert(b);
	setTimeout("document.getElementById('vconsig"+b+"').value=pend",1000);
	setTimeout("CalValPend()",2000);
	}
////  Quitar ultima consignacion ////
function QuitarConsig(){
	var a=document.getElementById('ntconsig').value;
	var b=parseInt(a)-1;
	FAjax('datcomprobc.php?dato='+b,'tiporecconsig','','post');	
	alert("Se calcularan nuevamente los valores recaudados");
	document.getElementById('ntconsig').value=b;
	CalValPend();
	}
///  Validar los campos del recaudo ////
function ValidarRecaudo() {
    var campos = document.getElementById('valrecaudo').value;
    var tiprecc = document.getElementById('tiprecc');
    var tiprecv = document.getElementById('tiprecv');
    var tiprece = document.getElementById('tiprece');
    if ((tiprecc.checked === false) && (tiprecv.checked === false) && (tiprece.checked === false)) {
        alert("Seleccione un tipo de recaudo y los datos requeridos");
        document.getElementById('tiprecc').className = 'campoRequerido';
        setTimeout("document.getElementById('tiprecc').focus()", 1);
        return false;
    }
    if (tiprecc.checked) {
        campos = campos + document.getElementById('valtiprecc').value;
    }
    if (tiprecv.checked || tiprece.checked) {
        campos = campos + document.getElementById('valtiprecv').value;
    }
    document.getElementById('tiprecc').className = '';
    var nombrecampo = campos.split(",");
    for (var i = 0; i < nombrecampo.length; i++) {
        if (nombrecampo[i] !== '') {
            if (document.getElementById(nombrecampo[i]).value.length < 1) {
                alert("Este campo no debe ser vacio");
                document.getElementById(nombrecampo[i]).className = 'campoRequerido';
                setTimeout("document.getElementById('" + nombrecampo[i] + "').focus()", 1);
                return false;
            }
            document.getElementById(nombrecampo[i]).className = '';
        }
    }
    document.getElementById('agregar').disabled = true;
    document.form.action = 'recaudo.php';
    document.form.submit();
}

function ValidarRecaudojimmy() {
    var campos = document.getElementById('valrecaudo').value;
    var tiprecc = document.getElementById('tiprecc');
    var tiprecv = document.getElementById('tiprecv');
    var tiprece = document.getElementById('tiprece');
    if ((tiprecc.checked === false) && (tiprecv.checked === false) && (tiprece.checked === false)) {
        alert("Seleccione un tipo de recaudo y los datos requeridos");
        document.getElementById('tiprecc').className = 'campoRequerido';
        setTimeout("document.getElementById('tiprecc').focus()", 1);
        return false;
    }
	if(tiprece.checked && myarray[0].length<=0){
		alert("Por favor agregar los titulos con el Botón Adicionar");
		return false;
	}
    if (tiprecc.checked) {
        campos = campos + document.getElementById('valtiprecc').value;
    }
    if (tiprecv.checked || tiprece.checked) {
        campos = campos + document.getElementById('valtiprecv').value;
    }
    document.getElementById('tiprecc').className = '';
    var nombrecampo = campos.split(",");
    for (var i = 0; i < nombrecampo.length; i++) {
        if (nombrecampo[i] !== '') {
            if (document.getElementById(nombrecampo[i]).value.length < 1) {
                alert("Este campo no debe ser vacio");
                document.getElementById(nombrecampo[i]).className = 'campoRequerido';
                setTimeout("document.getElementById('" + nombrecampo[i] + "').focus()", 1);
                return false;
            }
            document.getElementById(nombrecampo[i]).className = '';
        }
    }
	if(tiprece.checked){
		$.ajax({
				type: "POST",
				url: "tituloRecaudo.php?embargo="+$("#liquida").val(),
				data: JSON.stringify(myarray[0]),
				success: function(data){
					console.log(data);
					const arreglojson=JSON.parse(data);
					for(i=0;i<arreglojson.length; i++){
						if(arreglojson[i]!="" && arreglojson[i].substring(0,3)=="../"){
							window.open(arreglojson[i]);
						}
					}
					document.getElementById('agregar').disabled = true;
					document.form.action = 'recaudo.php';
					document.form.submit();
				}
			}).done();
	}else{
		document.getElementById('agregar').disabled = true;
		document.form.action = 'recaudo.php';
		document.form.submit();
	}
	
    
}
/// comvertir a mayusculas mientras se escribe ///
function Mayusculas(e){
	e.value=e.value.toUpperCase();
	}
/// comvertir a mayusculas mientras se escribe ///
function Minusculas(e){
	e.value=e.value.toLowerCase();
	}
////  Valida que un n\xfamero de consignacion de un banco sea unico  ////
function ValidaNoConsig() {
    if (document.getElementById('bancos').value < 1) {
        alert("Seleccione una entidad bancaria");
        document.getElementById('bancos').className = 'campoRequerido';
        setTimeout("document.getElementById('bancos'+i).focus()", 1);
        return false;
    }
    if (document.getElementById('nconsig').value.length < 1) {
        alert("Digite el n\xfamero de consignaci\xf3n");
        document.getElementById('nconsig').className = 'campoRequerido';
        setTimeout("document.getElementById('nconsig'+i).focus()", 1);
        return false;
    }
    var a = document.getElementById('bancos').value;
    var b = document.getElementById('nconsig').value;
    document.getElementById('bancos').className = '';
    document.getElementById('nconsig').className = '';
    FAjax('valconsig.php?dato=' + a + '|' + b, 'mensjvalconsig', '', 'post');
}
////  Valida que se digite un valor en el campo liquidacion y llama por ajax las paginas con la informacion del recaudo ////
function ValidaLiqInfo(){
	if(document.getElementById('liquida').value.length<1){
		alert("Digite un n\xfamero de liquidaci\xf3n y/o consignaci\xf3n");
		document.getElementById('liquida').className='campoRequerido';
		FAjax('nada.php','estliq','','post');
		FAjax('nada.php','infrecaudo','','post');
		FAjax('nada.php','infconsinante','','post');
		setTimeout("document.getElementById('liquida').focus()",1);
		return false;
		}
	document.getElementById('liquida').className='';
	var a=document.getElementById('liquida').value;
	FAjax('infestvalliq.php?dato='+a,'estliq','','post');
	FAjax('infrecaudos.php?dato='+a,'infrecaudo','','post');
	FAjax('infconsigna.php?dato='+a,'infconsinante','','post');
	}
	
////  Valida que se digite un valor en el campo liquidacion y llama por ajax las paginas con la informacion del recaudo externo ////
function ValidaLiqInfo_rec_ext(){
	if(document.getElementById('liquida').value.length<1){
		alert("Digite un n\xfamero de liquidaci\xf3n y/o consignaci\xf3n");
		document.getElementById('liquida').className='campoRequerido';
		FAjax('nada.php','estliq','','post');
		FAjax('nada.php','infrecaudo','','post');
		FAjax('nada.php','infconsinante','','post');
		setTimeout("document.getElementById('liquida').focus()",1);
		return false;
		}
	document.getElementById('liquida').className='';
	var a=document.getElementById('liquida').value;
	FAjax('infestvalliq_rec_ext.php?dato='+a,'estliq','','post');
	FAjax('infrecaudos_rec_ext.php?dato='+a,'infrecaudo','','post');
	FAjax('infconsigna_rec_ext.php?dato='+a,'infconsinante','','post');
	}
	
	
	
////  Valida que se seleccione una clase para enviar el dato y Buscar las carrocerias dependiendo la clase seleccionada ////
function BuscarCarroceria(){
	if(document.getElementById('Tvehiculos_clase').value<1){
		alert("Seleccione una clase de veh\xedculo");
		document.getElementById('Tvehiculos_clase').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_clase').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_clase').className='';
	document.getElementById('Tvehiculos_capacidadpasajeros').value='';
	FAjax('nada.php','pasaj','','get');
	document.getElementById('Tvehiculos_capacidadcarga').value='';	
	FAjax('nada.php','carg','','get');
	document.getElementById('Tvehiculos_cilindraje').value='';
	FAjax('nada.php','cilin','','get');
	FAjax('carroceria.php?dato='+document.getElementById('Tvehiculos_clase').value,'carroc','','get');
	}
//  Valida que se seleccione un valor en el campo tipo de servicio en la liquidacion ////
function BuscarLineas(){
	if(document.getElementById('Tvehiculos_marca').value<1){
		alert("Seleccione una marca de veh\xedculo");
		document.getElementById('Tvehiculos_marca').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_marca').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_marca').className='';
	FAjax('lineas.php?dato='+document.getElementById('Tvehiculos_marca').value,'linea','','get');
	}
//  Valida que se seleccione un valor en el campo tipo de servicio en la liquidacion ////
function ValidarPasajeros(){
	if(document.getElementById('Tvehiculos_clase').value<1){
		alert("Seleccione una clase de veh\xedculo");
		document.getElementById('Tvehiculos_capacidadpasajeros').value='';
		document.getElementById('Tvehiculos_clase').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_clase').focus()",1);
		return false;
		}
	if(document.getElementById('Tvehiculos_capacidadpasajeros').value.length<1){
		alert("Digite el n\xfamero de pasajeros");
		document.getElementById('Tvehiculos_capacidadpasajeros').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_capacidadpasajeros').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_clase').className='';
	document.getElementById('Tvehiculos_capacidadpasajeros').className='';
	FAjax('mensneg.php?dato='+document.getElementById('Tvehiculos_clase').value+'|'+document.getElementById('Tvehiculos_capacidadpasajeros').value+'|p','pasaj','','get');
	}
//  Valida que se seleccione un valor en el campo tipo de servicio en la liquidacion ////
function ValidarCarga(){
	if(document.getElementById('Tvehiculos_clase').value<1){
		alert("Seleccione una clase de veh\xedculo");
		document.getElementById('Tvehiculos_capacidadcarga').value='';
		document.getElementById('Tvehiculos_clase').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_clase').focus()",1);
		return false;
		}
	if(document.getElementById('Tvehiculos_capacidadcarga').value.length<1){
		alert("Digite la capacidad de carga en toneladas");
		document.getElementById('Tvehiculos_capacidadcarga').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_capacidadcarga').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_clase').className='';
	document.getElementById('Tvehiculos_capacidadcarga').className='';
	FAjax('mensneg.php?dato='+document.getElementById('Tvehiculos_clase').value+'|'+document.getElementById('Tvehiculos_capacidadcarga').value+'|c','carg','','get');
	}
//  Valida que se seleccione un valor en el campo tipo de servicio en la liquidacion ////
function ValidarCilindraje(){
	if(document.getElementById('Tvehiculos_clase').value<1){
		alert("Seleccione una clase de veh\xedculo");
		document.getElementById('Tvehiculos_cilindraje').value='';
		document.getElementById('Tvehiculos_clase').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_clase').focus()",1);
		return false;
		}
	if(document.getElementById('Tvehiculos_cilindraje').value.length<1){
		alert("Digite el cilindraje");
		document.getElementById('Tvehiculos_cilindraje').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_cilindraje').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_clase').className='';
	document.getElementById('Tvehiculos_cilindraje').className='';
	FAjax('mensneg.php?dato='+document.getElementById('Tvehiculos_clase').value+'|'+document.getElementById('Tvehiculos_cilindraje').value+'|cl','cilin','','get');
	}
//  Valida que se seleccione un valor en los campos modalidad y peso y habilita campos adicionales ////
function ValidarPeso(){
	if(document.getElementById('Tvehiculos_modalidad').value<1){
		alert("Seleccione la modalidad del veh\xedculo");
		document.getElementById('Tvehiculos_peso').value='';
		document.getElementById('Tvehiculos_modalidad').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_modalidad').focus()",1);
		return false;
		}
	if(document.getElementById('Tvehiculos_peso').value.length<1){
		alert("Digite el peso del veh\xedculo");
		document.getElementById('Tvehiculos_peso').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_peso').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_modalidad').className='';
	document.getElementById('Tvehiculos_peso').className='';
	if((document.getElementById('Tvehiculos_peso').value>10500)&&(document.getElementById('Tvehiculos_modalidad').value>1)){FAjax('pesobruto.php','peso','','get');}
	else{FAjax('nada.php','peso','','get');}
	}
//  Restablece los valores en peso y desabilita los campos adicinales mostrados ////
function RestablecerPeso(){
	if(document.getElementById('Tvehiculos_tiposervicio').value<1){
		alert("Seleccione el tipo de servicio del veh\xedculo");
		document.getElementById('Tvehiculos_modalidad').value='';
		document.getElementById('Tvehiculos_tiposervicio').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_tiposervicio').focus()",1);
		FAjax('nada.php','public','','get');
		return false;
		}
	if(document.getElementById('Tvehiculos_modalidad').value<1){
		alert("Seleccione la modalidad del veh\xedculo");
		document.getElementById('Tvehiculos_peso').value='';
		document.getElementById('Tvehiculos_modalidad').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_modalidad').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_tiposervicio').className='';
	document.getElementById('Tvehiculos_modalidad').className='';
	document.getElementById('Tvehiculos_peso').value='';
	FAjax('nada.php','peso','','get');
	if(document.getElementById('Tvehiculos_tiposervicio').value==2){
            if(document.getElementById('Tvehiculos_modalidad').value==1){
                    document.getElementById('Tvehiculos_tipopasajero').value='';
                    document.getElementById('Tvehiculos_tipopasajero').disabled=false;
                    }
            else{
                    document.getElementById('Tvehiculos_tipopasajero').value='';
                    document.getElementById('Tvehiculos_tipopasajero').disabled='disabled';
                    }
            }
	}
//  Valida si el chasis es independiente ////
function ChasisIndep(){
	if(document.getElementById('Tvehiculos_chasisind').checked==true){FAjax('chaind.php?dato=no','chasisind','','get');}
	else{FAjax('nada.php','chasisind','','get');}
	}
//  Valida si el chasis es independiente ////
function ValidaImporta(){
	if(document.getElementById('Tvehiculos_origen').value<1){
		alert("Seleccione el origen del veh\xedculo");
		document.getElementById('Tvehiculos_actaimportacion').value='';
		document.getElementById('Tvehiculos_declaracion').value='';
		document.getElementById('Tvehiculos_fdeclaracion').value='';
		document.getElementById('Tvehiculos_paisorigen').value='';
		document.getElementById('Tvehiculos_origen').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_origen').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_origen').className='';
	document.getElementById('Tvehiculos_actaimportacion').value='';
	if(document.getElementById('Tvehiculos_origen').value>1){
		document.getElementById('Tvehiculos_actaimportacion').disabled=false;
		document.getElementById('2Tvehiculos_actaimportacion').className='campoRequerido';
		document.getElementById('Tvehiculos_declaracion').disabled=false;
		document.getElementById('2Tvehiculos_declaracion').className='campoRequerido';
		document.getElementById('Tvehiculos_fdeclaracion').disabled=false;
		document.getElementById('2Tvehiculos_fdeclaracion').className='campoRequerido';
		document.getElementById('cal-Tvehiculos_fdeclaracion').disabled=false;
		document.getElementById('Tvehiculos_paisorigen').value='';
		document.getElementById('Tvehiculos_paisorigen').disabled=false;
		document.getElementById('2Tvehiculos_paisorigen').className='campoRequerido';
		}
	else{
		document.getElementById('Tvehiculos_actaimportacion').value='';
		document.getElementById('Tvehiculos_actaimportacion').disabled='disabled';
		document.getElementById('2Tvehiculos_actaimportacion').className='subtotales';
		document.getElementById('Tvehiculos_declaracion').value='';
		document.getElementById('Tvehiculos_declaracion').disabled='disabled';
		document.getElementById('2Tvehiculos_declaracion').className='subtotales';
		document.getElementById('Tvehiculos_fdeclaracion').value='';
		document.getElementById('Tvehiculos_fdeclaracion').disabled='disabled';
		document.getElementById('2Tvehiculos_fdeclaracion').className='subtotales';
		document.getElementById('cal-Tvehiculos_fdeclaracion').disabled='disabled';
		document.getElementById('Tvehiculos_paisorigen').value=47;
		document.getElementById('Tvehiculos_paisorigen').disabled='disabled';
		document.getElementById('2Tvehiculos_paisorigen').className='subtotales';
		}
	}
//  Buscar un vehiculo por numero de chasis ////
function BuscarVehiculoCh(){
	if(document.getElementById('Tvehiculos_chasis').value.length<1){
		alert("Digite el n\xfamero de chasis del veh\xedculo");
		document.getElementById('Tvehiculos_chasis').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_chasis').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_origen').className='';
	document.getElementById('Tvehiculos_chasis').className='';
	var c=document.getElementById('Tvehiculos_chasis').value;
	var p=document.getElementById('Tvehiculos_placa').value;
	var m=document.getElementById('Tvehiculos_motor').value;
	var mc=document.getElementById('Tvehiculos_marca').value;
	var l=document.getElementById('Tvehiculos_linea').value;
	FAjax('buscavehichasis.php?dato='+c+'|'+p+'|'+m+'|'+mc+'|'+l,'bvchasis','','get');
	}
//  Restablece los valores en peso y desabilita los campos adicinales mostrados ////
function ValidaTipoServ(){
	if(document.getElementById('Tvehiculos_tiposervicio').value<1){
		alert("Seleccione el tipo de servicio del veh\xedculo");
		document.getElementById('Tvehiculos_tiposervicio').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_tiposervicio').focus()",1);
		FAjax('nada.php','public','','get');
		return false;
		}
	document.getElementById('Tvehiculos_tiposervicio').className='';
	if(document.getElementById('Tvehiculos_tiposervicio').value==2){FAjax('publico.php','public','','get');}
	else{FAjax('nada.php','public','','get');}
	}
//  Valida si el chasis es independiente ////
function ValidaTiporegistro(){
	if(document.getElementById('Tvehiculos_MI_tiporeg').value<1){
		alert("Seleccione el tipo de registro");
		document.getElementById('Tvehiculos_MI_organismo').value='';
		document.getElementById('Tvehiculos_MI_acto').value='';
		document.getElementById('Tvehiculos_MI_facto').value='';
		document.getElementById('Tvehiculos_MI_placa1').value='';
		document.getElementById('Tvehiculos_MI_poliza').value='';
		document.getElementById('Tvehiculos_MI_fpoliza').value='';
		document.getElementById('Tvehiculos_MI_certificado').value='';
		document.getElementById('Tvehiculos_MI_fcertificado').value='';
		document.getElementById('Tvehiculos_MI_tiporeg').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_MI_tiporeg').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_MI_tiporeg').className='';
	if(document.getElementById('Tvehiculos_MI_tiporeg').value==1){
		document.getElementById('Tvehiculos_MI_organismo').disabled=false;
		document.getElementById('Tvehiculos_MI_acto').disabled=false;
		document.getElementById('Tvehiculos_MI_facto').disabled=false;
		document.getElementById('cal-Tvehiculos_MI_facto').disabled=false;
		document.getElementById('Tvehiculos_MI_placa1').disabled=false;
		document.getElementById('Tvehiculos_MI_poliza').value='';
		document.getElementById('Tvehiculos_MI_poliza').disabled='disabled';
		document.getElementById('Tvehiculos_MI_fpoliza').value='';
		document.getElementById('Tvehiculos_MI_fpoliza').disabled='disabled';
		document.getElementById('cal-Tvehiculos_MI_fpoliza').value='';
		document.getElementById('cal-Tvehiculos_MI_fpoliza').disabled='disabled';
		}
	else{
		document.getElementById('Tvehiculos_MI_organismo').value='';
		document.getElementById('Tvehiculos_MI_organismo').disabled='disabled';
		document.getElementById('Tvehiculos_MI_acto').value='';
		document.getElementById('Tvehiculos_MI_acto').disabled='disabled';
		document.getElementById('Tvehiculos_MI_facto').value='';
		document.getElementById('Tvehiculos_MI_facto').disabled='disabled';
		document.getElementById('cal-Tvehiculos_MI_facto').disabled='disabled';
		document.getElementById('Tvehiculos_MI_placa1').value='';
		document.getElementById('Tvehiculos_MI_placa1').disabled='disabled';
		document.getElementById('Tvehiculos_MI_poliza').disabled=false;
		document.getElementById('Tvehiculos_MI_fpoliza').disabled=false;
		document.getElementById('cal-Tvehiculos_MI_fpoliza').disabled=false;
		}
	}
//  Buscar un vehiculo por numero de chasis ////
function ValidarMatriIni(campos){
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(nombrecampo[i]=='Tvehiculos_tiposervicio'){
				if(document.getElementById('Tvehiculos_tiposervicio').value.length<1){
					alert("Seleccione un tipo de servicio");
					document.getElementById('Tvehiculos_tiposervicio').className='campoRequerido';
					setTimeout("document.getElementById('Tvehiculos_tiposervicio').focus()",1);
					return false;
					}
				document.getElementById('Tvehiculos_tiposervicio').className='';
				if(document.getElementById('Tvehiculos_tiposervicio').value==2){
					if(document.getElementById('Tvehiculos_cartaacepta').value.length<1){
						alert("Digite el n\xfamero de carta de aceptaci\xf3n");
						document.getElementById('Tvehiculos_cartaacepta').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_cartaacepta').focus()",1);
						return false;
						}
					document.getElementById('Tvehiculos_cartaacepta').className='';
					if(document.getElementById('Tvehiculos_transportador').value.length<1){
						alert("Seleccione una empresa transportadora");
						document.getElementById('Tvehiculos_transportador').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_transportador').focus()",1);
						return false;
						}
					document.getElementById('Tvehiculos_transportador').className='';
					if(document.getElementById('Tvehiculos_radio').value.length<1){
						alert("Seleccione un radio de operaci\xf3n");
						document.getElementById('Tvehiculos_radio').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_radio').focus()",1);
						return false;
						}
					document.getElementById('Tvehiculos_radio').className='';
					}
				}
			if(nombrecampo[i]=='Tvehiculos_modalidad'){
				if(document.getElementById('Tvehiculos_modalidad').value.length<1){
						alert("Seleccione una modalidad");
						document.getElementById('Tvehiculos_modalidad').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_modalidad').focus()",1);
						return false;
						}
				document.getElementById('Tvehiculos_modalidad').className='';
				if((document.getElementById('Tvehiculos_tiposervicio').value==2)&&(document.getElementById('Tvehiculos_modalidad').value==1)){
					if(document.getElementById('Tvehiculos_tipopasajero').value.length<1){
						alert("Seleccione el tipo de pasajeros");
						document.getElementById('Tvehiculos_tipopasajero').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_tipopasajero').focus()",1);
						return false;
						}
					document.getElementById('Tvehiculos_tipopasajero').className='';					
					}
				}
			if(nombrecampo[i]=='Tvehiculos_chasisind'){
				if(document.getElementById('Tvehiculos_chasisind').value.checked==true){
					if(document.getElementById('Tvehiculos_ftc').value.length<1){
						alert("Digite el n\xfamero de ficha tecnica de la carrocer\xeda");
						document.getElementById('Tvehiculos_ftc').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_ftc').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_ftc').className='';				
					if(document.getElementById('Tvehiculos_ftch').value.length<1){
						alert("Digite el n\xfamero de ficha tecnica del chasis");
						document.getElementById('Tvehiculos_ftch').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_ftch').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_ftch').className='';				
					if(document.getElementById('Tvehiculos_ffc').value.length<1){
						alert("Digite el n\xfamero de factura de fabricaci\xf3n de la carrocer\xeda");
						document.getElementById('Tvehiculos_ffc').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_ffc').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_ffc').className='';				
					if(document.getElementById('Tvehiculos_carrocero').value.length<1){
						alert("Seleccione el carrocero fabricante");
						document.getElementById('Tvehiculos_carrocero').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_carrocero').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_carrocero').className='';				
					}
				}
			if(nombrecampo[i]=='Tvehiculos_peso'){
				if(document.getElementById('Tvehiculos_peso').value.length<1){
					alert("Seleccione una modalidad");
					document.getElementById('Tvehiculos_peso').className='campoRequerido';
					setTimeout("document.getElementById('Tvehiculos_peso').focus()",1);
					return false;
					}
				document.getElementById('Tvehiculos_peso').className='';
				if((document.getElementById('Tvehiculos_modalidad').value>1)&&(document.getElementById('Tvehiculos_peso').value>1500)){
					if(document.getElementById('Tvehiculos_MI_tiporeg').value.length<1){
						alert("Seleccione el tipo de registro");
						document.getElementById('Tvehiculos_MI_tiporeg').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_MI_tiporeg').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_MI_tiporeg').className='';
					if(document.getElementById('Tvehiculos_MI_tiporeg').value==1){
						if(document.getElementById('Tvehiculos_MI_organismo').value.length<1){
							alert("Seleccione el organismo de transito que cancel\xf3 la matr\xedcula");
							document.getElementById('Tvehiculos_MI_organismo').className='campoRequerido';
							setTimeout("document.getElementById('Tvehiculos_MI_organismo').focus()",1);
							return false;
							}document.getElementById('Tvehiculos_MI_organismo').className='';				
						if(document.getElementById('Tvehiculos_MI_acto').value.length<1){
							alert("Digite el n\xfamero de acto administrativo de cancelaci\xf3n de la matr\xedcula");
							document.getElementById('Tvehiculos_MI_acto').className='campoRequerido';
							setTimeout("document.getElementById('Tvehiculos_MI_acto').focus()",1);
							return false;
							}document.getElementById('Tvehiculos_MI_acto').className='';
						if(document.getElementById('Tvehiculos_MI_facto').value.length<1){
							alert("Seleccione la fecha del acto administrativo de cancelaci\xf3n de la matr\xedcula");
							document.getElementById('Tvehiculos_MI_facto').className='campoRequerido';
							setTimeout("document.getElementById('Tvehiculos_MI_facto').focus()",1);
							return false;
							}document.getElementById('Tvehiculos_MI_facto').className='';
						if(document.getElementById('Tvehiculos_MI_placa1').value.length<1){
							alert("Digite el n\xfamero de placa del vehiculo al cual se le cancel\xf3 la matr\xedcula");
							document.getElementById('Tvehiculos_MI_placa1').className='campoRequerido';
							setTimeout("document.getElementById('Tvehiculos_MI_placa1').focus()",1);
							return false;
							}document.getElementById('Tvehiculos_MI_placa1').className='';
						}
					else{
						if(document.getElementById('Tvehiculos_MI_poliza').value.length<1){
							alert("Digite el n\xfamero de p\xf3liza");
							document.getElementById('Tvehiculos_MI_poliza').className='campoRequerido';
							setTimeout("document.getElementById('Tvehiculos_MI_poliza').focus()",1);
							return false;
							}document.getElementById('Tvehiculos_MI_poliza').className='';	
						if(document.getElementById('Tvehiculos_MI_fpoliza').value.length<1){
							alert("Seleccione la fecha de la p\xf3liza");
							document.getElementById('Tvehiculos_MI_fpoliza').className='campoRequerido';
							setTimeout("document.getElementById('Tvehiculos_MI_fpoliza').focus()",1);
							return false;
							}document.getElementById('Tvehiculos_MI_fpoliza').className='';	
						}
					if(document.getElementById('Tvehiculos_MI_certificado').value.length<1){
						alert("Digite el n\xfamero de certificado de cumplimiento de requisitos");
						document.getElementById('Tvehiculos_MI_certificado').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_MI_certificado').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_MI_certificado').className='';
	
					if(document.getElementById('Tvehiculos_MI_fcertificado').value.length<1){
						alert("Seleccione la fecha de certificado de cumplimiento de requisitos");
						document.getElementById('Tvehiculos_MI_fcertificado').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_MI_fcertificado').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_MI_fcertificado').className='';								
					}
				}
			if(nombrecampo[i]=='Tvehiculos_origen'){
				if(document.getElementById('Tvehiculos_origen').value.length<1){
					alert("Seleccione el origen del veh\xedculo");
					document.getElementById('Tvehiculos_origen').className='campoRequerido';
					setTimeout("document.getElementById('Tvehiculos_origen').focus()",1);
					return false;
					}
				document.getElementById('Tvehiculos_origen').className='';
				if(document.getElementById('Tvehiculos_origen').value>1){
					if(document.getElementById('Tvehiculos_actaimportacion').value.length<1){
						alert("Digite el n\xfamero de acta de importaci\xf3n");
						document.getElementById('Tvehiculos_actaimportacion').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_actaimportacion').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_actaimportacion').className='';				
					if(document.getElementById('Tvehiculos_declaracion').value.length<1){
						alert("Digite el n\xfamero de declaraci\xf3n");
						document.getElementById('Tvehiculos_declaracion').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_declaracion').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_declaracion').className='';				
					if(document.getElementById('Tvehiculos_fdeclaracion').value.length<1){
						alert("Seleccione la fecha de declaraci\xf3n");
						document.getElementById('Tvehiculos_fdeclaracion').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_fdeclaracion').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_fdeclaracion').className='';				
					if(document.getElementById('Tvehiculos_paisorigen').value.length<1){
						alert("Seleccione el pa\xeds de origen");
						document.getElementById('Tvehiculos_paisorigen').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_paisorigen').focus()",1);
						return false;
						}document.getElementById('Tvehiculos_paisorigen').className='';				
					}
				}
			if(document.getElementById('Tvehiculos_LT').value>0){
				if(document.getElementById('Tvehiculos_placa').value.length<1){
						alert("Digite un n\xfamero de placa");
						document.getElementById('Tvehiculos_placa').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_placa').focus()",1);
						return false;
					}document.getElementById('Tvehiculos_placa').className='';
				if(document.getElementById('Tvehiculos_sustrato').value<1){
						alert("Seleccione un sustrato");
						document.getElementById('Tvehiculos_sustrato').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_sustrato').focus()",1);
						return false;
					}document.getElementById('Tvehiculos_sustrato').className='';
				}
			if(nombrecampo[i]=='Tvehiculos_modelo'){	
				if(parseInt(document.getElementById('Tvehiculos_modelo').value)<1900){
						alert("El modelo debe ser superior a 1900");
						document.getElementById('Tvehiculos_modelo').value='';
						document.getElementById('Tvehiculos_modelo').className='campoRequerido';
						setTimeout("document.getElementById('Tvehiculos_modelo').focus()",1);
						return false;
					}
				document.getElementById('Tvehiculos_modelo').className='';
			}
			
			var campo = document.getElementById(nombrecampo[i]).value; //MOVE IT HERE
			if (campo.length < 1 || campo == null){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
			}
		
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	if(document.getElementById('registro').value==0){FAjax('insertmi.php','bvchasis','','post');}
	else if(document.getElementById('registro').value==1){FAjax('updatemi.php','bvchasis','','post');}
	else if(document.getElementById('registro').value==2){
		document.getElementById('aplica').value='1';
		document.form.action='regv.php';
		document.form.submit();
		//FAjax('insertregv.php','bvchasis','','post');
		}
	else if(document.getElementById('registro').value==3){FAjax('insertradica.php','bvchasis','','post');}
	else if(document.getElementById('registro').value==4){FAjax('updateradica.php','bvchasis','','post');}
	else{FAjax('updatemi.php','bvchasis','','post');}
	}
//  Buscar un vehiculo por numero de chasis ////
function ValidarMatriIni2(campos){
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	document.getElementById('aplica').value='1';
	document.form.action='regv2.php';
	document.form.submit();
	}
//  valida el numero de licencia de transito digitado si es igual al reistrado en la base de datos ////
function ValidarLicencia(tabla){
	if(document.getElementById(tabla+'_LTActual').value<1){
		alert("Digite un n\xfamero de licencia de transito");
		document.getElementById(tabla+'_LTActual').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_LTActual').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_LTActual').className='';
  	var a=document.getElementById(tabla+'_LTActual').value;
  	var b=document.getElementById(tabla+'_placa').value;
	FAjax('lic.php?dato='+a+'|'+b+'|'+tabla,'2'+tabla+'_LTActual','','get');
	}
//  valida el numero de licencia de transito digitado si es igual al reistrado en la base de datos ////
function ValidarDenuncia(tabla){
	if(document.getElementById(tabla+'_LTdenuncia').value.length>0){
		document.getElementById(tabla+'_fechadenuncia').readOnly=false;
		document.getElementById('2'+tabla+'_fechadenuncia').className='campoRequerido';
		document.getElementById('cal-'+tabla+'_fechadenuncia').disabled=false;
		}
	else{
		document.getElementById(tabla+'_fechadenuncia').readOnly='readonly';
		document.getElementById('2'+tabla+'_fechadenuncia').className='subtotales';
		document.getElementById('cal-'+tabla+'_fechadenuncia').disabled='disabled';
		}
	}
//  Valida que el numero de placa digitado no exista en la base de datos ////
function ValidaPlaca(nomcampo){
	if(nomcampo!='Tvehiculos_placa'){
		if(document.getElementById(nomcampo).value.length<1){
			alert("Ingrese un dato en este campo");
			document.getElementById(nomcampo).className='campoRequerido';
			//setTimeout("document.getElementById('"+nomcampo+"').focus()",1);
			return false;
			}
		}
	document.getElementById(nomcampo).className='';
	if(document.getElementById(nomcampo).value.length>0){
		var valcampo=document.getElementById(nomcampo).value;
		FAjax('valplaca.php?dato='+valcampo+'|'+nomcampo,'bvchasis','','get');
		}
	}
//  Valida que el numero de placa digitado no exista en la base de datos ////
function ValidaPlaca2(nomcampo){
	if(nomcampo!='Tvehiculos_placa'){
		if(document.getElementById(nomcampo).value.length<1){
			alert("Ingrese un dato en este campo");
			document.getElementById(nomcampo).className='campoRequerido';
			//setTimeout("document.getElementById('"+nomcampo+"').focus()",1);
			return false;
			}
		}
	document.getElementById(nomcampo).className='';
	if(document.getElementById(nomcampo).value.length>0){
		var valcampo=document.getElementById(nomcampo).value;
		FAjax('valplaca2.php?dato='+valcampo+'|'+nomcampo,'bvchasis','','get');
		}
	}
//  Valida si se selecciona un numero de licencia el campo de placa no este vacio ////
function ValidaLicen(){
	if(document.getElementById('Tvehiculos_LT').value>0){
		if(document.getElementById('Tvehiculos_placa').value.length<1){
			alert("Digite un n\xfamero de placa");
			document.getElementById('Tvehiculos_LT').value='';
			document.getElementById('Tvehiculos_placa').className='campoRequerido';
			setTimeout("document.getElementById('Tvehiculos_placa').focus()",1);
			return false;
			}
		if(document.getElementById('Tvehiculos_identificacion').value.length<1){
			document.getElementById('Tvehiculos_identificacion').disabled=false;
				alert("Digite el n\xfamero de documento del propietario del veh\xedculo, debe estar registrado");
				document.getElementById('Tvehiculos_LT').value='';
				document.getElementById('Tvehiculos_identificacion').className='campoRequerido';
				setTimeout("document.getElementById('Tvehiculos_identificacion').focus()",1);
				return false;
			}
		}
	}
//  Valida si se selecciona un numero de licencia el campo de placa no este vacio ////
function ValidaSustrato(){
	if(document.getElementById('Tvehiculos_sustrato').value>0){
		if(document.getElementById('Tvehiculos_LT').value<1){
			alert("Seleccione una licencia de transito");
			document.getElementById('Tvehiculos_sustrato').value='';
			document.getElementById('Tvehiculos_LT').className='campoRequerido';
			setTimeout("document.getElementById('Tvehiculos_LT').focus()",1);
			return false;
			}
		}
	}
////  Valida que el campo numero de documento en registro del vehiculo no sea vacio y envia los datos a pag php para validar que este registrado  ////
function BuscarPropietario(){
	if(document.getElementById('Tvehiculos_identificacion').value.length<1){
		alert("Digite un n\xfamero de documento");
		document.getElementById('Tvehiculos_identificacion').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_identificacion').focus()",1);
		return false;}
	document.getElementById('Tvehiculos_identificacion').className='';
	FAjax('datproreg.php?dato='+document.getElementById('Tvehiculos_identificacion').value,'nomapell','','post');
	}
////  Valida que el campo numero de documento en registro del vehiculo no sea vacio y envia los datos a pag php para validar que este registrado  ////
function ValidaTipoTraspaso(){
    if (document.getElementById('Tvehiculos_TP_liquidacion').value.length < 1) {
        alert("Digite un n\xfamero de liquidaci\xf3n");
        document.getElementById('Tvehiculos_TP_liquidacion').className = 'campoRequerido';
        setTimeout("document.getElementById('Tvehiculos_TP_liquidacion').focus()", 1);
        return false;
    }
    document.getElementById('Tvehiculos_TP_liquidacion').className = '';
    var a = document.getElementById('Tvehiculos_TP_tipo').value;
    if (a === '6') {
        document.getElementById('tiposiete1').style.display = 'block';
        document.getElementById('tiposiete2').style.display = 'block';
        document.getElementById('liqcmh').setAttribute("class", "campoRequerido");
    } else {
        document.getElementById('tiposiete1').style.display = 'none';
        document.getElementById('tiposiete2').style.display = 'none';
        document.getElementById('liqcmh').setAttribute("class", "subtotales");
    }
    if (a === '7') {
        document.getElementById('Tvehiculos_TP_identificacion').value = '';
        var fecha = new Date();
        var diames = fecha.getDate();
        var mes = fecha.getMonth() + 1;
        var ano = fecha.getFullYear();
        var newanio = parseInt(ano) - 3;
        var mess = (mes < 10) ? "0" + mes : mes;
        var diamess = (diames < 10) ? "0" + diames: diames;
        var newfecha = newanio + "" + mess + "" + diamess;
        var hoyfecha = ano + "" + mess + "" + diamess;
        Calendar.setup({inputField: "Tvehiculos_TP_fechat", trigger: "cal-Tvehiculos_TP_fechat", onSelect: function () {
                this.hide();
            }, showTime: 12, dateFormat: "%Y-%m-%d", min: +newfecha, max: +hoyfecha});
        document.getElementById('4Tvehiculos_TP_fechat').style.display = 'block';
        document.getElementById('3Tvehiculos_TP_fechat').style.display = 'none';
        document.getElementById('licenciaT').style.display = 'none';
        document.getElementById('sustrato').style.display = 'none';
    } else {
        Calendar.setup({inputField: "Tvehiculos_TP_fechat", trigger: "cal-Tvehiculos_TP_fechat", onSelect: function () {
                this.hide();
            }, showTime: 12, dateFormat: "%Y-%m-%d", min: 19000101, max: 20991231});
        document.getElementById('4Tvehiculos_TP_fechat').style.display = 'none';
        document.getElementById('3Tvehiculos_TP_fechat').style.display = 'block';
        document.getElementById('licenciaT').style.display = 'table-row';
        document.getElementById('sustrato').style.display = 'table-row';
    }
    if (a === '2') {
        document.getElementById('2Tvehiculos_TP_actaadjudicacion').setAttribute("class", "campoRequerido");
    } else {
        document.getElementById('2Tvehiculos_TP_actaadjudicacion').setAttribute("class", "subtotales");
    }
    setTimeout("document.getElementById('Tvehiculos_TP_liquidacion').focus()", 1);
    setTimeout("document.getElementById('Tvehiculos_TP_contrato').focus()", 1);
}
//Validar si tramite indeterminado esta presente en la liquidacion
function validateIndeter() {
    var a = document.getElementById('indeter').value || '';
    var t = document.getElementById('tramite').value || '';
    if (a === '7' && t !== "5") {
        document.getElementById('licenciaT').style.display = 'none';
        document.getElementById('sustrato').style.display = 'none';
    }
}

//  Valida si el radio esta chequeado y habilita o desabilita campos ////
function HabilitarTramite(n,c,p){
	if(document.getElementById(n).checked==true){FAjax(p,c,'','get');}
	else{
		FAjax('nada.php',c,'','get');
		document.getElementById(n+'_0').checked=true
		}
	}
//  Valida si el radio esta chequeado y habilita o desabilita campos ////
function HabilitaCampo(a,b){
	if(document.getElementById(a).checked==true){
		document.getElementById(b).disabled=false;
		}
	else{
		document.getElementById(a+'_0').checked=true;
		document.getElementById(b).disabled='disabled';
		}
	}
////  Valida que el campo numero de documento en registro del vehiculo no sea vacio y envia los datos a pag php para validar que este registrado  ////
function ValidaTipoCM(){
	if(document.getElementById('Tvehiculos_CM_liquidacion').value.length<1){
		alert("Digite un n\xfamero de liquidaci\xf3n");
		document.getElementById('Tvehiculos_CM_tipo').value='';
		document.getElementById('Tvehiculos_CM_liquidacion').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_CM_liquidacion').focus()",1);
		return false;}
	if(document.getElementById('Tvehiculos_CM_tipo').value<1){
		alert("Seleccione un tipo de cancelaci\xf3n de matr\xedcula");
		document.getElementById('Tvehiculos_CM_tipo').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_CM_tipo').focus()",1);
		return false;}
	document.getElementById('Tvehiculos_CM_tipo').className='';
	var a=document.getElementById('Tvehiculos_CM_placa').value;
	if(document.getElementById('Tvehiculos_CM_tipo').value==1){
		FAjax('cmhurto.php?dato='+a,'tipocm','','post');
		}else
	if(document.getElementById('Tvehiculos_CM_tipo').value==2){
		FAjax('cmacc.php?dato='+a,'tipocm','','post');
		}else
	if(document.getElementById('Tvehiculos_CM_tipo').value==3){
		FAjax('cmsacc.php','tipocm','','post');
		}else
	if(document.getElementById('Tvehiculos_CM_tipo').value==4){
		FAjax('cmexp.php','tipocm','','post');
		}else
	if(document.getElementById('Tvehiculos_CM_tipo').value==5){
		FAjax('cmft.php','tipocm','','post');
		}
	}
//  Valida el tipo de motor chequeado ////
function ValidaTipoMotor(){
	if(document.getElementById('Tvehiculos_cmotor_tmotor').checked==true){
		document.getElementById('2Tvehiculos_cmotor_importacion').setAttribute("class","campoRequerido");
		document.getElementById('2Tvehiculos_cmotor_certmotor').setAttribute("class","subtotales");
		document.getElementById('2Tvehiculos_cmotor_fcertmotor').setAttribute("class","subtotales");
		document.getElementById('2Tvehiculos_cmotor_ecertmotor').setAttribute("class","subtotales");
		document.getElementById('Tvehiculos_cmotor_importacion').disabled=false;
		document.getElementById('Tvehiculos_cmotor_certmotor').value='';
		document.getElementById('Tvehiculos_cmotor_certmotor').disabled='disabled';
		document.getElementById('Tvehiculos_cmotor_fcertmotor').value='';
		document.getElementById('Tvehiculos_cmotor_fcertmotor').disabled='disabled';
		document.getElementById('Tvehiculos_cmotor_ecertmotor').value='';
		document.getElementById('Tvehiculos_cmotor_ecertmotor').disabled='disabled';
		}
	else{
		document.getElementById('2Tvehiculos_cmotor_importacion').setAttribute("class","subtotales");
		document.getElementById('2Tvehiculos_cmotor_certmotor').setAttribute("class","campoRequerido");
		document.getElementById('2Tvehiculos_cmotor_fcertmotor').setAttribute("class","campoRequerido");
		document.getElementById('2Tvehiculos_cmotor_ecertmotor').setAttribute("class","campoRequerido");
		document.getElementById('Tvehiculos_cmotor_tmotor_0').checked=true;
		document.getElementById('Tvehiculos_cmotor_importacion').value='';
		document.getElementById('Tvehiculos_cmotor_importacion').disabled='disabled';
		document.getElementById('Tvehiculos_cmotor_certmotor').disabled=false;
		document.getElementById('Tvehiculos_cmotor_fcertmotor').disabled=false;
		document.getElementById('Tvehiculos_cmotor_ecertmotor').disabled=false;
		}
	}
////  Valida que la carroceria seleccionada sea o no igual a la del vehiculo  ////
function ValidaCarroceria(){
	if(document.getElementById('Tvehiculos_CC_cnueva').value<1){
		alert("Seleccione una carroceria");
		document.getElementById('Tvehiculos_CC_cnueva').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_CC_cnueva').focus()",1);
		return false;}
	document.getElementById('Tvehiculos_CC_cnueva').className='';
	FAjax('valclasecc.php?dato='+document.getElementById('Tvehiculos_CC_cnueva').value+'|'+document.getElementById('Tvehiculos_CC_cactual').value,'1Tvehiculos_CC_cnueva','','get');
	}      
function modal2(id){
	ruta="newcolor.php?id="+id;
	var left = (screen.width/2)-(650/2);
	var top = (screen.height/2)-(500/2);
	window.open(ruta,"",'dialogHeight:500px;dialogWidth:650px;top=+top+, left=+left;center:Yes;help:No;resizable: no;status:No;');
	}
//  Validar camposs Requeridos del los tramites de RNC ////
function ValidarTramRNC(campos){
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(nombrecampo[i]=='trest'){
				var a=document.getElementById('trest').value;var b=0;
				for(j=0;j<a;j++){
					if(document.getElementById('Tciudadanos_tramites_rest'+j).checked){b=parseInt(b)+1;}
					}
				if(b<1){
					alert("Seleccione una restricci\xf3n o la opci\xf3n \'Ninguna\' para continuar");
					document.getElementById('Tciudadanos_tramites_rest0').className='campoRequerido';
					setTimeout("document.getElementById('Tciudadanos_tramites_rest0').focus()",1);
					return false;
					}
				}
			else if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	FAjax('inserttramrnc.php','instramrnc','','post');
	}
//  valida el numero de licencia de conduccion digitado si es igual al reistrado en la base de datos ////
function ValidarLicA(){
	if(document.getElementById('Tciudadanos_tramites_CatLCA').value<1){
		alert("Seleccione la categor\xeda de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_LCActual').value='';
		document.getElementById('Tciudadanos_tramites_CatLCA').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_CatLCA').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_CatLCA').className='';
	if(document.getElementById('Tciudadanos_tramites_LCActual').value.length<1){
		alert("Digite un n\xfamero de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_LCActual').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_LCActual').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_LCActual').className='';
  	var a=document.getElementById('Tciudadanos_tramites_LCActual').value;
  	var b=document.getElementById('Tciudadanos_ident').value;
  	var c=document.getElementById('Tciudadanos_tramites_CatLCA').value;
	FAjax('licc.php?dato='+c+'|'+a+'|'+b,'2Tciudadanos_tramites_LCActual','','get');
	}
//  valida el numero de licencia de conduccion digitado si es igual al reistrado en la base de datos ////
function ValidarCatA(){
	if(document.getElementById('Tciudadanos_tramites_CatLCA').value<1){
		alert("Seleccione la categor\xeda de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_LCActual').value='';
		document.getElementById('Tciudadanos_tramites_CatLCA').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_CatLCA').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_CatLCA').className='';
  	var a=document.getElementById('Tciudadanos_tramites_LCActual').value;
  	var b=document.getElementById('Tciudadanos_ident').value;
  	var c=document.getElementById('Tciudadanos_tramites_CatLCA').value;
	FAjax('catlicc.php?dato='+c+'|'+a+'|'+b,'2Tciudadanos_tramites_CatLCA','','get');
	}
//  valida el numero de licencia de conduccion digitado si es igual al reistrado en la base de datos ////
function ValidarLicN(){
	if(document.getElementById('Tciudadanos_tramites_CatLC').value<1){
		alert("Seleccione la categor\xeda de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_CatLC').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_CatLC').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_CatLC').className='';
	if(document.getElementById('Tciudadanos_tramites_LC').value.length<1){
		alert("Digite un n\xfamero de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_LC').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_LC').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_LC').className='';
  	var a=document.getElementById('Tciudadanos_tramites_LC').value;
  	var b=document.getElementById('Tciudadanos_ident').value;
  	var c=document.getElementById('Tciudadanos_tramites_CatLC').value;
  	var d=document.getElementById('Tciudadanos_tramites_liq').value;
  	var e=document.getElementById('tramite').value;
	FAjax('liccn.php?dato='+c+'|'+a+'|'+b+'|'+d+'|'+e,'2Tciudadanos_tramites_LC','','get');
	}
//  valida el numero de licencia de conduccion digitado si es igual al reistrado en la base de datos ////
function ValidarCatN(){
	if(document.getElementById('Tciudadanos_tramites_CatLC').value<1){
		alert("Seleccione la categor\xeda de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_CatLC').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_CatLC').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_CatLC').className='';
  	var a=document.getElementById('Tciudadanos_tramites_LC').value;
  	var b=document.getElementById('Tciudadanos_ident').value;
  	var c=document.getElementById('Tciudadanos_tramites_CatLC').value;
  	var d=document.getElementById('Tciudadanos_tramites_liq').value;
  	var e=document.getElementById('tramite').value;
	FAjax('catliccn.php?dato='+c+'|'+a+'|'+b+'|'+d+'|'+e,'2Tciudadanos_tramites_CatLC','','get');
	}
//  valida el numero de licencia de conduccion digitado si es igual al reistrado en la base de datos ////
function ValidarLicS(){
	if(document.getElementById('Tciudadanos_tramites_CatLC').value<1){
		alert("Seleccione la categor\xeda de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_CatLC').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_CatLC').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_CatLC').className='';
	if(document.getElementById('Tciudadanos_tramites_LC').value.length<1){
		alert("Digite un n\xfamero de licencia de Conducci\xf3n");
		document.getElementById('Tciudadanos_tramites_LC').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_LC').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_LC').className='';
	if(document.getElementById('Tciudadanos_tramites_sustrato').value.length<1){
		alert("Digite un n\xfamero de sustrato");
		document.getElementById('Tciudadanos_tramites_sustrato').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_sustrato').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_sustrato').className='';
  	var a=document.getElementById('Tciudadanos_tramites_sustrato').value;
  	var b=document.getElementById('Tciudadanos_ident').value;
  	var c=document.getElementById('Tciudadanos_tramites_CatLC').value;
  	var d=document.getElementById('Tciudadanos_tramites_liq').value;
  	var e=document.getElementById('tramite').value;
	FAjax('sustn.php?dato='+c+'|'+a+'|'+b+'|'+d+'|'+e,'2Tciudadanos_tramites_sustrato','','get');
	}
//  confirmar si desea anular la liquidacion  //
function AnulaLiq(liq){
	if(confirm('¿Est\xe1 seguro que desea ANULAR la liquidaci\xf3n?')){FAjax('camestado.php?dato='+liq,'camestado','','post');}
	else{return false;}
	}
//  valida el modelo del vehiculo digitado si es igual al reistrado en la base de datos ////
function ValidaModelo(){
	if(parseInt(document.getElementById('Tvehiculos_modelo').value)<1900){
		alert("El modelo debe ser superior a 1900");
		document.getElementById('Tvehiculos_modelo').value='';
		document.getElementById('Tvehiculos_modelo').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_modelo').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_modelo').className='';
	}
//  valida el año seleccionado y reenvia el formulario para generar los derechos de transito ////
function ValidaDT(){
	if(document.getElementById('aniotram').value<1){
		alert("Seleccione un derecho de tansito a generar");
		document.getElementById('aniotram').className='campoRequerido';
		setTimeout("document.getElementById('aniotram').focus()",1);
		return false;
		}
	document.getElementById('aniotram').className='';
	document.form.action='generadt.php';
	document.form.submit();
	}
//// habilita un div y oculta el otro ////
function detectarCarga(){
	document.getElementById("pdf").style.display="none";
	document.getElementById("cargando").style.display="block";
	}
//// Valida que el campo no sea vacio y envia los valores a php para comprobar si existen en la base de datos ////
function ValidaSustLic(nombre,valor,donde,tabla){
	if(document.getElementById(nombre).value.length<1){
		alert("Digite un valor para este campo");
		document.getElementById(nombre).className='campoRequerido';
		setTimeout("document.getElementById('"+nombre+"').focus()",1);
		return false;
		}
	document.getElementById(nombre).className='';
	var liq=document.getElementById(tabla+'_liquidacion').value;
	FAjax('valsustlic.php?dato='+nombre+'|'+valor+'|'+donde+'|'+liq,donde,'','get');
	}
//// Recoge los datos enviados en el campo liquidacion  del tramite y los envia al archivo liq3.php via ajax para consultar ciudadano y tramite ////
function BuscaLiquida3(){
	if(document.getElementById('liquidacion').value.length<1){
		alert("Digite un n\xfamero de liquidaci\xf3n");
		document.getElementById('liquidacion').className='campoRequerido';
		setTimeout("document.getElementById('liquidacion').focus()",1);
		return false;
	}  
	
	if(document.getElementById('liquidacion').value.length>3){
		document.getElementById('liquidacion').className='';
		FAjax('liq3.php?dato='+document.getElementById('liquidacion').value,'mens','','get');
	}
}
///////// validacion del sustrato para registrar que no se halla ejecutado //////
function ValidaSustLic2(nombre,valor,donde){
	tipo=document.getElementById("tipodoc").value;
	if(document.getElementById(nombre).value.length<1 || Number.isNaN(tipo)){
		alert("Digite un valor para el sustrato y para la liquidacion");
		document.getElementById(nombre).className='campoRequerido';
		setTimeout("document.getElementById('"+nombre+"').focus()",1);
		return false;
	}
	
	document.getElementById(nombre).className='';
	FAjax('valsustlic2.php?dato='+nombre+'|'+document.getElementById(nombre).value+'|'+tipo,donde,'','get');
}	
//// Valida que el usuario seleccione un tipo de ciudadano y habilita el formulario correspondiente ////
function ValidaTipoCiud(p) {
    if (document.getElementById('Tciudadanos_tipo').value < 1) {
        alert("Seleccione el tipo de ciudadano");
        document.getElementById('Tciudadanos_tipo').className = 'campoRequerido';
        setTimeout("document.getElementById('Tciudadanos_tipo').focus()", 1);
        return false;
    }
    document.getElementById('Tciudadanos_tipo').className = '';
    var e = document.getElementById('Tciudadanos_tipo').value;
    if (e === "1") {
        FAjax(p + '.php', 'nomapell', '', 'post');
    } else {
        FAjax(p + '2.php', 'nomapell', '', 'post');
    }
}
//// Valida que el numero de culp no sea vacio y envia datos a archivo php para revisar en la tabla correspondiente ////
function ValidarCulp(e,v){
	var a=e.name;
	var o=e.value;
	if(document.getElementById(a).value<1){
		alert("Digite el n\xfamero de CULP");
		document.getElementById(a).className='campoRequerido';
		setTimeout("document.getElementById(a).focus()",1);
		return false;
		}
	document.getElementById(a).className='';
	FAjax('validaculp.php?dato='+o+'|'+v+'|'+a,'validaculp','','get');
	}
//  valida el numero de motor digitado si es igual al reistrado en la base de datos ////
function ValidarMotor(tabla){
	if(document.getElementById(tabla+'_nmotor').value<1){
		alert("Digite un n\xfamero de motor");
		document.getElementById(tabla+'_nmotor').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_nmotor').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_nmotor').className='';
  	var a=document.getElementById(tabla+'_nmotor').value;
  	var b=document.getElementById(tabla+'_placa').value;
	FAjax('motor.php?dato='+a+'|'+b+'|'+tabla,'2'+tabla+'_nmotor','','get');
	}
//  valida el numero de motor digitado si es igual al reistrado en la base de datos ////
function ValidarChasis(tabla){
	if(document.getElementById(tabla+'_nchasis').value<1){
		alert("Digite un n\xfamero de motor");
		document.getElementById(tabla+'_nchasis').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_nchasis').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_nchasis').className='';
  	var a=document.getElementById(tabla+'_nchasis').value;
  	var b=document.getElementById(tabla+'_placa').value;
	FAjax('chasis.php?dato='+a+'|'+b+'|'+tabla,'2'+tabla+'_nchasis','','get');
	}
//  valida el estado de la placa y agrega funcion a los campos requeridos ////
function ValidaEstPlaca(tabla){
	if(document.getElementById(tabla+'_estadoplaca').value<1){
		alert("Seleccione un estado de la plaqueta");
		document.getElementById(tabla+'_estadoplaca').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_estadoplaca').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_estadoplaca').className='';
	if(document.getElementById(tabla+'_estadoplaca').value==1){
		document.getElementById(tabla+'_denuncia').setAttribute("onblur","CampoVacio(this)");
		document.getElementById('2'+tabla+'_denuncia').setAttribute("class","campoRequerido");
		document.getElementById('cal-'+tabla+'_fdenunciap').removeAttribute("disabled");
		document.getElementById('2'+tabla+'_fdenunciap').setAttribute("class","campoRequerido");
		}
	else{
		document.getElementById(tabla+'_denuncia').removeAttribute("onblur");
		document.getElementById('2'+tabla+'_denuncia').setAttribute("class","subtotales");
		document.getElementById('cal-'+tabla+'_fdenunciap').setAttribute("disabled","disabled");
		document.getElementById('2'+tabla+'_fdenunciap').setAttribute("class","subtotales");
		}
	}
//  valida el estado de la placa y agrega funcion a los campos requeridos ////
function ValidaEstLic(tabla){
	if(document.getElementById(tabla+'_motivo').value<1){
		alert("Seleccione un motivo de cambio de la licencia de transito");
		document.getElementById(tabla+'_motivo').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_motivo').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_motivo').className='';
	if(document.getElementById(tabla+'_motivo').value==1){
		document.getElementById(tabla+'_LTdenuncia').setAttribute("onblur","CampoVacio(this)");
		document.getElementById('cal-'+tabla+'_fechadenuncia').setAttribute("onblur","CampoVacio(this)");
		}
	else{
		document.getElementById(tabla+'_LTdenuncia').setAttribute("onblur","");
		document.getElementById('cal-'+tabla+'_fechadenuncia').setAttribute("onblur","");
		}
	}
//  valida el estado de la placa y agrega funcion a los campos requeridos ////
function ValidarResolucion(tabla){
	if(document.getElementById(tabla+'_resolu').value.length<1){
		alert("Digite un n\xfamero de resoluci\xf3n de cancelaci\xf3n de matr\xedcula");
		document.getElementById(tabla+'_resolu').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_resolu').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_resolu').className='';
	if(document.getElementById(tabla+'_placa').value.length<1){
		alert("Digite un n\xfamero de placa");
		document.getElementById(tabla+'_placa').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_placa').focus()",1);
		}
	document.getElementById(tabla+'_placa').className='';
	var a=document.getElementById(tabla+'_resolu').value;
	var b=document.getElementById(tabla+'_placa').value;
	FAjax('resolmatri.php?dato='+a+'|'+b+'|'+tabla,'2'+tabla+'_resolu','','get');
	}
//  valida campo modificacion y agrega funcion a los campos requeridos ////
function ValidaModifica(tabla){
	if(document.getElementById(tabla+'_modifica').value<1){
		alert("Seleccione una opci\xf3n (SI/NO) el veh\xedculo recuperado ha sufrido modificaciones en sus caracter\xedsticas");
		document.getElementById(tabla+'_modifica').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_modifica').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_modifica').className='';
	if(document.getElementById(tabla+'_modifica').value==2){
		alert("NO puede continuar, debe realizar previamente el tr\xe1mite que legalice la modificaci\xf3n del veh\xedculo");
		document.getElementById(tabla+'_modifica').value='';
		document.getElementById(tabla+'_modifica').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_modifica').focus()",1);
		}
	}
//  valida campo motivo y agrega funcion a los campos requeridos ////
function ValidaMotivo(tabla){
	if(document.getElementById(tabla+'_motivo').value<1){
		alert("Seleccione un motivo del duplicado de la placa");
		document.getElementById(tabla+'_motivo').className='campoRequerido';
		setTimeout("document.getElementById(tabla+'_motivo').focus()",1);
		return false;
		}
	document.getElementById(tabla+'_motivo').className='';
	if(document.getElementById(tabla+'_motivo').value==1){
		document.getElementById(tabla+'_denuncia').setAttribute("onblur","CampoVacio(this)");
		document.getElementById('cal-'+tabla+'_fdenuncia').setAttribute("onblur","CampoVacio(this)");
		}
	else{
		document.getElementById(tabla+'_denuncia').setAttribute("onblur","");
		document.getElementById('cal-'+tabla+'_fdenuncia').setAttribute("onblur","");
		}
	}
//// Valida que el usuario seleccione un tipo de ciudadano y habilita el formulario correspondiente ////
function BuscarCiudadano(p){
	if(document.getElementById('Tciudadanos_tipo').value<1){
		alert("Seleccione el tipo de ciudadano");
		document.getElementById('Tciudadanos_tipo').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tipo').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tipo').className='';
	if(document.getElementById('tipodoc').value<1){
		alert("Seleccione el tipo de documento del ciudadano");
		document.getElementById('tipodoc').className='campoRequerido';
		setTimeout("document.getElementById('tipodoc').focus()",1);
		return false;
		}
	document.getElementById('tipodoc').className='';
	if(document.getElementById('identificacion').value<1){
		alert("Digite el n\xfamero de documento del ciudadano");
		document.getElementById('identificacion').className='campoRequerido';
		setTimeout("document.getElementById('identificacion').focus()",1);
		return false;
		}
	document.getElementById('identificacion').className='';
	var a=document.getElementById('identificacion').value;
	var e=document.getElementById('Tciudadanos_tipo').value;
	if(e=1){FAjax(p+'.php?dato='+a,'nomapell','','post');}
	else{FAjax(p+'2.php?dato='+a,'nomapell','','post');}
	}
//// Valida que el usuario seleccione un tipo de ciudadano y habilita el formulario correspondiente ////
function BuscarCiudadanoLicencia(){
	if(document.getElementById('Tciudadanos_tipo').value<1){
		alert("Seleccione el tipo de ciudadano");
		document.getElementById('Tciudadanos_tipo').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tipo').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tipo').className='';
	if(document.getElementById('tipodoc').value<1){
		alert("Seleccione el tipo de documento del ciudadano");
		document.getElementById('tipodoc').className='campoRequerido';
		setTimeout("document.getElementById('tipodoc').focus()",1);
		return false;
		}
	document.getElementById('tipodoc').className='';
	if(document.getElementById('identificacion').value<1){
		alert("Digite el n\xfamero de documento del ciudadano");
		document.getElementById('identificacion').className='campoRequerido';
		setTimeout("document.getElementById('identificacion').focus()",1);
		return false;
		}
	document.getElementById('identificacion').className='';
	var a=document.getElementById('identificacion').value;
	var e=document.getElementById('Tciudadanos_tipo').value;
	if(e=1){FAjax('nomalicencia.php?dato='+a,'nomapell','','post');}
	else{FAjax('nomalicencia2.php?dato='+a,'nomapell','','post');}
	}
//// Validar los campos del cuidadano y enviar el formulario para realizar insert y updete ////
function ValidarCiudadano(campos){
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	if(confirm('¿Est\xe1 seguro que desea guardar la informaci\xf3n?')){
		document.getElementById('aplica').value='1';
		document.form.action='ciudadano.php';
		document.form.submit();
		}
	else{document.getElementById('aplica').value='';}
	}
//// Validar los campos del cuidadano y enviar el formulario para realizar insert y updete ////
function ValidarCiudadanoLicencia(campos){
	var nombrecampo=campos.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	if(confirm('¿Est\xe1 seguro que desea guardar la informaci\xf3n?')){
		document.getElementById('aplica').value='1';
		document.form.action='ciudadanolicencia.php';
		document.form.submit();
		}
	else{document.getElementById('aplica').value='';}
	}
//  valida el estado de la placa y agrega funcion a los campos requeridos ////
function ValidarCategoria(){
	if(document.getElementById('Tvehiculos_AVE_catins').value<1){
		alert("Seleccione una categor\xeda de instrucci\xf3n");
		document.getElementById('Tvehiculos_AVE_catins').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_AVE_catins').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_AVE_catins').className='';
	if(document.getElementById('Tvehiculos_AVE_placa').value.length<1){
		alert("Digite un n\xfamero de placa");
		document.getElementById('Tvehiculos_AVE_placa').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_AVE_placa').focus()",1);
		}
	document.getElementById('Tvehiculos_AVE_placa').className='';
	var a=document.getElementById('Tvehiculos_AVE_catins').value;
	var b=document.getElementById('Tvehiculos_AVE_placa').value;
	FAjax('catens.php?dato='+a+'|'+b,'2Tvehiculos_AVE_catins','','get');
	}
//// Validar formulario tramite medida cautelar ////
function ValidarMedidaC(campos, nv) {
    var nombrecampo = campos.split(",");
    var tmc = document.getElementById('tipomc').value;
    if (tmc === "1") {
        numvhmc = 'Tvehiculos_mc_placa';
        msgmc = 'Seleccione un vehiculo para inscribir una medida cautelar';
    } else if (tmc === "0") {
        numvhmc = 'Tvehiculos_mc_levantar';
        msgmc = 'Seleccione un medida para medida cautelar para levantar';
    } else {
        alert("Seleccione el tipo de acción de la medida cautelar");
        document.getElementById('tipomc').focus();
        return false;
    }
    var nv = document.getElementById('nvehic').value;
    if (nv > 0) {
        var a = 0;
        for (var h = 0; h < nv; h++) {
            if (document.getElementById(numvhmc + h).checked) {
                a = a + 1;
            }
        }
        if (a < 1) {
            alert(msgmc);
            return false;
        }
    }else{
        alert('No hay medidas cautelares que se puedan levantar al vehiculo.');
    }
    for (var i = 0; i < nombrecampo.length; i++) {
        if (document.getElementById(nombrecampo[i]).value.length < 1) {
            alert("Este campo no debe ser vacio");
            document.getElementById(nombrecampo[i]).className = 'campoRequerido';
            setTimeout("document.getElementById(nombrecampo[i]).focus()", 1);
            return false;
        }
        document.getElementById(nombrecampo[i]).className = '';
    }
    FAjax('insertmc.php', 'medidacautelar', '', 'post');
}
//// Validar campo departamento y enviar dato para mostrar ciudades ////
function ValidaDepto(){
	if(document.getElementById('Tvehiculos_mc_departamento').value.length<1){
		alert("Este campo no debe ser vacio");
		document.getElementById('Tvehiculos_mc_departamento').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_mc_departamento').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_mc_departamento').className='';
	var a=document.getElementById('Tvehiculos_mc_departamento').value;
	FAjax('municip.php?dato='+a,'municipio','','post');
	}
//// Validar campo departamento y enviar dato para mostrar ciudades ////
function ValidaChasis(){
	if(document.getElementById('Tvehiculos_chasis').value.length<1){
		alert("Digite el n\xfamero de chasis");
		document.getElementById('Tvehiculos_chasis').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_chasis').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_chasis').className='';
	var a=document.getElementById('Tvehiculos_chasis').value;
	var p=document.getElementById('Tvehiculos_placa').value;
	FAjax('validami.php?chasis='+a+'|'+p,'chasis','','post');
	}
//// Validar campo departamento y enviar dato para mostrar ciudades ////
function ValidaMotor(){
	if(document.getElementById('Tvehiculos_chasis').value.length<1){
		alert("Digite el n\xfamero de chasis");
		document.getElementById('Tvehiculos_chasis').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_chasis').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_chasis').className='';
	if(document.getElementById('Tvehiculos_motor').value.length<1){
		alert("Digite el n\xfamero de motor");
		document.getElementById('Tvehiculos_motor').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_motor').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_motor').className='';
	var a=document.getElementById('Tvehiculos_motor').value;
	var p=document.getElementById('Tvehiculos_placa').value;
	FAjax('validami.php?motor='+a+'|'+p,'motor','','post');
	}
//// Validar campo departamento y enviar dato para mostrar ciudades ////
function ValidaMarca(){
	if(document.getElementById('Tvehiculos_chasis').value.length<1){
		alert("Digite el n\xfamero de chasis");
		document.getElementById('Tvehiculos_chasis').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_chasis').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_chasis').className='';
	if(document.getElementById('Tvehiculos_motor').value.length<1){
		alert("Digite el n\xfamero de motor");
		document.getElementById('Tvehiculos_motor').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_motor').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_motor').className='';
	if(document.getElementById('Tvehiculos_marca').value.length<1){
		alert("Seleccione la marca");
		document.getElementById('Tvehiculos_marca').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_marca').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_marca').className='';
	var a=document.getElementById('Tvehiculos_marca').value;
	var p=document.getElementById('Tvehiculos_placa').value;
	FAjax('validami.php?marca='+a+'|'+p,'marca','','post');
	}
//// Validar campo departamento y enviar dato para mostrar ciudades ////
function ValidaLinea(){
	if(document.getElementById('Tvehiculos_chasis').value.length<1){
		alert("Digite el n\xfamero de chasis");
		document.getElementById('Tvehiculos_chasis').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_chasis').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_chasis').className='';
	if(document.getElementById('Tvehiculos_motor').value.length<1){
		alert("Digite el n\xfamero de motor");
		document.getElementById('Tvehiculos_motor').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_motor').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_motor').className='';
	if(document.getElementById('Tvehiculos_marca').value<1){
		alert("Seleccione la marca");
		document.getElementById('Tvehiculos_marca').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_marca').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_marca').className='';
	if(document.getElementById('Tvehiculos_linea').value<1){
		alert("Seleccione la l\xednea");
		document.getElementById('Tvehiculos_linea').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_linea').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_linea').className='';
	var a=document.getElementById('Tvehiculos_linea').value;
	var p=document.getElementById('Tvehiculos_placa').value;
	FAjax('validami.php?linea='+a+'|'+p,'lineas','','post');
	}
//  valida el numero de certificado de enseñanaza digitado no sea igual al reistrado en la base de datos ////
function ValidaEnsena(){
	if(document.getElementById('Tciudadanos_tramites_CE').value.length<1){
		alert("Digite un n\xfamero de certificado de ense\xf1anza");
		document.getElementById('Tciudadanos_tramites_CE').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_CE').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_CE').className='';
  	var a=document.getElementById('Tciudadanos_tramites_CE').value;
	FAjax('certens.php?dato='+a+'|CE','2Tciudadanos_tramites_CE','','get');
	}
//  valida el numero de certificado MEDICO digitado no sea igual al reistrado en la base de datos ////
function ValidaMedico(){
	if(document.getElementById('Tciudadanos_tramites_CM').value.length<1){
		alert("Digite un n\xfamero de certificado m\xe9dico");
		document.getElementById('Tciudadanos_tramites_CM').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tramites_CM').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tramites_CM').className='';
  	var a=document.getElementById('Tciudadanos_tramites_CM').value;
	FAjax('certens.php?dato='+a+'|CM','2Tciudadanos_tramites_CM','','get');
	}
//  valida los campos del formulario filtro informe comparendos ////
function ValidaInfoComp(){
	var a=0;
	if(document.getElementById('identificacion').value.length>0){a+=1;}
	if(document.getElementById('placa').value.length>0){a+=1;}
	if(document.getElementById('comparendo').value.length>0){a+=1;}
	if(document.getElementById('liquidacion').value.length>0){a+=1;}
	if(document.getElementById('fechainicial').value.length>0){a+=1;}
	if(document.getElementById('fechafinal').value.length>0){a+=1;}
	if(a<1){
		alert("Digite o seleccione un filtro para generar el informe");
		document.getElementById('identificacion').className='campoRequerido';
		setTimeout("document.getElementById('identificacion').focus()",1);
		return false;
		}
	document.getElementById('identificacion').className='';
	document.form.action='infocompa.php';
	document.form.submit();
	}
//  valida los campos del formulario filtro informe comparendos ////
function ValidaEV(pagina){
	var campos=document.getElementById('campos').value;
	var camporeqv=document.getElementById('camporeqv').value;
	var camporeqpv=document.getElementById('camporeqpv').value;
	var camporeqt=document.getElementById('camporeqt').value;
	var campreq=campos+camporeqv+camporeqpv+camporeqt;
	var nombrecampo=campreq.split(",");
	console.log(nombrecampo);
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout("document.getElementById('"+nombrecampo[i]+"').focus()",1);
				document.getElementById('aplicar').value='';
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	document.getElementById('aplicar').value='OK';
	document.form.action=pagina;
	document.form.submit();
	}

//  valida el campo cinicial si no es tramite placa ////
function ValidaCinicial(v){
	if(document.getElementById('cinicial').value.length<1){
		alert("Este campo no debe ser vacio");
		document.getElementById('cinicial').className='campoRequerido';
		setTimeout("document.getElementById('cinicial').focus()",1);
		return false;
		}
	document.getElementById('cinicial').className='';
	document.getElementById('ver').value=v;
	ValidaCant();
	document.form.action='EVsustratos.php';
	document.form.submit();
	}
//  valida el campo cinicial en la entrega de comparendos ////
function ValidaCinicialE(){
	if(document.getElementById('cinicial').value.length<1){
		alert("Este campo no debe ser vacio");
		document.getElementById('cinicial').className='campoRequerido';
		setTimeout("document.getElementById('cinicial').focus()",1);
		return false;
		}
	document.getElementById('cinicial').className='';
	var v = document.getElementById('cinicial').value;
	document.getElementById('ver').value=v;
	ValidaCant();
	FAjax('validaevce.php','validae','','post');
	/*document.form1.action='EVCentrega.php';
	document.form1.submit();*/
	}
//  valida el campo cinicial en la consulta de entrega de comparendos ////
function ValidaCinicialC(){
	if(document.getElementById('cinicial').value.length<1){
		alert("Este campo no debe ser vacio");
		document.getElementById('cinicial').className='campoRequerido';
		setTimeout("document.getElementById('cinicial').focus()",1);
		return false;
		}
	document.getElementById('cinicial').className='';
	var v = document.getElementById('cinicial').value;
	document.getElementById('ver').value=v;
	ValidaCant();
	FAjax('validaevcc.php','validae','','post');
	/*document.form1.action='EVCentrega.php';
	document.form1.submit();*/
	}
//  valida el campo cinicial si es placa ////
function ValidaCinicialP(v){
	if(document.getElementById('letras').value.length<1){
		alert("Este campo no debe ser vacio");
		document.getElementById('letras').className='campoRequerido';
		setTimeout("document.getElementById('letras').focus()",1);
		return false;
		}
	document.getElementById('letras').className='';
	if(document.getElementById('cinicial').value.length<1){
		alert("Este campo no debe ser vacio");
		document.getElementById('cinicial').className='campoRequerido';
		setTimeout("document.getElementById('cinicial').focus()",1);
		return false;
		}
	document.getElementById('cinicial').className='';
	var a=document.getElementById('letras').value;
	var b=a+v;
	FAjax('validarplaca.php?dato='+b,'validarplaca','','get');
	/*document.form1.action='EVsustratos.php';
	document.form1.submit();*/
	}
//  Valida si el campo cinicial y cfinal estan vacios y los suma para hallar la cantidad de registros a insertar o actualizar ////
function ValidaCant(){
	if(document.getElementById('cinicial').value.length<1){
		alert("Este campo no debe ser vacio");
		document.getElementById('cinicial').className='campoRequerido';
		setTimeout("document.getElementById('cinicial').focus()",1);
		return false;
		}
	document.getElementById('cinicial').className='';
	var a=document.getElementById('cinicial').value;
	if(document.getElementById('cfinal').value.length<1){var b=0;var c=1;}
	else{
		var b=document.getElementById('cfinal').value;
		if((parseInt(a))>(parseInt(b))){
			alert("El valor Inicial no puede ser mayor al final!!!");
			document.getElementById('cfinal').className='campoRequerido';
			setTimeout("document.getElementById('cfinal').focus()",1);
			return false;
			}
		else{
			var d=parseInt(b)+1;
			var c=parseInt(d)-parseInt(a);
			}
		}	
	document.getElementById('TEVadmin_cantidad').value=c;
	}
//  Valida que el tipo de servicio sea diferente de 0 y habilita los campo si es publico ////
function ValidaServicio(){
	if(document.getElementById('Tcomparendos_servicio').value<1){
		alert("Seleccione el tipo de servicio del veh\xedculo");
		document.getElementById('Tcomparendos_servicio').className='campoRequerido';
		setTimeout("document.getElementById('Tcomparendos_servicio').focus()",1);
		return false;
		}
	document.getElementById('Tcomparendos_servicio').className='';
	document.getElementById('Tcomparendos_modalidad').value='';
	document.getElementById('Tcomparendos_tipopasajero').disabled=true;
	if(document.getElementById('Tcomparendos_servicio').value==2){
		//CampoVisibleOculto('Tcomparendos_solemp','solemp');
		//document.getElementById('solemp').style.display='block';
		document.getElementById('Tcomparendos_TO').disabled=false;
		document.getElementById('Tcomparendos_radio').disabled=false;
		document.getElementById('Tcomparendos_empresa').disabled=false;
		}
	else{
		document.getElementById('Tcomparendos_TO').disabled=true;
		//document.getElementById('solemp').style.display='none';
		document.getElementById('Tcomparendos_radio').disabled=true;
		document.getElementById('Tcomparendos_empresa').disabled=true;
		}}
//  Valida que la modalidad sea diferente de 0 y habilita los campo si es publico y modalidad pasajeros ////
function ValidaModalidad(){
	if(document.getElementById('Tcomparendos_servicio').value<1){
		alert("Seleccione el tipo de servicio del veh\xedculo");
		document.getElementById('Tcomparendos_servicio').className='campoRequerido';
		document.getElementById('Tcomparendos_modalidad').value='';
		setTimeout("document.getElementById('Tcomparendos_servicio').focus()",1);
		return false;
		}
	document.getElementById('Tcomparendos_servicio').className='';
	if(document.getElementById('Tcomparendos_modalidad').value<1){
		alert("Seleccione la modalidad del veh\xedculo");
		document.getElementById('Tcomparendos_modalidad').className='campoRequerido';
		setTimeout("document.getElementById('Tcomparendos_modalidad').focus()",1);
		return false;
		}
	document.getElementById('Tcomparendos_modalidad').className='';
	if((document.getElementById('Tcomparendos_servicio').value==2)&&(document.getElementById('Tcomparendos_modalidad').value==1)){
		document.getElementById('Tcomparendos_tipopasajero').disabled=false;
		}
	else{
		document.getElementById('Tcomparendos_tipopasajero').disabled=true;
		}}
//  valida el numero de certificado MEDICO digitado no sea igual al reistrado en la base de datos ////
function ValorCompa(){
	if(document.getElementById('Tcomparendos_fecha').value.length<1){
		alert("Seleccione la fecha y hora de infracci\xf3n");
		document.getElementById('Tcomparendos_fecha').className='campoRequerido';
		setTimeout("document.getElementById('Tcomparendos_fecha').focus()",1);
		return false;
		}
	document.getElementById('Tcomparendos_fecha').className='';
	if(document.getElementById('Tcomparendos_codinfraccion').value.length<1){
		alert("Seleccione un c\xf3digo de infrecci\xf3n");
		document.getElementById('Tcomparendos_codinfraccion').className='campoRequerido';
		setTimeout("document.getElementById('Tcomparendos_codinfraccion').focus()",1);
		return false;
		}
	if(document.getElementById('Tcomparendos_codinfraccion').value=="E3" || document.getElementById('Tcomparendos_codinfraccion').value=="E3_1" ||
	   document.getElementById('Tcomparendos_codinfraccion').value=="F")
	   {
		   	document.getElementById('gradoalcohol').disabled=false;
			document.getElementById('reincidencia').disabled=false;
			document.getElementById('gradoalcohol').value=0;
	   		document.getElementById('reincidencia').value=1;} 
	   else{
			document.getElementById('gradoalcohol').value=10;
			document.getElementById('reincidencia').value=0;
			document.getElementById('gradoalcohol').disabled=true;
			document.getElementById('reincidencia').disabled=true;
		}
	
	document.getElementById('Tcomparendos_codinfraccion').className='';
  	var a=document.getElementById('Tcomparendos_codinfraccion').value;
  	var b=document.getElementById('Tcomparendos_fecha').value;
	var c=document.getElementById('gradoalcohol').value;
	var d=document.getElementById('reincidencia').value;
	FAjax('valcompa.php?dato='+a+'|'+b+'|'+c+'|'+d,'2Tcomparendos_codinfraccion','','get');
	}
	
//// funcion que calcula el valor de un comparendo pero solo cuando ya existe el comparendo //// 
    function ValorCompa1(historico) {
        var a = document.getElementById('Tcomparendos_codinfraccion').value;
        var b = document.getElementById('Tcomparendos_fecha').value;
        var c = document.getElementById('gradoalcohol').value;
        var d = document.getElementById('reincidencia').value;
        var f = document.getElementById('verificacion').value;
        FAjax('valcompa.php?dato=' + a + '|' + b + '|' + c + '|' + d, '2Tcomparendos_codinfraccion', '', 'get');
        if (historico) {
            FAjax('valregistros.php?inf=' + window.btoa('buscar=' + f), 'registros', '', 'get');
        }
    }
//// funcion que abre una ventana emergente tipo modal para agregar nuevo agente de transito ////     
function NewDato(pag) {
    var left = (screen.width / 2) - (650 / 2);
    var top = (screen.height / 2) - (500 / 2);
    w = 750;
    h = 500;
    if (window.showModalDialog) {
        window.showModalDialog(pag, "", "dialogWidth:" + w + "px;dialogHeight:" + h + "px;top=" + top + ", left=" + left + ";");
    } else {
        window.open(pag, '', 'height=' + h + ',width=' + w + ',top=' + top + ', left=' + left + ',toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no ,modal=yes');
    }
}
//// Validar campo numero de documento del infrector y envia el dato  para validar si existe para traer los datos ////
function ValidaInfractor(a,b,c){
	if(document.getElementById(a).value.length<1){
		alert("Seleccione el tipo de documento");
		document.getElementById(a).value='';
		document.getElementById(b).className='campoRequerido';
		setTimeout("document.getElementById(a).focus()",1);
		return false;
		}
	document.getElementById(a).className='';
	if(document.getElementById(b).value.length<1){
		alert("Digite el n\xfamero de documento del infractor");
		document.getElementById(b).className='campoRequerido';
		setTimeout("document.getElementById('"+b+"').focus()",1);
		return false;
		}
	document.getElementById(b).className='';
	var a=document.getElementById(b).value;
	FAjax(c+'.php?dato='+a,c,'','get');
	
	var reincidencia = $.ajax({
 
            url: 'reincidencia.php?infractor='+a, //indicamos la ruta donde se genera el conteo de reincidencia
                dataType: 'int',//indicamos que es de tipo int
                async: false     //ponemos el parámetro asyn a falso
        }).responseText;
 
        //actualizamos el campo reincidencia
        document.getElementById("reincidencia").value = parseInt(document.getElementById("reincidencia").value)+parseInt(reincidencia);
		
	var a=document.getElementById('Tcomparendos_codinfraccion').value;
  	var b=document.getElementById('Tcomparendos_fecha').value;
	var c=document.getElementById('gradoalcohol').value;
	var d=document.getElementById('reincidencia').value;	
	FAjax('valcompa.php?dato='+a+'|'+b+'|'+c+'|'+d,'2Tcomparendos_codinfraccion','','get');

	}
//// Validar campo numero de documento del infrector y envia el dato  para validar si existe para traer los datos ////
function ValidaPropietario(a,b,c,d){
	if(document.getElementById(b).value.length>0){
		if(document.getElementById(a).value.length<1){
			alert("Seleccione el tipo de documento");
			document.getElementById(b).value='';
			document.getElementById(a).className='campoRequerido';
			setTimeout("document.getElementById('"+a+"').focus()",1);
			return false;
			}
		document.getElementById(a).className='';
		}
	else{document.getElementById(a).value='';}
	var a=document.getElementById(b).value;
	FAjax(c+'.php?dato='+a,c,'','get');
	}
//  valida el numero de licencia de conduccion digitado si es igual al registrado en la base de datos ////
function ValidarLicCond(){
	if(document.getElementById('Tciudadanos_catLC_a').value<1){
			alert("Seleccione la categor\xeda de licencia de Conducci\xf3n");
			document.getElementById('Tciudadanos_catLC_a').value='';
			document.getElementById('Tciudadanos_catLC_a').className='campoRequerido';
			setTimeout("document.getElementById('Tciudadanos_catLC_a').focus()",1);
			return false;
		}
	document.getElementById('Tciudadanos_catLC_a').className='';
	if(document.getElementById('Tciudadanos_licencia_a').value.length<1){
			alert("Digite un n\xfamero de licencia de Conducci\xf3n");
			document.getElementById('Tciudadanos_licencia_a').className='campoRequerido';
			setTimeout("document.getElementById('Tciudadanos_licencia_a').focus()",1);
			return false;
		}
	document.getElementById('Tciudadanos_licencia_a').className='';
  	var d=document.getElementById('Tciudadanos_licencia_a').value;
  	var e=document.getElementById('Tcomparendos_idinfractor').value;
  	var f=document.getElementById('Tciudadanos_catLC_a').value;
	//alert(f+' '+d+' '+e);
	FAjax('licc.php?dato='+f+'|'+d+'|'+e,'2Tciudadanos_licencia_a','','get');}
//// Valida y calcula la fecha de guia la fecha de notificacion  /////
function ValidaFechaN(a){
	alert(a);
	}
//  Valida que el tipo de INFRACTOR sea diferente de 0 y habilita los campo si es DIFERENTE DE PEATON ////
//// 1=CONDUCTOR - 2=MOTOCICLISTA - 3=CICLISTA - 4 PEATON ////
function ValidaTipoInfrac(){
	if(document.getElementById('Tcomparendos_tipoinfractor').value<1){
		alert("Seleccione el tipo de servicio del veh\xedculo");
		document.getElementById('Tcomparendos_tipoinfractor').className='campoRequerido';
		setTimeout("document.getElementById('Tcomparendos_tipoinfractor').focus()",1);
		return false;
		}
	document.getElementById('Tcomparendos_tipoinfractor').className='';
	if((document.getElementById('Tcomparendos_tipoinfractor').value==2)||(document.getElementById('Tcomparendos_tipoinfractor').value==3)||(document.getElementById('Tcomparendos_tipoinfractor').value==5)){
		document.getElementById('datveh').style.display='none';
		FAjax('datveh2.php','datveh','','get');
		document.getElementById('datpveh').style.display='none';
		FAjax('datpveh2.php','datpveh','','get');
		}
	else{
		document.getElementById('datveh').style.display='block';
		FAjax('datveh.php','datveh','','get');
		document.getElementById('datpveh').style.display='block';
		FAjax('datpveh.php','datpveh','','get');
		}}
//// Validar el numero de comparendo si existe en la tabla Tcomparendos y si el estado es valido para ser sancionado /////
function ValidaCompSan(){
	if(document.getElementById('Tcomparendos_comparendo').value.length<1){
		alert("Digite el n\xfamero de comparendo");
		document.getElementById('Tcomparendos_comparendo').className='campoRequerido';
		setTimeout("document.getElementById('Tcomparendos_comparendo').focus()",1);
		return false;
		}
	FAjax('compsan.php','compsan','','POST');
	}
//// Validar el valor adicional simit y recarga la pagina para mantener el valor y calcularnuevamente en liquidacion comparendos /////
function AgregarValor(n){
	if(document.getElementById('Tconceptos_valor'+n).value.length<1){
		alert("Digite el valor del concepto");
		document.getElementById('Tconceptos_valor'+n).className='campoRequerido';
		setTimeout("document.getElementById('Tconceptos_valor'+n).focus()",1);
		return false;
		}
	document.getElementById('Tconceptos_valor'+n).className='';
	ResetDatos();
	var a=document.getElementById('identificacion').value;
	FAjax('comparendos.php?dato='+a,'comparendos','','post');
	}
//// Validar el valor adicional simit y recarga la pagina para mantener el valor y calcularnuevamente en acuerdos de pago /////
function AgregarValorAP(n){
	if(document.getElementById('Tconceptos_valor'+n).value.length<1){
		alert("Digite el valor del concepto");
		document.getElementById('Tconceptos_valor'+n).className='campoRequerido';
		setTimeout("document.getElementById('Tconceptos_valor'+n).focus()",1);
		return false;
		}
	document.getElementById('Tconceptos_valor'+n).className='';
	var a=document.getElementById('identificacion').value;
	document.form.action='ap.php';
	document.form.submit();
	}
//// Valida que el campo este checked y suma el valor al total del comparendo ////
function SumarPatioGrua(a){
	var e=a.name;var i=a.value;
	var o=Extract(document.getElementById('valortotalt').value);
	//alert(o);
	if(document.getElementById(e).checked){
		var u=parseInt(Extract(o))+parseInt(Extract(i));
		document.getElementById('valortotalt').value=formatCurrency(u);
		}
	else{
		var u=parseInt(Extract(o))-parseInt(Extract(i));
		document.getElementById('valortotalt').value=formatCurrency(u);
		}
	}
//  valida los campos del formulario filtro salida de patios ////
function ValidaInfoSalP(p){
	var a=0;
	if(document.getElementById('identificacion').value.length>0){a+=1;}
	if(document.getElementById('placa').value.length>0){a+=1;}
	if(document.getElementById('comparendo').value.length>0){a+=1;}
	if(document.getElementById('fechainicial').value.length>0){a+=1;}
	if(document.getElementById('fechafinal').value.length>0){a+=1;}
	if(a<1){
		document.getElementById('aplica').value='';
		alert("Digite o seleccione un filtro para generar el informe");
		document.getElementById('identificacion').className='campoRequerido';
		setTimeout("document.getElementById('identificacion').focus()",1);
		return false;
		}
	document.getElementById('identificacion').className='';
	document.getElementById('aplica').value='1';
	document.form.action=p;
	document.form.submit();
	}
//  valida el campo liquidacion en salida de patios y envia datos para verificar si existe una liquidacion para este concepto ////
function ValidaLiqPatios(a,b,c,g){
	var e=a.name;var i=a.value;
	if(document.getElementById(e).value.length<1){
		alert("Digite el n\xfamero de liquidaci\xf3n");
		document.getElementById(e).className='campoRequerido';
		//setTimeout("document.getElementById('"+e+"').focus()",1);
		return false;
		}
	document.getElementById(e).className='';
	var f=document.getElementById(b).value;
	FAjax('valliqpatio.php?dato='+i+'|'+f+'|'+e+'|'+c+'|'+g,'2'+e,'','POST');
	}
//// Valida el formulario de salida de patios y envia la informcion para actualizar las tablas en la base de datos ////
function ValidaSalidaPatios(a){
	var nombrecampo=a.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				document.getElementById('aplica2').value='';
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout(document.getElementById(nombrecampo[i]).focus(),1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	if(confirm('¿Est\xe1 seguro que desea Agregar/Actualizar el registro?')){
		document.getElementById('aplica2').value='1';
		document.form.action='salidapatios.php';
		document.form.submit();
		}
	else{document.getElementById('aplica').value='';}
	}
//  valida el campo liquidacion en salida de patios y envia datos para verificar si existe una liquidacion para este concepto ////
function ValidaCarga(){
	if(document.getElementById('archivo').value.length<1){
		alert("Seleccione el archivo a cargar");
		document.getElementById('archivo').className='campoRequerido';
		setTimeout("document.getElementById('archivo').focus()",1);
		return false;
		}
	document.getElementById('archivo').className='';
	if(document.getElementById('separador').value.length<1){
		alert("Seleccione el separador de campos del archivo");
		document.getElementById('separador').className='campoRequerido';
		setTimeout("document.getElementById('separador').focus()",1);
		return false;
		}
	document.getElementById('separador').className='';
	if(document.getElementById('archivo2').value.length<1){
		alert("Seleccione el archivo PDF de la carta u oficio");
		document.getElementById('archivo2').className='campoRequerido';
		setTimeout("document.getElementById('archivo2').focus()",1);
		return false;
		}
	document.getElementById('archivo2').className='';
	/*document.form.action='recaudoext.php';
	document.form.submit();*/
	}
//  valida el campo liquidacion en salida de patios y envia datos para verificar si existe una liquidacion para este concepto ////
function ValidaCargaI(){
	if(document.getElementById('archivo').value.length<1){
		alert("Seleccione el archivo a cargar");
		document.getElementById('archivo').className='campoRequerido';
		setTimeout("document.getElementById('archivo').focus()",1);
		return false;
		}
	document.getElementById('archivo').className='';
	if(document.getElementById('separador').value.length<1){
		alert("Seleccione el separador de campos del archivo");
		document.getElementById('separador').className='campoRequerido';
		setTimeout("document.getElementById('separador').focus()",1);
		return false;
		}
	document.getElementById('separador').className='';
	/*document.form.action='recaudoext.php';
	document.form.submit();*/
	}
//  Marca o desmarca todos los checkbox del formulario ////
function CheckOnCheck() {
    $('input:checkbox').attr('checked', $('#todos').attr('checked'));
}
// Valida al menos 1 checkbox este marcado para enviar el formulario ////
function ValidaExporComp(){
    var b = $('input:checked').length;
    if (b < 1) {
        alert('Debe marcar un registro para continuar');
        return false;
    } else {
        return true;
    }
}
// Valida al menos 1 checkbox este marcado para enviar el formulario ////
function ValidaExporRes() {
    var b = $('input:checked').length;
    if (b < 1) {
        alert('Debe marcar un registro continuar');
        return false;
    } else {
        return true;
    }
}
// Valida al menos 1 checkbox este marcado para enviar el formulario ////
function ValidaExporRec(){
    var b = $('input:checked').length;
    if (b < 1) {
        alert('Debe marcar un registro continuar');
        return false;
    } else {
        return true;
    }
}
//// valida el tipo de deuda, habilita demas filtros  y recarga el combo estado deacuerdo al tipo de deuda seleccionado ////
function TipoDeuda(v){
	var e=v.name;var a=v.value;
	if(document.getElementById(e).value.length<1){
		alert("Seleccione el tipo de deuda");
		document.getElementById(e).className='campoRequerido';
		setTimeout("document.getElementById('"+e+"').focus()",1);
		return false;
		}
	document.getElementById(e).className='';
	//document.getElementById('fechas').style.display='block';
	FAjax('estfiltro.php?dato='+a,'estado','','POST');
	}
//  Marca o desmarca todos los checkbox del formulario aplicar honorarios ////
function CheckOnCheckh(){
	var a = document.getElementById('todosh').value;
	if(document.getElementById('todosh').checked){
		document.getElementById('todosh2').checked=false;
		for(var i=0; i<a; i++){
			if(document.getElementById('hono'+i) && document.getElementById('hono'+i).disabled == false){
				document.getElementById('honod'+i).checked=false;
				document.getElementById('hono'+i).checked=true;
			}
		}
	}else{
		for(var i=0; i<a; i++){
			if(document.getElementById('hono'+i)){document.getElementById('hono'+i).checked=false;}
			}
		}
	}
//  Marca o desmarca todos los checkbox del formulario aplicar honorarios ////
function CheckOnCheckh2(){
	var a = document.getElementById('todosh2').value;
	if(document.getElementById('todosh2').checked){
		document.getElementById('todosh').checked=false;
		for(var i=0; i<a; i++){
			if(document.getElementById('honod'+i)){
				document.getElementById('honod'+i).checked=true;
			}
			if (document.getElementById('hono'+i)){
				document.getElementById('hono'+i).checked=false;
			}
		}
	}else{
		for(var i=0; i<a; i++){
			if(document.getElementById('honod'+i)){document.getElementById('honod'+i).checked=false;}
			}
		}
	}
//  Marca o desmarca todos los checkbox del formulario aplicar cobranza ////
function CheckOnCheckc(){
	var a = document.getElementById('todosc').value;
	if(document.getElementById('todosc').checked){
		document.getElementById('todosc2').checked=false;
		for(var i=0; i<a; i++){
			if(document.getElementById('cobra'+i) && document.getElementById('cobra'+i).disabled == false){
				document.getElementById('cobra'+i).checked=true;
				document.getElementById('cobrad'+i).checked=false;
			}
		}
	}else{
		for(var i=0; i<a; i++){
			if(document.getElementById('cobra'+i)){document.getElementById('cobra'+i).checked=false;}
			}
		}
	}
//  Marca o desmarca todos los checkbox del formulario aplicar cobranza ////
function CheckOnCheckc2(){
	var a = document.getElementById('todosc2').value;
	if(document.getElementById('todosc2').checked){
		document.getElementById('todosc').checked=false;
		for(var i=0; i<a; i++){
			if(document.getElementById('cobrad'+i)){
				document.getElementById('cobrad'+i).checked=true;
			}
			if (document.getElementById('cobra'+i)){
				document.getElementById('cobra'+i).checked=false;
			}
		}
	}else{
		for(var i=0; i<a; i++){
			if(document.getElementById('cobrad'+i)){document.getElementById('cobrad'+i).checked=false;}
			}
		}
	}
//Valida marcado individual en el forumlario aplicar honorario
function CheckOnCheckhc(sel, num){
	if(sel.disabled == false){
		if (sel.id == ('hono'+num)){
			document.getElementById('honod'+num).checked=false;
		}else if (sel.id == ('honod'+num)){
			document.getElementById('hono'+num).checked=false;
		}else if (sel.id == ('cobra'+num)){
			document.getElementById('cobrad'+num).checked=false;
		}else if (sel.id == ('cobrad'+num)){
			document.getElementById('cobra'+num).checked=false;
		}
	}
}
//Valida marcado individual en el forumlario aplicar honorario
	
//// valida que el campo tipo de deuda, se haya seleccionado ////
function ValidaTP(){
	if(document.getElementById('tipodeuda').value<1){
		alert("Seleccione el tipo de deuda");
		document.getElementById('tipodeuda').className='campoRequerido';
		setTimeout("document.getElementById('tipodeuda').focus()",1);
		return false;
		}
	document.getElementById('tipodeuda').className='';
	if(document.getElementById('tercero').value<1){
		alert("Seleccione el tercero");
		document.getElementById('tercero').className='campoRequerido';
		setTimeout("document.getElementById('tercero').focus()",1);
		return false;
		}
	document.getElementById('tercero').className='';
	}
//  valida los campos del formulario filtro informe comparendos ////
function ValidaHonoCobra(){
	var a=0;
	if(document.getElementById('tipodeuda').value<1){
		alert("Seleccione el tipo de deuda");
		document.getElementById('tipodeuda').className='campoRequerido';
		setTimeout("document.getElementById('tipodeuda').focus()",1);
		return false;
		}
	document.getElementById('tipodeuda').className='';
	if(document.getElementById('tercero').value<1){
		alert("Seleccione el tercero");
		document.getElementById('tercero').className='campoRequerido';
		setTimeout("document.getElementById('tercero').focus()",1);
		return false;
		}
	document.getElementById('tercero').className='';
	if(document.getElementById('identificacion').value.length>0){a+=1;}
	if(document.getElementById('placa').value.length>0){a+=1;}
	if(document.getElementById('comparendo').value.length>0){a+=1;}
	if(document.getElementById('estado_deuda').value.length>0){a+=1;}
	if(document.getElementById('fechainicial').value.length>0){a+=1;}
	if(document.getElementById('fechafinal').value.length>0){a+=1;}
	if(a<1){
		alert("Digite o seleccione un filtro para generar el informe");
		document.getElementById('fechainicial').className='campoRequerido';
		setTimeout("document.getElementById('fechainicial').focus()",1);
		return false;
		}
	document.getElementById('fechainicial').className='';
	//document.form.action='honorarios_cobranza.php';
	//document.form.submit();
	FAjax('listhonocobra.php','lista','','POST');
	}
//  valida los campos del formulario actualizar registros honorarios / cobranza ////
function ValidaGenHonoCobra(){
	var a=document.getElementById('totalchecks').value;
	var e=0;
	for(var i=0; i<a; i++){
		if(document.getElementById('hono'+i).checked){
			e+=1;
			}
		}
	alert(e);
	if(e<1){
		alert("Marque una opcion");
		return false;
		}
	//document.form.action='honorarios_cobranza.php';
	//document.form.submit();
	FAjax('listhonocobra.php','lista','','POST');
	}
//  valida el numero de certificado MEDICO digitado no sea igual al reistrado en la base de datos ////
function ValTabla(v){
	if(document.getElementById('tabla').value.length<1){
		alert("Seleccione la tabla a auditar");
		document.getElementById('tabla').className='campoRequerido';
		setTimeout("document.getElementById('tabla').focus()",1);
		return false;
		}
	document.getElementById('tabla').className='';
	FAjax('valtabla.php?dato='+v,'tablaaudi','','POST');
	}
//  valida el numero de certificado MEDICO digitado no sea igual al reistrado en la base de datos ////
function AgregaTablaAudi(){
	if(document.getElementById('tabla').value.length<1){
		alert("Seleccione la tabla a auditar");
		document.getElementById('tabla').className='campoRequerido';
		setTimeout("document.getElementById('tabla').focus()",1);
		return false;
		}
	document.getElementById('tabla').className='';
	FAjax('instabla.php', 'mostrar', '', 'POST');
	}
//  valida el numero de certificado MEDICO digitado no sea igual al reistrado en la base de datos ////
function ListaResult(){
	FAjax('listaudi.php', 'listado', '', 'post');
	}
//  Validar camposs Requeridos del los tramites de RNC ////
function ValidarFormov(){
	var a=document.getElementById('camval').value;
	var nombrecampo=a.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout(document.getElementById(nombrecampo[i]).focus(),1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	var t=document.getElementById('tabla').value;
	var v=document.getElementById('ver').value;	
	if(confirm('¿Est\xe1 seguro que desea Agregar/Actualizar el registro?')){
		document.form.action='formmov.php?tabla='+t+'&ver='+v;
		document.form.submit();
		}
	}
//  Validar camposs Requeridos del los tramites de RNC ////
function ValidarFormovTram(){
	var a=document.getElementById('camval').value;
	var nombrecampo=a.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout(document.getElementById(nombrecampo[i]).focus(),1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	var t=document.getElementById('tabla').value;
	var v=document.getElementById('ver').value;	
	if(confirm('¿Est\xe1 seguro que desea Agregar/Actualizar el registro?')){
		document.form.action='formmovtram.php?tabla='+t+'&ver='+v;
		document.form.submit();
		}
	}
////  Valida que el campo tipo doc sea tipo rna o rnc para colocar requeridos los campos clase y servicio ////
function ValidaTipoDoc(){
	var a=document.getElementById('Tconceptos_tipodoc').value;
	if((a==1)||(a==2)){
		document.getElementById('2Tconceptos_clase').setAttribute("class","campoRequerido");
		document.getElementById('2Tconceptos_servicioVeh').setAttribute("class","campoRequerido");
		var b=document.getElementById('camval').value;
		var c=b+'Tconceptos_clase,Tconceptos_servicioVeh';
		document.getElementById('camval').value=c;
		}
	else{		
		document.getElementById('2Tconceptos_clase').setAttribute("class","subtotales");
		document.getElementById('2Tconceptos_servicioVeh').setAttribute("class","subtotales");
		var b=document.getElementById('camval').value;
		var c=b.replace(',Tconceptos_clase,Tconceptos_servicioVeh', '');
		document.getElementById('camval').value=c;
		}
	}
//// Borrar empleado y usuario ////
function BorrarEmpleado(){
	if(confirm('1. Se borrar\xe1 la informaci\xf3n del empleado\n2. Se borrar\xe1 el usuario (login)\n\xbfEst\xe1 seguro que desea eliminar la informaci\xf3n del empleado y el usuario?')){
		FAjax('borrar.php', 'quitar', '', 'post');
		}
	else{return false;}
	}
//// Calcula el valor del concepro curso sobre normas de transito ////
function CalcularValorCurso(v, b){
	if(document.getElementById('identificacion').value.length<1){
		alert("Digite numero de documento del ciudadano");
		document.getElementById('identificacion').className='campoRequerido';
		setTimeout(document.getElementById('identificacion').focus(),1);
		return false;
		}
	document.getElementById('identificacion').className='';
	if(document.getElementById('vcompa').value.length<1){
		alert("Digite el valor del comparendo");
		document.getElementById('vcompa').className='campoRequerido';
		setTimeout(document.getElementById('vcompa').focus(),1);
		return false;
		}
	document.getElementById('vcompa').className='';
	if(document.getElementById('dcompa').value.length<1){
		alert("Selecione el porcentaje de descuento del comparendo");
		document.getElementById('dcompa').className='campoRequerido';
		setTimeout(document.getElementById('dcompa').focus(),1);
		return false;
		}
	document.getElementById('dcompa').className='';
	document.getElementById('identificaciont').value=document.getElementById('identificacion').value;
	FAjax('curconcept.php','cursos','','post');
	}
//Valida si desea anular el acuerdo de pago selecionado.
function AnularAP(v) {
    if (confirm('Se anular\xe1 el acuerdo de pago, Desea continuar?')) {
        FAjax('anulaap.php?dato=' + v + '&accion=anular', 'anulaap', '', 'post');
    } else {
        return false;
    }
}
//Valida si desea vencer el acuerdo de pago selecionado, para incumplimiento.
function VencerAP(v) {
    if (confirm('Se marcara como vencido el acuerdo de pago para incumplimiento, Desea continuar?')) {
        FAjax('anulaap.php?dato=' + v + '&accion=vencer', 'anulaap', '', 'post');
    } else {
        return false;
    }
}
//// redirecciona a la pagina de salida por inactividad ////
function logout(){
	alert("Su sesion ha expirado por inactividad.");
	location.href='../out.php';
	}
//// resetea el timeout de la session ////
function resetTimer(s){
	var t;	
	var miliseg=parseInt(s)*1000;
	clearTimeout(t);
	t=setTimeout(logout, miliseg); //logs out in "s" minutes
	}
//// redirecciona a la pagina de salida por inactividad ////
function logout2(){
	alert("Su sesion ha expirado por inactividad.");
	location.href='out.php';
	}
//// resetea el timeout de la session ////
function resetTimer2(s){
	var t;	
	var miliseg=parseInt(s)*1000;
	clearTimeout(t);
	t=setTimeout(logout2, miliseg); //logs out in "s" minutes
	}
//// Valida si el nuevo propietario del vehiculo esta registrado en la base de datos en tramite traspaso ////
function ValidaPropTP(){
	if(document.getElementById('Tvehiculos_TP_identificacion').value.length<1){
		alert("Digite el numero de identificacion del nuevo propietario (sin puntos,comas ni espacios)");
		document.getElementById('Tvehiculos_TP_identificacion').className='campoRequerido';
		setTimeout("document.getElementById('Tvehiculos_TP_identificacion').focus()",1);
		return false;
		}
	document.getElementById('Tvehiculos_TP_identificacion').className='';
	var v = document.getElementById('Tvehiculos_TP_identificacion').value;
	FAjax('datprotp.php?dato='+v,'newprop','','POST');
	}
//// Validar los campos tipo tinyint en formov de form ////
function ValidarTinyInt(n){
	if(document.getElementById(n).value>255){
		alert("El valor del campo no debe ser mayor a 255");
		document.getElementById(n).value='';
		document.getElementById(n).className='campoRequerido';
		setTimeout("document.getElementById('"+n+"').focus()",1);
		return false;
		}
	document.getElementById(n).className='';
	}
//// Valida el tipo de liquidacion y habilita el campo tramites ////
function ValidaTipoLiq(){
	if(document.getElementById('tipoliquida').value.length<1){
		alert("Seleccione el tipo de liquidacion");
		document.getElementById('tipoliquida').className='campoRequerido';
		setTimeout("document.getElementById('tipoliquida').focus()",1);
		return false;
		}
	document.getElementById('tipoliquida').className='';
	var v = document.getElementById('tipoliquida').value;
	FAjax('tram.php?dato='+v,'tipotram','','POST');
	}
//  valida los campos del formulario filtro informe liquidacion ////
function ValidaInfoLiq(){
	var a=0;
	if(document.getElementById('tipoliquida').value.length>0){a+=1;}
	if(document.getElementById('tipotramite').value.length>0){a+=1;}
	if(document.getElementById('identificacion').value.length>0){a+=1;}
	if(document.getElementById('placa').value.length>0){a+=1;}
	if(document.getElementById('fechainicial').value.length>0){a+=1;}
	if(document.getElementById('fechafinal').value.length>0){a+=1;}
	if(a<1){
		alert("Digite o seleccione un filtro para generar el informe");
		document.getElementById('tipoliquida').className='campoRequerido';
		setTimeout("document.getElementById('tipoliquida').focus()",1);
		return false;
		}
	//alert(document.getElementById('tipotramite2').value);
	document.getElementById('tipoliquida').className='';
	document.getElementById('aplica').value='1';
	document.getElementById('tipotramite').value=document.getElementById('tipotramite2').value;
	document.form.action='infoliquida.php';
	document.form.submit();
	}
//  valida los campos del formulario filtro informe liquidacion ////
function ValidaInfoRec(action){
	var a=0;
	if(document.getElementById('liquidacion').value.length>0){a+=1;}
	if(document.getElementById('identificacion').value.length>0){a+=1;}
	if(document.getElementById('fechainicial').value.length>0){a+=1;}
	if(document.getElementById('fechafinal').value.length>0){a+=1;}
	if (document.getElementById('tiporec') !== null){
		if(document.getElementById('tiporec').value.length>0){a+=1;}
		if(document.getElementById('mediorec').value.length>0){a+=1;}
	}
	if(a<1){
		alert("Digite o seleccione un filtro para generar el informe");
		document.getElementById('fechainicial').className='campoRequerido';
		setTimeout("document.getElementById('fechainicial').focus()",1);
		return false;
		}
	//alert(document.getElementById('tipotramite2').value);
	document.getElementById('fechainicial').className='';
	document.getElementById('aplica').value='1';
	document.form.action=action;
	document.form.submit();
	}
//  valida los campos del formulario filtro informe liquidacion ////
function ValidaInfoRecTerc(){
	var a=0;
	if(document.getElementById('liquidacion').value.length>0){a+=1;}
	if(document.getElementById('identificacion').value.length>0){a+=1;}
	if(document.getElementById('fechainicial').value.length>0){a+=1;}
	if(document.getElementById('fechafinal').value.length>0){a+=1;}
	if(a<1){
		alert("Digite o seleccione un filtro para generar el informe");
		document.getElementById('fechainicial').className='campoRequerido';
		setTimeout("document.getElementById('fechainicial').focus()",1);
		return false;
		}
	//alert(document.getElementById('tipotramite2').value);
	document.getElementById('fechainicial').className='';
	document.getElementById('aplica').value='1';
	document.form.action='inforectercero.php';
	document.form.submit();
	}
//// valida y habilita los campos de los items en la entrada a patios ////
function SiNoItems(a){
	//alert(a);
	if(document.getElementById('patios_iteminv_ID'+a).checked){
		document.getElementById('2patios_iteminv_ID'+a).checked=false;
		document.getElementById('cant'+a).disabled=false;
		document.getElementById('2cant'+a).className='campoRequerido';
		document.getElementById('patios_estado'+a).disabled=false;
		document.getElementById('2patios_estado'+a).className='campoRequerido';
		
		}
	}
//// Valida y deshabilita los campos dels items en entrqada a patios ////
function SiNoItems2(a){
	//alert(a);
	if(document.getElementById('2patios_iteminv_ID'+a).checked){
		document.getElementById('patios_iteminv_ID'+a).checked=false;
		document.getElementById('cant'+a).value='';
		document.getElementById('cant'+a).disabled=true;
		document.getElementById('2cant'+a).className='subtotales';
		document.getElementById('patios_estado'+a).value='';
		document.getElementById('patios_estado'+a).disabled=true;
		document.getElementById('2patios_estado'+a).className='subtotales';
		}
	}
//// Valida el formulario de entrqada a patios ////
function ValidaEntradaPatios(a){
	var nombrecampo=a.split(",");
	for(var i=0;i<nombrecampo.length;i++){
		if(nombrecampo[i]!=''){
			if(document.getElementById(nombrecampo[i]).value.length<1){
				document.getElementById('aplica2').value='';
				alert("Este campo no debe ser vacio");
				document.getElementById(nombrecampo[i]).className='campoRequerido';
				setTimeout(document.getElementById(nombrecampo[i]).focus(),1);
				return false;
				}
			document.getElementById(nombrecampo[i]).className='';
			}
		}
	var numtinv=document.getElementById('totaltinv').value;
	for(var j=0; j<numtinv; j++){
		var numiinv=document.getElementById('totaliinv'+j).value;
		for(var k=0; k<numiinv; k++){
			if(document.getElementById('iditem'+j+k).value!=''){
				var iditem=document.getElementById('iditem'+j+k).value;
				var nomitem=document.getElementById('nomitem'+j+k).value;
				if((nomitem!='Tapiceria')&&(nomitem!='Latonería y Pintura')){
					if(document.getElementById('patios_iteminv_ID'+iditem).checked){
					if(document.getElementById('cant'+iditem).value.length<1){
						document.getElementById('aplica2').value='';
						alert("Este campo no debe ser vacio");
						document.getElementById('cant'+iditem).className='campoRequerido';
						setTimeout(document.getElementById('cant'+iditem).focus(),1);
						return false;
						}
					document.getElementById('cant'+iditem).className='';
					if(document.getElementById('patios_estado'+iditem).value<1){
						document.getElementById('aplica2').value='';
						alert("Este campo no debe ser vacio");
						document.getElementById('patios_estado'+iditem).className='campoRequerido';
						setTimeout(document.getElementById('patios_estado'+iditem).focus(),1);
						return false;
						}
					document.getElementById('patios_estado'+iditem).className='';
					}
					}
				}
			}
		}		
	if(confirm('¿Est\xe1 seguro que desea Agregar/Actualizar el registro?')){
		document.getElementById('aplica2').value='1';
		document.form.action='entradapatios.php';
		document.form.submit();
		}
	else{document.getElementById('aplica2').value='';}	
	}
//// Valida el servicio y la clase en la liquidacion si la especie venal no tiene la informacion ////
function ValidaClaseServicio() {
    if (document.getElementById('servicio').value < 1) {
        alert("Seleccione el Tipo de servicio del vehiculo");
        document.getElementById('servicio').className = 'campoRequerido';
        setTimeout("document.getElementById('servicio').focus()", 1);
        return false;
    }
    document.getElementById('servicio').className = '';
    if (document.getElementById('clase').value.length < 1) {
        alert("Seleccione la clase del vehiculo");
        document.getElementById('clase').className = 'campoRequerido';
        setTimeout("document.getElementById('clase').focus()", 1);
        return false;
    }
    document.getElementById('clase').className = '';
    if (document.getElementById('idtramite').value === "1") {
        if (document.getElementById('tipoplaca').value.length < 1) {
            alert("Digite el numero de placa");
            document.getElementById('tipoplaca').className = 'campoRequerido';
            setTimeout("document.getElementById('tipoplaca').focus()", 1);
            return false;
        }
        document.getElementById('tipoplaca').className = '';
        document.getElementById('tipoplaca').setAttribute("readOnly", true);
    }
    var c = document.getElementById('tipotram').value;
    var b = document.getElementById('numtram').value;
    var f = document.getElementById('clase').value;
    var g = document.getElementById('servicio').value;
    document.getElementById('vclase').value = f;
    document.getElementById('vservicio').value = g;
    document.getElementById('plus').style.display = 'block';
    FAjax('concept.php?dato=' + c + '|' + b, 'concept' + b, '', 'post');
}
	
//// Valida si se debe o no habilitar la validacion de que exista el vehiculo si otro parametros se coloca en si ////
function ValidaParametros() {
    var soat = document.getElementById('Tparametrosliq_soat').value;
    var vp = document.getElementById('Tparametrosliq_vp').value;
    var cs = document.getElementById('Tparametrosliq_cs').value;
    var despig = document.getElementById('Tparametrosliq_despig').value;
    var mc = document.getElementById('Tparametrosliq_mc').value;
    var rc = document.getElementById('Tparametrosliq_rc').value;
    var particular = document.getElementById('Tparametrosliq_particular').value;
    var inactivo = document.getElementById('Tparametrosliq_inactivo').value;
    var gas = document.getElementById('Tparametrosliq_gas').value;
    var claserv = document.getElementById('Tparametrosliq_claserv').value;
    var pig = document.getElementById('Tparametrosliq_pig').value;
    var tecno = document.getElementById('Tparametrosliq_tecno').value;
    var veh = document.getElementById('Tparametrosliq_veh').value;
    if ((soat > 0) || (vp > 0) || (cs > 0) || (despig > 0) || (mc > 0) || (rc > 0) || (particular > 0) || (inactivo > 0) || (gas > 0) || (claserv > 0) || (pig > 0) || (tecno > 0)) {
        if (veh < 1) {
            document.getElementById('Tparametrosliq_veh').value = 1;
        }
    }
}	
//// funciones para cambier el botos de los campos tipo file ////
function borrar(a, b){
	document.getElementById(a).innerHTML = "";
	document.getElementById(b).value = "";
	}
function nombre_ar(id_archivo, b){
	var archivo = document.getElementById(id_archivo).value;
	if(navigator.userAgent.indexOf('Linux') != -1){var SO = "Linux";}
	else if((navigator.userAgent.indexOf('Win') != -1) &&(navigator.userAgent.indexOf('95') != -1)){var SO = "Win";}
	else if((navigator.userAgent.indexOf('Win') != -1) &&(navigator.userAgent.indexOf('NT') != -1)){var SO = "Win";}
	else if(navigator.userAgent.indexOf('Win') != -1){var SO = "Win";}
	else if(navigator.userAgent.indexOf('Mac') != -1){var SO = "Mac";}
	else {var SO = "no definido";}
	if(SO = "Win"){var arr_ruta = archivo.split("\\");}
	else{var arr_ruta = archivo.split("/");}
	var nombre_archivo = (arr_ruta[arr_ruta.length-1]);
	var ext_validas = /\.(pdf|gif|jpg|png|jpeg)$/i.test(nombre_archivo);
	if (!ext_validas){
		borrar(id_archivo, b);
		alert("Archivo con extensión no válida\nArchivo: " + nombre_archivo);
		return false;
		}
	document.getElementById(b).innerHTML = "<b>" + nombre_archivo + "<\/b>";
	}
function btn_submit(){
	var archivo = document.getElementById('Tciudadanos_foto').value;
	if(archivo == ''){
		alert('Debe seleccionar un archivo previamente');
		return false;
		}
	}
//// Busca los div de inptus files para aplciar formato.        
function personaliza() {
    // buscar la clase en los divs
    var personalizar = document.getElementsByClassName('clase_inputfile');
    for (var i = (personalizar.length - 1); i >= 0; i--) {
        personalizar[i].className = 'clase_inputfile_js';
    }
    var cambia_input_files = document.getElementsByClassName('cambia_input_file');
    for (var i = (cambia_input_files.length - 1); i >= 0; i--) {
        var cambiarclase = cambia_input_files[i].cloneNode(true);
        cambiarclase.className = 'cambia_input_file_js';
        cambiarclase.style.outline = "none";
        cambiarclase.style.opacity = 0;
        cambiarclase.style.MozOpacity = 0;
        cambiarclase.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity:0)';

        var padre = cambia_input_files[i].parentNode;
        padre.removeChild(cambia_input_files[i]);
        padre.appendChild(cambiarclase);
    }
}
////  Recoge los datos enviados en registro de vehiculo y los envia al archivo nomarv.php via java para hacer las tareas requeridas  ////
function BuscarPropietRV(){
	var i=document.getElementById('tipodoc').value;
	var a=document.getElementById('identificacion').value;
	if(document.getElementById('Tciudadanos_tipo').value<1){
		alert("Seleccione el tipo de ciudadano");
		document.getElementById('Tciudadanos_tipo').className='campoRequerido';
		setTimeout("document.getElementById('Tciudadanos_tipo').focus()",1);
		return false;
		}
	document.getElementById('Tciudadanos_tipo').className='';
	if(document.getElementById('tipodoc').value.length<1){
		alert("Seleccione un tipo de documento");
		document.getElementById('tipodoc').className='campoRequerido';
		setTimeout("document.getElementById('tipodoc').focus()",1);
		return false;}
	if(document.getElementById('identificacion').value.length<1){
		alert("Digite un n\xfamero de documento");
		document.getElementById('identificacion').className='campoRequerido';
		setTimeout("document.getElementById('identificacion').focus()",1);
		return false;}
	document.getElementById('tipodoc').className='';
	document.getElementById('identificacion').className='';
	document.getElementById('Tciudadanos_nombres').disabled=false;
	document.getElementById('Tciudadanos_nombres').value='';
	document.getElementById('Tciudadanos_apellidos').value='';
	var e=document.getElementById('Tciudadanos_tipo').value;
	if(e==1){FAjax('nomarv.php?dato='+a,'nomapell','','post');}
	else{FAjax('nomarv2.php?dato='+a,'nomapell','','post');}	
	}
	
function historicoTramite (){
	var tid = 'Tcerttrad_tramite_id'; var tplaca = 'Tcerttrad_placa';
	var idt=document.getElementById(tid).value;
	var placa=document.getElementById(tplaca).value;
	if (placa.length > 4 && idt != ""){	
		if (idt == 5){
			var pagimp="formhistram.php?placa="+placa+"&tramite="+idt;
			modalWin(pagimp, 650, 300);
			$("input[name='agregar']").attr('disabled', true);
		}else{
			$("input[name='agregar']").attr('disabled', false);
		}
	}else if(idt != ""){
		alert("Digite la placa");
		document.getElementById(tplaca).className='campoRequerido';
		setTimeout("document.getElementById("+tplaca+").focus()",1);
		return false;		
	}
}