import React from 'react';
import ReactDOM from 'react-dom/client';
import AppRoutes from './routes'; //Config de rotas
import './index.css';

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <>
    <AppRoutes />  {/* Apenas um Router aqui */}
  </>
);
