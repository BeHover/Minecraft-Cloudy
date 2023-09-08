import React, {useEffect} from "react";
import 'react-tabs/style/react-tabs.css';
import "../../assets/styles/main.css";
import Header from "../../layouts/Header";
import {useNavigate} from "react-router-dom";
import {getToken, getUser} from "../../services/UserService";

import "../../assets/styles/components/Profile/profile.css";
import ProfileMenu from "../../components/profile/ProfileMenu";
import UserLandsList from "../../components/profile/UserLands/UserLandsList";

export default function ProfileLandManagePage() {
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
                        <UserLandsList />
                    </div>
                </section>
            </main>
        </div>
    );
}