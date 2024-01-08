import nltk
#####################
import numpy as np
import json
import glob
import string
#####################
#Gensim
import gensim
import gensim.corpora as corpora
from gensim.utils import simple_preprocess
from gensim.models import CoherenceModel
#####################
#vis
import pyLDAvis
import pyLDAvis.gensim_models

#spacy
import spacy
#####################
file_path1 = 'C:/Users/Pc/Documents/Unicamp/IC/2024/education_search_1.ris'
file_path2 = 'C:/Users/Pc/Documents/Unicamp/IC/2024/education_search_2.ris'

def criaArray(path):
    with open(path, 'r', encoding='utf-8') as file:
        lines = file.readlines()
    
    in_abs = False
    abs = []
    count = 0
    
    for line in lines:
        line = line.strip()
        if line.startswith('AB  - '):
            in_abs = True
            abs.append(line[6:])
    
    return abs

abstracts1 = criaArray(file_path1)
abstracts2 = criaArray(file_path2)

abstracts = []
abstracts.extend(abstracts1)
abstracts.extend(abstracts2)

array_unidimensional = np.array(abstracts)

# Transformando em um array bidimensional onde cada elemento seja um array de uma string
array_bidimensional = [[item] for item in array_unidimensional]

primeiros_abs = array_bidimensional[0:2]
# print(primeiros_abs)
#####################
def gen_words(texts):
    final = []
    for array in texts:
        for text in array:
            # Acessa o texto dentro de cada array e aplica simple_preprocess
            new = simple_preprocess(str(text), deacc=True)  # Convertendo para string aqui
            final.append(new)
    return final

data_words = gen_words(primeiros_abs)
# print(data_words)
#####################
print("quantidades de artigos: ", len(data_words))
print("quantidades de tokens artigo 1: ", len(data_words[0]))
print("quantidades de tokens artigo 1: ", len(data_words[1]))
#####################
from nltk.corpus import stopwords
stop_words = stopwords.words("english")

def filter_stopwords(words_list):
    filtered_result = []
    
    for sublist in words_list:
        filtered_sublist = []
        for word in sublist:
            if word.lower() not in stop_words:
                filtered_sublist.append(word)
        filtered_result.append(filtered_sublist)
    
    return filtered_result

data_words = filter_stopwords(data_words)
# print(data_words)
#####################
def lemmatization(texts, allowed_postags=["NOUN", "ADJ", "VERB", "ADV"]):
    #spacy é um modelo de processamento de linguagem natural. Aqui será usado para o método ".lemma_"
    nlp = spacy.load("en_core_web_sm", disable=["parser", "ner"])
    output = []
    
    for document in texts:
        lemma_abs = []  # Redefinir lemma_abs a cada iteração
        for word in document:
            doc = nlp(word)
            for token in doc:
                if token.pos_ in allowed_postags:
                    lemma_abs.append(token.lemma_)
    
        output.append(lemma_abs)
    return (output)

data_words = lemmatization(data_words)
# print(data_words)
#####################
# corpora.Dictionary elimina palavras repitidas
dictionary = corpora.Dictionary(data_words)
# doc2bow é um método do Dictionary que converte uma lista em  BoW
corpus = [dictionary.doc2bow(doc) for doc in data_words]

# (ID da palavra no dicionário, frequência da palavra no documento)
# print(corpus)
#####################
lda_model = gensim.models.ldamodel.LdaModel(corpus=corpus,
                                           id2word=dictionary,
                                           num_topics=10,
                                           random_state=100, #semente
                                           update_every=1, #frequência que o modelo é atualizado ao ver cada documento
                                           chunksize=50, #número de documentos a serem usados em cada iteração
                                           passes=10, #número de vezes que o modelo percorrerá o corpus inteiro durante o treinamento
                                           alpha="auto" #distribuição de tópicos por documento
                                           )
#####################
pyLDAvis.enable_notebook()
vis = pyLDAvis.gensim_models.prepare(lda_model, corpus, dictionary, mds="mmds", R=10)
vis
#####################