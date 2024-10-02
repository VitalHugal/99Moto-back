import React, { useState, useRef, useEffect } from "react";
import { useNavigate } from "react-router-dom";
// import Cookies from "js-cookie";
import { USER_COORDINATES } from "../../API/userApi";
import { toast } from "react-toastify";

export default function UserCoordinates() {
    const [latitude, setLatitude] = useState("");
    const [longitude, setLongitude] = useState("");
    const [currentDate, setCurrentDate] = useState("");
    const local_timeRef = useRef("");

    const navigate = useNavigate();

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
            navigator.geolocation.getCurrentPosition(function (position) {
                console.log("Latitude: " + position.coords.latitude);
                console.log("Longitude: " + position.coords.longitude);
                setCount(`Latitude: ${position.coords.latitude} / Longitude: ${position.coords.longitude}`);
            }, function (error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        console.log("Permissão negada pelo usuário.");
                        setCount("Permissão negada pelo usuário.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.log("Informação de localização indisponível.");
                        setCount("Informação de localização indisponível.");
                        break;
                    case error.TIMEOUT:
                        console.log("A requisição de localização expirou.");
                        setCount("A requisição de localização expirou.");
                        break;
                    case error.UNKNOWN_ERROR:
                        console.log("Ocorreu um erro desconhecido.");
                        setCount("Ocorreu um erro desconhecido.");
                        break;
                }
            }, {
                timeout: 10000 // Tempo limite de 10 segundos
            });
        } else {
            console.log("Geolocalização não é suportada pelo navegador.");
            setCount("Geolocalização não é suportada pelo navegador.");
        }
    }, []);

    async function handleSubmitRegister(e) {
        e.preventDefault();

        if (latitude !== "" && longitude !== "" && currentDate !== "") {
            try {
                const response = await USER_COORDINATES(latitude, longitude, currentDate);

            } catch (erro) {
                console.error("Erro ao cadastrar!");
            }
        } else {
            console.log("Erro ao cadastrar!");
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
