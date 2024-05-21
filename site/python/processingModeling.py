#!pip install pyLDAvis
#!pip install mysql-connector-python
#!pip install openpyxl 
import nltk
#nltk.download('stopwords')
#nltk.download('punkt')
#nltk.download('averaged_perceptron_tagger')
#nltk.download('wordnet')
#nltk.download('stopwords')
from nltk.corpus import stopwords
stop_words = stopwords.words('english')
import pandas as pd
import os
import sys
import re
#Gensim
import gensim
from gensim import corpora
from nltk.corpus import wordnet
from nltk.stem import WordNetLemmatizer
#vis
import pyLDAvis
import pyLDAvis.gensim_models
#spacy
import spacy
import mysql.connector
from mysql.connector import Error

import mysql.connector
from mysql.connector import Error

clean = sys.argv[1]
clean = re.sub("[^0-9]", "", clean)
lemma = sys.argv[2]
lemma = re.sub("[^0-9]", "", lemma)
topics = sys.argv[3]
topics = re.sub("[^0-9]", "", topics)
words = sys.argv[4]
words = re.sub("[^0-9]", "", words)
interaction = sys.argv[5]
interaction = re.sub("[^0-9]", "", interaction)
typeModeling = sys.argv[6]
typeModeling = re.sub("[^0-9]", "", typeModeling)

import mysql.connector
from mysql.connector import Error

try:
    connection = mysql.connector.connect(host='localhost',
                                         database='topicgeneration',
                                         user='root',
                                         password='')
    if connection.is_connected():
        db_Info = connection.get_server_info()
        cursor = connection.cursor()
        cursor.execute("select database();")
        record = cursor.fetchone()
except Error as e:
    print("Erro SQL")
    exit()

connection.autocommit = True

if connection.is_connected():
    # Executar a consulta SQL para selecionar os dados da coluna desejada
    cursor = connection.cursor()
    cursor.execute("SELECT col FROM tabela_topicgeneration")
    
    # Obter todos os resultados da consulta
    results = cursor.fetchall()
    
    # Criar um DataFrame pandas com os resultados
    df = pd.DataFrame(results, columns=['col'])
    
    # Fechar o cursor e a conexão
    cursor.close()
    connection.close()

def Document_Cleansing(Document):
    # Verificar se Document é um valor nulo
    if pd.isna(Document):
        return ''  # Retorna uma string vazia para valores nulos
    Document = " ".join([word for word in Document.split() if word not in stop_words])
    Document = " ".join([word for word in Document.split() if len(word) > 2 ])

    # This will make all the words in the documents lower-case:
    Document = Document.lower()

    # removing ambiguous characters
    Document = re.sub(r'[^\w\s]', '', Document)

    # removing numbers which contain commas:
    Document = re.sub(r'(\d+),(\d+),?(\d*)', '',  Document)

    # removing \n terms:
    Document = re.sub(r'(\\n)', '', Document)

    # removing numbers which contain commas:
    Document = re.sub(r'(\d+),(\d+),?(\d*)', " ", Document)

    return Document

if(clean == '1'):
    df['col'] = df['col'].apply(Document_Cleansing)

df['tokenized'] = df['col'].apply(nltk.word_tokenize)
array_df = df['tokenized'].tolist()

def lemmatization(texts, allowed_postags=["NOUN", "ADJ", "VERB", "ADV"]):
    # Carrega o modelo do spaCy uma única vez para economizar tempo
    nlp = spacy.load("en_core_web_sm", disable=["parser", "ner"])

    output = []
    for text in texts:
        doc = nlp(" ".join(text))  # Converte a lista de tokens em uma string para processamento
        lemma_abs = []  # Redefine lemma_abs para cada documento
        for token in doc:
            if token.pos_ in allowed_postags:
                lemma_abs.append(token.lemma_)
        output.append(lemma_abs)
    return output

if(lemma == '1'):
    array_df = lemmatization(array_df)

# corpora.Dictionary elimina palavras repitidas
dictionary = corpora.Dictionary(array_df)
# doc2bow é um método do Dictionary que converte uma lista em  BoW
corpus = [dictionary.doc2bow(doc) for doc in array_df]

# Tipo LDA
if(typeModeling == '1'):
    try:
        lda_model = gensim.models.ldamodel.LdaModel(corpus=corpus,
                                            id2word=dictionary,
                                            num_topics= int(topics),
                                            random_state=100, #semente
                                            update_every=1, #frequência que o modelo é atualizado ao ver cada documento
                                            chunksize=10, #número de documentos a serem usados em cada iteração
                                            passes=int(interaction), #número de vezes que o modelo percorrerá o corpus inteiro durante o treinamento
                                            alpha="auto" #distribuição de tópicos por documento
                                            )
        # Visualizar os tópicos gerados pelo modelo LDA
        topics = lda_model.show_topics(num_topics=int(topics), num_words=int(words), formatted=False)

        # Criar um DataFrame
        data = []
        for topic_id, topic_words in topics:
            for word, weight in topic_words:
                data.append([topic_id, word, weight])

        df_export = pd.DataFrame(data, columns=["topic", "word", "weight"])
        excel_file = "lda.xlsx"
        dir = r"D:\Downloads\Programas\xampp\htdocs\IC-2024\site\exportExcel"
        # Combina o diretório e o nome do arquivo
        output_path = os.path.join(dir, excel_file)
        try:
            df_export.to_excel(output_path, index=False)
            print("OK")
        except Error as e:
            print("Erro EXCEL")
            exit()

    except Error as e:
        print("Erro LDA")
        exit()