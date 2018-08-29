<?php
$INICIO=$argv[1];
$FIN=$argv[2];

$INIDATE=new \DateTime($INICIO);
$FINDATE=new \DateTime($FIN);
/*
#export LANG=es_ES.UTF-8
INIDAY=$(date -d $INICIO +"%d")
INIMONTH=$(date -d $INICIO +"%m")
INIYEAR=$(date -d $INICIO +"%Y")
INIPREF=$(date -d $INICIO +"%Y%m%d")
FINDAY=$(date -d $FIN +"%d")
FINMONTH=$(date -d $FIN +"%m")
FINYEAR=$(date -d $FIN +"%Y")
FINPREF=$(date -d $FIN +"%Y%m%d")
*/
$PATTERN="contratosmenores";
$PREFIJO="${PATTERN}_". $INIDATE->format('%Y%m%d') .'_' . $FINDATE->format('%Y%m%d');
$UTF8FILE="$PREFIJO.csv";
$ISOFILE="$PREFIJO.tmp";
$HTMLFILE="$PREFIJO.html";
$WGETFILE="${PREFIJO}_wget.tmp";
$UALINES=system("cat user-agent.txt|wc -l");
$DATETIMESTART=new \DateTime();
//DATEHUMAN=$(date -d "@$DATETIMESTART" +"%d/%m/%Y %T")
#http_proxy="http://127.0.0.1:9050"
#TOR=1
#Cache para referencias
$REFDIR="REFS";
system("mkdir -p $REFDIR");

class PortalSearch
{


	# Variables input hidden
	private $SITEURL="http://www.madrid.org/cs/";
	private	$pageid	= '1142536600028';
	private	$pagename	= 'PortalContratacion/Comunes/Presentacion/PCON_resultadoBuscadorAvanzado';
	private $COMMONDATA=[
		'language'	=> 'es',
		'entidadAdjudicadora'	=> '1109266187266',
		'pagename'	=> $this->pagename,
		'fechaFormalizacionDesde'	=> '',
		'fechaFormalizacionHasta'	=> '',
		'tipoPublicacion'	=> 'Contratos Menores',
		'procedimientoAdjudicacion'	=> 'Contratos Menores',
	];
	private $POSTDATA=[
		'_charset_'	=> 'UTF-8',
		'pageid'	=> $this->pageid,
		'numeroExpediente' => '',
		'referencia'	=> '',
	];
	private $GETDATA=[
 		'c'	=> 'Page',
		'cid'	=> '1142536600028',
		'codigo'	=> 'PCON_',
		'idPagina'	=> $this->pageid,
		'newPagina'	=> 0, // param
		'numPagListado'	=> 5,
		'paginaActual'	=> 0, // $pagina-1
		'paginasTotal'=> 0, //param
		'rootelement'	=> $this->pagename,
		'site'	=> 'PortalContratacion',
	];
	private $buscar;

	public function setStartDate($date)
	{
			$d=new \DateTime($date);
			$this->COMMONDATA['fechaFormalizacionDesde']=$d->format('d-m-Y');
	}

	public function setEndDate($date)
	{
			$d=new \DateTime($date);
			$this->COMMONDATA['fechaFormalizacionHasta']=$d->format('d-m-Y');
	}

	public function buscarFechaFormalizacion (\DateTime $start, \DateTime $end)
	{
		$this->setStartDate($start);
		$this->setEndDate($end);
		$URL=$this->SITEURL."Satellite";
		//$POSTDATA="_charset_=UTF-8&language=es&pagename=${h_pagename}&pageid=${h_pageid}&entidadAdjudicadora=${entidadAdjudicadora}&numeroExpediente=${numeroExpediente}&referencia=${referencia}&tipoPublicacion=${tipoPublicacion}&procedimientoAdjudicacion=${procedimientoAdjudicacion}&fechaFormalizacionDesde=${fechaFormalizacionDesde}&fechaFormalizacionHasta=${fechaFormalizacionHasta}";
		$POSTDATA="";
		foreach ($this->POSTDATA as $key => $value) {
			$POSTDATA.="$key=$value&";
		}
		foreach ($this->COMMONDATA as $key => $value) {
			$POSTDATA.="$key=$value&";
		}

		//	torsocks wget --user-agent=Mozilla/5.0 --post-data="$POSTDATA" $URL -O $HTMLFILE

		$this->buscar=file_get_contents($HTMLFILE);
	}
}

