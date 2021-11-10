import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";

export default class MapCopyDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            showMessage: false,
            toMap: '',
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

    async copy() {
        try {
            const response = await axios.put('/api/admin/maps/' + this.props.id, {
                type: 'map-copy',
                toMap: this.state.toMap,
                connection: this.props.connection,
            });

            let type = 'danger';

            if(response.data.status === 'Success') {
                type = 'success';
            }

            this.setState({ show: false });
            this.setState({ showMessage: true, message: response.data.message, messageType: type });
        } catch (error) {
            console.log(error.response);
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
                <span className="btn btn-primary btn-sm" style={{'cursor': 'pointer'}} onClick={this.handleShow}>Map kopieren</span>

                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Map #{this.props.id} auf Server kopieren</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form>
                            <Form.Group>
                                <Form.Label>Map ersetzen (Optional, überschreibt Objekte)</Form.Label>
                                <InputGroup>
                                    <Form.Control name="toMap" type="text" placeholder="Id" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>
                        </Form>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="primary" onClick={this.copy.bind(this)}>
                            Kopieren
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

var mapCopyDialogs = document.getElementsByTagName('react-map-copy-dialog');

for (var index in mapCopyDialogs) {
    const component = mapCopyDialogs[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<MapCopyDialog {...props} />, component);
    }
}

