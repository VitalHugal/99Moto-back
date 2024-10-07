// Config JSON:
import api from './configApi.json';
// Funcionalidades / Libs:
import axios from "axios";

// Variaveis:
// Base URL: http://10.10.0.210:8000/api
export const API_URL = api.api_url;

// End-Points / Rota da API:
// REGISTER USER COORDINATES //
// Adiciona coordenadas do usuario (CREATE):
export async function USER_COORDINATES(user_coordinates_latitudine, user_coordinates_longitudine, local_time) {
    console.log('CALL FUNCTION API');

    const response = await axios.post(API_URL + '/coordenadas-users', {
        "user_coordinates_latitudine": user_coordinates_latitudine,
        "user_coordinates_longitudine": user_coordinates_longitudine,
        "local_time": local_time
    },
        {
            headers: {
                "Accept": "application/json",
                'X-Dry-Run': true,
                'Access-Control-Allow-Origin': '*',
            }
        }
    );

    console.log(response.data);
    return response.data;
}