function buscarPaginaNumero(\DateTime $start, \DateTime $end,$pagina,$paginasTotal)
{
	$this->setStartDate($start);
	$this->setEndDate($end);
	$this->GETDATA['newPagina']=$pagina;
	$this->GETDATA['paginaActual']=$pagina-1;
	$this->GETDATA['paginasTotal']=$paginasTotal;
	print "Buscando página ${newPagina}: ";
	//SITE="http://www.madrid.org/cs/Satellite"
	//$URL=url ("?c=Page&cid=1142536600028&codigo=PCON_&entidadAdjudicadora=${entidadAdjudicadora}&fechaFormalizacionDesde=${fechaFormalizacionDesde}&fechaFormalizacionHasta=${fechaFormalizacionHasta}&idPagina=${h_pageid}&language=es&newPagina=${pagina}&numPagListado=5&pagename=${h_pagename}&paginaActual=${paginaActual}&paginasTotal=${totalPaginas}&procedimientoAdjudicacion=Contratos+Menores&rootelement=PortalContratacion%2FComunes%2FPresentacion%2FPCON_resultadoBuscadorAvanzado&site=PortalContratacion&tipoPublicacion=Contratos+Menores");
		$GETDATA="";
		foreach ($this->GETDATA as $key => $value) {
			$GETDATA.="$key=". urlencode($value).'&';
		}
		foreach ($this->COMMONDATA as $key => $value) {
			$GETDATA.="$key=". urlencode($value).'&';
		}

	//torsocks wget -q --user-agent="$useragent" $SITE$URL -O $HTMLFILE
	$this->buscar=file_get_contents($HTMLFILE);
}


function comillas ($text)
{
	$comilla=preg_replace('"','""',
		preg_replace('"$', '',
		preg_replace('^"','',$text))
	);
	//comilla=$(echo "$1"|sed -e 's/^"//' | sed -e 's/"$//' | sed -e 's/"/""/g')
	return $comilla;
}

