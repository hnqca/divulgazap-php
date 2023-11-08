# <img width="50" src="_readme/logo.png"> DivulgaZAP 

> 🚧 Projeto não finalizado

<div align="center">
    <img src="_readme/cover.png" width="100%" />
</div><br/>


Simples aplicação web desenvolvida com o propósito de compartilhar e descobrir novos grupos do WhatsApp. 


Os visitantes podem encontrar grupos com base em seus interesses, filtrando por categorias. 

_____

Este projeto foi inspirado no site: [gruposwhats.app](https://gruposwhats.app)
_____


### 📖 Dependências utilizadas:

Execute o comando ``composer install`` para instalar todas as dependências utilizadas neste projeto.

| Nome | Versão |
| --- | --- |
| **[coffeecode/router](https://packagist.org/packages/coffeecode/router)**| ^2.0 |
| **[twig/twig](https://packagist.org/packages/twig/twig)**| ^3.6 |
| **[vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv)** | ^5.5 |

É necessário ter o [composer](https://getcomposer.org/download/) instalado.
_____

**Importante:** Modifique a URL da aplicação no arquivo [**.env**](https://github.com/HenriqueCacerez/divulgazap/blob/main/.env) e em [general.js](https://github.com/HenriqueCacerez/divulgazap/blob/main/public/assets/js/general.js)
_____

### PHP:

Este projeto é baseado em PHP no seu back-end, utilizando orientação a objetos e seguindo o padrão MVC (Model, View e Controller).

Também faz uso do template engine [Twig](https://twig.symfony.com/doc).

Todo acesso ao banco de dados foi implementado utilizando [PDO](https://www.php.net/manual/pt_BR/book.pdo.php) com prepared statements.

_____

### reCAPTCHA:
Foi utilizado o [Google reCAPTCHA](https://www.google.com/recaptcha/about/) no formulário de cadastro para mitigar ataques automizados a aplicação.

você pode facilmente definir as suas credenciais no arquivo [**.env**](https://github.com/HenriqueCacerez/divulgazap/blob/main/.env)

```bash
reCAPTCHA_public_key = "YOUR_SITE_KEY"
reCAPTCHA_secret_key = "YOUR_SECRET_KEY"
```

_____

### Banco de Dados (MySQL):

Importe o arquivo [divulgazap.sql](https://github.com/HenriqueCacerez/divulgazap/blob/main/divulgazap.sql) em seu banco de dados.

Defina as credenciais de conexão no arquivo [**.env**](https://github.com/HenriqueCacerez/divulgazap/blob/main/.env)

```bash
# MySQL Database Connection #
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=divulgazap 
DB_USER=root
DB_PASS=
```
_____

### Web Scraping:

Foi utilizado a função nativa [file_get_contents](https://www.php.net/manual/en/function.file-get-contents.php) para coletar o conteúdo HTML da página de convite do WhatsApp 

``https://chat.whatsapp.com/{id}``

Em seguida, foi utilizado [preg_match_all](https://www.php.net/manual/en/function.preg-match-all.php) para filtrar somente os dados relevantes através do uso de expressões regulares:

| Informação | Expressão Regular |
| --- | --- |
| **Nome do Grupo** | `/<h3 class="_9vd5 _9scr" style="color:#5E5E5E;">(.*?)<\/h3>/s` |
| **URL da Imagem** | `/<img class="_9vx6" src="(.*?)"/` |


e por último, retornando os dados em formato JSON para o JavaScript:

```JSON
{
    "data": {
        "status": "success",
        "group": {
            "name": "Nome do Grupo",
            "image": "https://pps.whatsapp.net/v/caminho_da_imagem"
        }
    }
}
```
_____


### O que ainda falta:

- [ ] Adicionar paginação na listagem dos grupos.
- [ ] Integração com o Google ADS.
- [ ] Configurar uma tarefa agendada (CRON JOB) para validar automaticamente os links dos grupos em intervalos definidos e remover os grupos inválidos.
- [ ] Exibir quantas vezes um determinado grupo foi acessado por meio da aplicação.
- [ ] Integração com Stripe para oferecer a opção de destacar grupos na tela inicial da aplicação por um período após a aprovação do pagamento.

Tem alguma sugestão para acrescentar à lista? Compartilhe [aqui](https://github.com/HenriqueCacerez/divulgazap/labels/%F0%9F%92%A1%20ideias).

### Você é muito bem-vindo(a) para contribuir com este projeto

Faça um fork do repositório e submeta um pull request com as alterações propostas =)