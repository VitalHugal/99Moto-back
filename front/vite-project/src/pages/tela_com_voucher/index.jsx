import React, { useEffect } from 'react';
import Cookies from 'js-cookie';
import { useNavigate } from 'react-router-dom';

const Voucher = () => {
    const navigate = useNavigate();

    const cookies = Cookies.get();
    console.log(cookies)

    useEffect(() => {

        const isAuthenticated = !!Cookies.get('userId');

        if (!isAuthenticated) {
            navigate('/'); // Redireciona se não autenticado
        }
    }, [navigate]);

    const cupom = Cookies.get('cupom');

    return (
        <div>
            <p>Atingimos <strong>1 BILHÃO</strong></p> 
            <p>de corridas graças a você,</p> 
            <p>obrigado!</p>
            <p>{cupom || 'Nenhum cupom encontrado.'}</p>
            <button>Copiar código</button>

            <p>Utilize o código na aba meus</p>
            <p>descontos no app 99.</p>

            <p>Ainda não tem</p>
            <p>o app 99? <a href="#">Clique aqui.</a></p>
        </div>
    );
};

export default Voucher;
