<?php

namespace App\Tests\Validations;

use App\Entity\Visite;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of VisiteValidationsTest
 *
 * @author compteadmin
 */
class VisiteValidationsTest extends KernelTestCase{
    public function getVisite():Visite{
        return (new Visite())
                ->setVille("New York")
                ->setPays("USA");
    }
    
    public function testValidNoteVisite(){
        $visite=$this->getVisite()->setNote(10);
        $this->assertErrors($visite,0,"10 devrait réussir");
        $visite=$this->getVisite()->setNote(0);
        $this->assertErrors($visite,0,"0 devrait réussir");
        $visite=$this->getVisite()->setNote(20);
        $this->assertErrors($visite,0,"20 devrait réussir");
    }
    
    public function testNonValidNoteVisite(){
        $visite=$this->getVisite()->setNote(21);
        $this->assertErrors($visite,1,"21 devrait échouer");
        $visite=$this->getVisite()->setNote(-1);
        $this->assertErrors($visite,1,"-1 devrait échouer");
        $visite=$this->getVisite()->setNote(29);
        $this->assertErrors($visite,1,"29 devrait échouer");
        $visite=$this->getVisite()->setNote(-14);
        $this->assertErrors($visite,1,"-14 devrait échouer");
    }
    
    public function testValidTempmaxVisite(){
        $visite=$this->getVisite()
                ->setTempmin(-2)
                ->setTempMax(25);
        $this->assertErrors($visite,0,"min=-2, max=25 devrait réussir");
        $visite=$this->getVisite()
                ->setTempmin(20)
                ->setTempMax(21);
        $this->assertErrors($visite,0,"min=20, max=21 devrait réussir");
    }
    
    public function testNonValidTempmaxVisite(){
        $visite=$this->getVisite()
                ->setTempmin(20)
                ->setTempMax(-6);
        $this->assertErrors($visite,1,"min=20, max=18 devrait échouer");
        $visite=$this->getVisite()
                ->setTempmin(20)
                ->setTempMax(20);
        $this->assertErrors($visite,1,"min=20, max=20 devrait échouer");
    }
    
    public function testValidDatecreationVisite(){
        $plustot = (new DateTime())->sub(new DateInterval("P5D"));
        $visite=$this->getvisite()
                ->setDatecreation($plustot);
        $this->assertErrors($visite,0,"plus tôt devrait réussir");
        $aujourdhui = new DateTime();
        $visite=$this->getvisite()
                ->setDatecreation($aujourdhui);
        $this->assertErrors($visite,0,"aujourd'hui devrait réussir");
    }
    
    public function testNonValidDatecreationVisite(){
        $demain = (new DateTime())->add(new DateInterval("P1D"));
        $visite=$this->getvisite()
                ->setDatecreation($demain);
        $this->assertErrors($visite,1,"demain devrait échouer");
        $plustard = (new DateTime())->add(new DateInterval("P5D"));
        $visite=$this->getvisite()
                ->setDatecreation($plustard);
        $this->assertErrors($visite,1,"plus tard devrait échouer");
    }
    
    public function assertErrors(Visite $visite,int $nbErreursAttendues,string $message=""){
        self::bootKernel();
        $validator=self::getContainer()->get(ValidatorInterface::class);
        $error=$validator->validate($visite);
        $this->assertCount($nbErreursAttendues,$error,$message);
    }
}
