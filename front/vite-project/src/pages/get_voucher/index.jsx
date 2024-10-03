import React from 'react';
import { useState, useEffect } from 'react';
import { useParams, useLocation } from 'react-router-dom';
import { GET_VOUCHER } from '../../API/requestApi';

function GetVoucher() {
    const { idUser } = useParams(); // Pega o parâmetro idUser da URL
    const location = useLocation(); // Pega o estado se for necessário

    const [voucherData, setVoucherData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        console.log("Iniciando requisição de voucher...");

        async function carregaVoucher() {
            try {
                const response = await GET_VOUCHER(idUser);
                console.log('Dados do Voucher:', response); // Verifica se está retornando os dados corretos
                setVoucherData(response); // Salva os dados no estado
            } catch (erro) {
                console.error('Erro ao buscar o voucher:', erro);
                setError(erro.message || 'Erro desconhecido');
            } finally {
                setLoading(false);
            }
        }

        carregaVoucher(); // Chama a função que busca os dados do voucher
    }, [idUser]);

    if (loading) {
        return <div>Carregando...</div>;
    }

    if (error) {
        return <div>Erro ao carregar o voucher: {error}</div>;
    }

    return (
        <div>
            <h1>Get Voucher Page</h1>
            <p>Bem-vindo, usuário {idUser}</p> {/* Exibe o ID do usuário */}
        </div>
    );
}

export default GetVoucher;