function procesarPagina($buscar)
{
	entradas=$(echo "$buscar"|grep txt07azu -A1)
	lineloop=0
	while read -r class href linea;do
		if [[ $lineloop == 0 ]];then
			urlcontrato=$(echo $linea|cut -d\' -f2)
			#urlcid="http://www.madrid.org/cs/Satellite?c=CM_ConvocaPrestac_FA&${CID}&definicion=Contratos+Publicos&idPagina=1142536600028&language=es&op2=PCON&pagename=PortalContratacion%2FPage%2FPCON_contratosPublicos&tipoServicio=CM_ConvocaPrestac_FA"
			URL="http://www.madrid.org${urlcontrato}"

			torsocks wget -q --user-agent="$useragent" $URL -O $WGETFILE
			htmlcontrato=$(cat $WGETFILE)
			siniva=$(echo "$htmlcontrato"|grep -B 2 '/tbody' |head -n 1|sed 's/<td>//g'|sed 's@</td>@@g')
			lineafecha=$(echo "$htmlcontrato"|grep "Fecha del contrato")
			__FECHA=$(echo "$lineafecha"|cut -d\> -f5|cut -d\< -f1)
			_FECHA=$(echo "$__FECHA"|tr [A-Z] [a-z] |sed 's@ enero @/01/@'|sed 's@ febrero @/02/@'|sed 's@ marzo @/03/@'|sed 's@ abril @/04/@'|sed 's@ mayo @/05/@'|sed 's@ junio @/06/@'|sed 's@ julio @/07/@'|sed 's@ agosto @/08/@'|sed 's@ septiembre @/09/@'|sed 's@ octubre @/10/@'|sed 's@ noviembre @/11/@'|sed 's@ diciembre @/12/@')
			FECHA=$(echo "$_FECHA"|sed 's@ january @/01/@'|sed 's@ february @/02/@'|sed 's@ march @/03/@'|sed 's@ april @/04/@'|sed 's@ may @/05/@'|sed 's@ june @/06/@'|sed 's@ july @/07/@'|sed 's@ august @/08/@'|sed 's@ september @/09/@'|sed 's@ october @/10/@'|sed 's@ november @/11/@'|sed 's@ december @/12/@')
			printf "lineafecha: %s, FECHA: %s. " "$__FECHA" $FECHA
			FDATE=$(printf "%s%s%s" $(echo $FECHA|cut -d"/" -f3) $(echo $FECHA|cut -d"/" -f2) $(echo $FECHA|cut -d"/" -f1))
			lineloop=1
		elif [[ $lineloop == 1 ]];then
			REF=$(echo "$linea"|grep "Ref:" |cut -d\; -f2|cut -d\< -f1)
			echo "Buscando referencia: $REF"
			if [[ $REF ]];then
				REFCSV="$REFDIR/${REF}.csv"
				if [[ ! -f $REFCSV ]];then
					echo "$FECHA|$siniva" > $REFCSV
				fi

				WGETDATEDIR=$(date -d $FDATE +"%Y/%m")
				WGETDATEFILE="${WGETDATEDIR}/${PATTERN}_"$(date -d $FDATE +"%Y%m%d")"_latin1.csv"
				piped=$(buscarFechaFormalizacion $FDATE)
				#set -x
				refline=$(echo "$piped"|grep "\|$REF\|")

				if [[ $refline ]];then
					set +x
					ifs="$IFS"
					while IFS="|" read -r tipo _entidad expediente referencia _objeto tipoc proc presup nif adju importe
					do
						entidad=$(comillas "$_entidad")
						objeto=$(comillas "$_objeto")
						echo "$FECHA|$tipo|\"$entidad\"|$expediente|$referencia|\"$objeto\"|$tipoc|$proc|$presup|$nif|$adju|$siniva|$importe" >> $ISOFILE
					done <<< "$refline"
					IFS="$ifs"
				else
					set +x
					echo "FALLA $WGETDATEFILE!!"
					contratosMal=$(( contratosMal + 1 ))
				fi
				lineloop=2
				contratoNumero=$(( contratoNumero + 1 ))
				printf "Procesado contrato: %s de %s, pagina %s de %s\n" $contratoNumero $resultados $pagina $totalPaginas
			else
				echo "No hay referencia!!!"
			fi
		else
			# Para la linea "--" de grep
			lineloop=0
		fi
	done <<< "$entradas"

}

buscar $INICIO $FIN
#clear
pagina=1
contratoNumero=0
contratosMal=0
res0=$(echo "$buscar"|grep txt08gr1)
res=$(echo $res0|cut -d: -f2|cut -d. -f1)
resultados=$(echo $res)
totalPaginas=$((( resultados / 10 ) + 1))
echo "resultados: $resultados, totalPaginas: $totalPaginas"
echo "FECHA|TIPO DE PUBLICACIÓN|ENTIDAD ADJUDICADORA|Nº EXPEDIENTE|REFERENCIA|OBJETO DEL CONTRATO|TIPO CONTRATO|PROCEDIMINETO DE ADJUDICACIÓN|PRESUPUESTO DE LICITACIÓN(CON IVA)|NIF ADJUDICATARIO|ADJUDICATARIO|IMPORTE DE ADJUDICACIÓN(SIN IVA)|IMPORTE DE ADJUDICACIÓN(CON IVA)"|iconv -f UTF-8 -t ISO-8859-1 > $ISOFILE

procesarPagina


while [[ $totalPaginas -gt $pagina ]];do
	pagina=$(( pagina + 1 ))
	nale=$(shuf -i1-${UALINES} -n1)
	useragent=$(cat user-agent.txt | head -n$nale | tail -n1)
	buscarPagina
  if [[ $buscar ]];then
		procesarPagina
  fi

	NOW=$(date +"%s")
	AVG=$((( NOW - DATETIMESTART ) / pagina ))
  echo -n "Media por página: $AVG"
  REMAIN=$((( totalPaginas - pagina ) * AVG ))
  SECONDSEND=$(( NOW + REMAIN ))
	DATEEND=$(date -d "@$SECONDSEND" +"%d/%m/%Y %T")
  printf " Fin: %s %s Faltan: %s segundos\n\n" $DATEEND $REMAIN
done

iconv -f ISO-8859-1 -t UTF-8 $ISOFILE > $UTF8FILE
contratosOK=$(( contratoNumero - contratosMal ))
echo "Número de contratos OK: $contratosOK de $contratoNumero, paginas: $pagina"
echo "Archivo: $UTF8FILE"

rm $HTMLFILE $WGETFILE
