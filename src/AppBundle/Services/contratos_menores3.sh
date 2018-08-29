#!/bin/bash
INICIO=${1:-2016-01-01}
if [[ -z $2 ]];then
	FIN=$(date +"%Y-%m-%d")
else
	FIN=$2
fi

. funciones_contratosmenores_inc.sh

#Búsqueda inicial para calcular tiempo según el total de contratos
nale=$(shuf -i1-${UALINES} -n1)
useragent=$(cat user-agent.txt | head -n$nale | tail -n1)
buscarResultadosFechas $INICIO $FIN
res0=$(echo "$buscar"|grep txt08gr1)
res=$(echo $res0|cut -d: -f2|cut -d. -f1)
contratosFechas=$(echo $res)

if [[ -z $res ]]; then
	echo "ERROR"
	exit 1
fi

contratosRestantes=$contratosFechas
contratosProcesados=0
contratosYaCacheados=0
contratosNoCacheados=0
rm $HTMLRESULTADOSFILE

CURRENT=$INICIO

while [[ $CURRENT < $FIN || $CURRENT == $FIN ]];do

	nale=$(shuf -i1-${UALINES} -n1)
	useragent=$(cat user-agent.txt | head -n$nale | tail -n1)

	PREFIJO="${PATTERN}_${CURRENT}"
	HTMLFILE="$PREFIJO.html"
	WGETFILE="${PREFIJO}_wget.tmp"

	#clear
	#UTF8FILE="$PREFIJO.csv"
	#ISOFILE="$PREFIJO.tmp"
	buscarResultadosFechas $CURRENT $CURRENT
	contratoNumero=0
	contratosMal=0
	res0=$(echo "$buscar"|grep txt08gr1)
	res=$(echo $res0|cut -d: -f2|cut -d. -f1)
	contratosFecha=$(echo $res)
	if (( ${contratosFecha#0} % 10 == 0 ));then
		e=0
	else
		e=1
	fi
	totalPaginasFecha=$((( contratosFecha / 10 ) + e ))
	echo "resultados: $contratosFecha, totalPaginasFecha: $totalPaginasFecha"
	#echo "FECHA|TIPO DE PUBLICACIÓN|ENTIDAD ADJUDICADORA|Nº EXPEDIENTE|REFERENCIA|OBJETO DEL CONTRATO|TIPO CONTRATO|PROCEDIMINETO DE ADJUDICACIÓN|PRESUPUESTO DE LICITACIÓN(CON IVA)|NIF ADJUDICATARIO|ADJUDICATARIO|IMPORTE DE ADJUDICACIÓN(SIN IVA)|IMPORTE DE ADJUDICACIÓN(CON IVA)"|iconv -f UTF-8 -t ISO-8859-1 > $ISOFILE
	pagina=1
	buscarFechaPagina $CURRENT $pagina
	cacheDateHTML $CURRENT $pagina
	extraerReferenciasPagina

	while [[ $totalPaginasFecha -gt $pagina ]];do
		#pagina=$(( pagina + 1 ))
		(( pagina++ ))
		buscarFechaPagina $CURRENT $pagina
		cacheDateHTML $CURRENT $pagina
		extraerReferenciasPagina
		calcularTiempoRestante
	done
	CURRENT=$(incrementa_dia $CURRENT)
done

printf "\n\nContratos previamente cacheados: %s. Contratos cacheados ahora: %s. Total contratos: %s\n" $contratosYaCacheados $contratosNoCacheados $contratosFechas

echo "$ERRORES"
