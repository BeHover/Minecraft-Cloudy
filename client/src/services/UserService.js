export function setToken(token) {
    const expirationTime = new Date().getTime() + 3600000; // 1 hour in milliseconds (3600000)
    const tokenData = { token, expirationTime };

    localStorage.setItem("token", JSON.stringify(tokenData));
}

export function getToken() {
    const token = JSON.parse(localStorage.getItem("token"));

    if (null === token) {
        return null;
    }

    if (new Date().getTime() < token.expirationTime) {
        return token.token;
    } else {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        return null;
    }
}

export function setUser(user) {
    localStorage.setItem("user", JSON.stringify(user));
}

export function getUser() {
    const userDataString = localStorage.getItem("user");

    if (null === userDataString) {
        return {};
    }

    return JSON.parse(userDataString);
}