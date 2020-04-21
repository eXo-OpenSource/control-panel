import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, Table} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import {Link} from "react-router-dom";

export default class PunishHistoryDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            showMessage: false,
            data: null,
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
            this.loadData();
        };

        this.handleMessageClose = () => {
            this.setState({ show: false });
            this.setState({ showMessage: false });
        };
    }

    async loadData() {
        const response = await axios.get('/api/admin/punish/' + this.props.id + '/log');

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    onChange(e) {
        if(e.target.type == 'checkbox') {
            this.setState({ [e.target.name]: e.target.checked });
        } else {
            this.setState({ [e.target.name]: e.target.value });
        }
    }

    render() {
        let body = <div className="text-center"><Spinner animation="border" /></div>;

        if(this.state.data) {
            body =

                <table className="table table-sm">
                    <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Grund</th>
                        <th>Alter Grund</th>
                        <th>Dauer</th>
                        <th>Alte Dauer</th>
                        <th>Intern</th>
                        <th>Alter Intern</th>
                        <th>Gelöscht</th>
                        <th>Alt Gelöscht</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.state.data.map((log, i) => {
                        return (
                            <tr>
                                <td>{log.Admin}</td>
                                <td>{log.Reason}</td>
                                <td>{log.ReasonPrev}</td>
                                <td>{log.Duration / 3600}h</td>
                                <td>{log.DurationPrev / 3600}h</td>
                                <td>{log.InternalMessage}</td>
                                <td>{log.InternalMessagePrev}</td>
                                <td>{log.DeletedAt}</td>
                                <td>{log.DeletedAtPrev}</td>
                            </tr>
                        );
                    })}
                    </tbody>
                </table>;
        }

        return (
            <>
                <Button variant="primary" size="sm" onClick={this.handleShow}>
                    Änderungen
                </Button>

                <Modal show={this.state.show} onHide={this.handleClose} size="xl">
                    <Modal.Header closeButton>
                        <Modal.Title>Änderungen</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {body}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>

                <Modal show={this.state.showMessage} onHide={this.handleMessageClose} dialogClassName={'modal-' + this.state.messageType}>
                    <Modal.Header closeButton>
                        <Modal.Title>Status</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <span>{this.state.message}</span>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleMessageClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}

var punish = document.getElementsByTagName('react-punish-history-dialog');

for (var index in punish) {
    const component = punish[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<PunishHistoryDialog {...props} />, component);
    }
}
