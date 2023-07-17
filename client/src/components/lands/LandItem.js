import React from "react";

export default function LandItem({name, owner, title, membersQuantity, membersQuantityText, balance, createdAt, handleShow}) {
    return(
        <button type="button" className="lands__link" onClick={handleShow}>
            <div className="lands__header">
                <p className="lands__name">{name}</p>
            </div>
            {title !== ""
                ? <p className="lands__title">{title}</p>
                : <p className="lands__title">Малоразвитый молодой город</p>
            }
            <ul className="lands__info-list">
                <li className="lands__info-item">Население города: <span>{membersQuantity} {membersQuantityText}</span></li>
                <li className="lands__info-item">Баланс города: <span>{balance} Фрупий</span></li>
                <li className="lands__info-item">Дата основания: <span>{createdAt}</span></li>
            </ul>
            <span className="lands__text">Нажмите, чтобы узнать больше<i style={{marginLeft: "8px"}} className="fas fa-arrow-circle-right" /></span>
        </button>
    );
}