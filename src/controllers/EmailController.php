<?php
namespace src\controllers;

use \core\Controller;

class EmailController extends Controller
{
    public function index()
    {
        echo "Rota WhatsApp";
        exit;
    }

    public function enviarSemAnexoParams($emailCliente, $mensagem, $id_movimento)
    {
        /* Email válido  */
        $remetente = "contato@eurosoftware.com.br";//"contato@blfitsuplementos.com.br";

        /* Valores recebidos do formulário  */
        $idpedido = $id_movimento;	// Pega o valor do campo Telefone
        $assunto = 'COBRANÇA EURO';	// Pega o valor do campo Telefone
        $email = $emailCliente;	// Pega o valor do campo Email
        $mensagem_form = $mensagem;	// Pega os valores do campo Mensagem
        $copia = "marceldcampostj@gmail.com";
        $replyto = $copia; // Email que será respondido

        /* Destinatário e remetente - EDITAR SOMENTE ESTE BLOCO DO CÓDIGO */
        $to = $email;
        $cc = $copia;

        /* Cabeçalho da mensagem  */
        $boundary = "XYZ-" . date("dmYis") . "-ZYX";
        $headers = "MIME-Version: 1.0\n";
        $headers .= "From: $remetente\n";
        $headers .= "Reply-To: $replyto\n";
        $headers .= "Bcc: $cc\n";
        $headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
        $headers .= "$boundary\n";

        /* Layout da mensagem  */
        $corpo_mensagem = "
                    <br>Olá prezado cliente...
                    <br>--------------------------------------------<br>	
                    <br><strong>Assunto:</strong> $assunto
                    <br><strong>Email:</strong> $replyto	
                    <br><strong>Mensagem:</strong> $mensagem_form      
                    <br><strong>No pedido:</strong> $idpedido
                    <br><br>--------------------------------------------";

        $mensagem = "--$boundary\n";
        $mensagem .= "Content-Transfer-Encoding: 8bits\n";
        $mensagem .= "Content-Type: text/html; charset=\"utf-8\"\n\n";
        $mensagem .= "$corpo_mensagem\n";

        if (mail($to, $assunto, $mensagem, $headers)) {
        } else {
            echo "<br><br><center><b><font color='red'>Ocorreu um erro ao enviar a mensagem!" . "<br>";
        }
    }

    public function enviarSemAnexo()
    {
        /* Email válido  */
        $remetente = "contato@eurosoftware.com.br";//"contato@blfitsuplementos.com.br";

        /* Valores recebidos do formulário  */
        $idpedido = $_POST['id_pedido'];	// Pega o valor do campo Telefone
        $assunto = $_POST['assunto'];	// Pega o valor do campo Telefone
        $email = $_POST['email'];	// Pega o valor do campo Email
        $mensagem_form = $_POST['message'];	// Pega os valores do campo Mensagem
        $copia = "marceldcampostj@gmail.com";
        $replyto = $copia; // Email que será respondido

        /* Destinatário e remetente - EDITAR SOMENTE ESTE BLOCO DO CÓDIGO */
        $to = $email;
        $cc = $copia;

        /* Cabeçalho da mensagem  */
        $boundary = "XYZ-" . date("dmYis") . "-ZYX";
        $headers = "MIME-Version: 1.0\n";
        $headers .= "From: $remetente\n";
        $headers .= "Reply-To: $replyto\n";
        $headers .= "Bcc: $cc\n";
        $headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
        $headers .= "$boundary\n";

        /* Layout da mensagem  */
        $corpo_mensagem = "
                    <br>Olá prezado cliente...
                    <br>--------------------------------------------<br>	
                    <br><strong>Assunto:</strong> $assunto
                    <br><strong>Email:</strong> $replyto	
                    <br><strong>Mensagem:</strong> $mensagem_form      
                    <br><strong>No pedido:</strong> $idpedido
                    <br><br>--------------------------------------------";

        $mensagem = "--$boundary\n";
        $mensagem .= "Content-Transfer-Encoding: 8bits\n";
        $mensagem .= "Content-Type: text/html; charset=\"utf-8\"\n\n";
        $mensagem .= "$corpo_mensagem\n";

        if (mail($to, $assunto, $mensagem, $headers)) {
        } else {
            echo "<br><br><center><b><font color='red'>Ocorreu um erro ao enviar a mensagem!" . "<br>";
        }
    }

