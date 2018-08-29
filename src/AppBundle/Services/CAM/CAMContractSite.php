<?php

namespace AppBundle\Services\CAM;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


#class CamContractSite extends ContainerAwareInterface
class CAMContractSite
{
	private $csvFields=[
		"FECHA",
		"TIPO DE PUBLICACIÓN",
		"ENTIDAD ADJUDICADORA",
		"Nº EXPEDIENTE",
		"REFERENCIA",
		"OBJETO DEL CONTRATO",
		"TIPO CONTRATO",
		"PROCEDIMINETO DE ADJUDICACIÓN",
		"PRESUPUESTO DE LICITACIÓN(CON IVA)",
		"NIF ADJUDICATARIO",
		"ADJUDICATARIO",
		"IMPORTE DE ADJUDICACIÓN(SIN IVA)",
		"IMPORTE DE ADJUDICACIÓN(CON IVA)"
	];

	# Variables input hidden
	private $SITEURL="http://www.madrid.org/cs/";
	private	$pageid	= '1142536600028';
	private	$pagename	= 'PortalContratacion/Comunes/Presentacion/PCON_resultadoBuscadorAvanzado';
	private $commonFields=[
		'language',
		'entidadAdjudicadora',
		'pagename',
		'fechaFormalizacionDesde',
		'fechaFormalizacionHasta',
		'tipoPublicacion',
		'procedimientoAdjudicacion',
	];
	private $commonData=[
		'language'	=> 'es',
		'entidadAdjudicadora'	=> '1109266187266',
		//'pagename'	=> $this->pagename,
		'fechaFormalizacionDesde'	=> '',
		'fechaFormalizacionHasta'	=> '',
		'tipoPublicacion'	=> 'Contratos Menores',
		'procedimientoAdjudicacion'	=> 'Contratos Menores',
	];
	private $POSTDATA=[
		'_charset_'	=> 'UTF-8',
		//'pageid'	=> $this->pageid,
		'numeroExpediente' => '',
		'referencia'	=> '',
	];
	private $GETDATA=[
 		'c'	=> 'Page',
		'cid'	=> '1142536600028',
		'codigo'	=> 'PCON_',
		//'idPagina'	=> $this->pageid,
		'newPagina'	=> 0, // param
		'numPagListado'	=> 5,
		'paginaActual'	=> 0, // $pagina-1
		'paginasTotal'=> 0, //param
		//'rootelement'	=> $this->pagename,
		'site'	=> 'PortalContratacion',
	];
	private $buscar;
	private $useragent=[];

	public function __construct()
	{
		mkdir($this->getRefsDir());
		$this->useragent=file($this->getParameter('%UserAgentFile%'));
	}


	public function getCacheDir()
	{
		return $this->getParameter('%CAMCacheDir%');
	}

	public function getRefsDir()
	{
		return $this->getCacheDir()."/REFS/";
	}

	private function getCommonData()
	{
		$commonData="";
		foreach ($this->commonData as $key => $value) {
			$commonData .="${key}=${value}&";
		}
		return $commonData;
	}

	private function getPostData()
	{
		$postData=$this->getCommonData();
		foreach ($this->postData as $key => $value) {
			$postData .="${key}=${value}&";
		}
		return $postData;
	}

	public function setCommonData(array $commonData)
	{
		foreach ($commonData as $key => $value) {
			$this->commonData[$key]=$value;
		}

		return $this;
	}

	public function setStartDate($date)
	{
			$d=new \DateTime($date);
			$this->commonData['fechaFormalizacionDesde']=$d->format('d-m-Y');
	}

	public function setEndDate($date)
	{
			$d=new \DateTime($date);
			$this->commonData['fechaFormalizacionHasta']=$d->format('d-m-Y');
	}

	function buscarDatosFecha ($date)
	{
		if (!file_exists($WGETDATEFILE)) {
			echo "No existe $WGETDATEFILE";

			mkdir($WGETDATEDIR,true);

			$a=new \DateTime($date);
			$this->setCommonData(
				[
					'fechaFormalizacionDesde' => $a->format('d/m/Y'),
					'fechaFormalizacionHasta' => $a->format('d/m/Y'),
				]
			);

			$POSTDATA=$this->getCommonData();
			system("torsocks wget -q --user-agent=\"$useragent\" --post-data=\"$POSTDATA\" ${SITEURL}/cs/Satellite -O $HTMLFILE");
			$buscar=file_get_contents($HTMLFILE);
			if ($buscar) {
				$EXPORTURL=system("echo \"$buscar\"|grep exportresultados|cut -d= -f5-7|cut -d\" -f2");
				//echo "EXPORTURL: $EXPORTURL";
				system("torsocks wget --user-agent=\"$useragent\" ${SITEURL}${EXPORTURL} -O $WGETDATEFILE");
			}	else {
				echo "ERROR EN $HTMLFILE";
			}
		}	else {
			echo "existe $WGETDATEFILE. Usando caché para $1";
		}
	}

