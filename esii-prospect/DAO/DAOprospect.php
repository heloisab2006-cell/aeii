<?php
namespace DAO;

mysqli_report(MYSQLI_REPORT_STRICT);
require_once('../model/Prospect.php');
use model\Prospect;

/**
 * Classe responsável pela comunicação com o banco de dados
 * para operações CRUD sobre prospects.
 */
class DAOProspect {

    /**
     * FAZ A CONEXÃO COM O BANCO
     * @return \mysqli
     * @throws \Exception
     */

    private function conectarBanco() {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        if (!defined('BASE_DIR')) {
            define('BASE_DIR', dirname(__FILE__) . DS);
        }

        require_once(DS . 'bd_config.php'); // espera: $db, $user, $password, $dbhost

        try {
            $conn = new \MySQLi($dbhost, $user, $password, $db);
            return $conn;
        } catch (\mysqli_sql_exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    } // FIM DA QUERY DE CONEXÃO COM O BANCO



    /**
     * INCLUI UM NOVO PROSPECT.
     * @param string $nome
     * @param string $email
     * @param string $celular
     * @param string $facebook
     * @param string $whatsapp
     * @return bool TRUE em caso de sucesso
     * @throws \Exception em caso de erro
     */

    public function incluirProspect($nome, $email, $celular, $facebook, $whatsapp) {
        try {
            $conn = $this->conectarBanco();
        } catch (\Exception $e) {
            throw $e;
        }

        $sql = "INSERT INTO prospect (nome, email, celular, facebook, whatsapp)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            throw new \Exception("Erro ao preparar statement: " . $conn->error);
        }

        $stmt->bind_param("sssss", $nome, $email, $celular, $facebook, $whatsapp);

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            $conn->close();
            throw new \Exception("Erro ao incluir prospect: " . $err);
        }

        $stmt->close();
        $conn->close();
        return TRUE;
    }

    /**
     * ATUALIZA OS DADOS DE UM PROSPECT
     * @param string $nome
     * @param string $email
     * @param string $celular
     * @param string $facebook
     * @param string $whatsapp
     * @param int $codProspect
     * @return bool TRUE em caso de sucesso
     * @throws \Exception em caso de erro
     */

    public function atualizarProspect($nome, $email, $celular, $facebook, $whatsapp, $codProspect) {
        try {
            $conn = $this->conectarBanco();
        } catch (\Exception $e) {
            throw $e;
        }

        $sql = "UPDATE prospect
                SET nome = ?, email = ?, celular = ?, facebook = ?, whatsapp = ?
                WHERE cod_prospect = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            throw new \Exception("Erro ao preparar statement: " . $conn->error);
        }

        $stmt->bind_param("sssssi", $nome, $email, $celular, $facebook, $whatsapp, $codProspect);

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            $conn->close();
            throw new \Exception("Erro ao atualizar prospect: " . $err);
        }

        $stmt->close();
        $conn->close();
        return TRUE;
    }

    /**
     * EXCLUI OS DADOS DE UM PROSPECT
     * @param int $codProspect
     * @return bool TRUE em caso de sucesso
     * @throws \Exception em caso de erro
     */
    public function excluirProspect($codProspect) {
        try {
            $conn = $this->conectarBanco();
        } catch (\Exception $e) {
            throw $e;
        }

        $sql = "DELETE FROM prospect WHERE cod_prospect = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            throw new \Exception("Erro ao preparar statement: " . $conn->error);
        }

        $stmt->bind_param("i", $codProspect);

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            $conn->close();
            throw new \Exception("Erro ao excluir prospect: " . $err);
        }

        $stmt->close();
        $conn->close();
        return TRUE;
    }

    /**
     * FAZ O SELECT dos prospect
     * @param string|null $email
     * @return Prospect[] array de objetos Prospect
     * @throws \Exception em caso de erro
     */
    public function buscarProspects($email = null) {
        try {
            $conn = $this->conectarBanco();
        } catch (\Exception $e) {
            throw $e;
        }

        $prospects = [];

        if ($email !== null) {
            $sql = "SELECT cod_prospect, nome, email, celular, facebook, whatsapp
                    FROM prospect
                    WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                $conn->close();
                throw new \Exception("Erro ao preparar statement: " . $conn->error);
            }
            $stmt->bind_param("s", $email);
        } else {
            $sql = "SELECT cod_prospect, nome, email, celular, facebook, whatsapp FROM prospect";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                $conn->close();
                throw new \Exception("Erro ao preparar statement: " . $conn->error);
            }
        }

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            $conn->close();
            throw new \Exception("Erro ao executar busca: " . $err);
        }

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $prospect = new Prospect();
            if (method_exists($prospect, 'addProspect')) {
                // addProspect(cod, nome, email, celular, facebook, whatsapp)
                $prospect->addProspect(
                    $row['cod_prospect'],
                    $row['nome'],
                    $row['email'],
                    $row['celular'],
                    $row['facebook'],
                    $row['whatsapp']
                );
            } else {
                $prospect->cod_prospect = $row['cod_prospect'];
                $prospect->nome = $row['nome'];
                $prospect->email = $row['email'];
                $prospect->celular = $row['celular'];
                $prospect->facebook = $row['facebook'];
                $prospect->whatsapp = $row['whatsapp'];
            }

            $prospects[] = $prospect;
        }

        $stmt->close();
        $conn->close();

        return $prospects;
    }
}
?>
