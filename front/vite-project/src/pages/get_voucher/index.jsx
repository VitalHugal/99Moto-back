import React, { useState, useEffect } from 'react';
import { useParams, useLocation, useNavigate, redirect, Navigate } from 'react-router-dom';
import Cookies from 'js-cookie';
import { GET_VOUCHER } from '../../API/getVoucherApi';

function GetVoucher() {

    const navigate = useNavigate();

    // Recupera o ID do usuário do cookie
    const idUser = Cookies.get('userId');

    const location = useLocation();
    const [voucherData, setVoucherData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {

        const isAuthenticated = !!Cookies.get('userId');

        if (!isAuthenticated) {
            navigate('/'); // Redireciona se não autenticado
        }

        console.log("Iniciando requisição de voucher...");

        console.log(idUser);

        async function carregaVoucher() {
            try {
                const response = await GET_VOUCHER(idUser);

                if (response.success === true) {

                    Cookies.set('cupom', response.message, { expires: 7 });
                    console.log(response.message)
                    navigate(`/result`);
                } else {
                    setError('erro ao obter voucher')
                }
            } catch (erro) {
                console.error('Erro ao buscar o voucher:', erro);
                setError(erro.message || 'Erro desconhecido');
            } finally {
                setLoading(false);
            }
        }

        // Verifica se o idUser existe antes de fazer a requisição
        if (idUser) {
            carregaVoucher();
        } else {
            setError("ID do usuário não encontrado.");
            setLoading(false);
        }
    }, [idUser, navigate]);

    if (loading) {
        return <div>Carregando...</div>;
    }

    if (error) {
        return <div>Erro ao carregar o voucher: {error}</div>;
    }

    return (
        <div>
            <h1>Get Voucher Page</h1>
            <p>Bem-vindo, usuário {idUser}</p>
        </div>
    );
}

export default GetVoucher;
