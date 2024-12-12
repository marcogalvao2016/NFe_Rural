<?php

namespace src\models;

use \core\Model;

class NFeModel extends Model
{
    private $conexao; // Variável para armazenar a conexão PDO

    function __construct()
    {
        require_once 'conexao/db_connection.php';
        $this->conexao = new \DB_Con();
    }

    function getLastNFe()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT idnf FROM notanfe ORDER BY idnf DESC LIMIT 1";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "idnf" => $row['idnf'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/nfe/getlastnfe",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "nfe" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "nfe" => $data,
        ];
    }

    public function gravarNFe($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        $idcli = $data['idcli'];
        $idfunc = $data['idfunc'];
        $idnf = $data['idnf'];
        $tipo = $data['tipo'];
        $subtotal = $data['subtotal'];
        $vldesconto = $data['vldesconto'];
        $chave = $data['chave'];
        $qt_itens = $data['qt_itens'];
        $protocolo = $data['protocolo'];
        $total_produtos = $data['total_produtos'];
        $total_nf = $data['total_nf'];
        $id_venda = $data['id_venda'];
        $caminho_xml = $data['caminho_xml'];
        $cnpj = $data['cnpj'];
        $nome_cliente = $data['nome_cliente'];
        $ie = $data['ie'];
        $xml = $data['xml'];

        $produtos = $data['produtos'];

        try {
            $qry = "INSERT INTO notanfe(
                    idcli, 
                    idfunc,
                    idnf,
                    emissao,
                    hora,
                    tipo,
                    subtotal,
                    vldesconto,
                    datapagto,
                    parcelas,
                    qtitens,
                    idcaixa,
                    situacao,
                    agrupada,
                    chave,
                    retorno,
                    arquivo,
                    nprotocolo,
                    digival,
                    totalprodutos,
                    totalnf,
                    vlbcicms,
                    vlicms,
                    vlfrete,
                    vlpis,
                    vlcofins,
                    vlseguro,
                    vloutrasdespesas,
                    vlipi,
                    modelo,
                    serie,
                    formapagto,
                    formaemissao,
                    operacao,
                    finalidadeemissao,
                    cfop,
                    ambiente,
                    idvenda,
                    caminho_xml,
                    cnpj,
                    vencimento,
                    nome_cliente,
                    ie,
                    arquivo_xml
                    )VALUES(
                        :p01, 
                        :p02,
                        :p03,
                        :p04,
                        :p05,
                        :p06,
                        :p07,
                        :p08,
                        :p09,
                        :p10,
                        :p11,
                        :p12,
                        :p13,
                        :p14,
                        :p15,
                        :p16,
                        :p17,
                        :p18,
                        :p19,
                        :p20,
                        :p21,
                        :p22,
                        :p23,
                        :p24,
                        :p25,
                        :p26,
                        :p27,
                        :p28,
                        :p29,
                        :p30,
                        :p31,
                        :p32,
                        :p33,
                        :p34,
                        :p35,
                        :p36,
                        :p37,
                        :p38,
                        :p39,
                        :p40,
                        :p41,
                        :p42,
                        :p43,
                        :p44)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $idcli);
            $stmt->bindValue("p02", $idfunc);
            $stmt->bindValue("p03", $idnf);
            $stmt->bindValue("p04", $dataAtual);
            $stmt->bindValue("p05", $hora);
            $stmt->bindValue("p06", $tipo);
            $stmt->bindValue("p07", $subtotal);
            $stmt->bindValue("p08", $vldesconto);
            $stmt->bindValue("p09", $dataAtual);
            $stmt->bindValue("p10", 1);
            $stmt->bindValue("p11", $qt_itens);
            $stmt->bindValue("p12", 2);
            $stmt->bindValue("p13", "EMITIDA");
            $stmt->bindValue("p14", "N");
            $stmt->bindValue("p15", $chave);
            $stmt->bindValue("p16", $dataAtual);
            $stmt->bindValue("p17", 'C:\\Impacto\\Fiscais\\NFe\\Logs\\');
            $stmt->bindValue("p18", $protocolo);
            $stmt->bindValue("p19", '');
            $stmt->bindValue("p20", $total_produtos);
            $stmt->bindValue("p21", $total_nf);
            $stmt->bindValue("p22", 0);
            $stmt->bindValue("p23", 0);
            $stmt->bindValue("p24", 0);
            $stmt->bindValue("p25", 0);
            $stmt->bindValue("p26", 0);
            $stmt->bindValue("p27", 0);
            $stmt->bindValue("p28", 0);
            $stmt->bindValue("p29", 0);
            $stmt->bindValue("p30", '55');
            $stmt->bindValue("p31", 1);
            $stmt->bindValue("p32", 'A VISTA');
            $stmt->bindValue("p33", 'NORMAL');
            $stmt->bindValue("p34", 'SAIDA');
            $stmt->bindValue("p35", 'NORMAL');
            $stmt->bindValue("p36", '5102');
            $stmt->bindValue("p37", 2);
            $stmt->bindValue("p38", $id_venda);
            $stmt->bindValue("p39", $caminho_xml);
            $stmt->bindValue("p40", $cnpj);
            $stmt->bindValue("p41", $dataAtual);
            $stmt->bindValue("p42", $nome_cliente);
            $stmt->bindValue("p43", $ie);
            $stmt->bindValue("p44", $xml);
            $stmt->execute();

            // Pega o ultimo registro
            $vUltimoId = "0";
            $ultimoIDSQL = "SELECT id FROM notanfe ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($ultimoIDSQL);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vUltimoId = $row['id'];
            }
            // Pega o ultimo registro

            foreach ($produtos as $key => $value) {
                $qry = "INSERT INTO notanfeitens(
                    idvenda, 
                    idproduto,
                    vlunitario,
                    quantidade,
                    vldesconto,
                    totalproduto,
                    vlbcicms,
                    vlicms,
                    vlpis,
                    vlcofins,
                    vlseguro,
                    vloutrasdespesas,
                    vlipi,
                    id_nfe,
                    cfop,
                    cst,
                    cst_pis,
                    cst_cofins,
                    unidade,
                    descricao,
                    valtributos,
                    cest,
                    ncm,
                    csosn
                    )VALUES(
                        :p01, 
                        :p02,
                        :p03,
                        :p04,
                        :p05,
                        :p06,
                        :p07,
                        :p08,
                        :p09,
                        :p10,
                        :p11,
                        :p12,
                        :p13,
                        :p14,
                        :p15,
                        :p16,
                        :p17,
                        :p18,
                        :p19,
                        :p20,
                        :p21,
                        :p22,
                        :p23,
                        :p24)";
                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $id_venda);
                $stmt->bindValue("p02", $value['id_produto']);
                $stmt->bindValue("p03", $value['preco']);
                $stmt->bindValue("p04", $value['quantidade']);
                $stmt->bindValue("p05", 0);
                $stmt->bindValue("p06", $value['total']);
                $stmt->bindValue("p07", 0);
                $stmt->bindValue("p08", 0);
                $stmt->bindValue("p09", 0);
                $stmt->bindValue("p10", 0);
                $stmt->bindValue("p11", 0);
                $stmt->bindValue("p12", 0);
                $stmt->bindValue("p13", 0);
                $stmt->bindValue("p14", $vUltimoId);
                $stmt->bindValue("p15", $value['cfop']);
                $stmt->bindValue("p16", $value['cst']);
                $stmt->bindValue("p17", 0);
                $stmt->bindValue("p18", 0);
                $stmt->bindValue("p19", $value['sigla']);
                $stmt->bindValue("p20", $value['descricao_item']);
                $stmt->bindValue("p21", 0);
                $stmt->bindValue("p22", "");
                $stmt->bindValue("p23", $value['idncm']);
                $stmt->bindValue("p24", $value['csosn_cst']);
                $stmt->execute();
            }

            $retorno = true;
        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro inserido com sucesso',
            'request' => array(
                'type' => 'POST',
                'description' => 'Inclusão de registro',
                'request' => array(
                    'type' => 'PUT',
                    'description' => 'Registro inserido com sucesso',
                    'url' => 'api/nfe'
                )
            )
        );

        return $response;
    }
}
