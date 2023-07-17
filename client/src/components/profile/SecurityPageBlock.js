import React from "react";
import SecurityPageChangePassword from "./SecurityPageChangePassword";
import SecurityPageChangeEmail from "./SecurityPageChangeEmail";
import SecurityPageVerifyEmail from "./SecurityPageVerifyEmail";

export default function SecurityPageBlock() {
    return(
        <div className="profile__grid">
            <div className="profile__grid profile__grid--2">
                <SecurityPageChangePassword/>
                <SecurityPageChangeEmail/>
            </div>
            <SecurityPageVerifyEmail/>
        </div>
    );
}