    public function enviar()
    {
        /* Email válido  */
        //$remetente = "contato@eurosoftware.com.br";
        //$remetente = "eventosdaf.smcel@cuiaba.mt.gov.br";        

        /* Nome e Email do remetente */
        $nome_remetente = "CONTATO";//"SECRETARIA MUNICIPAL DE CULTURA ESPORTE E LAZER"; // Defina o nome que deseja exibir
        $email_remetente = "contato@euro-sistemas.app.br"; // Defina o e-mail que deseja usar
        $remetente = "$nome_remetente <$email_remetente>";

        /* Valores recebidos do formulário  */
        $arquivo = $_FILES['pdf'];
        $idpedido = $_POST['id_pedido'];	// Pega o valor do campo Telefone
        $assunto = $_POST['assunto'];	// Pega o valor do campo Telefone
        $email = $_POST['email'];	// Pega o valor do campo Email
        $lista_emails = $_POST['lista_emails'];	// Pega o valor do campo Email
        $mensagem_form = $_POST['message'];	// Pega os valores do campo Mensagem
        $copia = $email;//"eventosdaf.smcel@cuiaba.mt.gov.br";
        $replyto = $email;//"eventosdaf.smcel@cuiaba.mt.gov.br"; // Email que será respondido

        /* Destinatário e remetente - EDITAR SOMENTE ESTE BLOCO DO CÓDIGO */
        //$to = $email;
        $cc = $copia;

        /* Processa a lista de e-mails */
        $lista_emails_array = explode("\n", $lista_emails);
        $lista_emails_array = array_map('trim', $lista_emails_array); // Remove espaços em branco

        // Adiciona o e-mail principal à lista, se a lista estiver vazia
        if (empty($lista_emails_array[0])) {
            $to = $email;
        } else {
            $lista_emails_array[] = trim($email); // Adiciona o e-mail principal à lista
            $to = implode(", ", $lista_emails_array); // Converte array em string separada por vírgulas
        }
        /* Processa a lista de e-mails */

        /* Cabeçalho da mensagem  */
        $boundary = "XYZ-" . date("dmYis") . "-ZYX";
        $headers = "MIME-Version: 1.0\n";
        $headers .= "From: $remetente\n";
        $headers .= "Reply-To: $replyto\n";
        $headers .= "Bcc: $cc\n";
        $headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
        $headers .= "$boundary\n";

        $corpo_mensagem = $mensagem_form;

        /* Função que codifica o anexo para poder ser enviado na mensagem  */
        if (file_exists($arquivo["tmp_name"]) and !empty($arquivo)) {
            $fp = fopen($_FILES["pdf"]["tmp_name"], "rb"); // Abri o arquivo enviado.
            $anexo = fread($fp, filesize($_FILES["pdf"]["tmp_name"])); // Le o arquivo aberto na linha anterior
            $anexo = base64_encode($anexo); // Codifica os dados com MIME para o e-mail
            fclose($fp); // Fecha o arquivo aberto anteriormente
            $anexo = chunk_split($anexo); // Divide a variável do arquivo em pequenos pedaços para poder enviar
            $mensagem = "--$boundary\n"; // Nas linhas abaixo possuem os parâmetros de formatação e codificação, juntamente com a inclusão do arquivo anexado no corpo da mensagem
            $mensagem .= "Content-Transfer-Encoding: 8bits\n";
            $mensagem .= "Content-Type: text/html; charset=\"utf-8\"\n\n";
            $mensagem .= "$corpo_mensagem\n";
            $mensagem .= "--$boundary\n";
            $mensagem .= "Content-Type: " . $arquivo["type"] . "\n";
            $mensagem .= "Content-Disposition: attachment; filename=\"" . $arquivo["name"] . "\"\n";
            $mensagem .= "Content-Transfer-Encoding: base64\n\n";
            $mensagem .= "$anexo\n";
            $mensagem .= "--$boundary--\r\n";
        } else // Caso não tenha anexo
        {
            $mensagem = "--$boundary\n";
            $mensagem .= "Content-Transfer-Encoding: 8bits\n";
            $mensagem .= "Content-Type: text/html; charset=\"utf-8\"\n\n";
            $mensagem .= "$corpo_mensagem\n";
        }

        if (mail($to, $assunto, $mensagem, $headers)) {
        } else {
            echo "<br><br><center><b><font color='red'>Ocorreu um erro ao enviar a mensagem!" . "<br>";
        }
    }

}