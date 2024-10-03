import { useState, useEffect } from 'react';
import { useParams, useLocation, useNavigate } from 'react-router-dom';
import { GET_VOUCHER } from '../../API/requestApi';

export default function GetVoucher() {
    const { idUser } = useParams(); // Pega o parâmetro idUser da URL
    const navigate = useNavigate();
    const location = useLocation();
    const dadoRecebido = location.state;

    const [voucherData, setVoucherData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        console.log("Iniciando requisição de voucher...");

        async function carregaGruposProjeto() {
            console.log('Effect /Projeto carregaGruposProjeto');
            try {
                const response = await GET_VOUCHER(idUser);
                console.log('Resposta da API: ', response); // Verifique o conteúdo aqui
                setVoucherData(response); // Armazena os dados no estado
            } catch (erro) {
                console.log('Erro ao buscar o projeto desta page: ', erro);
                setError(erro.message || 'Erro desconhecido');
            } finally {
                setLoading(false);
            }
        }

        carregaGruposProjeto();
    }, [idUser]);

    if (loading) {
        return <div>Carregando...</div>;
    }

    if (error) {
        return <div>Erro ao carregar o voucher: {error}</div>;
    }

    return (
        <div>
            <h1>Voucher do usuário: {idUser}</h1>
            {voucherData ? (
                <div>
                    <p>Detalhes do voucher: {JSON.stringify(voucherData)}</p> {/* Exibe os dados do voucher */}
                </div>
            ) : (<p>Dados não encontrados.</p>)};

        </div>
    );
}
