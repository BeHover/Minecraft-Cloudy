import React from "react";

export default function LandModal({name, owner, title, membersQuantity, membersQuantityText, balance, createdAt, handleClose}) {
    return(
        <div className="backdrop is-hidden">
            <div className="modal">
                <h2 className="modal__title">{name}</h2>
                <p className="modal__subtitle">{title ? title : "Малоразвитый молодой город"}</p>
                <div className="modal__body">
                    <p className="modal__text">
                        <div className="modal__icon"><i className="fas fa-crown" /></div>
                        Правитель города:<span>{owner}</span>
                    </p>
                    <p className="modal__text">
                        <div className="modal__icon"><i className="fas fa-sack" /></div>
                        Баланс города: <span>{balance} Фрупий</span>
                    </p>
                    <p className="modal__text">
                        <div className="modal__icon"><i className="fas fa-user" /></div>
                        Население города: <span>{membersQuantity} {membersQuantityText}</span>
                    </p>
                    <p className="modal__text">
                        <div className="modal__icon"><i className="fas fa-clock" /></div>
                        Дата основания: <span>{createdAt}</span>
                    </p>
                </div>
                <div style={{display: "flex", justifyContent: "center"}}>
                    <button type="button" className="btn btn--black" onClick={handleClose}>
                        Закрыть окно
                    </button>
                </div>
            </div>
        </div>
    );
}