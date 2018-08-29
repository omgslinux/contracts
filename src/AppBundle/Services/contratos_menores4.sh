#!/bin/bash
INICIO=${1:-2016-01-01}
if [[ -z $2 ]];then
	FIN=$(date +"%Y-%m-%d")
else
	FIN=$2
fi

. funciones_contratosmenores_inc.sh

#Búsqueda inicial para calcular tiempo según el total de contratos
#nale=$(shuf -i1-${UALINES} -n1)
#useragent=$(cat user-agent.txt | head -n$nale | tail -n1)

contratosProcesados=0

CURRENT=$INICIO

while [[ $CURRENT < $FIN || $CURRENT == $FIN ]];do
	procesarReferenciasMes $CURRENT
	CURRENT=$(incrementa_mes $CURRENT)
done
