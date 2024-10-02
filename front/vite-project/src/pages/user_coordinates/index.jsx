// Funcionalidades / Libs:
import { useState, useRef } from "react";
import { useNavigate, Link } from "react-router-dom";
//import Cookies from "js-cookie";
import { USER_COORDINATES } from "../../API/userApi";


export default function UserCoordinates(){
    const today = new Date();

    const month = today.getMonth() + 1;
    const year = today.getFullYear();
    const date = today.getDate();
    const hours = today.getHours();
    const minutes = today.getMinutes();
    const seconds = today.getSeconds();
  
    const currentDate = date + "-" + month + "-" + year + ' ' + hours + ":" + minutes + ":" + seconds;
  
    console.log(currentDate);
  
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      console.log("ERRO AO BUSCAR GEOLOCALIZAÇÃO");
    }
  
    function showPosition(position) {
      console.log("Latitude: " + position.coords.latitude + "\nLongitude: " + position.coords.longitude);
    }
}