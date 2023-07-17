import React, {useEffect, useState} from "react";
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import 'react-tabs/style/react-tabs.css';
import "../assets/styles/main.css";
import Header from "../layouts/Header";
import NavigateButtonWithIcon from "../components/UI/buttons/NavigateButtonWithIcon";
import axios from "axios";
import {useNavigate} from "react-router-dom";
import DataLoader from "../components/loaders/DataLoader";
import LoadingBanner from "../components/banners/LoadingBanner";
import HomePageProfileBlock from "../components/profile/HomePageProfileBlock";
import SecurityPageBlock from "../components/profile/SecurityPageBlock";
import BanPageBlock from "../components/profile/BanPageBlock";
import SupportPageBlock from "../components/profile/SupportPageBlock";
import LandManagePageBlock from "../components/profile/LandManagePageBlock";
import AdminPageBlock from "../components/profile/AdminPageBlock";
import TabItem from "../components/tabs/TabItem";

export default function ProfilePage() {
    const token = localStorage.getItem("token");
    const [userData, setUserData] = useState({});
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const [isAdmin, setIsAdmin] = useState(false);
    const navigate = useNavigate();

    useEffect(() => {
        if (localStorage.getItem("token") === null) {
            return navigate("/login", { replace: true });
        }
    }, []);

    useEffect(() => {
        if (localStorage.getItem("user") === null) {
            const config = {
                headers: { Authorization: `Bearer ${token}` }
            };

            axios.get('https://127.0.0.1:8000/api/user', config)
                .then((response) => {
                    setUserData(response.data);
                    setIsLoading(false);
                    localStorage.setItem("user", JSON.stringify(userData));

                    if (response.data.roles.includes("ROLE_MODERATOR")) {
                        setIsAdmin(true);
                    }
                })
                .catch((error) => {
                    setError(error.response.data.message);
                    setIsLoading(false);

                    if (error.response.status === 401 && error.response.data.message === 'Ошибка сессии текущего пользователя') {
                        localStorage.removeItem("token");
                        localStorage.removeItem("user");

                        return navigate("/login", { replace: true });
                    }
                });
        } else {
            setUserData(JSON.parse(localStorage.getItem("user")));
            console.log(JSON.parse(localStorage.getItem("user")));

            if (JSON.parse(localStorage.getItem("user")).roles.includes("ROLE_MODERATOR")) {
                setIsAdmin(true);
            }

            setIsLoading(false);
        }
    }, []);

    return (
        <div id="react-dom" style={{overflow: "hidden", background: "#f7f7f7"}}>
            <Header/>
            {isLoading
                ? <DataLoader title="Происходит загрузка данных пользователя" subtitle="Пожалуйста, дождитесь окончания загрузки данных вашего профиля." />
                : <main>
                    {error
                        ? <LoadingBanner
                            title={error}
                            firstText="Сообщите нам об ошибке, с которой вы столкнулись, если причина её появления вам не ясна или вы не знаете как её устранить."
                            button={<NavigateButtonWithIcon to="/report" text="Сообщить об ошибке" icon="fas fa-flag-alt" classNames="btn btn--red error__href" />}
                            image="https://mc-cloudy.com/public/images/mobile-nav.webp"
                        />
                        : <Tabs>
                            <section className="container container--profile">
                                <div className="profile">
                                    <div className="profile__menu" style={{alignSelf: "start"}}>
                                        <p className="profile__menu-title profile__menu-title--border">Меню управления</p>
                                        <TabList>
                                            <Tab>
                                                <TabItem title="Главная страница" icon="fas fa-home" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Безопасность" icon="fas fa-shield" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Блокировки" icon="fas fa-ban" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Служба поддержки" icon="fas fa-headphones" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Управление городом" icon="fas fa-landmark-alt" />
                                            </Tab>
                                            {isAdmin
                                                ? <Tab>
                                                    <TabItem title="Панель администратора" icon="fas fa-tools" />
                                                </Tab>
                                                : ""
                                            }

                                        </TabList>
                                    </div>

                                    <TabPanel>
                                        <HomePageProfileBlock userData={userData} />
                                    </TabPanel>
                                    <TabPanel>
                                        <SecurityPageBlock/>
                                    </TabPanel>
                                    <TabPanel>
                                        <BanPageBlock/>
                                    </TabPanel>
                                    <TabPanel>
                                        <SupportPageBlock/>
                                    </TabPanel>
                                    <TabPanel>
                                        <LandManagePageBlock/>
                                    </TabPanel>
                                    {isAdmin
                                        ? <TabPanel>
                                            <AdminPageBlock/>
                                        </TabPanel>
                                        : ""
                                    }

                                </div>
                            </section>
                        </Tabs>
                    }
                </main>
            }
        </div>
    );
}