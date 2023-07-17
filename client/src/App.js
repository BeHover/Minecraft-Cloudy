import React from "react";
import AppRouter from "./components/AppRouter";
import {BrowserRouter} from "react-router-dom";

export default function App() {
    return (
        <BrowserRouter>
            <AppRouter/>
        </BrowserRouter>
    );
}