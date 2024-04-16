function creaAjax(){
	var objetoAjax=false;
	try{
		/*Para navegadores distintos a internet explorer*/
		objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
		}
	catch(e){
		try{
			/*Para explorer*/
			objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
			}
		catch(E){
			objetoAjax = false;
			}
		}	
	if(!objetoAjax && typeof XMLHttpRequest!='undefined') {
		objetoAjax = new XMLHttpRequest();
		}
	return objetoAjax;
	}
function FAjax(url,capa,valores,metodo){ 
	var ajax=creaAjax();
	if(document.getElementById(capa)!=null){
		var capaContenedora = document.getElementById(capa);
		}
	else{
		//alert("Capa "+capa+"no existe");
		//FAjax (url,capa,valores,metodo);
		return;
		}
	//prototipo que sirve para tratar la respuesta:
	String.prototype.tratarResponseText = function(){
		//[\\?&]"+sParametro+"=([^&#38;#]*)"
		var pat=/<script[^>]*>([\S\s]*?)<\/script[^>]*>/ig;
		var pat2=/\b\s+src=[^>\s]+\b/g;
		var elementos = this.match(pat) || [];	
		for(i = 0; i < elementos.length; i++){		
			var nuevoScript = document.createElement('script');
			nuevoScript.type = 'text/javascript';
			var tienesrc=elementos[i].match(pat2) || [];		
			if (tienesrc.length) {
				nuevoScript.src=tienesrc[0].split("'").join('').split('"').join('').split('src=').join('').split(' ').join('');
				}
			else{		
				var elemento = elementos[i].replace(pat,'$1');
				nuevoScript.text = elemento;			
				}	
			//console.log(nuevoScript.src);
			document.getElementsByTagName('body')[0].appendChild(nuevoScript);		
			}	
		return this.replace(pat,'');
		} 
	/*Creamos y ejecutamos la instancia si el metodo elegido es POST*/
	if(metodo.toUpperCase()=='POST'){
		ajax.open ('POST', url, true);
		ajax.onreadystatechange = function() {
		//alert(ajax.status+" "+ajax.readyState);
		if (ajax.readyState==1){
			capaContenedora.innerHTML="<br /><br /><strong>Por favor espere....</strong><br /><br /><img src=\"../images/loadingAnimation.gif\" width=\"208\" height=\"13\" alt=\"Por favor espere\" /><br /><br /><strong>Cargando...</strong><br /><br />";
			}
		else if (ajax.readyState==4){
			if(ajax.status==200){
				document.getElementById(capa).innerHTML=ajax.responseText.tratarResponseText();
				}
			else if(ajax.status==404){			
				capaContenedora.innerHTML = "La direccion no existe";
				}
			else{
				capaContenedora.innerHTML = "Error: "+ajax.status;
				}
			}
		}
		ajax.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');		  
		//ajax.setRequestHeader("encoding", "iso-8859-1");
		f=document.form;
		ajax.send(varsForm(f));
		// alert(url);
		return true;
		}
	/*Creamos y ejecutamos la instancia si el metodo elegido es GET*/
	if (metodo.toUpperCase()=='GET'){
		ajax.open ('GET', url, true);
		ajax.onreadystatechange = function(){
			if (ajax.readyState==1){
				capaContenedora.innerHTML="<br /><br /><strong>Por favor espere....</strong><br /><br /><img src=\"../images/loadingAnimation.gif\" width=\"208\" height=\"13\" alt=\"Por favor espere\" /><br /><br /><strong>Cargando...</strong><br /><br />";
				}
			else if (ajax.readyState==4){
				if(ajax.status==200){
					 document.getElementById(capa).innerHTML=ajax.responseText.tratarResponseText();
				}
				else if(ajax.status==404){				
					capaContenedora.innerHTML = "La direccion no existe";
					}
				else{
					capaContenedora.innerHTML = "Error: "+ajax.status;
					}
				}
			}
		ajax.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
		//ajax.setRequestHeader("encoding", "iso-8859-1");
		ajax.send(null);
		return
		}
	} 
function varsForm(f){
	var str="";
	for(i=0;i<f.length;i++){		
		if(f.elements[i].type=="radio"){
			if(f.elements[i].checked){str+="&"+f.elements[i].name+"="+f.elements[i].value;}
			else{str+="";}
			}
		else if(f.elements[i].type=="checkbox"){
			if(f.elements[i].checked){str+="&"+f.elements[i].name+"="+f.elements[i].value;}
			else{str+="";}
			}
		else{str+="&"+f.elements[i].name+"="+f.elements[i].value;}
		}
		return str;
		//alert(str);
	}