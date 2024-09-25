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

## Como executar localmente

1. Gere um _Personal Access Token_ do GitHub para acessar a API [seguindo esse tutorial](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens)
2. Clone este repositório
    ```shell
    git clone git@github.com:vcampitelli/phpnaomorreu.git
    ```
3. Copie o arquivo `.env.dist` para `.env` e adicione na variável `GITHUB_TOKEN` o _token_ que você gerou no passo 1
4. Certifique-se que você tenha o [Docker](https://docs.docker.com/get-docker) e [Docker Compose](https://docs.docker.com/compose/install) instalados
5. Execute o comando abaixo para fazer o _build_
    ```shell
    docker compose up
    ```
6. Em seu navegador, abra o arquivo `public/index.html` (que é gerado com o comando acima)
7. Cada alteração no projeto irá disparar um _build_ automático para atualizar o `public/index.html`, então basta acompanhar os logs do passo 5

## Como contribuir

Para contribuir com o projeto, [crie um fork](https://github.com/vcampitelli/phpnaomorreu/fork) deste repositório, faça
suas alterações no arquivo [template/index.phtml](template/index.phtml) e abra uma _pull request_.
