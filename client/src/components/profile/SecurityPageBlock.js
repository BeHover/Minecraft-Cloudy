import React from "react";
import SecurityPageChangePassword from "./SecurityPageChangePassword";
import SecurityPageChangeEmail from "./SecurityPageChangeEmail";
import SecurityPageVerifyEmail from "./SecurityPageVerifyEmail";

export default function SecurityPageBlock() {
    return(
        <div className="profile-block__grid">
            <div className="profile-block__grid profile-block__grid--x2">
                <SecurityPageChangePassword/>
                <SecurityPageChangeEmail/>
            </div>
            <SecurityPageVerifyEmail/>
        </div>
    );
}