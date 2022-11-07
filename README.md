## Web Crawler

Web Crawler feito em PHP/Laravel para buscar informações sobre moedas no Wikipedia.

Como Executar:

- Realizar Clone do Projeto.
- Instalar o composer e depois rodar o comando composer install.
- Dentro da pasta Raiz do projeto, executa o seguinte comando: ./vendor/bin/sail up
- Aguarde o Sail instalar as dependencias necessárias e quando finalizar, o crawler estará ativo na porta 80


## Endpoint

Existe apenas um endpoint disponível: "http://localhost/currency**?code=GBP**"

Nesse endpoint podemos passar algumas opções:

Parâmetro **?code=GBP** : Usado para buscar apenas uma moeda específica usando o código da mesma.

Parâmetro **?code_list[]=GBP&code_list[]=USD** : Para buscar informação de mais de uma moeda, passamos o parametro code_list como um array

Parâmetro **?number=884** : Usado para buscar apenas uma moeda especifica usando o numero da mesma.

Parâmetro **?number_lists[]=445&number_lists[]=555** : Usado para buscar informação de mais de uma moeda usando uma lista de numeros da moeda. 

## Exemplos:

- GET http://localhost/currency?code=GBP
  ```json
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
    ]


- GET http://localhost/currency?code_list[]=GBP&code_list[]=HTG&code_list[]=KES
  ``` json
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
        },
        {
            "code": "HTG",
            "number": 332,
            "decimal": 2,
            "currency": "Gourde",
            "currency_locations": [
                {
                    "location": "Haiti",
                    "icon": "https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Haiti.svg/22px-Flag_of_Haiti.svg.png"
                }
            ]
        },
        {
            "code": "KES",
            "number": 404,
            "decimal": 2,
            "currency": "Xelim queniano",
            "currency_locations": [
                {
                    "location": "Quênia",
                    "icon": "https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Kenya.svg/22px-Flag_of_Kenya.svg.png"
                }
            ]
        }
    ]