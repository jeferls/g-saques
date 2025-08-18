# G-Saques

Este projeto fornece uma API Laravel executada via Docker. Siga os passos abaixo para configurar e executar o ambiente de desenvolvimento.

## Requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Make](https://www.gnu.org/software/make/)

## Clonando o repositório

```bash
git clone git@github.com:seu-usuario/g-saques.git
cd g-saques
```

## Configuração de variáveis de ambiente

Copie o arquivo de template e ajuste os valores conforme necessário:

```bash
cp .env.template .env
```

O valor de `API_ACCESS_KEY` será utilizado para autenticar requisições na API através do cabeçalho `X-API-Key`.

## Subindo os serviços

Crie a rede Docker utilizada pelos serviços (caso ainda não exista) e inicie os contêineres:

```bash
docker network create greenn-network  # executado apenas uma vez
make up
```

## Executando migrações

Após os contêineres estarem em execução, rode as migrações do banco de dados:

```bash
make migrate
```

## Logs e encerramento

Acompanhe os logs ou derrube os serviços quando necessário:

```bash
make logs   # exibe os últimos logs
make down   # encerra os contêineres
```

A API ficará disponível em `http://localhost:8017`.
