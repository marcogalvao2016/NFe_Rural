<?php

use GuzzleHttp\Psr7\Message;

error_reporting(E_ERROR);
ini_set('display_errors', 'On');

use NFePHP\NFe\Tools;
use NFePHP\NFe\Make;
use NFePHP\Common\Certificate;

use NFePHP\DA\NFe\Danfe;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;

function emissaoNFe(
    $id,
    $pNumeroNFe,
    $pEmitente,
    $pPedido,
    $pDestinatario,
    $pProdutos
) {
    $arr = [
        "atualizacao" => "2017-02-20 09:11:21",
        "tpAmb" => 2,
        "razaosocial" => $pEmitente['razaosocial'],
        "cnpj" => $pEmitente['cnpj'],
        "siglaUF" => $pEmitente['uf'],
        "schemes" => "PL_009_V4",
        "versao" => '4.00',
        "tokenIBPT" => "AAAAAAA",
        "CSC" => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
        "CSCid" => "000001",
        "proxyConf" => [
            "proxyIp" => "",
            "proxyPort" => "",
            "proxyUser" => "",
            "proxyPass" => ""
        ]
    ];

    $certPath = __DIR__ . '/../notafiscal/cert.pfx';

    $configJson = json_encode($arr);
    $pfxcontent = file_get_contents($certPath);

    $tools = new Tools($configJson, Certificate::readPfx($pfxcontent, '123'));

    //$tools->disableCertValidation(true); //tem que desabilitar
    $tools->model('55');

    $vNumeroscNF = generateRandomNumberString();
    $vContadorItem = 0;
    $vTotalItens = 0;

    try {
        $make = new Make();

        //infNFe OBRIGATÓRIA
        $std = new \stdClass();
        $std->Id = '';
        $std->versao = '4.00';
        $infNFe = $make->taginfNFe($std);

        //ide OBRIGATÓRIA
        $std = new \stdClass();
        $std->cUF = 51;
        $std->cNF = $vNumeroscNF;
        $std->natOp = 'VENDA CONSUMIDOR';
        $std->mod = 55;
        $std->serie = 1;
        $std->nNF = $pNumeroNFe;
        $std->dhEmi = (new \DateTime())->format('Y-m-d\TH:i:sP');
        $std->dhSaiEnt = null;
        $std->tpNF = 1;
        $std->idDest = 1;
        $std->cMunFG = 5103403;
        $std->tpImp = 1;
        $std->tpEmis = 1;
        $std->cDV = 2;
        $std->tpAmb = 2; //Homologação
        $std->finNFe = 1;
        $std->indFinal = 1;
        $std->indPres = 1;
        $std->procEmi = 3;
        $std->verProc = '4.13';
        $std->dhCont = null;
        $std->xJust = null;
        $ide = $make->tagIde($std);

        //emit OBRIGATÓRIA
        $std = new \stdClass();
        $std->xNome = $pEmitente['razaosocial'];
        $std->xFant = $pEmitente['nomefantasia'];
        $std->IE = $pEmitente['insestadual'];
        $std->IEST = null;
        //$std->IM = '95095870';
        $std->CNAE = '';
        $std->CRT = 1;
        $std->CNPJ = $pEmitente['cnpj'];
        //$std->CPF = '12345678901'; //NÃO PASSE TAGS QUE NÃO EXISTEM NO CASO
        $emit = $make->tagemit($std);

        //enderEmit OBRIGATÓRIA
        $std = new \stdClass();
        $std->xLgr = $pEmitente['endereco'];
        $std->nro = $pEmitente['numero'];
        $std->xCpl = $pEmitente['observacoes'];
        $std->xBairro = $pEmitente['bairro'];
        $std->cMun = 5103403;
        $std->xMun = $pEmitente['cidade'];
        $std->UF = 'MT';
        $std->CEP = $pEmitente['cep'];
        $std->cPais = 1058;
        $std->xPais = 'Brasil';
        $std->fone = $pEmitente['telefone1'];
        $ret = $make->tagenderemit($std);

        //dest OPCIONAL
        $std = new \stdClass();
        $std->xNome = $pDestinatario['razaosocial'];

        if (strlen($pDestinatario['cfpcnpj']) <= 11) {
            $std->CPF = $pDestinatario['cfpcnpj'];
        } else {
            $std->CNPJ = $pDestinatario['cfpcnpj'];
        }

        //$std->CNPJ = $pDestinatario['cfpcnpj'];
        //$std->CPF = '69484600115';
        //$std->idEstrangeiro = 'AB1234';
        $std->indIEDest = 9;
        $std->IE = '';
        $std->IM = '';
        $std->email = $pDestinatario['correio'];
        $dest = $make->tagdest($std);

        //enderDest OPCIONAL
        $std = new \stdClass();
        $std->xLgr = $pDestinatario['endereco'];
        $std->nro = $pDestinatario['numero'];
        $std->xCpl = null;
        $std->xBairro = $pDestinatario['bairro'];
        $std->cMun = 5103403;
        $std->xMun = $pDestinatario['cidade'];
        $std->UF = $pDestinatario['uf'];
        $std->CEP = $pDestinatario['cep'];
        $std->cPais = 1058;
        $std->xPais = 'Brasil';
        $std->fone = $pDestinatario['telefone1'];
        $ret = $make->tagenderdest($std);

        //prod OBRIGATÓRIA
        foreach ($pProdutos as $key => $produto) {
            # code...  
            $vContadorItem = intval($vContadorItem + 1);

            $vTotalItens = floatval($vTotalItens + $produto['total']);

            $std = new \stdClass();
            $std->item = $vContadorItem;
            $std->cProd = $produto['id_produto'];
            $std->cEAN = "SEM GTIN";
            $std->xProd = $produto['descricao_item'];
            $std->NCM = 61052000;
            $std->EXTIPI = '';
            $std->CFOP = $produto['cfop'];
            $std->uCom = $produto['sigla'];
            $std->qCom = $produto['quantidade'];
            $std->vUnCom = $produto['preco'];
            $std->vProd = $produto['total'];
            $std->cEANTrib = "SEM GTIN"; //'6361425485451';
            $std->uTrib = $produto['sigla'];
            $std->qTrib = $produto['quantidade'];
            $std->vUnTrib = $produto['preco'];
            $std->indTot = 1;
            //$std->vFrete = 0;
            //$std->vSeg = 0;
            //$std->vDesc = 0;
            //$std->vOutro = 0;
            //$std->xPed = '';
            //$std->nItemPed = 1;
            //$std->nFCI = '12345678-1234-1234-1234-123456789012';
            $prod = $make->tagprod($std);

            // $tag = new \stdClass();
            // $tag->item = $vContadorItem;
            // $tag->infAdProd = '';
            // $make->taginfAdProd($tag);

            //Imposto 
            $std = new stdClass();
            $std->item = $vContadorItem; //item da NFe
            $std->vTotTrib = 0.00;
            $make->tagimposto($std);

            $std = new stdClass();
            $std->item = $vContadorItem; //item da NFe
            $std->orig = 0;
            $std->CSOSN = $produto['csosn_cst'];
            $std->CST = $produto['cst'];
            $std->pCredSN = 0.00;
            $std->vCredICMSSN = 0.00;
            $std->modBCST = null;
            $std->pMVAST = null;
            $std->pRedBCST = null;
            $std->vBCST = null;
            $std->pICMSST = null;
            $std->vICMSST = null;
            $std->vBCFCPST = null; //incluso no layout 4.00
            $std->pFCPST = null; //incluso no layout 4.00
            $std->vFCPST = null; //incluso no layout 4.00
            $std->vBCSTRet = null;
            $std->pST = null;
            $std->vICMSSTRet = null;
            $std->vBCFCPSTRet = null; //incluso no layout 4.00
            $std->pFCPSTRet = null; //incluso no layout 4.00
            $std->vFCPSTRet = null; //incluso no layout 4.00
            $std->modBC = null;
            $std->vBC = null;
            $std->pRedBC = null;
            $std->pICMS = null;
            $std->vICMS = null;
            $std->pRedBCEfet = null;
            $std->vBCEfet = null;
            $std->pICMSEfet = null;
            $std->vICMSEfet = null;
            $std->vICMSSubstituto = null;
            $make->tagICMSSN($std);

            //PIS
            $std = new stdClass();
            $std->item = $vContadorItem; //item da NFe
            $std->CST = '99';
            //$std->vBC = 1200;
            //$std->pPIS = 0;
            $std->vPIS = 0.00;
            $std->qBCProd = 0;
            $std->vAliqProd = 0;
            $pis = $make->tagPIS($std);

            //COFINS
            $std = new stdClass();
            $std->item = $vContadorItem; //item da NFe
            $std->CST = '99';
            $std->vBC = null;
            $std->pCOFINS = null;
            $std->vCOFINS = 0.00;
            $std->qBCProd = 0;
            $std->vAliqProd = 0;
            $make->tagCOFINS($std);
        }

        //icmstot OBRIGATÓRIA
        $std = new \stdClass();
        //$std->vBC = 100;
        //$std->vICMS = 0;
        //$std->vICMSDeson = 0;
        //$std->vFCPUFDest = 0;
        //$std->vICMSUFDest = 0;
        //$std->vICMSUFRemet = 0;
        //$std->vFCP = 0;
        //$std->vBCST = 0;
        //$std->vST = 0;
        //$std->vFCPST = 0;
        //$std->vFCPSTRet = 0.23;
        $std->vProd = $vTotalItens;
        //$std->vFrete = 100;
        //$std->vSeg = null;
        //$std->vDesc = null;
        //$std->vII = 12;
        //$std->vIPI = 23;
        //$std->vIPIDevol = 9;
        //$std->vPIS = 6;
        //$std->vCOFINS = 25;
        //$std->vOutro = null;
        $std->vNF = $vTotalItens;
        //$std->vTotTrib = 0.00;
        $icmstot = $make->tagicmstot($std);

        //transp OBRIGATÓRIA
        $std = new \stdClass();
        $std->modFrete = 0;
        $transp = $make->tagtransp($std);

        //pag OBRIGATÓRIA
        $std = new \stdClass();
        $std->vTroco = 0;
        $pag = $make->tagpag($std);

        //detPag OBRIGATÓRIA Pagamento
        $std = new \stdClass();
        $std->indPag = 1;
        $std->tPag = '01';
        $std->vPag = $vTotalItens;
        $detpag = $make->tagdetpag($std);

        //infadic
        $std = new \stdClass();
        $std->infAdFisco = '';
        $std->infCpl = '';
        $info = $make->taginfadic($std);

        // $std = new stdClass();
        // $std->CNPJ = '29850144000119'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
        // $std->xContato = 'Fulano de Tal'; //Nome da pessoa a ser contatada
        // $std->email = 'fulano@soft.com.br'; //E-mail da pessoa jurídica a ser contatada
        // $std->fone = '65992565018'; //Telefone da pessoa jurídica/física a ser contatada        
        // $make->taginfRespTec($std);

        $make->monta();
        $xml = $make->getXML();
        $xml = $tools->signNFe($xml);

        $xmlAssinado = $xml;

        try {
            //$content = conteúdo do certificado PFX
            $tools = new Tools($configJson, Certificate::readPfx($pfxcontent, '123'));
            $idLote = str_pad(1, 15, '0', STR_PAD_LEFT); // Identificador do lote
            $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);

            $st = new Standardize();
            $std = $st->toStd($resp);

            if ($std->cStat != 103) {
                //erro registrar e voltar
                exit("[$std->cStat] $std->xMotivo");
            }

            $recibo = $std->infRec->nRec; // Vamos usar a variável $recibo para consultar o status da nota

            try {
                sleep(6);

                $protocolo = $tools->sefazConsultaRecibo($recibo);
            } catch (\Exception $e) {
                //aqui você trata possíveis exceptions da consulta
                exit($e->getMessage());
            }

            try {
                //header('Content-Type: application/json; charset=UTF-8');                
                $xml = Complements::toAuthorize($xmlAssinado, $protocolo);

                // Padronizar a resposta
                $stdProtocolo = $st->toStd($protocolo);

                // Obter a chave de acesso
                $chaveNFe = $stdProtocolo->protNFe->infProt->chNFe;
                $nProt = $stdProtocolo->protNFe->infProt->nProt;

                echo $xml;
                $xmlFilePath = __DIR__ . '/xml/' . $chaveNFe . '.xml';

                file_put_contents($xmlFilePath, $xml);

                // Carregue o XML do arquivo
                $xmlContent = file_get_contents($xmlFilePath);

                // Gere o DANFE
                $danfe = new Danfe($xmlContent);
                $danfe->debugMode(false);
                $pdf = $danfe->render();

                // Salve o PDF em um arquivo                
                $pdfFilePath = __DIR__ . '/pdf/' . $chaveNFe . '.pdf';
                file_put_contents($pdfFilePath, $pdf);

                // Leia o conteúdo do PDF
                $pdfContent = file_get_contents($pdfFilePath);

                // Converta o PDF para base64
                $pdfBase64 = base64_encode($pdfContent);

                // Prepare a resposta JSON
                $response = ['pdf' => $pdfBase64];

                // return json_encode([
                //     'response' => '<embed src="data:application/pdf;base64,' . $pdfBase64 . '" width="100%" height="100%" type="application/pdf"/>'
                // ]);

                http_response_code(response_code: 200);
                // Exiba o PDF no navegador
                return json_encode([
                    'response' => 'data:application/pdf;base64,' . $pdfBase64,
                    'chave_nfe' => $chaveNFe,
                    'protocolo_nfe' => $nProt,
                    'xml' => $xml
                ]);
            } catch (\Exception $e) {
                http_response_code(500);
                return json_encode(['response' => $e->getMessage()]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['response' => $e->getMessage()]);
        }
    } catch (\Exception $e) {
        return json_encode(['response' => $e->getMessage()]);
    }
}
