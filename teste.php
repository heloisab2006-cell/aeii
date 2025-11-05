<?php
namespace test;

require_once('../vendor/autoload.php');
require_once('../model/Prospect.php');
require_once('../DAO/DAOProspect.php');

use PHPUnit\Framework\TestCase;
use models\Prospect;
use DAO\DAOProspect;

class ProspectTest extends TestCase {

    /** @test */
    public function testIncluirProspect() {
        $daoProspect = new DAOProspect();
        $this->assertEquals(
            TRUE,
            $daoProspect->incluirProspect(
                "João Silva",
                "joao.silva@email.com",
                "47999998888",
                "facebook.com/joaosilva",
                "47999998888"
            )
        );
        unset($daoProspect);
    }

    /** @test */
    public function testBuscarProspects() {
        $daoProspect = new DAOProspect();
        $prospects = $daoProspect->buscarProspects();
        $this->assertIsArray($prospects);
        if (count($prospects) > 0) {
            $this->assertInstanceOf(Prospect::class, $prospects[0]);
        }
        unset($daoProspect);
    }

    /** @test */
    public function testAtualizarProspect() {
        $daoProspect = new DAOProspect();
        $this->assertEquals(
            TRUE,
            $daoProspect->atualizarProspect(
                "João Silva Atualizado",
                "joao.silva@email.com",
                "47999998888",
                "facebook.com/joaosilva",
                "47999998888",
                1
            )
        );
        unset($daoProspect);
    }

    /** @test */
    public function testExcluirProspect() {
        $daoProspect = new DAOProspect();
        $this->assertEquals(
            TRUE,
            $daoProspect->excluirProspect(1)
        );
        unset($daoProspect);
    }
}
?>
