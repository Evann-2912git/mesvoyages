<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of VoyagesControllerTest
 *
 * @author compteadmin
 */
class VoyagesControllerTest extends WebTestCase{
    public function testAccessPage(){
        $client=static::createClient();
        $client->request('GET','/voyages');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testContenuPage(){
        $client=static::createClient();
        $crawler=$client->request('GET','/voyages');
        $this->assertSelectorTextContains('h1','Mes voyages');
        $this->assertSelectorTextContains('th','Ville');
        $this->assertCount(4,$crawler->filter('th'));
        $this->assertSelectorTextContains('h5','Melilla');
    }
    
    public function testLinkVille(){
        $client=static::createClient();
        $client->request('GET','/voyages');
        //client sur un lien(le nom d'une ville)
        $client->clickLink('Melilla');
        //récupération du résultat du clic
        $response=$client->getResponse();
        //contrôle si le lien existe
        $this->assertEquals(Response::HTTP_OK,$response->getStatusCode());
        //récupération de la route et contrôle qu'elle est correcte
        $uri=$client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/voyages/voyage/1',$uri);
    }
    
    public function testFiltreVille(){
        $client=static::createClient();
        $client->request('GET','/voyages');
        //simualtion de la soumission du formulaire
        $crawler=$client->submitForm('filtrer',[
            'recherche'=>'Vorst'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(1,$crawler->filter('h5'));
        //vérifie si la ville correspond à la recherche
        $this->assertSelectorTextContains('h5','Vorst');
    }
}
