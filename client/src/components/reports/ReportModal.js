import React, {useState} from "react";
import {
    deactivateReportRequest,
    getReportByUUIDRequest,
    postReportMessageRequest
} from "../../services/ServerRequestService";

export default function ReportModal({token, report, handleClose}) {
    const [loading, setLoading] = useState(false);
    const [formData, setFormData] = useState({ message: '' });
    const [reportData, setReportData] = useState(report);

    const handleChange = event => {
        setFormData({[event.target.name]: event.target.value});
    };

    const handleDeactivate = async event => {
        event.preventDefault();

        try {
            setLoading(true);
            await deactivateReportRequest(token, report.report.uuid);
            const response = await getReportByUUIDRequest(token, report.report.uuid);
            setReportData(response.data);
            setLoading(false);
        } catch (error) {
            setLoading(false);
        }
    }

    const handleSubmit = async event => {
        event.preventDefault();

        try {
            setLoading(true);
            await postReportMessageRequest(token, report.report.uuid, formData);
            const response = await getReportByUUIDRequest(token, report.report.uuid);
            setReportData(response.data);
            setFormData({ message: '' });
            setLoading(false);
        } catch (error) {
            setLoading(false);
        }
    };

    return(
        <div className="backdrop is-hidden">
            <div className="modal modal--reports">
                <div className="modal__header">
                    <div>
                        <h2 className="modal__title">{reportData.report.type.name}</h2>
                        <p className="modal__subtitle">
                            {reportData.report.closed ? "Обращение решено" : "Обращение в процессе решения"}
                        </p>
                    </div>
                    <div style={{marginLeft: "auto"}}>
                        <button type="button" style={{marginRight: "6px"}} className="btn btn--red profile-block__button" onClick={handleClose}>
                            <i className="fas fa-angle-double-right" style={{color: "white", marginRight: "6px"}} />
                            Открыть обращение
                        </button>
                        <button type="button" className="btn btn--black profile-block__button" onClick={handleClose}>
                            Закрыть окно
                        </button>
                    </div>
                </div>
                {loading
                    ? <div className="data-loader" style={{margin: "auto", marginTop: "30px", marginBottom: "30px"}}></div>
                    : <div className="modal__body">
                        <p className="modal__username">Обращение от игрока {reportData.report.created.user.username}:</p>
                        <p className="modal__text">{reportData.report.text}</p>
                        <p className="modal__datetime">{reportData.report.created.datetime}</p>
                    </div>
                }
            </div>
        </div>
    );
}