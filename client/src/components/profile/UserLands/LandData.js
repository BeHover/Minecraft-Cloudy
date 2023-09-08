import DeclensionOfNumber from "../../utils/DeclensionOfNumber";
import React from "react";

export default function LandData({land}) {
    const playerForms = ["житель", "жителя", "жителей"];

    return(
        <div className="profile-lands__item">
            <div className="profile-lands__header">
                <div>
                    <p className="profile-lands__name">{land.name}</p>
                    <p className="profile-lands__title">{land.title}</p>
                </div>
                <button type="button" className="btn btn--black profile-lands__button">
                    <i className="fas fa-university" />
                </button>
            </div>
            <div className="profile-lands__text-block">
                <p className="profile-lands__text">
                    Население города:
                    <span className="profile-lands__text--color-black">
                        {land.membersQuantity} <DeclensionOfNumber number={land.membersQuantity} titles={playerForms} />
                    </span>
                </p>
                <p className="profile-lands__text">
                    Баланс города:
                    <span className="profile-lands__text--color-black">{land.balance} Фрупий</span>
                </p>
                <p className="profile-lands__text">
                    Дата основания:
                    <span className="profile-lands__text--color-black">{land.created}</span>
                </p>
            </div>
        </div>
    );
}