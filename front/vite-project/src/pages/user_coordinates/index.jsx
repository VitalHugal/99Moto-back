import React, { useState, useRef, useEffect } from "react";
import { useParams, useLocation, useNavigate, redirect } from 'react-router-dom';
import { USER_COORDINATES } from "../../API/userApi.js";
import { toast } from "react-toastify";

import Cookies from 'js-cookie';

export default function UserCoordinates() {

    const navigate = useNavigate();

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
        const formattedDate = date + "-" + month + "-" + year + " " + hours + ":" + minutes + ":" + seconds;

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

        // Verifica se os campos não estão vazios
        if (latitude !== "" && longitude !== "" && currentDate !== "") {

            // verifica se a hora dentro do intervalo permitido (19:00 até 05:00)
            if (hoursFinal >= '18:00:00' && hoursFinal <= '23:00:00') {

                const response = await USER_COORDINATES(latitude, longitude, currentDate);
                if (response.success == false) {
                    console.log('sem cupom');

                    Cookies.set('userId', response.idUser, { expires: 7 });
                    navigate(`/get-vouchers`);

                } else if (response.success == true) {
                    Cookies.set('userId', response.idUser, { expires: 7 });
                    navigate(`/get-vouchers`);

                } else
                    console.log('fora do horario de participação');
                return;
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