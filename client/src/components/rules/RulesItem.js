import React from "react";

export default function RulesItem({number, title, text, onClickEvent}) {
    return(
        <li className="rules__item">
            <button type="button" className="rules__link">
                <span className="rules__number">{number}</span>
                <span className="rules__title">{title}</span>
                <span className="rules__text">{text}</span>
            </button>
        </li>
    );
}