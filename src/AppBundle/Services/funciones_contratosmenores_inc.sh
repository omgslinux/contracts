export LANG=es_ES.UTF-8

INIPREF=$(date -d $INICIO +"%Y%m%d")
FINPREF=$(date -d $FIN +"%Y%m%d")
PATTERN="contratosmenores"
#PREFIJO="${PATTERN}_${INIPREF}_${FINPREF}"
#UTF8FILE="$PREFIJO.csv"
#ISOFILE="$PREFIJO.tmp"
#HTMLFILE="$PREFIJO.html"
#WGETFILE="${PREFIJO}_wget.tmp"
UALINES=$(cat user-agent.txt|wc -l)
DATETIMESTART=$(date +"%s")
BEFORE=$DATETIMESTART
PROCESSED=0
ERRORES=""
DATEHUMAN=$(date -d "@$DATETIMESTART" +"%d/%m/%Y %T")
REFDIR="REFS"
mkdir -p $REFDIR
HTMLDIR="HTML"
RESULTADOSDIR="$HTMLDIR/resultados"
mkdir -p $RESULTADOSDIR

# Variables input hidden
SITEURL="http://www.madrid.org/"
h_pagename="PortalContratacion/Comunes/Presentacion/PCON_resultadoBuscadorAvanzado"
h_pageid="1142536600028"
numeroExpediente=""
referencia=""
entidadAdjudicadora="1109266187266"
fechaFormalizacionDesde=$(date -d $INICIO +"%d/%m/%Y")
fechaFormalizacionHasta=$(date -d $FIN +"%d/%m/%Y")
tipoPublicacion="Contratos Menores"
procedimientoAdjudicacion="Contratos Menores"
UTFHEADER="FECHA|TIPO DE PUBLICACIÓN|ENTIDAD ADJUDICADORA|Nº EXPEDIENTE|REFERENCIA|OBJETO DEL CONTRATO|TIPO CONTRATO|PROCEDIMINETO DE ADJUDICACIÓN|PRESUPUESTO DE LICITACIÓN(CON IVA)|NIF ADJUDICATARIO|ADJUDICATARIO|IMPORTE DE ADJUDICACIÓN(SIN IVA)|IMPORTE DE ADJUDICACIÓN(CON IVA)"

