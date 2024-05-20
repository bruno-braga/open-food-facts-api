# Open food facts api

## Indice

1. Analise do problema
    1. Por onde começar
    2. ImportCommand
    3. GzstreamService
    4. A Fila

## 1. Analise do problema

Primeiramente comecei investigando a API open food facts através dos links no README da tarefa. Comecei pelo link que tem a lista de arquivos a ser importados e em seguida fiz o download de um arquivo manualmente mesmo para ter uma noção do que se tratava - tamanho do arquivo e formato dos dados -. Notei que os arquivos não são trivialmente pequenos então comecei a pesquisar qual a melhor maneira de criar uma stream para ler um arquivo e limitar apenas as 100 primeiras linhas como pedido na tarefa.

### 1.1 Por onde começar

Sendo assim decidi que de inicio seria legal já ir criando uma migration para uma tabela products de acordo com o arquivo products.json e a partir daí poderia começar o desenvolvimento de um comando laravel para que seja possível importar os dados através dos links a seguir:
1. https://challenges.coode.sh/food/data/json/index.txt
2. https://challenges.coode.sh/food/data/json/{filename}

### 1.2 ImportProductsCommand

Iniciei criando um comando pois ele me dá a flexibilidade de poder rodar o import a qualquer momento algo que considero valioso durante o desenvolvimento. A ideia é depois usar ele em conjunto com o Schedule do laravel para então poder determinar que ele rodará 1 vez por dia.
O comando funciona da seguinte forma:
1. Pega a lista de arquivos
2. Parseia o nome de cada arquivo
3. Despacha um arquivo por vez para uma fila
Dentro da fila é onde começamos o processo de importar os dados.

### 1.3 GzstreamService

Criei um serviço especifico para a leitura dos arquivos e o faz da seguinte forma:
1. Baixa o arquivo de acordo com o nome recebido
2. ler as 100 primeiras linhas dos arquivos
3. retorna-lo como um array

Utilizei a função nativa do php gzopen que cria uma stream de dados para evitar colocar todo o arquivo na memoria já que o arquivo não era tão pequeno assim.

### 1.4 A Fila

A file recebe o nome do arquivo e logo em seguida faz um GET para uma URL como no exemplo abaixo:

```
https://challenges.coode.sh/food/data/json/products_01.json.gz
```

Após o arquivo ser baixado cria-se uma stream para ler o arquivo linha por linha e assim poder limitar quantos produtos podem ser importados e também nos permite não colocar o arquivo inteiro na memoria para importar os dados. 

Dentro da fila, como já mencionado em 1.2, é onde o processo de importar os dados do arquivo começa de fato. Apartir do nome do arquivo pegamos os dados e logo em seguida:

1. Pego o id do arquivo da tabela files
2. Salvo tudo que estiver com status published na tabela products na tabela products_histories, porém, com status trash
3. Seto o status de todos os produtos daquele arquivo especifico que já na tabela products para draft
4. Removo os drafts da tabela products
5. Salvo os dados do novo arquivo na tabela products com status published

Decidi colocar um file_id na tabela de produtos e adicionar uma tabela auxiliar que contem o nome dos arquivos pois assim também posso ter um registro de que arquivo um produto específico veio e assim consigo diminuir o escopo de uma possível investigação caso alguma coisa aconteça.

A seguir um diagrama de sequencia e um ER do banco de dados:

[ER](./er.png)
[Diagrama de sequência](./ds.png)