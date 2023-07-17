import NavigateButtonWithIcon from "../UI/buttons/NavigateButtonWithIcon";
import React from "react";

export default function HomePageBanner() {
    return(
        <section className="section section--gray">
            <div className="container home">
                <div className="home__container">
                    <h3 className="home__title">Лучший военно-политический сервер в истории Minecraft</h3>
                    <div>
                        <p className="home__subtitle home__subtitle--href">Выживайте в суровом мире, возводите города, приводите новых поселенцев, управляйте налогами, казной и своими территориями, вершите судьбу в мире майнкрафта</p>
                        <NavigateButtonWithIcon to="/register" icon="fas fa-arrow-right" text="Начать играть" classNames="btn btn--black home__href" />
                    </div>
                </div>
                <ul className="home__list">
                    <li className="home__item">
                        <img className="home__image" src="https://mc-cloudy.com/public/images/feature-01.webp" alt="HomePage item 1" draggable={false} />
                    </li>
                    <li className="home__item">
                        <img className="home__image" src="https://mc-cloudy.com/public/images/feature-03.webp" alt="HomePage item 2" draggable={false} />
                    </li>
                    <li className="home__item">
                        <img className="home__image" src="https://mc-cloudy.com/public/images/phoenix.webp" alt="HomePage item 3" draggable={false} />
                    </li>
                </ul>
                <div className="home-description">
                    <ul className="home-description__list">
                        <li className="home-description__item">
                            <p className="home-description__title">30.000<i className="fas fa-plus-circle"></i></p>
                            <p className="home-description__subtitle">Строк написанного кода для полного функционала сервера</p>
                        </li>
                        <li className="home-description__item">
                            <p className="home-description__title">2.100<i className="fas fa-plus-circle"></i></p>
                            <p className="home-description__subtitle">Уникальных игроков за 3 сезона работы нашего сервера</p>
                        </li>
                        <li className="home-description__item">
                            <p className="home-description__title">250<i className="fas fa-plus-circle"></i></p>
                            <p className="home-description__subtitle">Добавлено абсолютно новых функциональных предметов</p>
                        </li>
                        <li className="home-description__item">
                            <p className="home-description__title">200<i className="fas fa-plus-circle"></i></p>
                            <p className="home-description__subtitle">Добавлено новых элементов кастомизации для персонажа</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    );
}