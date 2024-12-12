<?php
// Permitir a origem específica que deseja acessar a API (substitua "*") pelo seu domínio
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, DELETE, PUT, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-type: application/json");

use core\Router;

$router = new Router();

$router->get('/error', 'ErroController@index');

// home
$router->get('/', 'HomeController@index');

// Grupos
require_once 'routes/GrupoRoute.php';

// Caixas
require_once 'routes/CaixaRoute.php';

// Usuários
require_once 'routes/UsuarioRoute.php';

// Grupo de acesso
require_once 'routes/GrupoAcessoRoute.php';

// Funcionarios
require_once 'routes/FuncionarioRoute.php';

// Clientes
require_once 'routes/ClienteRoute.php';

// Tabela de preço
require_once 'routes/TabelaPrecoRoute.php';

// Forma de pagto
require_once 'routes/FormaPagtoRoute.php';

// Plano de contas
require_once 'routes/PlanoContasRoute.php';

// Cidades
require_once 'routes/CidadeRoute.php';

// Bairros
require_once 'routes/BairroRoute.php';

// Unidades
require_once 'routes/UnidadeRoute.php';

// NCM's
require_once 'routes/NCMRoute.php';

// SubGrupo
require_once 'routes/SubGrupoRoute.php';

// Cargos
require_once 'routes/CargoRoute.php';

// Categoria de Fornecedor
require_once 'routes/CatFornecedorRoute.php';

// Categoria de cliente
require_once 'routes/CatClienteRoute.php';

// Seguimento de cliente
require_once 'routes/SeguimentoClienteRoute.php';

// Video
require_once 'routes/VideoRoute.php';

// Categoria de video
require_once 'routes/CatVideoRoute.php';

// Produtos
require_once 'routes/ProdutoRoute.php';

// Funcionarios
require_once 'routes/FuncionarioRoute.php';

// Fornecedor
require_once 'routes/FornecedorRoute.php';

// Empresa
require_once 'routes/EmpresaRoute.php';

// Clientes
require_once 'routes/ClienteRoute.php';

// Bordas
require_once 'routes/BordaRoute.php';

// Complemento
require_once 'routes/ComplementoRoute.php';

// Registro
require_once 'routes/RegistroRoute.php';

// Dashbord
require_once 'routes/DashboardRoute.php';

// Venda
require_once 'routes/VendaRoute.php';

// Fluxo de caixa
require_once 'routes/FluxoCaixaRoute.php';

// Contas a pagar
require_once 'routes/ContasPagarRoute.php';

// Contas a receber
require_once 'routes/ContasReceberRoute.php';

// Marcas
require_once 'routes/MarcaRoute.php';

// Produto cadastro
require_once 'routes/ProdutoCadastroRoute.php';

// Whatsapp
require_once 'routes/WhatsAppRoute.php';

// Email
require_once 'routes/EmailRoute.php';

// CNPJ
require_once 'routes/CNPJRoute.php';

// Cep
require_once 'routes/CEPRoute.php';

// Cor
require_once 'routes/CorRoute.php';

// Cor
require_once 'routes/DepartamentoRoute.php';

// Ecommerce
require_once 'routes/CategoryEcommerceRoute.php';
require_once 'routes/ProductEcommerceRoute.php';

// Sistema
require_once 'routes/TelasSistemaRoute.php';

// Clientes Euro
require_once 'routes/ClienteEuroRoute.php';

// Boletos Euro
require_once 'routes/BoletoEuroRoute.php';

// Contratos
require_once 'routes/ContratoRoute.php';

// OS Secretaria
require_once 'routes/OsSecretariaRoute.php';

// Emissão NF-e
require_once 'routes/EmissaoNFe.php';

// Conta hospitalar
require_once 'routes/ContaHospilatar.php';

// Tipo hospitalar
require_once 'routes/TipoHospilatar.php';

// Dirigentes
require_once 'routes/DirigenteRoute.php';

// Mapas
require_once 'routes/MapasRoute.php';

// Designação
require_once 'routes/DesignacaoRoute.php';

// MobTEF
require_once 'routes/MobTEFRoute.php';

// Menu
require_once 'routes/MenuRoute.php';

// Medidas
require_once 'routes/MedidaRoute.php';

// Gerencianet
require_once 'routes/GerencianetRoute.php';

// Acerto de estoque
require_once 'routes/AcertoEstoqueRoute.php';