function buscarDatosFecha ()
{
	if [[ ! -s $WGETDATEFILE ]];then
		echo "No existe $WGETDATEFILE" >&2

		mkdir -p $WGETDATEDIR
		fechaFormalizacionDesde=$(date -d $1 +"%d/%m/%Y")
		fechaFormalizacionHasta=$(date -d $1 +"%d/%m/%Y")

		POSTDATA="_charset_=UTF-8&language=es&pagename=${h_pagename}&pageid=${h_pageid}&entidadAdjudicadora=${entidadAdjudicadora}&numeroExpediente=${numeroExpediente}&referencia=${referencia}&tipoPublicacion=${tipoPublicacion}&procedimientoAdjudicacion=${procedimientoAdjudicacion}&fechaFormalizacionDesde=${fechaFormalizacionDesde}&fechaFormalizacionHasta=${fechaFormalizacionHasta}"
		torsocks wget -q --user-agent="$useragent" --post-data="$POSTDATA" "${SITEURL}/cs/Satellite" -O $HTMLFILE
		buscar=$(cat $HTMLFILE)
		if [[ "$buscar" ]];then
			EXPORTURL=$(echo "$buscar"|grep exportresultados|cut -d= -f5-7|cut -d\" -f2)
			#echo "EXPORTURL: $EXPORTURL"
			torsocks wget --user-agent="$useragent" $SITEURL${EXPORTURL} -O $WGETDATEFILE
		else
			echo "ERROR EN $HTMLFILE"
		fi
	else
		echo "existe $WGETDATEFILE. Usando caché para $1" >&2
	fi
}

function buscarFechaPagina ()
{
	f=$(date -d $1 +"%d/%m/%Y")
	p=$2
	echo -n "Buscando fecha $f página $p de $totalPaginasFecha: "
	HTMLDATEFILENAME=$(getDateHTMLFilename $1 $2)
	if [[ -f $HTMLDATEFILENAME ]];then
		if [[ $(stat -c "%s" $HTMLDATEFILENAME) -lt 100 ]];then
			rm $HTMLDATEFILENAME
		fi
	fi

	if [[ ! -f $HTMLDATEFILENAME ]];then
		echo -n "NO "
		paginaActual=$(( p - 1 ))
		SITE="http://www.madrid.org/cs/Satellite"
		URL=$(url "?c=Page&cid=1142536600028&codigo=PCON_&entidadAdjudicadora=${entidadAdjudicadora}&fechaFormalizacionDesde=${f}&fechaFormalizacionHasta=${f}&idPagina=${h_pageid}&language=es&newPagina=${p}&numPagListado=5&pagename=${h_pagename}&paginaActual=${paginaActual}&paginasTotal=${totalPaginas}&procedimientoAdjudicacion=Contratos+Menores&rootelement=PortalContratacion%2FComunes%2FPresentacion%2FPCON_resultadoBuscadorAvanzado&site=PortalContratacion&tipoPublicacion=Contratos+Menores")
		torsocks wget -q --user-agent="$useragent" $SITE$URL -O $HTMLDATEFILENAME
	fi
	echo "cacheado"

	buscar=$(cat $HTMLDATEFILENAME)
}

function buscarResultadosFechas ()
{
	fechaFormalizacionDesde=$(date -d $1 +"%d/%m/%Y")
	fechaFormalizacionHasta=$(date -d $2 +"%d/%m/%Y")
	if [[ $1 == $2 ]];then
		HTMLRESULTADOSFILE="$RESULTADOSDIR/${PATTERN}_${1}.html"
	else
		HTMLRESULTADOSFILE="$RESULTADOSDIR/${PATTERN}_${1}_${2}.html"
	fi
	if [[ -f $HTMLRESULTADOSFILE ]];then
		if [[ $(stat -c "%s" $HTMLRESULTADOSFILE) -lt 100 ]];then
			rm $HTMLRESULTADOSFILE
		fi
	fi

	if [[ ! -f $HTMLRESULTADOSFILE ]]; then
		URL="http://www.madrid.org/cs/Satellite"
		POSTDATA="_charset_=UTF-8&language=es&pagename=${h_pagename}&pageid=${h_pageid}&entidadAdjudicadora=${entidadAdjudicadora}&numeroExpediente=${numeroExpediente}&referencia=${referencia}&tipoPublicacion=${tipoPublicacion}&procedimientoAdjudicacion=${procedimientoAdjudicacion}&fechaFormalizacionDesde=${fechaFormalizacionDesde}&fechaFormalizacionHasta=${fechaFormalizacionHasta}"

		torsocks wget -q --user-agent="$useragent" --post-data="$POSTDATA" $URL -O $HTMLRESULTADOSFILE
	fi

	buscar=$(cat $HTMLRESULTADOSFILE)
}

function cacheDateHTML()
{
	HTMLDATEFILENAME=$(getDateHTMLFilename $1 $2)
	entradas=$(echo "$buscar"|grep txt07azu -A2|grep -v '#Top'|grep -v SUBIR)
	if [[ -f $HTMLDATEFILENAME ]];then
		if [[ $(stat -c "%s" $HTMLDATEFILENAME) -lt 100 ]];then
			rm $HTMLDATEFILENAME
		fi
	fi
	if [[ ! -s $HTMLDATEFILENAME ]];then
		#entradas=$(echo "$buscar"|grep txt07azu -A2|grep -v '#Top'|grep -v SUBIR)
		echo "$entradas" > $HTMLDATEFILENAME
	fi
}

function cacheReferenciaHTML()
{
	if [[ -f $REFHTML ]];then
		if [[ $(stat -c "%s" $REFHTML) -lt 30 ]];then
			rm $REFHTML
		fi
	fi
	if [[ ! -f $REFHTML ]];then
		datosReferencia=$(echo "$buscar"|grep tit11gr3 -A60)
		echo "$datosReferencia" > $REFHTML
		contratosNoCacheados=$(( contratosNoCacheados + 1 ))
	else
		contratosYaCacheados=$(( contratosYaCacheados + 1 ))
	fi
}

function calcularTiempoRestante()
{
	NOW=$(date +"%s")
	LASTDELAY=$(( NOW - BEFORE ))
	#contratosProcesados=$(( contratosProcesados + contratoNumero ))
	PROCESSEDNOW=$(( contratosProcesados - PROCESSED ))
	contratosRestantes=$(( contratosFechas - contratosProcesados ))
	SECONDS=$(( NOW - DATETIMESTART	))
	AVG=$(( SECONDS / contratosProcesados ))
	if [[ $LASTDELAY == "0" ]];then
		LASTDELAY=1
	fi
	if [[ $PROCESSEDNOW == "0" ]];then
		PROCESSEDNOW=1
	fi
	LASTAVG=$(( LASTDELAY / PROCESSEDNOW ))
  echo -n "Procesados: $contratosProcesados contratos de ${contratosFechas}. Faltan $contratosRestantes Ultimo: $LASTDELAY segundos."
  REMAIN=$(( contratosRestantes * LASTDELAY / PROCESSEDNOW ))
  SECONDSEND=$(( NOW + REMAIN ))
	DATEEND=$(date -d "@$SECONDSEND" +"%d/%m/%Y %T")
  printf " Fin: %s %s Faltan: %s segundos\n\n" $DATEEND $REMAIN
	BEFORE=$NOW
	PROCESSED=$contratosProcesados
}

function comillas ()
{
	comilla=$(echo "$1"|sed -e 's/^"//' | sed -e 's/"$//' | sed -e 's/"/""/g')
	echo "$comilla"
}

function exportarDatosFecha ()
{
	buscarDatosFecha $@
	tail -n +2 $WGETDATEFILE|sed 's@";"@"|"@g'|sed 's@";$@"|@g'
}

function exportarResultados ()
{
	FECHA=$1
	if [[ ! -f $WGETEXPORTFILE ]];then
		torsocks wget -q --user-agent="$useragent" $URL -O $WGETEXPORTFILE
	fi

	piped=$(tail -n +2 $WGETEXPORTFILE|sed 's@";"@"|"@g'|sed 's@";$@"|@g')

	ifs="$IFS"
	while IFS="|" read -r tipo _entidad expediente referencia _objeto tipoc proc presup nif adju importe
	do
		entidad=$(comillas "$_entidad")
		objeto=$(comillas "$_objeto")
		echo "$FECHA|$tipo|\"$entidad\"|$expediente|$referencia|\"$objeto\"|$tipoc|$proc|$presup|$nif|$adju|$importe" >> $ISOFILE
	done <<< "$piped"
	IFS="$ifs"
}

function extraerReferenciasPagina
{
	#entradas=$(echo "$buscar"|grep txt07azu -A2|grep -v '#Top'|grep -v SUBIR)
	lineloop=0
	while read -r class href linea;do
		if [[ $lineloop == 0 ]];then
			urlcontrato=$(echo $linea|cut -d\' -f2)
			URL="http://www.madrid.org${urlcontrato}"
			CID=$(echo $urlcontrato|cut -d\& -f4|cut -d= -f2)
			lineloop=1
		elif [[ $lineloop == 1 ]];then

			REF=$(echo "$linea"|grep "Ref:" |cut -d\; -f2|cut -d\< -f1)
			echo -n "Buscando referencia: $REF: "
			if [[ $REF ]];then
				REFHTML="$REFDIR/${REF}.html"
				if [[ -f $REFHTML ]];then
					if [[ -z $(grep txt07 $REFHTML) ]];then
						rm $REFHTML
					else
						if [[ $(stat -c "%s" $REFHTML) -lt 100 ]];then
							rm $REFHTML
						fi
					fi
				fi

				if [[ ! -f $REFHTML ]];then
					echo -n "NO "
					torsocks wget -q --user-agent="$useragent" $URL -O $REFHTML
				fi
				echo "cacheado"
				buscar=$(cat $REFHTML)
				cacheReferenciaHTML

				REFCSV="$REFDIR/${REF}.csv"
				if [[ -f $REFCSV ]];then
					#if [[ $(stat -c "%s" $REFCSV) -lt 22 ]];then
						rm $REFCSV
					#fi
				fi
				if [[ ! -f $REFCSV ]];then
					siniva=$(echo "$buscar"|grep -B 2 '/tbody' |head -n 1|sed 's/<td>//g'|sed 's@</td>@@g')
					lineafecha=$(echo "$buscar"|grep "Fecha del contrato")
					__FECHA=$(echo "$lineafecha"|cut -d\> -f5|cut -d\< -f1)
					_FECHA=$(echo "$__FECHA"|tr [A-Z] [a-z] |sed 's@ enero @/01/@'|sed 's@ febrero @/02/@'|sed 's@ marzo @/03/@'|sed 's@ abril @/04/@'|sed 's@ mayo @/05/@'|sed 's@ junio @/06/@'|sed 's@ julio @/07/@'|sed 's@ agosto @/08/@'|sed 's@ septiembre @/09/@'|sed 's@ octubre @/10/@'|sed 's@ noviembre @/11/@'|sed 's@ diciembre @/12/@')
					FECHA=$(echo "$_FECHA"|sed 's@ january @/01/@'|sed 's@ february @/02/@'|sed 's@ march @/03/@'|sed 's@ april @/04/@'|sed 's@ may @/05/@'|sed 's@ june @/06/@'|sed 's@ july @/07/@'|sed 's@ august @/08/@'|sed 's@ september @/09/@'|sed 's@ october @/10/@'|sed 's@ november @/11/@'|sed 's@ december @/12/@')
					printf "lineafecha: %s, FECHA: %s. " "$__FECHA" $FECHA

					# Cacheo de CSV
					echo "$FECHA|$siniva|$CID" > $REFCSV
				else
					IFS="|" read FECHA siniva CID < $REFCSV
				fi
				FDATE=$(printf "%s%s%s" $(echo $FECHA|cut -d"/" -f3) $(echo $FECHA|cut -d"/" -f2) $(echo $FECHA|cut -d"/" -f1))

				lineloop=2
				contratoNumero=$(( contratoNumero + 1 ))
				contratosProcesados=$(( contratosProcesados + 1 ))
				printf "Procesado contrato: %s de %s, pagina %s de %s\n" $contratoNumero $contratosFecha $pagina $totalPaginasFecha
			else
				echo "No hay referencia!!!"
				ERRORES.="ERROR en referencia ${REF}. Fecha: $FECHA, contrato $contratoNumero\n"
				sleep 2
			fi
		else
			# Para la linea "--" de grep
			if [[ $class == '--' ]];then
				lineloop=0
			fi
		fi
	done <<< "$entradas"
}

function getDateHTMLFilename()
{
	# $1 FECHA, $2 pagina
	f=$(date -d $1 +"%Y%m%d")
	p=$(printf "%05d" $2)
	echo "$HTMLDIR/${PATTERN}_${f}_p${p}.html"
}

function incrementa_dia()
{
	__YEAR=$(date -d $1 +"%Y")
	__MONTH=$(date -d $1 +"%m")
	__DAY=$(date -d $1 +"%d")
	__DAY=$(( ${__DAY#0} + 1 ))
	if [[ $__MONTH == "04" || $__MONTH == "06" || $__MONTH == "09" || $__MONTH == "11" ]];then
		__LIMIT=30
	elif [[ $__MONTH == "02" ]];then
		if (( ${__YEAR#0} % 4 == 0 ));then
		   __LIMIT=29
		else
		   __LIMIT=28
		fi
	else
		__LIMIT=31
	fi
	if (( $__DAY > $__LIMIT ));then
		__DAY=1
		__MONTH=$(( ${__MONTH#0} + 1 ))
		if (( __MONTH > 12 ));then
			__MONTH=1
			__YEAR=$(( __YEAR + 1 ))
		fi
	fi
	printf "%s-%02d-%02d" $__YEAR ${__MONTH#0} $__DAY
}

function incrementa_mes()
{
	__YEAR=$(date -d $1 +"%Y")
	__MONTH=$(date -d $1 +"%m")
	__DAY=$(date -d $1 +"%d")
	__MONTH=$(( ${__MONTH#0} + 1 ))
	if (( __MONTH > 12 ));then
		__MONTH=1
		__YEAR=$(( __YEAR + 1 ))
	fi
	printf "%s-%02d-%02d" $__YEAR ${__MONTH#0} $__DAY
}

function procesarReferencia()
{
	WGETDATEDIR=$(date -d $FDATE +"%Y/%m")
	WGETDATEFILE="${WGETDATEDIR}/${PATTERN}_"$(date -d $FDATE +"%Y%m%d")"_latin1.csv"
	piped=$(buscarFechaFormalizacion $FDATE)
	refline=$(echo "$piped"|grep -a -w "$REF")

	if [[ $refline ]];then
		ifs="$IFS"
		while IFS="|" read -r tipo _entidad expediente referencia _objeto tipoc proc presup nif adju importe
		do
			entidad=$(comillas "$_entidad")
			objeto=$(comillas "$_objeto")
			echo "$FECHA|$tipo|\"$entidad\"|$expediente|$referencia|\"$objeto\"|$tipoc|$proc|$presup|$nif|$adju|$siniva|$importe" >> $ISOFILE
		done <<< "$refline"
		IFS="$ifs"
	else
		echo "FALLA $WGETDATEFILE!!"
		contratosMal=$(( contratosMal + 1 ))
	fi

}

function procesarReferenciasMes()
{
	# Lee los ficheros del mes de lo descargado manualmente buscando las referencias
	# Se genera el fichero del día con todos los datos.
	FDATE=$1
	WGETDATEDIR=$(date -d $FDATE +"%Y/%m")

	for csvfile in $(ls ${WGETDATEDIR}/${PATTERN}*_latin1.csv);do
		WGETDATEFILE=$csvfile
		if [[ -f "${WGETDATEFILE}.OK" ]];then
			WGETDATEFILE="${WGETDATEFILE}.OK"
		fi
		HEADER="0"

		ifs="$IFS"
		while IFS=";" read -r tipo _entidad expediente referencia _objeto tipoc proc presup nif adju importe
		do
			entidad=$(comillas "$_entidad")
			objeto=$(comillas "$_objeto")
			if [[ $HEADER == "0" ]];then
				HEADER="FECHA|$tipo|$entidad|$expediente|$referencia|$objeto|$tipoc|$proc|$presup|$nif|$adju|IMPORTE SIN IVA|$importe|CID"
			else
				set +x
				REF=$(echo $referencia|sed 's@"@@g')
				REFCSV="$REFDIR/${REF}.csv"
				IFS="|" read _FECHA siniva CID < $REFCSV
				if [[ -z $siniva ]];then
					siniva=$CID
					CID='""'
					echo "poniendo siniva=$siniva en $REF de fecha $_FECHA"
				fi
				if [[ $(echo $siniva|grep [^0-9.,]) ]];then
					echo "cambiando numero erroneo $siniva <-> $CID en $REF"
					echo "$_FECHA|$CID|$siniva" > $REFCSV
					IFS="|" read _FECHA siniva CID < $REFCSV
				fi
				set +x
				FECHA=$(echo $_FECHA|awk -F/ '{ print $3"-"$2"-"$1 }')
				FULLDATEFILE="$WGETDATEDIR/${PATTERN}_"$(date -d $FECHA +"%Y%m%d")"_UTF8.csv"
				if [[ ! -f $FULLDATEFILE ]];then
					echo "$HEADER"|sed 's@"@@g'| iconv -f ISO-8859-1 -t UTF-8 > $FULLDATEFILE
					HEADER="1"
				fi
				echo "$FECHA|$tipo|\"$entidad\"|$expediente|$referencia|\"$objeto\"|$tipoc|$proc|$presup|$nif|$adju|$siniva|$importe|\"$CID\""| iconv -f ISO-8859-1 -t UTF-8 >> $FULLDATEFILE
			fi
		done < "$WGETDATEFILE"
		IFS="$ifs"
	done

}

function url ()
{
	newurl=$(echo "$1"|sed 's@/@%2F@g')
  echo "$newurl"
}
