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
      <h1>Parabéns!</h1>
      <p>Você capturou um <strong>cupom</strong> de</p>
      <p>99MOTO</p>
      <button onClick={handleGoToVouchers} className="btn-primary">
        Continuar
      </button>
    </div>
  );
};

export default Result;
