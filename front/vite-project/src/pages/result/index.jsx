import React, { useEffect } from 'react';
import Cookies from 'js-cookie';
import { useNavigate } from 'react-router-dom';

const Result = () => {

  const navigate = useNavigate();

  const isAuthenticated = !!Cookies.get('userId');

  if (!isAuthenticated) {
    navigate('/'); // Redireciona se não autenticado
  }

  useEffect(() => {

    const isAuthenticated = !!Cookies.get('userId');

    if (!isAuthenticated) {
      navigate('/'); // Redireciona se não autenticado
    }
  }, [navigate]);


  const handleGoToVouchers = () => {
    navigate('/voucher');
  };

  return (
    <div>
      <h1>Parabens</h1>
      <p>Aqui estão seu cupom!</p>
      <button onClick={handleGoToVouchers} className="btn-primary">
        Ir para Vouchers
      </button>
    </div>
  );
};

export default Result;
