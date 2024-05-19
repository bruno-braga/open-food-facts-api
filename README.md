# Open food facts api

## Indice

1. Analise do problema
    1. Por onde começar
    2. ImportCommand
    3. GzstreamService

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
3. Despacha para uma fila
Dentro da fila é onde começamos o processo de importar os dados.

### 1.3 GzstreamService

Criei um serviço especifico para a leitura dos arquivos e o faz da seguinte forma:
1. Baixa o arquivo de acordo com o nome recebido
2. ler as 100 primeiras linhas dos arquivos
3. retorna-lo como um array

Utilizei a função nativa do php gzopen que cria uma stream de dados para evitar colocar todo o arquivo na memoria já que o arquivo não era tão pequeno assim.
