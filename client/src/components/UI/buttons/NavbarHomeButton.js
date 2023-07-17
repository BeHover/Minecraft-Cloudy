import {useNavigate} from "react-router-dom";
import React from "react";

export default function NavbarHomeButton({title, subtitle}) {
    const navigate = useNavigate();

    return (
        <a className="logo" onClick={() => navigate("/", { replace: true })}>
            <img className="logo__image" src="https://mc-cloudy.com/public/favicon.ico" alt="Logo" draggable="false" />
            <div className="logo__text">
                <p className="logo__title">{title}</p>
                <p className="logo__subtitle">{subtitle}</p>
            </div>
        </a>
    );
}