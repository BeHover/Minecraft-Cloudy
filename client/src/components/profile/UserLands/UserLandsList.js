import React, {useEffect, useState} from "react";
import {getToken} from "../../../services/UserService";
import {getLandsByUser} from "../../../services/ServerRequestService";
import LandData from "./LandData";

export default function UserLandsList() {
    const token = getToken();
    const [lands, setLands] = useState(null);
    const [landsLoading, setLandsLoading] = useState(true);
    const [tokenLoading, setTokenLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        getLands().then(r => console.log(r));
    }, [token]);

    useEffect(() => {
        if (null !== token) {
            setTokenLoading(false);
        }
    }, [token]);

    const getLands = async () => {
        try {
            setLandsLoading(true);
            const response = await getLandsByUser(token);

            setLandsLoading(false);
            if (response.data !== null && typeof response.data === 'object' && Object.keys(response.data).length !== 0) {
                setLands(response.data);
            } else {
                setLands(null);
            }
        } catch (error) {
            setLandsLoading(false);
            setError(error.response.data.message);
        }
    };

    return(
        <main>
            {landsLoading
                ? <div className="profile-block profile-block--align-start">
                    <p className="profile-block__title">Управление городом</p>
                    <p className="profile-block__subtitle profile-block__subtitle--without-border">Здесь можно изменить аватарку и описание города</p>
                </div>
                : error !== null
                    ? <div className="profile-block profile-block--align-start">
                        <p className="profile-block__title">Управление городом</p>
                        <p className="profile-block__subtitle">Здесь можно изменить аватарку и описание города</p>
                        <button type="button" className="btn btn--black profile-block__reload" onClick={() => {window.location.reload()}}>
                            <i className="fas fa-sync-alt profile-options__icon" />
                            <p className="profile-options__text">Обновить страницу</p>
                        </button>
                        <p className="profile-block__text profile-block__text--center">Возникла непредвиденная ошибка по получению данных.</p>
                    </div>
                    : lands !== null
                        ? <div className="profile-block profile-block--align-start">
                            <p className="profile-block__title">Управление городом</p>
                            <p className="profile-block__subtitle profile-block__subtitle--without-border">Здесь можно изменить аватарку и описание города</p>
                            <button type="button" className="btn btn--black profile-block__reload profile-block__reload--center" onClick={() => {getLands()}}>
                                <i className="fas fa-sync-alt profile-options__icon" />
                                <p className="profile-options__text">Обновить данные</p>
                            </button>
                    </div>
                        : <div className="profile-block profile-block--align-start">
                            <p className="profile-block__title">Управление городом</p>
                            <p className="profile-block__subtitle">Здесь можно изменить аватарку и описание города</p>
                            <button type="button" className="btn btn--black profile-block__reload" onClick={() => {getLands()}}>
                                <i className="fas fa-sync-alt profile-options__icon" />
                                <p className="profile-options__text">Обновить данные</p>
                            </button>
                            <p className="profile-block__text profile-block__text--center">Вы не являетесь правителем ни одного из городов сервера.</p>
                        </div>
            }

            {tokenLoading
                ? <div className="data-loader profile-block__loader" />
                : landsLoading
                    ? <div className="data-loader profile-block__loader" />
                    : lands !== null
                        ? <div className="profile-lands">
                            <ul className="profile-lands__list">
                                {lands.map(land =>
                                    <LandData land={land} />
                                )}
                            </ul>
                        </div>
                        : ""
            }
        </main>
    );
}