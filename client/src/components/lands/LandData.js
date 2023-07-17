import React, {useState} from "react";
import DeclensionOfNumber from "../utils/DeclensionOfNumber";
import LandModal from "./LandModal";
import LandItem from "./LandItem";

export default function LandData({name, owner, title, membersQuantity, balance, createdAt}) {
    const [showModal, setShowModal] = useState(false);
    const playerForms = ["житель", "жителя", "жителей"];
    const membersQuantityText = <DeclensionOfNumber number={membersQuantity} titles={playerForms} />;

    const handleShow = () => setShowModal(true);
    const handleClose = () => setShowModal(false);

    return (
        <li className="lands__item">
            <LandItem name={name} owner={owner} title={title} membersQuantity={membersQuantity} membersQuantityText={membersQuantityText} balance={balance} createdAt={createdAt} handleShow={handleShow} />

            {showModal && (
                <LandModal name={name} owner={owner} title={title} membersQuantity={membersQuantity} membersQuantityText={membersQuantityText} balance={balance} createdAt={createdAt} handleClose={handleClose} />
            )}
        </li>
    );
}