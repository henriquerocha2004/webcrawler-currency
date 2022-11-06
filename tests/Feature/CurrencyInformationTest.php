<?php

namespace Tests\Feature;

use App\Repository\WikipediaRepository;
use App\Services\CurrencyInformationInput;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CurrencyInformationTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_error_if_not_pass_currency_identification_at_url(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency');
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function should_return_error_if_code_currency_param_is_greater_than_3_character(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?code=ABCD');

        $response->assertUnprocessable();
        $content = json_decode($response->getContent());
        $this->assertEquals('The code must not be greater than 3 characters.', $content->message);
    }

    /**
     * @test
     */
    public function should_return_error_if_code_list_is_not_valid() : void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?code_list[]=AAA&code_list[]=BB&code_list[]=CCC');

        $response->assertUnprocessable();

        $content = json_decode($response->getContent());
        $this->assertEquals('The code_list.1 must be at least 3 characters.', $content->message);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?code_list[]=123&code_list[]=BB&code_list[]=CCC');

        $response->assertUnprocessable();

        $content = json_decode($response->getContent(), true);

        $expectedErrorMessages = [
            'The code_list.0 must only contain letters.',
            'The code_list.1 must be at least 3 characters.'
        ];
        $errors = array_values($content["errors"]);
        foreach ($expectedErrorMessages as $key => $errorMessage) {
            $this->assertEquals($errorMessage, $errors[$key][0]);
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?code_list=AAABBASS');

        $response->assertUnprocessable();
        $content = json_decode($response->getContent());
        $this->assertEquals('The code list must be an array.', $content->message);
    }

    /**
     * @test
     */
    public function should_return_error_if_number_param_is_invalid(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?number=AAA');

        $response->assertUnprocessable();

        $content = json_decode($response->getContent());
        $this->assertEquals('The number must be an integer.', $content->message);
    }

    /**
     * @test
     */
    public function should_return_error_if_number_list_param_is_invalid() : void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?number_lists=AAA');

        $response->assertUnprocessable();
        $content = json_decode($response->getContent());
        $this->assertEquals('The number lists must be an array.', $content->message);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?number_lists[]=AAA');
        $response->assertUnprocessable();
        $content = json_decode($response->getContent(), true);
        $errors = array_values($content["errors"]);
        $this->assertEquals('The number_lists.0 must be an integer.', $errors[0][0]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?number_lists[]=41');

        $response->assertUnprocessable();
        $content = json_decode($response->getContent());
        $this->assertEquals('The number_lists.0 must be 3 digits.', $content->message);
    }

    /**
     * @test
     */
    public function should_return_error_if_pass_in_query_string_more_than_one_parameters(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/currency?code=AAA&number=123');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function should_return_currency_information_using_single_code(): void
    {
        $expected = json_decode('
            [
                {  
                  "code": "GBP",  
                  "number": 826,  
                  "decimal": 2,  
                  "currency": "Libra Esterlina",  
                  "currency_locations": [  
                    {  
                      "location": "Reino Unido",  
                      "icon": "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Flag_of_the_United_Kingdom.svg/22px-Flag_of_the_United_Kingdom.svg.png"  
                    },  
                    {  
                      "location": "Ilha de Man",  
                      "icon": ""  
                    },  
                    {  
                      "location": "Guernesey",  
                      "icon": ""  
                    },  
                    {  
                      "location": "Jersey",  
                      "icon": ""  
                    }  
                  ]  
               }
          ]', true);

        $currencyInformationInput = new CurrencyInformationInput(['GBP'], []);
        $wikipediaRepository = new WikipediaRepository();
        $result = $wikipediaRepository->find($currencyInformationInput);
        $this->assertEquals($expected, $result->data);
    }

    /**
     * @test
     */
    public function should_return_currency_information_using_single_number(): void
    {
        $expected = json_decode('
            [
                {  
                  "code": "GBP",  
                  "number": 826,  
                  "decimal": 2,  
                  "currency": "Libra Esterlina",  
                  "currency_locations": [  
                    {  
                      "location": "Reino Unido",  
                      "icon": "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Flag_of_the_United_Kingdom.svg/22px-Flag_of_the_United_Kingdom.svg.png"  
                    },  
                    {  
                      "location": "Ilha de Man",  
                      "icon": ""  
                    },  
                    {  
                      "location": "Guernesey",  
                      "icon": ""  
                    },  
                    {  
                      "location": "Jersey",  
                      "icon": ""  
                    }  
                  ]  
               }
          ]', true);

        $currencyInformationInput = new CurrencyInformationInput([], [826]);
        $wikipediaRepository = new WikipediaRepository();
        $result = $wikipediaRepository->find($currencyInformationInput);
        $this->assertEquals($expected, $result->data);
    }

    /**
     * @test
     */
    public function should_return_currency_information_using_list_code(): void
    {
        $expected = json_decode('
            [
               {
                  "code":"GBP",
                  "number":826,
                  "decimal":2,
                  "currency":"Libra Esterlina",
                  "currency_locations":[
                     {
                        "location":"Reino Unido",
                        "icon":"https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Flag_of_the_United_Kingdom.svg/22px-Flag_of_the_United_Kingdom.svg.png"
                     },
                     {
                        "location":"Ilha de Man",
                        "icon":""
                     },
                     {
                        "location":"Guernesey",
                        "icon":""
                     },
                     {
                        "location":"Jersey",
                        "icon":""
                     }
                  ]
               },
               {
                  "code":"GEL",
                  "number":981,
                  "decimal":2,
                  "currency":"Lari",
                  "currency_locations":[
                     {
                        "location":"Geórgia",
                        "icon":"https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/22px-Flag_of_Georgia.svg.png"
                     }
                  ]
               }
         ]', true);

        $currencyInformationInput = new CurrencyInformationInput(['GBP', 'GEL'], []);
        $wikipediaRepository = new WikipediaRepository();
        $result = $wikipediaRepository->find($currencyInformationInput);
        $this->assertEquals($expected, $result->data);
    }

    /**
     * @test
     */
    public function should_return_currency_information_using_list_number()
    {
        $expected = json_decode('
            [
               {
                  "code":"GBP",
                  "number":826,
                  "decimal":2,
                  "currency":"Libra Esterlina",
                  "currency_locations":[
                     {
                        "location":"Reino Unido",
                        "icon":"https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Flag_of_the_United_Kingdom.svg/22px-Flag_of_the_United_Kingdom.svg.png"
                     },
                     {
                        "location":"Ilha de Man",
                        "icon":""
                     },
                     {
                        "location":"Guernesey",
                        "icon":""
                     },
                     {
                        "location":"Jersey",
                        "icon":""
                     }
                  ]
               },
               {
                  "code":"GEL",
                  "number":981,
                  "decimal":2,
                  "currency":"Lari",
                  "currency_locations":[
                     {
                        "location":"Geórgia",
                        "icon":"https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/22px-Flag_of_Georgia.svg.png"
                     }
                  ]
               }
         ]', true);

        $currencyInformationInput = new CurrencyInformationInput([], [826,981]);
        $wikipediaRepository = new WikipediaRepository();
        $result = $wikipediaRepository->find($currencyInformationInput);
        $this->assertEquals($expected, $result->data);
    }

    /**
     * @test
     */
    public function should_not_return_currency_information_using_wrong_information(): void
    {
        $currencyInformationInput = new CurrencyInformationInput(['AAA'], []);
        $wikipediaRepository = new WikipediaRepository();
        $result = $wikipediaRepository->find($currencyInformationInput);
        $this->assertEquals([], $result->data);
    }
}
