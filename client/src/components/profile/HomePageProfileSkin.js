import React from "react";

export default function HomePageProfileSkin() {
    return(
        <div className="profile-block">
            <p className="profile-block__title">Скин персонажа</p>
            <p className="profile-block__subtitle">Здесь вы можете изменить свой скин</p>
            <p className="profile-block__text">
                Скин не должен нарушать
                <span className="profile-block__text--color-blue profile-block__text--link">
                    правила проекта
                    <i className="fas fa-mouse-pointer profile-block__icon" />
                </span>
            </p>
            <div className="profile-block__buttons">
                <button type="button" className="btn btn--primary profile-block__button">
                    <i className="fas fa-upload" /> Выбрать файл
                </button>
                <button type="button" className="btn btn--red profile-block__button">
                    <i className="fas fa-trash-alt" /> Сбросить скин
                </button>
            </div>
        </div>
    );
}