<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'menu.php';

$row_parame = ParamEcono();
$psedes=BuscarSedes();

$nrs=@$psedes['Tsedes_RS'];
$nnit=@$psedes['Tsedes_NIT'];
$ndir=@$psedes['Tsedes_DIR'];
$ntel1=@$psedes['Tsedes_tel1'];
$ntel2=@$psedes['Tsedes_tel2'];
$ndivipo=@$psedes['Tsedes_divipo'];

$_SESSION['snrs']=$nrs;
$_SESSION['snnit']=$nnit;
$_SESSION['sndir']=$ndir;
$_SESSION['sntel1']=$ntel1;
$_SESSION['sntel2']=$ntel2;
$_SESSION['sndivipo']=$ndivipo;

$parmliq=ParamLiquida();

$nid=$parmliq['Tparametrosliq_ID'];
$ndvl=$parmliq['Tparametrosliq_DVL'];
$ndvt=$parmliq['Tparametrosliq_DVT'];
$nlogo=$parmliq['Tparametrosliq_logo'];
$nct=$parmliq['Tparametrosliq_ct'];
$nleyenda1=$parmliq['Tparametrosliq_leyenda1'];
$nleyenda2=$parmliq['Tparametrosliq_leyenda2'];
$nleyenda3=$parmliq['Tparametrosliq_leyenda3'];
$ncodinf=$parmliq['Tparametrosliq_inf'];

$_SESSION['snid']=$nid;
$_SESSION['sndvl']=$ndvl;
$_SESSION['sndvt']=$ndvt;
$_SESSION['snlogo']=$nlogo;
$_SESSION['snct']=$nct;
$_SESSION['snleyenda1']=$nleyenda1;
$_SESSION['snleyenda2']=$nleyenda2;
$_SESSION['snleyenda3']=$nleyenda3;
$_SESSION['sncodinf']=$ncodinf;

$fechaini=date('Y-m-d H:i:s');
$fechhoy=date('Ymd');

?>    
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="funciones.js"></script>

<div class="card container-fluid">
    <div class="header">
        <h2>Generar Documento de Cobro</h2>
    </div>
    <br>
    
	<form name="form" id="form" action="" method="POST" onSubmit="">
	<table width="800" border="0" align="center" bgcolor="#FFFFFF">
  
      
        <tr>
            <td align="center" colspan="10"><p class="highlight2"><<- Debe seleccionar minimo un filtro para generar el listado ->></p></td>
        </tr>
        <tr>
        	<td align="center" colspan="10">
                <table width="790" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left"><strong> A&ntilde;o inicial</strong><br></td>
                    <td colspan="2" align="left"><input class="form-control" name="fechainicial" type="text" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo @$_POST['fechainicial']; ?>" /></td>
                    <td align="left"><strong> A&ntilde;o final</strong><br></td>
                    <td colspan="2" align="left"><input class="form-control" name="fechafinal" type="text" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_POST['fechafinal']; ?>" /></td>
                    <td colspan="2" align="left"><strong>No. registros x p&aacute;gina</strong><br></td>
                    <td colspan="2" align="left">
						<select class="form-control" name="nregistros" id="nregistros">
						<?php for($k=25; $k<=500; $k+=25){?>
							<option value="<?php echo $k; ?>" <?php if($k==50){echo "selected";}?>><?php echo $k; ?></option>
						<?php }?>
						</select>
					</td>
                </tr>
				<tr>
					<td align="center" colspan="10">&nbsp;</td>
				</tr>
                <tr>
                    <td align="left"><strong id="lplaca">Placas</strong></td>
                    <td colspan="9" align="left"><textarea class="form-control" name='placa' type='text' id='placa' cols="60"><?php echo @$_POST['placa'];?></textarea></td>
				</tr>
				<tr>	
					<td>&nbsp;</td>
					<td colspan="9" align="left" colspan="2"><strong class="highlight2">Si busca por placas asegurese que estan separadas.</strong></td>
                </tr>
                <tr>
                    <td width="88"></td>
                    <td width="79"></td>
                    <td width="79"></td>
                    <td width="79"></td>
                    <td width="79"></td>
                    <td width="79"></td>
                    <td width="79"></td>
                    <td width="79"></td>
                    <td width="79"></td>
                    <td width="70"></td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="10">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" colspan="10"><input class="btn btn-success" name="Comprobar" type="button" id="Comprobar" value="Consultar" onclick="ValidaDocCobra()"/><br /><?php echo @$mesliq;?></td>
        </tr>
        <tr>
            <td align="center" colspan="10">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" colspan="10" id="lista">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10" align="left">&nbsp;</td>
        </tr>
	    <tr>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
    	</tr>
	</table>
    </form>
</div>
</div>
<script>
function ValidaDocCobra(){
	var a=0;
	// if(document.getElementById('identificacion').value.length>0){a+=1;}
	if(document.getElementById('placa').value.length>0){a+=1;}
	if(document.getElementById('fechainicial').value.length>0){a+=1;}
	if(document.getElementById('fechafinal').value.length>0){a+=1;}
	if(a<1){
		alert("Digite o seleccione un filtro para generar el informe");
		document.getElementById('fechainicial').className='campoRequerido';
		setTimeout("document.getElementById('fechainicial').focus()",1);
		return false;
	}
	document.getElementById('fechainicial').className='';
	FAjax('listdoccobra.php','lista','','POST');
}

function CheckOnCheckDT(){
	var a = document.getElementById('todos').value;
	if(document.getElementById('todos').checked){
		for(var i=0; i<a; i++){
			if(document.getElementById('placadt'+i)){document.getElementById('placadt'+i).checked=true;}
			}
		}
	else{
		for(var i=0; i<a; i++){
			if(document.getElementById('placadt'+i)){document.getElementById('placadt'+i).checked=false;}
			}
		}
	}
</script>
</body>
</html>

<?php include 'scripts.php'; ?>