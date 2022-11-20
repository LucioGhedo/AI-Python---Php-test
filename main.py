import math as mt 
import random as rd 

rd.seed(1)

def RN(m1,m2):
    t=m1*w1+m2*w2+b 
    return sigmoide(t)

def sigmoide(t):
    return 1/(1+mt.exp(-t))

#dataset 
dataset=[[9,7.0,0],
[2,5.0,1],
[3.2,4.94,1],
[9.1,7.46,0],
[1.6,4.83,1],
[8.4,7.46,0],
[8,7.28,0],
[3.1,4.58,1],
[6.3,9.14,0],
[3.4,5.36,1]]    

#definisco la derivata della funzione sigmoide
def sigmoide_p(t):
    return sigmoide(t)*(1 - sigmoide(t)) 

def train():

    #pesi inizializzati inizialmente in modo casuale
    w1 = rd.random()
    w2 = rd.random()
    b = rd.random()     

    iterazioni = 10000  #numero di iterazioni 10000
    learning_rate = 0.1 #imposto il learning rate 0.1
    
    for i in range(iterazioni):
       
        ri = rd.randint(0,len(dataset)-1) # genero un indice casuale
        point = dataset[ri] # prendo un gatto casuale dal dataset
        
        z = point[0] * w1 + point[1] * w2 + b
        pred = sigmoide(z) # previsione della rete
        
        target = point[2] #il mio valore obiettivo
        
        # costo del punto casuale attuale
        cost = (pred - target)**2
        
        #CALCOLO DELLE DERIVATE PARZIALI
        dcost_dpred = 2 * (pred - target) #derivata parziale del costo rispetto alla previsione
        dpred_dz = sigmoide_p(z) #derivata parziale della previsione rispetto a z
        
        dz_dw1 = point[0] #derivata parziale di z rispetto a w1
        dz_dw2 = point[1] #derivata parziale di z rispetto a w2
        dz_db = 1         #derivata parziale di z rispetto a b
        
        dcost_dz = dcost_dpred * dpred_dz #derivata parziale di z rispetto alla previsione (uso la regola della catena)
        
        #REGOLA DELLA CATENA
        dcost_dw1 = dcost_dz * dz_dw1 #derivata parziale del costo rispetto a w1
        dcost_dw2 = dcost_dz * dz_dw2 #derivata parziale del costo rispetto a w2
        dcost_db = dcost_dz * dz_db #derivata parziale del costo rispetto a b
        
        #aggiornamento dei pesi e del bias
        w1 = w1 - learning_rate * dcost_dw1
        w2 = w2 - learning_rate * dcost_dw2
        b = b - learning_rate * dcost_db
        
    return w1, w2, b


#carichiamo i pesi e il bias 
w1, w2, b = train()

pred=[] #array vuoto che conterrà le previsioni

for gatto in dataset:  #per ogni gatto nel dataset
    z = w1 * gatto[0] + w2 * gatto[1] + b
    prediction=sigmoide(z)    #previsione della rete
    if prediction <= 0.5: #se la previsione è minore o uguale a 0.5
        pred.append('giungla') #aggiungi la stringa "giungla" all'array pred
    else: 
        pred.append('sabbie') #altrimenti aggiungi la stringa "sabbie" all'arrat pred

print(pred) #stampa a schermo l'array pred