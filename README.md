# O PHP não morreu

[![Build](https://github.com/vcampitelli/phpnaomorreu/actions/workflows/build.yml/badge.svg)](https://github.com/vcampitelli/phpnaomorreu/actions/workflows/build.yml)

Mesmo sendo a linguagem mais usada na Web, muitas pessoas ainda se perguntam diariamente: "o PHP morreu?". Felizmente, não!
Veja aqui estatísticas, referências e alguns projetos dessa linguagem.

[phpnaomorreu.com.br](https://phpnaomorreu.com.br)

## Motivação

Comecei a programar em PHP em 2007 e, como qualquer um que trabalha na área, já ouvi muitas vezes essa
pergunta que inclusive virou motivo de piada na comunidade. Recentemente, estive
no [PHP Community Summit 2022](https://php.locaweb.com.br) e, mais uma vez, brincamos sobre o "fim" do PHP. Mas sabemos
que isso é uma dúvida séria de pessoas que estão ingressando na área — ou de aqueles que possuem uma visão desatualizada
sobre a linguagem.

Não tenho a pretensão de transformar o projeto em uma referência de bibliotecas ou boas práticas — já temos ótimas
opções para isso —, mas simplesmente ser um canal de fácil acesso para responder à pergunta, principalmente daqueles que
buscam sobre nos mecanismos de pesquisas. 

## Como executar

- Instalar as dependências e configure o _autoload_ do PHP:
    ```shell
    composer install
    ```
- Então, a cada mudança que fizer (ou na 1a vez que for rodar o projeto), execute:
    ```shell
    composer run build
    ```
- Esse comando vai gerar um arquivo `public/index.html`, que você deve acessar direto pelo navegador

## Como contribuir

Para contribuir com o projeto, [crie um fork](https://github.com/vcampitelli/phpnaomorreu/fork) deste repositório, faça
suas alterações no arquivo [template/index.phtml](template/index.phtml) e abra uma _pull request_.
