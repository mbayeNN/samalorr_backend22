from fastapi import FastAPI
from pydantic import BaseModel  # <--- AJOUTEZ CETTE LIGNE
from typing import List
import joblib
import uvicorn
import os

app = FastAPI()

# Chargement sécurisé du modèle
base_dir = os.path.dirname(os.path.abspath(__file__))
model_path = os.path.join(base_dir, 'model_risques.pkl')
model = joblib.load(model_path)

def generer_conseil(temp, niveau_risque):
    # Nettoyage du nom (parfois str(prediction) ajoute des espaces ou majuscules bizarres)
    niveau = niveau_risque.strip().upper()
    
    if niveau == "ÉLEVÉ":
        if temp >= 40:
            return "ALERTE EXTRÊME : Risque de déshydratation sévère. Rentrez immédiatement, rafraîchissez vos points de pulsation (poignets/cou) et buvez de l'eau fraîche, pas glacée."
        return "Risque thermique important. Restez à l'ombre, évitez tout effort physique et portez des vêtements légers en coton."
    
    elif niveau == "MODÉRÉ":
        return "Température élevée. Buvez 2L d'eau par jour et évitez de sortir entre 12h et 16h."
    
    return "Conditions normales. Continuez à bien vous hydrater pour votre bébé."

def generer_conseil_apres_symptome(symptomes_list):
    """
    Retourne un dictionnaire de conseils structuré par symptôme.
    """
    # Base de connaissances organisée
    dictionnaire_conseils = {
        "Maux de tête violents / Vertiges": [
            "Allongez-vous dans une pièce sombre.",
            "Hydratez-vous par petites gorgées.",
            "Prenez votre tension si possible."
        ],
        "Contractions douloureuses": [
            "Prenez un bain chaud pour détendre les muscles.",
            "Chronométrez la fréquence des contractions.",
            "Contactez votre sage-femme en cas de régularité."
        ],
        "Fatigue extrême ou déshydratation": [
            "Reposez-vous sans culpabiliser.",
            "Privilégiez des repas légers et fréquents.",
            "Buvez régulièrement de l'eau (1.5L à 2L/jour)."
        ],
        "Fièvre ou sensation de forte chaleur": [
            "Rafraîchissez-vous avec un linge humide.",
            "Portez des vêtements légers en coton.",
            "Surveillez votre température toutes les 2 heures."
        ],
        "Gonflement anormal des pieds/mains": [
            "Surélevez vos jambes au repos.",
            "Évitez de rester debout trop longtemps.",
            "Réduisez l'apport en sel dans votre alimentation."
        ]
    }

    resultats = {}
    
    for symptome in symptomes_list:
        # Si le symptôme est connu, on prend ses conseils, sinon un conseil générique
        resultats[symptome] = dictionnaire_conseils.get(
            symptome, 
            ["Surveillez l'évolution et reposez-vous.", "En cas de doute, consultez un spécialiste."]
        )
        
    return resultats

@app.get("/predict")
async def predict_risk(temp: float, semaines: int, hta: int):
    # Le modèle attend un tableau avec 3 colonnes : [Temp, Semaines, HTA]
    prediction = model.predict([[temp, semaines, hta]])[0] 
    
    return {
        "temperature": temp,
        "niveau_risque": str(prediction),
        "conseil": generer_conseil(temp, str(prediction))
    }

class AnalyseRequest(BaseModel):
    symptomes: List[str]
    remarque: str = ""

@app.post("/analyser")
async def analyser(data: AnalyseRequest):
    # Appel de votre fonction existante
    conseils = generer_conseil_apres_symptome(data.symptomes)
    
    return {
        "status": "success",
        "data": conseils
    }

if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=5000)