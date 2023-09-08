import React, {useState} from "react";
import ReportItem from "./ReportItem";
import ReportModal from "./ReportModal";

export default function ReportData({token, report}) {
    const [showModal, setShowModal] = useState(false);

    const handleShow = () => setShowModal(true);
    const handleClose = () => setShowModal(false);

    return (
        <li className="reports__item">
            <ReportItem report={report} handleShow={handleShow} />

            {showModal && (
                <ReportModal token={token} report={report} handleClose={handleClose} />
            )}
        </li>
    );
}