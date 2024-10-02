import React, { useState, useRef, useEffect } from "react";
import { useParams, useLocation, useNavigate, Link } from 'react-router-dom';
import { USER_COORDINATES } from "./API/userApi.js";
import { toast } from "react-toastify";
import './App.css';

function App() {
  const navigate = useNavigate(); // Correção aqui

  const [latitude, setLatitude] = useState("");
  const [longitude, setLongitude] = useState("");
  const [currentDate, setCurrentDate] = useState("");
  const local_timeRef = useRef("");

  useEffect(() => {
    const today = new Date();
    const month = today.getMonth() + 1;
    const year = today.getFullYear();
    const date = today.getDate();
    const hours = today.getHours();
    const minutes = today.getMinutes();
    const seconds = today.getSeconds();
    const formattedDate =
      date + "-" + month + "-" + year + " " + hours + ":" + minutes + ":" + seconds;
    setCurrentDate(formattedDate);

    if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          setLatitude(position.coords.latitude.toFixed(10));
          setLongitude(position.coords.longitude.toFixed(10));
        },
        function (error) {
          switch (error.code) {
            case error.PERMISSION_DENIED:
              toast.error("Permissão negada pelo usuário.");
              break;
            case error.POSITION_UNAVAILABLE:
              toast.error("Informação de localização indisponível.");
              break;
            case error.TIMEOUT:
              toast.error("A requisição de localização expirou.");
              break;
            case error.UNKNOWN_ERROR:
              toast.error("Ocorreu um erro desconhecido.");
              break;
            default:
              break;
          }
        },
        {
          timeout: 10000, // Tempo limite de 10 segundos
        }
      );
    } else {
      toast.error("Geolocalização não é suportada pelo navegador.");
    }
  }, []);

  async function handleSubmitRegister(e) {
    e.preventDefault();

    const hoursFormated = currentDate.split(' ');
    const hoursFinal = hoursFormated[1];

    if (latitude !== "" && longitude !== "" && currentDate !== "") {
      const response = await USER_COORDINATES(latitude, longitude, currentDate);

      if (hoursFinal < '17:45:00') {
        console.log('fora do horário de participação');
      }
      
      if (response.success === false) {
        console.log('Erro: ', response.message);
        console.log('ID do Usuário: ', response.idUser);
        return; // Aqui estava faltando
      }

      if (response.success === true) {
        console.log('Requisição bem-sucedida.');
        console.log('ID do Usuário: ', response.idUser);

        // Usando o idUser corretamente no navigate
        const idUser = response.idUser;
        navigate(`/get-vouchers/${idUser}`);
      }
    }
  }

  return (
    <>
      <form onSubmit={handleSubmitRegister} autoComplete="off">
        <div className="input-div">
          <ion-icon name="location-outline"></ion-icon>
          <input
            type="text"
            placeholder="Latitude"
            value={latitude}
            onChange={(e) => setLatitude(e.target.value)}
            required
          />
        </div>

        <div className="input-div">
          <ion-icon name="location-outline"></ion-icon>
          <input
            type="text"
            placeholder="Longitude"
            value={longitude}
            onChange={(e) => setLongitude(e.target.value)}
            required
          />
        </div>

        <div className="input-div">
          <ion-icon name="time-outline"></ion-icon>
          <input
            type="text"
            placeholder="Data e Hora Local"
            ref={local_timeRef}
            value={currentDate}
            onChange={(e) => setCurrentDate(e.target.value)}
            required
          />
        </div>

        <button className="btn-primary">Cadastrar</button>
      </form>
    </>
  );
}

export default App;
