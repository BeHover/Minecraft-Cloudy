import React, {useEffect, useState} from "react";
import {getToken} from "../../services/UserService";
import {getReportsRequest} from "../../services/ServerRequestService";
import ReportData from "../reports/ReportData";

export default function AdminPageBlock() {
    const token = getToken();
    const [reports, setReports] = useState(null);
    const [tokenLoading, setTokenLoading] = useState(true);
    const [reportsLoading, setReportsLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        getReports().then(r => console.log(r));
    }, [token]);

    useEffect(() => {
        if (null !== token) {
            setTokenLoading(false);
        }
    }, [token]);

    const getReports = async () => {
        try {
            setReportsLoading(true);
            const response = await getReportsRequest(token);
            setReports(response.data);

            setReportsLoading(false);
        } catch (error) {
            setReportsLoading(false);
            setError(error.response.data.message);
        }
    };

    return(
        <main>
            {reportsLoading
                ? <div className="profile-block profile-block--align-start">
                    <p className="profile-block__title">Служба поддержки</p>
                    <p className="profile-block__subtitle profile-block__subtitle--without-border">Здесь можно обратиться в службу поддержки за помощью</p>
                </div>
                : error !== null
                    ? <div className="profile-block profile-block--align-start">
                        <p className="profile-block__title">Служба поддержки</p>
                        <p className="profile-block__subtitle ">Здесь можно обратиться в службу поддержки за помощью</p>
                        <button type="button" className="btn btn--black profile-block__reload" onClick={() => {window.location.reload()}}>
                            <i className="fas fa-sync-alt profile-options__icon" />
                            <p className="profile-options__text">Обновить страницу</p>
                        </button>
                        <p className="profile-block__text profile-block__text--center">Возникла непредвиденная ошибка по получению данных.</p>
                    </div>
                    : reports !== null
                        ? <div className="profile-block profile-block--align-start">
                            <p className="profile-block__title">Служба поддержки</p>
                            <p className="profile-block__subtitle">Здесь можно обратиться в службу поддержки за помощью</p>
                            <button type="button" className="btn btn--black profile-block__reload profile-block__reload--center" onClick={() => {getReports()}}>
                                <i className="fas fa-sync-alt profile-options__icon" />
                                <p className="profile-options__text">Обновить данные</p>
                            </button>
                            <button type="button" className="flex profile-block__text profile-block__text--center profile-block__text--color-blue profile-block__text--link" onClick={() => {getReports()}}>
                                Создать обращение в службу поддержки
                                <i className="fas fa-plus-circle profile-block__icon" />
                            </button>
                        </div>
                        : <div className="profile-block profile-block--align-start">
                            <p className="profile-block__title">Служба поддержки</p>
                            <p className="profile-block__subtitle">Здесь можно обратиться в службу поддержки за помощью</p>
                            <button type="button" className="btn btn--black profile-block__reload" onClick={() => {getReports()}}>
                                <i className="fas fa-sync-alt profile-options__icon" />
                                <p className="profile-options__text">Обновить данные</p>
                            </button>
                            <button type="button" className="flex profile-block__text profile-block__text--center profile-block__text--color-blue profile-block__text--link" onClick={() => {getReports()}}>
                                Создать обращение в службу поддержки
                                <i className="fas fa-plus-circle profile-block__icon" />
                            </button>
                        </div>
            }

            {tokenLoading
                ? <div className="data-loader profile-block__loader" />
                : reportsLoading
                    ? <div className="data-loader profile-block__loader" />
                    : error === null
                        ? reports !== null
                            ? <div className="reports">
                                <ul className="reports__list">
                                    {reports.map(report =>
                                        <ReportData token={token} report={report} />
                                    )}
                                </ul>
                            </div>
                            : ""
                        : ""
            }
        </main>
    );
}