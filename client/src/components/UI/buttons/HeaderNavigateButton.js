import {useLocation, useNavigate} from "react-router-dom";
import React, {useEffect, useState} from "react";

export default function HeaderNavigateButton({to, icon, text}) {
    const navigate = useNavigate();
    const location = useLocation();
    const [url, setUrl] = useState(null);

    useEffect(() => {
        setUrl(location.pathname);
    }, [location]);


    const classNames = url === to ? "nav__link nav__link--active" : "nav__link";

    return (
        <button type="button" onClick={() => navigate(to, { replace: true })} className={classNames}>
            <span className="nav__icon"><i className={icon}></i></span>
            <span className="nav__label">{text}</span>
        </button>
    );
}