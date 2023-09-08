import React, {useEffect} from "react";
import 'react-tabs/style/react-tabs.css';
import "../../assets/styles/main.css";
import Header from "../../layouts/Header";
import {useNavigate} from "react-router-dom";
import {getToken} from "../../services/UserService";

import "../../assets/styles/components/Profile/profile.css";
import ProfileMenu from "../../components/profile/ProfileMenu";
import SupportPageBlock from "../../components/profile/SupportPageBlock";
import AdminPageBlock from "../../components/profile/AdminPageBlock";

export default function ProfileSupportPage() {
    const token = getToken();
    const navigate = useNavigate();

    useEffect(() => {
        if (null === token) {
            return navigate("/login", { replace: true });
        }
    }, []);

    return (
        <div id="cloudy-web-content" className="cloudy-background">
            <Header />
            <main>
                <section className="container container--profile">
                    <div className="profile">
                        <ProfileMenu />
                        <AdminPageBlock />
                    </div>
                </section>
            </main>
        </div>
    );
}