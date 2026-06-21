from fastapi import FastAPI
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

@app.get("/predict")
async def predict_risk(temp: float, semaines: int, hta: int):
    # Le modèle attend un tableau avec 3 colonnes : [Temp, Semaines, HTA]
    prediction = model.predict([[temp, semaines, hta]])[0] 
    
    return {
        "temperature": temp,
        "niveau_risque": str(prediction),
        "conseil": generer_conseil(temp, str(prediction))
    }

if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=5000)