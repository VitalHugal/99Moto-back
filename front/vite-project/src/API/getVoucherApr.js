// Config JSON:
import api from './configApi.json';
// Funcionalidades / Libs:
import axios from "axios";

// Variaveis:
// Base URL: http://10.10.0.210:8000/api
export const API_URL = api.api_url;

// End-Points / Rotas da API:
// GETVOUCHER //
// Pega vouchers pelo id do user (READ):

// Pega Voucher pelo ID (READ):
export async function GET_VOUCHER(idUser) {
    console.log('CALL FUNCTION API');

    const response = await axios.get(API_URL + '/get-vouchers/' + idUser, {
        headers: { "Accept": "application/json", 'X-Dry-Run': true }
    });

    // console.log(response.data);
    return response.data;
}
