import React, {useEffect, useState} from "react";
import {useLocation, useNavigate} from "react-router-dom";

export default function TabItem({link, title, icon}) {
    const navigate = useNavigate();
    const location = useLocation();
    const [url, setUrl] = useState(null);

    useEffect(() => {
        setUrl(location.pathname);
    }, [location]);

    const styles = url === link ? "profile-menu__link profile-menu__link--selected" : "profile-menu__link";

    return(
        <li className="profile-menu__item">
            <button type="button" className={styles} onClick={() => { navigate(link, { replace: true })}}>
                <i className={icon} />
                {title}
            </button>
        </li>
    );
}