<div align="center">
    <img src="_readme/cover2.jpg" width="100%" />
</div>
_____

### 📗 Navegação:

- [Dependências](#dependencias)
- [Google reCAPTCHA](#reCAPTCHA)
- [Banco de dados](#database)
- [Web Scraping](#webscraping)
- [Demonstração](#demo)
_____

<a id="dependencias"></a>

### 📖 Dependências utilizadas:

use ``composer install`` para instalar todas as dependências utilizadas neste projeto.

| Nome | Versão |
| --- | --- |
| **[twig/twig](https://packagist.org/packages/twig/twig)**| ^3.6 |
| **[vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv)** | ^5.5 |

_____

<a id="reCAPTCHA"></a>

### reCAPTCHA:
Foi utilizado o [Google reCAPTCHA](https://www.google.com/recaptcha/about/) no formulário de cadastro para mitigar ataques automizados a aplicação.

você pode facilmente definir as suas credenciais no arquivo [**.env**](https://github.com/hnqca/divulgazap-php/blob/main/.env)

```bash
reCAPTCHA_public_key = "YOUR_SITE_KEY"
reCAPTCHA_secret_key = "YOUR_SECRET_KEY"
```

_____

<a id="database"></a>

### Banco de Dados (MySQL):

Importe o arquivo [divulgazap.sql](https://github.com/hnqca/divulgazap-php/blob/main/divulgazap.sql) em seu banco de dados.

Defina as credenciais de conexão no arquivo [**.env**](https://github.com/hnqca/divulgazap-php/blob/main/.env)

```bash
# MySQL Database Connection #
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=divulgazap 
DB_USER=root
DB_PASS=
```

Todo acesso ao banco de dados foi implementado utilizando [PDO](https://www.php.net/manual/pt_BR/book.pdo.php) com prepared statements.
_____

<a id="webscraping"></a>

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

<a id="demo"></a>

### Demonstração:

<div align="center">
    <img src="_readme/demoDivulgaZAP.gif" width="100%" />
</div>