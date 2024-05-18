# Open food facts api

## Indice

1. Analise do problema
    1.1 Por onde começar

## 1. Analise do problema

Primeiramente comecei investigando a API open food facts através dos links no README da tarefa. Comecei pelo link que tem a lista de arquivos a ser importados e em seguida fiz o download de um arquivo manualmente mesmo para ter uma noção do que se tratava - tamanho do arquivo e formato dos dados -. Notei que os arquivos não são trivialmente pequenos então comecei a pesquisar qual a melhor maneira de criar uma stream para ler um arquivo e limitar apenas as 100 primeiras linhas como pedido na tarefa.

### 1.1 Por onde começar

    Sendo assim decidi que de inicio seria legal já ir criando uma migration para uma tabela products de acordo com o arquivo products.json e a partir daí poderia começar o desenvolvimento de um comando laravel para que seja possível importar os dados através dos links a seguir:

> https://challenges.coode.sh/food/data/json/index.txt
> https://challenges.coode.sh/food/data/json/{filename}