	function buscarFechaPagina ($current, $p)
	{
		$date=new \DateTime($current);
		$f=$date->format("%d/%m/%Y");
		//echo "Buscando fecha $f página $p de $totalPaginasFecha: ";
		$HTMLDATEFILENAME=$this->getDateHTMLFilename($f, $p);
		if (!file_exists($HTMLDATEFILENAME)) {
			$b=file_get_contents($HTMLDATEFILENAME);
			if (strlen($b<100)) {
				unlink($HTMLDATEFILENAME);
			}
		}

		if (!file_exists($HTMLDATEFILENAME)) {
			//echo -n "NO ";
			$this->setStartDate($start);
			$this->setEndDate($end);
			$this->paginaActual=$p-1;
			$SITE="http://www.madrid.org/cs/Satellite";
			//$URL=urlencode("?c=Page&cid=1142536600028&codigo=PCON_&entidadAdjudicadora=${entidadAdjudicadora}&fechaFormalizacionDesde=${f}&fechaFormalizacionHasta=${f}&idPagina=${h_pageid}&language=es&newPagina=${p}&numPagListado=5&pagename=${h_pagename}&paginaActual=${paginaActual}&paginasTotal=${totalPaginas}&procedimientoAdjudicacion=Contratos+Menores&rootelement=PortalContratacion%2FComunes%2FPresentacion%2FPCON_resultadoBuscadorAvanzado&site=PortalContratacion&tipoPublicacion=Contratos+Menores");
			$GETDATA="";
			foreach ($this->GETDATA as $key => $value) {
				$GETDATA.="$key=". urlencode($value).'&';
			}
			foreach ($this->COMMONDATA as $key => $value) {
				$GETDATA.="$key=". urlencode($value).'&';
			}
			system("torsocks wget -q --user-agent=\"$useragent\" $SITE$URL -O $HTMLDATEFILENAME");
		}
		//echo "cacheado";

		$this->buscar=file_get_contents($HTMLDATEFILENAME);
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

	function cacheDateHTML($fecha, $pagina)
	{
		$HTMLDATEFILENAME=$this->getDateHTMLFilename($fecha, $pagina);
		$this->entradas=system("echo \"" . $this->buscar ."\"|grep txt07azu -A2|grep -v '#Top'|grep -v SUBIR");
		if (file_exists($HTMLDATEFILENAME)) {
			if (filesize($HTMLDATEFILENAME) < 10000) {
				unlink($HTMLDATEFILENAME);
			}
		}

		if (!file_exists($HTMLDATEFILENAME)){
			file_put_contents($HTMLDATEFILENAME, $this->entradas);
			//sleep(5);
		}
	}

	function cacheReferenciaHTML($REFHTML)
	{
		if (file_exists($REFHTML)) {
			$stat=stat($REFHTML);
			if ($stat['size'] < 30) {
				unlink($REFHTML);
			}
		}

		if (!file_exists($REFHTML)) {
			$datosReferencia=system("echo \"". $this->buscar. "\"|grep tit11gr3 -A60");
			file_put_contents($REFHTML, $datosReferencia);
			//contratosNoCacheados=$(( contratosNoCacheados + 1 ))
			return false;
		} else {
			//contratosYaCacheados=$(( contratosYaCacheados + 1 ))
			return true;
		}
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
/*
	function extraerReferenciasPagina()
	{
		#entradas=$(echo "$buscar"|grep txt07azu -A2|grep -v '#Top'|grep -v SUBIR)
		//$lineas=file($this->entradas);
		$buscar=$this->buscar;
		$lineloop=0;
		foreach ($this->entradas as $linea) {
			if ($lineloop === 0) {
				$array=explode("'", $linea);

				//urlcontrato=$(echo $linea|cut -d\' -f2)
				$urlcontrato=$array[1];
				//idoc=$(echo $urlcontrato|cut -d\& -f4|cut -d= -f2)
				$href=explode('&', $urlcontrato);
				$href3=$href[3];
				$p=explode('=', $href3);
				$idoc=$p[1];
				#urlcid="http://www.madrid.org/cs/Satellite?c=CM_ConvocaPrestac_FA&${CID}&definicion=Contratos+Publicos&idPagina=1142536600028&language=es&op2=PCON&pagename=PortalContratacion%2FPage%2FPCON_contratosPublicos&tipoServicio=CM_ConvocaPrestac_FA"
				$URL="http://www.madrid.org${urlcontrato}";

				$lineloop=1;
			} elseif ($lineloop == 1) {

				//REF=$(echo "$linea"|grep "Ref:" |cut -d\; -f2|cut -d\< -f1)
				echo "Buscando referencia: ";
				$pos=strpos($linea,'Ref:');
				if($pos){
					$ref2=explode(';',$linea);
					$ref3=explode('<',$ref2[1]);
					$REF=$ref3[0];
				}

				if ($REF){
					$REFHTML=$this->getRefsDir() . "/${REF}.html";
					if (file_exists($REFHTML)) {
						$pos=strpos(file_get_contents($REFHTML), 'txt07');
						if ($pos===false) {
							unlink($REFHTML);
						}
					} else {
						$stat=stat($REFHTML);
						if ($stat['size']<100) {
							unlink($REFHTML);
						}
					}

					if (!file_exists($REFHTML)) {
						echo "NO ";
						system("torsocks wget -q --user-agent=\"$useragent\" $URL -O $REFHTML");
					}
					echo "cacheado";
					$this->buscar=file_get_contents($REFHTML);
					$this->cacheReferenciaHTML($REFHTML);

					$REFCSV=$this->getRefsDir(). "/${REF}.csv";
					if (file_exists($REFCSV)) {
						$stat=stat($REFCSV);
						if ($stat['size'] < 21) {
							unlink($REFCSV);
						}
					}

					if (!file_exists($REFCSV)) {
						$siniva=system("echo \"$buscar\"|grep -B 2 '/tbody' |head -n 1|sed 's/<td>//g'|sed 's@</td>@@g'");
						$lineafecha=system("echo \"$buscar\"|grep \"Fecha del contrato\"");
						$__FECHA=system("echo \"$lineafecha\"|cut -d\> -f5|cut -d\< -f1");
						$_FECHA=system("echo \"$__FECHA\"|tr [A-Z] [a-z] |sed 's@ enero @/01/@'|sed 's@ febrero @/02/@'|sed 's@ marzo @/03/@'|sed 's@ abril @/04/@'|sed 's@ mayo @/05/@'|sed 's@ junio @/06/@'|sed 's@ julio @/07/@'|sed 's@ agosto @/08/@'|sed 's@ septiembre @/09/@'|sed 's@ octubre @/10/@'|sed 's@ noviembre @/11/@'|sed 's@ diciembre @/12/@'");
						$FECHA=system("echo \"$_FECHA\"|sed 's@ january @/01/@'|sed 's@ february @/02/@'|sed 's@ march @/03/@'|sed 's@ april @/04/@'|sed 's@ may @/05/@'|sed 's@ june @/06/@'|sed 's@ july @/07/@'|sed 's@ august @/08/@'|sed 's@ september @/09/@'|sed 's@ october @/10/@'|sed 's@ november @/11/@'|sed 's@ december @/12/@'");
						printf("lineafecha: %s, FECHA: %s. ", $__FECHA, $FECHA);

						# Cacheo de CSV
						file_put_contents($REFCSV, "$FECHA|$idoc|$siniva");
					} else {

						//IFS="|" read FECHA siniva < $REFCSV
					}
					FDATE=$(printf "%s%s%s" $(echo $FECHA|cut -d"/" -f3) $(echo $FECHA|cut -d"/" -f2) $(echo $FECHA|cut -d"/" -f1))

					lineloop=2
					contratoNumero=$(( contratoNumero + 1 ))
					contratosProcesados=$(( contratosProcesados + 1 ))
					printf "Procesado contrato: %s de %s, pagina %s de %s\n" $contratoNumero $contratosFecha $pagina $totalPaginasFecha
				} else {

					echo "No hay referencia!!!"
					ERRORES="${ERRORES}ERROR en referencia ${CURRENT}. Fecha: $FECHA, contrato $contratoNumero\n"
					sleep 2
				}
				}
			else
				# Para la linea "--" de grep
				if ($class == '--') {
					$lineloop=0
				}
			}
		}

	function getDateHTMLFilename($fecha, $pagina)
	{
		# $1 FECHA, $2 pagina
		$f=new \DateTime($fecha);
		$f=$f->format("Ymd");
		$p=sprintf("%05d", $pagina);
		return "$HTMLDIR/${PATTERN}_${f}_p${p}.html";
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

*/

}
