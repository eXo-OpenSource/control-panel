import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, Table} from 'react-bootstrap';
import axios from "axios";

export default class PunishEditDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            showMessage: false,
            data: null,
            internal: '',
            deleted: ''
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
        const response = await axios.get('/api/admin/punish/' + this.props.id);

        try {
            this.setState({
                data: response.data,
                reason: response.data.punish.Reason,
                duration: response.data.punish.Duration / 3600,
                internal: response.data.punish.InternalMessage,
                deleted: response.data.punish.DeletedAt != null,
                rank: response.data.rank,
            });
        } catch (error) {
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    async update() {
        try {
            const response = await axios.put('/api/admin/punish/' + this.props.id, {
                reason: this.state.reason,
                internal: this.state.internal,
                duration: this.state.duration * 3600,
                deleted: this.state.deleted,
            });

            let type = 'danger';

            if(response.data.status === 'Success') {
                type = 'success';
            } else if(response.data.status === 'Warning') {
                type = 'warning';
            }

            this.setState({ show: false });
            this.setState({ showMessage: true, message: response.data.message, messageType: type });
        } catch (error) {
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    onChange(e) {
        if(e.target.type === 'checkbox') {
            this.setState({ [e.target.name]: e.target.checked });
        } else {
            this.setState({ [e.target.name]: e.target.value });
        }
    }

    render() {
        let body = <div className="text-center"><Spinner animation="border" /></div>;
        let footer;

        if(this.state.data) {
            let deleted;

            if(this.state.rank >= 5) { // Admin+
                deleted =
                    <Form.Group>
                        <Form.Check name="deleted" type="checkbox" label="Gelöscht?" checked={this.state.deleted} onChange={this.onChange.bind(this)} />
                        <Form.Text className="text-muted">
                            Dies geht erst ab Admin oder höher
                        </Form.Text>
                    </Form.Group>;
            }

            body =
                <Form>
                    <Form.Group>
                        <Form.Label>Grund</Form.Label>
                        <InputGroup>
                            <Form.Control name="reason" as="textarea" value={this.state.reason} onChange={this.onChange.bind(this)} />
                        </InputGroup>
                    </Form.Group>

                    <Form.Group>
                        <Form.Label>Dauer</Form.Label>
                        <InputGroup>
                            <Form.Control name="duration" type="text" placeholder="Dauer" value={this.state.duration} onChange={this.onChange.bind(this)} />
                            <InputGroup.Append>
                                <InputGroup.Text>Stunden</InputGroup.Text>
                            </InputGroup.Append>
                        </InputGroup>
                        <Form.Text className="text-muted">
                            Falls permanent oder kein Wert einfach den Wert auf 0 lassen.
                        </Form.Text>
                    </Form.Group>

                    <Form.Group>
                        <Form.Label>Intern</Form.Label>
                        <InputGroup>
                            <Form.Control name="internal" as="textarea" value={this.state.internal} onChange={this.onChange.bind(this)} />
                        </InputGroup>
                    </Form.Group>

                    {deleted}
                </Form>;

            footer =
                <Button variant="primary" onClick={this.update.bind(this)}>
                    Speichern
                </Button>;
        }

        return (
            <>
                <Button variant="primary" size="sm" onClick={this.handleShow}>
                    Bearbeiten
                </Button>

                <Modal show={this.state.show} onHide={this.handleClose} size="lg">
                    <Modal.Header closeButton>
                        <Modal.Title>Strafe</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {body}
                    </Modal.Body>
                    <Modal.Footer>
                        {footer}
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

var punish = document.getElementsByTagName('react-punish-edit-dialog');

for (var index in punish) {
    const component = punish[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<PunishEditDialog {...props} />, component);
    }
}
