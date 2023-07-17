import React from "react";

export default function HelpItem({icon, title, text}) {
    return(
        <li className="help__item">
            <div className="help__link">
                <i className={icon} />
                <span className="help__title">{title}</span>
                <span className="help__text">{text}</span>
            </div>
        </li>
    );
}