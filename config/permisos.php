<?php

function get_cadena($user_id_iws){

	global $con;
	$cadena_permisos=[];
	$i=0;

	$sql="select * from  appwmenu,appwperfil where appwperfil.menu=appwmenu.id and appwperfil.codven=$user_id_iws";

	$query_user=mysqli_query($con, $sql);

	while ($row=mysqli_fetch_array($query_user)){
	

	$cadena_permisos[$i]=$row['menu'];
	$i++;
}
	return $cadena_permisos;

}

function permisos($modulo,$cadena){

	global $modulo_permisos, $permisos_ver, $permisos_editar,$permisos_eliminar;

	$count_search=strlen($modulo)+6;

	$inicio = stripos($cadena,$modulo);

	if (is_numeric($inicio)){

	$cadena=substr($cadena,$inicio,$count_search);

	list($modulo_permisos,$permisos_ver,$permisos_editar,$permisos_eliminar)=explode(",",$cadena);

	}

}

function permisos_menu($modulo,$cadena){

	global $modulo_permisos_menu, $permisos_ver_menu, $permisos_editar_menu,$permisos_eliminar_menu;

	$count_search=strlen($modulo)+6;

	$inicio = stripos($cadena,$modulo);

	if (is_numeric($inicio)){

	$cadena=substr($cadena,$inicio,$count_search);

	list($modulo_permisos_menu,$permisos_ver_menu,$permisos_editar_menu,$permisos_eliminar_menu)=explode(",",$cadena);

	}

}







?>