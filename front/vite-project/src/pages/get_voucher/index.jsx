// Funcionalidades / Libs:
import { useState, useEffect } from 'react';
import { useParams, useLocation, useNavigate } from 'react-router-dom';
import { GET_VOUCHER } from '../../API/requestApi';

export default function Projeto() {
    const navigate = useNavigate();
    const { idUser } = useParams();
    const location = useLocation();
    const dadoRecebido = location.state;

    useEffect(() => {
        async function carregaGruposProjeto() {
            console.log('Effect /Projeto carregaGruposProjeto');

            try {
                const response = await GET_VOUCHER(idUser);
                console.log('Dados deste projeto (GET_ID): ', response)
            }
            catch (erro) {
                console.log('Deu erro ao buscar Projeto desta page: ', erro);

                if (erro.response.data.erro) {
                    console.log('erro');
                }
            }
        }
        carregaGruposProjeto();
    }, [idUser]);
}

return (
    <>

    </>
);
