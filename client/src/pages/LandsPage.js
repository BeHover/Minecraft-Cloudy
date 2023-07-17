import React, {useEffect, useState} from "react";
import "../assets/styles/main.css";
import Header from "../layouts/Header";
import axios from "axios";
import LandData from "../components/lands/LandData";
import NavigateButtonWithIcon from "../components/UI/buttons/NavigateButtonWithIcon";
import LoadingBanner from "../components/banners/LoadingBanner";
import DataLoader from "../components/loaders/DataLoader";
import BannerWithButton from "../components/banners/BannerWithButton";

export default function LandsPage() {
    const [lands, setLands] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        axios.get("https://127.0.0.1:8000/api/lands")
            .then((response) => {
                setLands(response.data);
                setIsLoading(false);
            })
            .catch((error) => {
                console.log(error.response.data.message);
                setError("Возникла серверная ошибка. Пожалуйста, оповестите разработчика или других администраторов о возникшей проблеме для её скорейшего устранения.");
                setIsLoading(false);
            });
    }, []);

    return (
        <div id="react-dom">
            <Header/>

            {isLoading
                ? <DataLoader title="Происходит загрузка информации о поселениях" subtitle="Пожалуйста, дождитесь окончания загрузки информации о поселениях игроков." />
                : error !== null
                    ? <LoadingBanner
                        title="К сожалению, возникла какая-то ошибка"
                        firstText={error}
                        button={<NavigateButtonWithIcon to="/report" text="Сообщить об ошибке" icon="fas fa-flag-alt" classNames="btn btn--red error__href" />}
                        image="https://mc-cloudy.com/public/images/mobile-nav.webp"
                    />
                    : lands !== null
                        ? <main>
                            <BannerWithButton
                                title="Первый прорыв градостроения в майнкрафт индустрии"
                                subtitle="Зарегистрируй аккаунт на нашем проекте и окунись в суровое выживание в политическом мире. Построй свою империю, стань главным политическим деятелем сервера!"
                                button={<NavigateButtonWithIcon to="/register" text="Зарегистрировать аккаунт" icon="fas fa-arrow-right" classNames="btn btn--black home__href" />}
                            />

                            <section className="section">
                                <div className="container">
                                    <div className="rules">
                                        <h3 className="rules__description">Список городов игроков</h3>
                                        <p className="rules__punishment">Здесь вы можете ознакомиться со всеми городами сервера</p>
                                    </div>

                                    <div className="lands">
                                        <ul className="lands__list">
                                            {lands.map(land =>
                                                <LandData key={land.id} name={land.name} owner={land.owner} title={land.title} membersQuantity={land.membersQuantity} balance={land.balance} createdAt={land.createdAt} />
                                            )}
                                        </ul>
                                    </div>
                                </div>
                            </section>
                        </main>
                        : <LoadingBanner
                            title="К сожалению, на сервере нет поселений"
                            firstText="Вы можете стать первым, кто построит город и найдёт поселенцев, которые будут разделять вашу страсть к градостроительству и помогут в реализации всех идей."
                            secondText="Не упустите шанс стать частью нашего уникального сообщества и создать свой собственный город в Майнкрафте! Зарегистрируйте аккаунт прямо сейчас и начните строить свою мечту. Мы ждем вас на нашем сервере!"
                            button={<NavigateButtonWithIcon to="/register" text="Зарегистрировать аккаунт" icon="fas fa-arrow-right" classNames="btn btn--black home__href" />}
                            image="https://mc-cloudy.com/public/images/mobile-nav.webp"
                        />
            }
        </div>
    );
}