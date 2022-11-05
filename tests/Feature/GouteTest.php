<?php

namespace Tests\Feature;

use Tests\TestCase;
use Weidner\Goutte\GoutteFacade;

class GouteTest extends TestCase
{
    public function test_should_return_first_paragraph_of_laravel_description_in_Wikipedia()
    {
        $expectedContent = 'Laravel é um framework PHP livre e open-source criado por Taylor B. Otwell para o desenvolvimento de sistemas web que utilizam o padrão MVC (model, view, controller). Algumas características proeminentes do Laravel são sua sintaxe simples e concisa, um sistema modular com gerenciador de dependências dedicado, várias formas de acesso a banco de dados relacionais e vários utilitários indispensáveis no auxílio ao desenvolvimento e manutenção de sistemas.';
        $crawler = GoutteFacade::request('GET', "https://pt.wikipedia.org/wiki/Laravel");
        $content = trim(str_replace(" ", " ", $crawler->filter('#mw-content-text p')->text()));
        $this->assertSame($expectedContent, $content);
    }
}
