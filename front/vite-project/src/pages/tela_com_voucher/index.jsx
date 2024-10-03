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
            <h1>CUPOM</h1>
            <p>CUPOM : {cupom || 'Nenhum cupom encontrado.'}</p>
        </div>
    );
};

export default Voucher;
