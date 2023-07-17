import React, {useState} from "react";

export default function SimplifiedStats({playername, online, createdAt, timePlayed, balance, discord}) {
    const [modal, setModal] = useState(null);

    return (
        <li className="stats__item">
            {modal !== null
                ? <div className="backdrop is-hidden">
                    <div className="modal">
                        <button type="button" className="modal__close" onClick={() => {setModal(null)}}>
                            <i className="fal fa-times-circle"></i>
                        </button>

                        <h2 className="modal__title">Статистика игрока {playername}</h2>
                    </div>
                </div>
                : ""
            }
            <a className="stats__link" onClick={() => {setModal(playername)}}>
                <div className="stats__header">
                    <p className="stats__name">{playername}</p>
                    <p className="stats__status">
                        {online
                            ? "Онлайн"
                            : "Оффлайн"
                        }
                    </p>
                </div>
                <p className="stats__created-at">На проекте с <span>{createdAt}</span></p>
                <ul className="stats-info__list">
                    <li className="stats-info__item">Время в игре: <span>{timePlayed}</span></li>
                    <li className="stats-info__item">Игровой баланс: <span>{balance} Фрупий</span></li>
                </ul>
                <p className="stats__discord">
                    <i className="fab fa-discord"></i>
                    {discord === null
                        ? "Не указан"
                        : discord
                    }
                </p>
            </a>
        </li>
    );
}