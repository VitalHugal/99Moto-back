// Funcionalidades / Libs:
import { useState, useEffect } from 'react';
import { useParams, useLocation, useNavigate } from 'react-router-dom';
import { GET_VOUCHER } from '../../API/requestApi';

export default function Projeto() {
    const navigate = useNavigate();
    const { idUser } = useParams(); // pega o parametro definido no routes.jsx
    const location = useLocation();
    const dadoRecebido = location.state; // recebe os dados(do projeto clicado) da page(/home) anterior pelo useLocation()


    useEffect(() => {
        async function carregaGruposProjeto() {
            console.log('Effect /Projeto carregaGruposProjeto');

            if (tokenCookie) {
                //=> Carrega dados do projeto:
                let dadosProjeto = {};

                try {
                    const response = await GET_VOUCHER(idUser);
                    console.log('Dados deste projeto (GET_ID): ', response);
                    dadosProjeto = response;
                }
                catch (erro) {
                    console.log('Deu erro ao buscar Projeto desta page: ', erro);

                    if (erro.response.data.erro) {
                        // usa navigate para page "NotFound" quando não encontrar cliente id
                        console.log('erro');
                    }
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
