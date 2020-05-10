import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";

export default class TeamSpeakUnbanDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            showMessage: false,
            reason: '',
            message: '',
            messageType: ''
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleMessageClose = () => {
            this.setState({ showMessage: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
            if(this.state.data === null) {
                this.loadData();
            }
        };

    }

    async unban() {
        try {
            const response = await axios.put('/api/admin/users/' + this.props.id + '/teamspeak', {
                type: 'unban',
                reason: this.state.reason
            });

            let type = 'danger';

            if(response.data.status === 'Success') {
                type = 'success';
            }

            this.setState({ show: false });
            this.setState({ showMessage: true, message: response.data.message, messageType: type });
        } catch (error) {
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    render() {
        return (
            <>
                <span className="dropdown-item" style={{'cursor': 'pointer'}} onClick={this.handleShow}>Entsperren</span>

                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>{this.props.name} entsperren</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form>
                            <Form.Group>
                                <Form.Label>Grund</Form.Label>
                                <InputGroup>
                                    <Form.Control name="reason" type="text" placeholder="Grund" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>
                        </Form>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="danger" onClick={this.unban.bind(this)}>
                            Entsperren
                        </Button>
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

var unbanDialogs = document.getElementsByTagName('react-team-speak-unban-dialog');

for (var index in unbanDialogs) {
    const component = unbanDialogs[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<TeamSpeakUnbanDialog {...props} />, component);
    }
}

