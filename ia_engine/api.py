from fastapi import FastAPI
from pydantic import BaseModel
from typing import List
import joblib
import uvicorn
import os

app = FastAPI()

# --- Chargement du modèle ---
base_dir = os.path.dirname(os.path.abspath(__file__))
model_path = os.path.join(base_dir, 'model_risques.pkl')
model = joblib.load(model_path)

# --- Fonctions de logique métier ---

def generer_conseil(temp, niveau_risque):
    niveau = niveau_risque.strip().upper()
    if niveau == "ÉLEVÉ":
        if temp >= 40:
            return "ALERTE EXTRÊME : Risque de déshydratation sévère. Rentrez immédiatement."
        return "Risque thermique important. Restez à l'ombre et évitez tout effort physique."
    elif niveau == "MODÉRÉ":
        return "Température élevée. Buvez 2L d'eau par jour et évitez de sortir entre 12h et 16h."
    return "Conditions normales. Continuez à bien vous hydrater."

def analyser_previsions_meteo(forecast_list):
    """Analyse les données météo pour générer des alertes intelligentes."""
    alertes = []
    # 1. Détection de pic
    temps_max = max(forecast_list, key=lambda x: x['temp'])
    if temps_max['temp'] > 38:
        alertes.append(f"Pic de chaleur à {temps_max['temp']}°C prévu à {temps_max['heure']}.")
    # 2. Détection de nuit chaude
    nuit_temp = next((x['temp'] for x in forecast_list if x['heure'] == "22:00"), 25)
    if nuit_temp > 28:
        alertes.append(f"Nuit chaude prévue ({nuit_temp}°C) : préparez votre chambre.")
    return alertes

def generer_conseil_apres_symptome(symptomes_list):
    dictionnaire_conseils = {
        "Maux de tête violents / Vertiges": ["Allongez-vous dans une pièce sombre.", "Hydratez-vous."],
        "Contractions douloureuses": ["Prenez un bain chaud.", "Chronométrez la fréquence."],
        "Fatigue extrême ou déshydratation": ["Reposez-vous.", "Privilégiez des repas légers."],
        "Fièvre ou sensation de forte chaleur": ["Rafraîchissez-vous avec un linge humide."],
        "Gonflement anormal des pieds/mains": ["Surélevez vos jambes."]
    }
    return {s: dictionnaire_conseils.get(s, ["Reposez-vous et consultez en cas de doute."]) for s in symptomes_list}

def generer_notifications(alertes_meteo, niveau_risque):
    """Transforme les alertes brutes en messages de notification."""
    notifications = []
    
    # Priorité : Si le risque est ÉLEVÉ, on transforme chaque alerte en notification critique
    for alerte in alertes_meteo:
        notifications.append({
            "titre": "Alerte Environnementale",
            "message": alerte,
            "type": "warning" if "Pic" in alerte else "info"
        })
    
    # Ajout d'une notification basée sur le risque IA
    if niveau_risque.strip().upper() == "ÉLEVÉ":
        notifications.append({
            "titre": "Action Requise",
            "message": "Votre indice de risque est ÉLEVÉ. Consultez vos recommandations immédiatement.",
            "type": "critical"
        })
        
    return notifications

# --- Routes API ---

@app.get("/predict_smart")
async def predict_smart(lat: float, lon: float, semaines: int, hta: int):
    # Simulation météo
    mock_forecast = [{'heure': '09:00', 'temp': 30}, {'heure': '14:00', 'temp': 41}, {'heure': '22:00', 'temp': 29}]
    temp_actuelle = mock_forecast[0]['temp']

    prediction = model.predict([[temp_actuelle, semaines, hta]])[0]
    str_prediction = str(prediction)

    alertes = analyser_previsions_meteo(mock_forecast)
    conseil = generer_conseil(temp_actuelle, str_prediction)
    notifications = generer_notifications(alertes, str_prediction)
    
    return {
        "temperature": temp_actuelle,
        "niveau_risque": str_prediction,
        "conseil_ia": conseil,  # C'est cette clé qui est attendue par Laravel et Flutter
        "predictions": alertes,
        "notifications": notifications,
    }

class AnalyseRequest(BaseModel):
    symptomes: List[str]
    remarque: str = ""

@app.post("/analyser")
async def analyser(data: AnalyseRequest):
    return {"status": "success", "data": generer_conseil_apres_symptome(data.symptomes)}

if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=5000)